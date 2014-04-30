<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-7)."/config.php");

$sql = "select * from auto_tag where pagenum < 4 and catid!=3 and gettime > '".date("Y-m-d",time())."'  order by pagenum asc, zhishu desc limit 1";

$tag_arr = query($sql);
if(isset($tag_arr[0]["tag"])){ 
	echo date("Y-m-d H:i:s",time());
	echo "<br><hr>"; 
	echo "搜狐<br><hr>";
	echo $url = "http://news.sogou.com/news?query=site:sohu.com".urlencode(" ".$tag_arr[0]["tag"])."&manual=true&mode=1&sort=0";
	echo "<br><hr>";
	$html = mb_convert_encoding(html($url), "UTF-8","GBK") ;
	// echo $html;
	preg_match("/<a class=\"pp\" href=\"(.*)\" id=\"uigs_0\" target=\"_blank\">(.*)<\/a>/iUs", $html,$matches);
	// print_r($matches);
	echo $link = $matches[1];
	echo "<br><hr>"; 
	$tag = $tag_arr[0]["tag"];
	$catid = $tag_arr[0]["catid"];
	$click = $tag_arr[0]["zhishu"]+rand(1,500);
	$tagid = $tag_arr[0]["id"];
	$sql = "select * from auto_article where link ='".$link."' ";
	$is_exist = query($sql);
	$title = "";
	$content = "";
	if(!isset($is_exist[0]['id'])){
		$content_t = mb_convert_encoding(html($link), "UTF-8","GBK") ;
		// echo $content_t; 
		if(strpos($content_t, '<!-- 文章标题 -->')){
			preg_match("/<!-- 文章标题 -->(.*)<h1(.*)>(.*)<\/h1>/iUs",$content_t,$title_m);
			// print_r($title_m);
			$title = $title_m[3];
			preg_match("/<div class=\"text clear\" id=\"contentText\">(.*)<div class=\"original-title\">/iUs", $content_t,$content_m);
			$content = "<div class=\"text clear\" id=\"contentText\">".preg_replace("/<iframe(.*)<\/script>/iUs", "", $content_m[1]);
			// echo $content;
		}else{ 
			preg_match("/topicTitle = '(.*)';/iUs", $content_t,$title_m);
			// print_r($title_m);
			$title = $title_m[1]; 
			preg_match("/<div class=\"text clear\" id=\"contentText\">(.*)<div class=\"autoShare clear\">/iUs", $content_t,$content_m);
			$content = "<div class=\"text clear\" id=\"contentText\">".preg_replace("/<script(.*)<\/script>/iUs", "", $content_m[1]);
			// print_r($content);
		}

		if($title&&$content&$tag){
			insert_article($title,$content,$tag,$link,$catid,"souhu",$click); 
		} 
	}
	set_update_tag($tagid);  
	echo $title."<br><hr>".$content."<br><hr>";

	//凤凰
	echo "凤凰<br><hr>";
	$link = get_link("ifeng.com",$tag_arr[0]["tag"]);
	$click = $tag_arr[0]["zhishu"]+rand(1,500);
	$title = "";
	$content = "";
	echo $link."<br><hr>";
	if(!link_exist($link)){
		$html = html($link) ;
		preg_match("/<h1(.*)id=\"artical_topic\">(.*)<\/h1>/iUs", $html,$title_m);
		// print_r($title_m);
		$title = $title_m[2];
		// echo $title."<br><hr>"; 
		preg_match("/<div id=\"main_content\"(.*)>(.*)<span class=(.*)ifengLogo(.*)>/iUs", $html,$content_m);
		// print_r($content_m);
		$content = "<div id=\"main_content\">".$content_m[2]."</p></div>";
		// echo $content."<br><hr>";
		if($title&&$content&$tag){
			$rs = insert_article($title,$content,$tag,$link,$catid,"ifeng",$click); 
			// $sql = "insert into auto_article (link,tag,content,title,gettime,catid,source,click) value ('".$link."','".$tag."','".$content."','".$title."','".date("Y-m-d H:i:s",time())."','".$catid."','sohu','".$click."')";	
			// echo $sql ;
			if($rs){
				$sql = "update auto_tag set pagenum = pagenum +1 where id='".$tagid."' ;";
				mysql_query($sql);	
			}	
		}
	 
	} 
	set_update_tag($tagid);
	echo $title."<br><hr>".$content."<br><hr>";


	//网易 
	$link = get_link("163.com",$tag_arr[0]["tag"]);
	$click = $tag_arr[0]["zhishu"]+rand(1,500);
	$title = "";
	$content = "";
	echo $link."<br><hr>";
	if(!link_exist($link)){
		$html = mb_convert_encoding(html($link), "UTF-8","GBK") ; ;
		preg_match("/<h1 id=\"h1title\"(.*)>(.*)<\/h1>/iUs", $html,$title_m);
		$title = $title_m[2];
		preg_match("/<div id=\"endText\">(.*)<div class=(.*)ep-source cDGray(.*)>/iUs", $html,$content_m);
		$content = "<div class=\"text clear\" id=\"contentText\">".preg_replace("/<script(.*)<\/script>/iUs", "", $content_m[1]);
		$content = preg_replace("/<p><b>延伸阅读(.*)<!--文章主体结束-->/iUs", "", $content)."</div>";
		if($title&&$content&$tag){
			$rs = insert_article($title,$content,$tag,$link,$catid,"163",$click); 
			if($rs){
				$sql = "update auto_tag set pagenum = pagenum +1 where id='".$tagid."' ;";
				mysql_query($sql);	
			}	
		} 
	} 
	set_update_tag($tagid);
	echo $title."<br><hr>".$content."<br><hr>";

}
/////common

