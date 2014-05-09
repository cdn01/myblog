<?php
require 'header.php'; 
$title = "";
function div_replace($content){
	$content = trim($content);
	$content = preg_replace("/<ifram(.*)>(.*)<\/iframe>/iUs","",$content);
	$content = preg_replace("/<a(.*)>/iUs","<font class='focus_font'>",$content);
	$content = str_replace("</a>","</font>",$content);
	$content = preg_replace("/<div style=\"display:none;\">(.*)<\/div>/iUs","",$content);
	$content = preg_replace("/<\!\-\- (.*) \-\->/iUs","",$content);
	$content = preg_replace("/class=\"(.*)\"/iUs","",$content);
	$content = preg_replace("/id=\"(.*)\"/iUs","",$content);
	$content = preg_replace_callback("/<[img|Img|IMG](.*)src=[\'|\"](.*)[\'|\"](.*)>/iUs", "getImage",$content);
	return $content;
}
function getImage($matches){ 
	global $title;
	$img_info = @getimagesize($matches[2]);
	if($img_info[0]<300&&$img_info[1]<300){
		return "";
	}else{
		return " <img src='".$matches[2]."' alt='".$title."'> ";
	}
}
function match_soho($str){
	global $title;
	preg_match("/<h1 itemprop=\"headline\">(.*)<\/h1>/iUs",$str,$title_p);
	$title = trim($title_p[1]);
	preg_match("/<\!\-\- 正文 \-\->(.*)<\!\-\- 分享 \-\->/iUs",$str,$content_p);
	return $content = div_replace($content_p[1]);
}

function match_ifeng($str){
	global $title;
	preg_match("/<h1 itemprop=\"headline\" id=\"artical_topic\">(.*)<\/h1>/iUs", $str,$title_p);
	$title = trim($title_p[1]);
	preg_match("/<\!\-\-mainContent begin\-\->(.*)<\!\-\-mainContent end\-\->/iUs",$str,$content_p);
	return $content = div_replace($content_p[1]);
}
$content = "";
$url = "http://ent.ifeng.com/a/20140507/40049157_0.shtml";
$file = html($url);
if(!preg_match("/charset=\"utf(.*)\"/iUs", $file)){
	$file = mb_convert_encoding(html($url), "UTF-8","GBK") ;
} 
if(strpos($file,"<!-- 分享 -->")){
	$content = match_soho($file);
}
else if(strpos($file,"<!--mainContent end-->")){
	$content = match_ifeng($file);
}

if(!$content) die("content is null");
println($content);
$wei_content = rewrite($content);
$sql = "insert into auto_article (link,tag,content,title,gettime,catid,source,click) value ('".$url."','".str_conv($tag)."','".str_conv($content)."','".str_conv($title)."','".date("Y-m-d H:i:s",time())."','".$catid."','sohu','".$click."')";
$rs = mysql_query($sql);
println();
?>