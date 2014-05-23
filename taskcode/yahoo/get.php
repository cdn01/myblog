<?php

include 'header.php'; 
println(date("Y-m-d H:i:s",time()));

$key = getKey();  

$link = getLink($key);

$article = getArticle2($link);
$insert_id = insertDB("yahoo_article", $article);
$comments_arr = getComments($link); 
insertDB("yahoo_comment",$comments_arr,$insert_id);







function getArticle2($link){
	println($link);
	if(strpos($link, "r.search.yahoo.com")){
		$start = strpos($link,"RU=http");
		$end = strpos($link, "/RK=0"); 
		$link = urldecode(substr($link, $start+3,$end-$start-3));
		println($link);
	}
	
	$html = html($link);
	$img = array();
	if(preg_match("/<meta itemprop=\"image\" content=\"(.*)\"/iUs", $html,$itemprop_image_m)){ 
// 		$img[] = $itemprop_image_m[1];
	}
	preg_match("/<title>(.*) - /iUs", $html,$title_m);
	preg_match("/<\!\-\- google\_ad\_section\_start \-\->(.*)<\!\-\- google\_ad\_section\_end \-\->/iUs", $html,$matches);
	$article = array();
	$article['link'] = $link;
	
	if(preg_match("/<div class=\"yom-art-lead-img\">(.*)<\/div/iUs", $html,$image_m)){
		$img[] = $image_m[1];
	}
	
	if(preg_match("/<div(.*)id=\"mediaarticlelead\">(.*)<\/div>/iUs", $html,$vedio_m)){
		if(!strpos($vedio_m[2], '<div class="yom-art-lead-img">')){
			$article['vedio'] = $vedio_m[2]."</div>";
		}
	}
	
	if(preg_match("/<figure class=\"small-cover(.*)<img src=\"(.*)\"(.*)<div class=\"caption\">(.*)<\/div>/iUs", $html,$img_small_m)){
		$img[] = $img_small_m[2]."<!--caption-->".$img_small_m[4];
	}
	$article['images'] = implode("@@@@", $img);
	$article['title'] = $title_m[1];
	$article['content'] = trim($matches[1]);  
	return $article;
	
}

function getLink($key){
	$url = "https://news.search.yahoo.com/search;_ylt=?p=".urlencode($key." site:news.yahoo.com");
	$content = html($url);
	preg_match_all("/<a class=\"yschttl spt\" href=\"(.*)\"/iUs", $content,$matches);
	foreach ($matches[1] as $l){
		if(strpos($l, "answers.yahoo.com")===false||strpos($l, "r.search.yahoo.com")===false||strpos($l, "movies.yahoo.com")===false){
			return $l;
		}
	}
	die("{\"message\":\"Link is null\"}");
}

function getKey(){
	$sql = "select * from yahoo_key order by postnum asc ,trafficBucketLowerBound desc ,gettime desc limit 1 ;";
	$key = query($sql);
	$sql = "update yahoo_key set postnum = postnum+1 where id = '".$key[0]['id']."' ;";
	mysql_query($sql);
	if (!empty($key[0]['title'])){
		return $key[0]['title'];
	} 
	die("{\"message\":\"Key is null\"}");
} 
function getTitle($data){
	return $data['title'];
}
function getTags($data){
	return implode(",", $data['tags']);
}
function getContent($data){
	return $data['text'];
}
function getHtml($data){
	return $data['html'];
}
function getMedia($data,$type="image"){
	$rs = array(); 
	if($type === "video"){
		foreach ($data['media'] as $val){
			if($val['type']=="video"){
				return $val['link'];
			}
		}
		return "";
	}
	$img_width = 0;
	foreach ($data['media'] as $val){
		if($val['type']=="image"){
			$img_dir = "./images/".time().".jpg";
			$img = file_put_contents($img_dir, html($val['link']));
			$size = getimagesize($img_dir);
			if($size[0]>200&&$size[1]>150){
				$caption = "   ";
				if(!empty($val['caption'])) $caption = $val['caption'];
				 
				$rs[] = $val['link']."<!--!>".$caption;
			}
		}
	}
	return   implode('@@@@@@@', $rs);
}









function getArticle($link){
	$url = "http://www.diffbot.com/api/article?token=diffbotcomtestdrive&format=json&tags=true&url=".urlencode($link);
	$data = json_decode(html($url),true);
	$article = array();
	$article['title'] = $data['title']; 
	if(empty($data['title'])) die($link."<br>Title is null");
	$article['content'] = $data['text'];
	if(empty($data['content'])) die($link."<br>Content is null");
	$article['gettime'] = date("Y-m-d H:i:s",time());
	$article['link'] = $data['url']; 
	$article['html'] = $data['html'];
	$article['images'] = getMedia($data);
	$article['vedio'] = getMedia($data,"video");
	println($article); 
	return $article;
}










function getComments($url,$aid=1){
	$html = html($url);
	preg_match("/content\_id=(.*)&amp;/iUs", $html,$comtent_id_m);
	if(empty($comtent_id_m[1])) return ;
	$url = urlencode($url) ;
	$comments = json_decode(html("http://news.yahoo.com/_xhr/contentcomments/get_all/?content_id=".$comtent_id_m[1]."&_device=full&done=".$url."&comments_listening_type=0&_media.modules.content_comments.switches._enable_view_others=1&_media.modules.content_comments.switches._enable_mutecommenter=1&enable_collapsed_comment=1"),true);
	preg_match_all("/nickname\">(.*)<span(.*)>(.*)<\/span>(.*)down\">(.*)<span class=\"count\">(.*)<\/span>(.*)up\">(.*)<span class=\"count\">(.*)<\/span>(.*)<p class=\"comment-content \">(.*)<\/p>/iUs", $comments["commentList"],$comment_m);
	$rs = array();
	$i=0;
	foreach ($comment_m[3] as $key=>$val){
		if($val){
			$rs[$i]['aid'] = $aid;
			$rs[$i]['nickname'] = $val;
			$rs[$i]['up'] = $comment_m[9][$key];
			$rs[$i]['down'] = $comment_m[6][$key];
			$rs[$i]['content'] = $comment_m[11][$key]; 
			$i++;
		}
	} 
	return $rs;
}






function insertDB($table,$data,$type='single'){
	$key = $val = array();
	switch ($type){
		case "single":
			foreach ($data as $k=>$v){
				$key[] = $k;
				$val[] = mysql_real_escape_string(stripslashes($v));
			}
			$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $key) . ') VALUES ("' . implode('", "', $val) . '");';
			break;
		default:
			$key = array_keys($data[0]);
			foreach ($data as $item)
			{ 
				$values = array();
				foreach ($key as $k) { $values[] = mysql_real_escape_string(stripslashes($item[$k])); }
				$val[] = implode('", "', $values);
			}
			$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $key) . ') VALUES ("' . implode('"), ("', $val) . '");';
			break;
	}
	mysql_query($sql);
	return  mysql_insert_id();
}

?>
 
 