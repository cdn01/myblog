<?php
//http://face.39.net/mrhf/hfyd/ky/kyff/index.html
include(str_replace("\\", "/", dirname(__FILE__)."/config.php")); 
$url = "http://face.39.net/mrhf/hfyd/ky/kyff/index.html";
$list_content = mb_convert_encoding(html($url), "UTF-8","GBK") ;
// echo $list_content;
preg_match_all("/<span class=\"text\"><a href=\"(.*)\">(.*)<\/a>/iUs", $list_content,$link_m);
foreach($link_m[1] as $key => $val){
	if(!empty($val)){
		$sql = "insert into 39_article (title,link) values ('".$link_m[2][$key]."','".$val."')";
		mysql_query($sql);		
	}
	
}






























?>