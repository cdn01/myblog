<?php

/*

CREATE TABLE `matches` (
  `id` bigint(20) NOT NULL,
  `matches` text,
  `parent` int not null DEFAULT 0, 
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


http://roll.sohu.com/20140502/n399068546.shtml
*/
header("Content-type:text/html;charset=utf-8");
$url = "http://yule.sohu.com/20140430/n399015764.shtml";
$content = mb_convert_encoding(file_get_contents($url), "UTF-8","GBK") ;;
//echo $content;
$conn = mysql_connect("localhost","root","");
mysql_select_db("task",$conn);
mysql_query("set names utf8");

function query($sql){
	echo $sql;
	$cmd = mysql_query($sql);
	$result = array();
	while($rs = mysql_fetch_array($cmd))
	{
		$result[] = $rs;
	}
	if($result){
		return false;
	}
	return $result;
}

function println($str){
	echo $str."<br><hr><br>";
}

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
function match($str){
	$title_arr = query("select * from matches where typename = 'title' group by belong order by id desc");
	print_r($title_arr);
	//Title
	preg_match("/<h1 itemprop=\"headline\">(.*)<\/h1>/iUs",$str,$title_p);
	$title = trim($title_p[1]);
	println($title);
	//Content
	preg_match("/<\!\-\- 正文 \-\->(.*)<\!\-\- 分享 \-\->/iUs",$str,$content_p);
	$content = div_replace($content_p[1]);
	print_r($content);
}

match($content);
?>