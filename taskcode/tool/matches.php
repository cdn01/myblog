<?php
require 'header.php'; 

function div_replace($content){
	$content = trim($content);
	$content = preg_replace("/<ifram(.*)>(.*)<\/iframe>/iUs","",$content);
	$content = preg_replace("/<a(.*)>/iUs","<font class='focus_font'>",$content);
	$content = str_replace("</a>","</font>",$content);
	$content = preg_replace("/<div style=\"display:none;\">(.*)<\/div>/iUs","",$content);
	$content = preg_replace("/<\!\-\- (.*) \-\->/iUs","",$content);
	
	return $content;
}
function getImage($content){
	preg_match_all("//");
return ;
}
function match_soho($str){
	preg_match("/<h1 itemprop=\"headline\">(.*)<\/h1>/iUs",$str,$title_p);
	$title = trim($title_p[1]);
	preg_match("/<\!\-\- 正文 \-\->(.*)<\!\-\- 分享 \-\->/iUs",$str,$content_p);
	return $content = div_replace($content_p[1]);
}

function match_ifeng($str){
	preg_match("/<h1 itemprop=\"headline\" id=\"artical_topic\">(.*)<\/h1>/iUs", $str,$title_p);
	$title = trim($title_p[1]);
	preg_match("/<\!\-\-mainContent begin\-\->(.*)<\!\-\-mainContent end\-\->/iUs",$str,$content_p);
	return $content = div_replace($content_p[1]);
}
$content = "";
$url = "http://ent.ifeng.com/a/20140507/40049157_0.shtml";
$file = mb_convert_encoding(html($url), "UTF-8","GBK") ;
if(strpos("<!-- 分享 -->", $file)){
	$content = match_soho($file);
}
else if(strpos("<!--mainContent end-->", $file)){
	$content = match_ifeng($file);
}

println($content);
?>