<?php
require 'header.php';
 
echo "<br><hr>".date("H:i:s",time())."<br><hr>";
$url = "http://s.weibo.com/top/summary?cate=homepage";
$content = html($url);
// echo $content = str_replace("\/","/",str_replace('\"', '"', $content));
preg_match_all('~list_index_all\\\">(.*)<\\\/a>(.*)star_num\\\"><span>(.*)<\\\/span>~iU', $content,$keywords_t1);
preg_match_all('~list_index_event\\\">(.*)<\\\/a>(.*)star_num\\\"><span>(.*)<\\\/span>~iU', $content,$keywords_t2);
preg_match_all('~list_index_films\\\">(.*)<\\\/a>(.*)star_num\\\"><span>(.*)<\\\/span>~iU', $content,$keywords_t3);
preg_match_all('~list_index_person\\\">(.*)<\\\/a>(.*)star_num\\\"><span>(.*)<\\\/span>~iU', $content,$keywords_t4);
preg_match_all('~list_index_sports\\\">(.*)<\\\/a>(.*)star_num\\\"><span>(.*)<\\\/span>~iU', $content,$keywords_t5);
preg_match_all('~list_index_finance\\\">(.*)<\\\/a>(.*)star_num\\\"><span>(.*)<\\\/span>~iU', $content,$keywords_t6);
 
$keyword_arr = array();

foreach ($keywords_t1[1] as $k1 => $v1) {
	if($k1<10){
		$keyword_arr[] = array("tag"=>unicode_decode($v1),"zhishu"=>$keywords_t1[3][$k1]); 
	}
		
}
foreach ($keywords_t2[1] as $k2 => $v2) {
	if($k2<10){
		$keyword_arr[] = array("tag"=> unicode_decode($v2),"zhishu"=> $keywords_t2[3][$k2]); 
	}
}
foreach ($keywords_t3[1] as $k3 => $v3) {
	if($k3<9){
		$keyword_arr[] = array("tag"=> unicode_decode($v3),"zhishu"=> $keywords_t3[3][$k3]); 
	}
}
foreach ($keywords_t4[1] as $k4 => $v4) {
	if($k4<6){
		$keyword_arr[] = array("tag"=> unicode_decode($v4),"zhishu"=> $keywords_t4[3][$k4] ); 
	}
}
foreach ($keywords_t5[1] as $k5 => $v5) {
	if($k5<6){
		$keyword_arr[] = array("tag"=> unicode_decode($v5),"zhishu"=> $keywords_t5[3][$k5]); 
	}
}
  
$keyword_arr = array_reverse($keyword_arr);
// print_r($keyword_arr);

foreach ($keyword_arr as $key => $value) {
	$sql =  "insert into auto_tag (tag,catid,gettime,zhishu) values ('".$value['tag']."','3','".date("Y-m-d H:i:s",time())."','".$value['zhishu']."') ;";
	// echo $sql;
	// echo "<br><hr>";
	if(!mysql_query($sql)){
		$sql = "select * from auto_tag where tag ='".$value['tag']."' and gettime > '".date("Y-m-d",time())."' ";
		$temp = query($sql);
		if(!isset($temp[0]['id'])){
			$sql = "update auto_tag set gettime = '".date("Y-m-d H:i:s",time())."' ,zhishu='".$value['zhishu']."', pagenum = '0' where tag='".$value['tag']."' ;";
			mysql_query($sql);
		}
	}
}

/* 
//实时热点排行榜 
getContent("http://top.baidu.com/buzz?b=1",4); 
//七日关注排行榜 
getContent("http://top.baidu.com/buzz?b=42&c=12",2);
//今日社会民生排行榜 
getContent("http://top.baidu.com/buzz?b=342",6);
//今日娱乐八卦排行榜
getContent("http://top.baidu.com/buzz?b=344&c=7",7);
//今日世说新词排行榜
getContent("http://top.baidu.com/buzz?b=396&c=12",8);

 */




function getContent($url,$catid){
	$html = mb_convert_encoding(html($url), "UTF-8","GBK") ;
	// echo $html;
	preg_match_all("/<a class=\"list-title\" target=\"\_blank\" href=\"\.\/detail(.*)\">(.*)<\/a>/iUs", $html, $ss_tag_m);
	print_r($ss_tag_m);
	$keyword_arr = $ss_tag_m[2];
	preg_match_all("/<span class=\"icon-(.*)\">(.*)<\/span>/iUs", $html, $num_tag_m);
	print_r($num_tag_m);
	$keyword_arr_num = $num_tag_m[2];
	insertDB($keyword_arr,$keyword_arr_num,$catid);
}










// print_r($keyword_arr);
function insertDB($keyword_arr,$keyword_arr_num,$catid=47){
	foreach ($keyword_arr as $key => $value) {
	$sql =  "insert into auto_tag (tag,catid,gettime,zhishu) values ('".$value."','".$catid."','".date("Y-m-d H:i:s",time())."','".$keyword_arr_num[$key]."') ;";
	// echo $sql;
	// echo "<br><hr>";
	if(!mysql_query($sql)){
			$sql = "select * from auto_tag where tag ='".$value."' and gettime > '".date("Y-m-d",time())."' ";
			$temp = query($sql);
			if(!isset($temp[0]['id'])){
				$sql = "update auto_tag set gettime = '".date("Y-m-d H:i:s",time())."' , pagenum = '0' ,zhishu='".$keyword_arr_num[$key]."' where tag='".$value."' ;";
				mysql_query($sql);
			}
		}
	}
}


 
?>
<script type='text/javascript'>
		setTimeout("location.href='gettags.php'",1000*60*30);
</script>