function get_link($source,$tag){
	echo $url = "http://news.sogou.com/news?query=site:$source".urlencode(" ".$tag)."&manual=true&mode=1&sort=0";
	echo "<br><hr>";
	$html = mb_convert_encoding(html($url), "UTF-8","GBK") ;
	// echo $html;
	preg_match("/<a class=\"pp\" href=\"(.*)\" id=\"uigs_0\" target=\"_blank\">(.*)<\/a>/iUs", $html,$matches);
	// print_r($matches);
	$link = $matches[1]; 
	return $link;
}

function link_exist($link){
	$sql = "select * from auto_article where link ='".$link."' ";
	$is_exist = query($sql);
	if(!isset($is_exist[0]['id'])){
		return false;
	}
	return true;
}

// function set_add($title,$content,$tag,$link,$catid,$source,$click,$tagid){
// 	if($title&&$content&$tag){
// 		$sql = "insert into auto_article (link,tag,content,title,gettime,catid,source,click) value ('".$link."','".str_conv($tag)."','".str_conv($content)."','".str_conv($title)."','".date("Y-m-d H:i:s",time())."','".$catid."','".$source."','".$click."')";	
// 		echo $sql ;
// 		mysql_query($sql);
// 	}
// 	set_update_tag($tagid);
// }

function set_update_tag($tagid){
	echo $sql = "update auto_tag set pagenum = pagenum +1 where id='".$tagid."' ;";
	mysql_query($sql);	
} 

function insert_article($title,$content,$tag,$link,$catid,$source,$click){
	$content = preg_replace("/<\/?iframe(.*)>/iUs","",$content);
	$content = preg_replace("/<\/?div(.*)>/iUs","",$content);
	$content = preg_replace("/<a(.*)>/iUs","<font id='tagkey'>",$content);
	$content = str_replace("</a>","</font>",$content);
	$content = preg_replace("/<link(.*)>/iUs","",$content);
	$content = preg_replace("/style=\"(.*)\"/iUs","",$content);
	$content = preg_replace("/<script(.*)>(.*)<\/script>/iUs","",$content);
	

	if(strlen($content)<100) return false;
	$sql = "insert into auto_article (link,tag,content,title,gettime,catid,source,click) value ('".$link."','".str_conv($tag)."','".str_conv($content)."','".str_conv($title)."','".date("Y-m-d H:i:s",time())."','".$catid."','sohu','".$click."')";	
	$rs = mysql_query($sql);
	return $rs;
}
?>
<script type='text/javascript'>
	 setTimeout('location.href="getcontent.php"',1000*9);
</script>