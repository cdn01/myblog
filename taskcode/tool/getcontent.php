<?php
require 'header.php';
$sql = "select * from auto_tag order by pagenum asc ,zhishu desc limit 1 ";
$auto_tag = query($sql);
print_r($auto_tag[0]);

 

function get_link($source,$tag){
	$url = "http://news.sogou.com/news?query=site:$source".urlencode(" ".$tag)."&manual=true&mode=1&sort=0";
	println($url);
	$html = mb_convert_encoding(html($url), "UTF-8","GBK") ;
	preg_match("/<a class=\"pp\" href=\"(.*)\" id=\"uigs_0\" target=\"_blank\">(.*)<\/a><cite title=\"*(.*)\">(.*)2014-(.*)</iUs", $html,$matches);
	 
	$link[] = $matches[1];
	$link[] = "2014-".$matches[5];
	return $link;
} 
$link_arr = array();
$link_arr[] = get_link("sohu.com", $auto_tag[0]['tag']);
$link_arr[] = get_link("163.com", $auto_tag[0]['tag']);
$link_arr[] = get_link("ifeng.com", $auto_tag[0]['tag']);

println($link_arr); 

$link =array("xx","2014-01-01");

foreach ($link_arr as $val){
	if($val[1]>$link[1]){
		$link = $val;
	}
}

println($link);