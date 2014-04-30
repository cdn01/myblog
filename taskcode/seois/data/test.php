<?php
//http://face.39.net/mrhf/hfyd/ky/kyff/index.html
include(str_replace("\\", "/", dirname(__FILE__)."/config.php")); 
// $url = "http://fitness.39.net/jfff/ydjf/";//运动减肥12  减肥
// $url = "http://sports.39.net/jskc/yykc/yywd/";//有氧舞蹈13 健身
// $url = "http://face.39.net/mrhf/hfcs/index.html";//护肤常识 9 护肤
// $url = "http://face.39.net/mrhf/hfyd/xiezhuang/index.html";// 卸妆 10 化妆
$url = "http://face.39.net/mrxf/hfcs/index.html";// 护发常识 11 护发
 


$list_content = mb_convert_encoding(html($url,false,"fitness.39.net",$url), "UTF-8","GBK") ;
// echo $list_content;
preg_match_all("/<span class=\"text\"><a href=\"(.*)\">(.*)<\/a>/iUs", $list_content,$link_m);
foreach($link_m[1] as $key => $val){
	if(!empty($val)){
		$sql = "insert into 39_article_2 (title,link,catid) values ('".$link_m[2][$key]."','".$val."','11')";
		mysql_query($sql);		
	}
	
}






























?>