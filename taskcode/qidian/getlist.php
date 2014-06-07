<?php
include(str_replace("\\", "/", dirname(__FILE__))."/config.php");
//include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-7)."/config.php");
$url = "http://read.qidian.com/BookReader/3175708.aspx";

$html = html($url);

preg_match_all("/<li(.*)itemprop='chapter'(.*)><a itemprop='url' href=\"(.*)\" title='(.*)'><span itemprop='headline'>(.*)<\/span><\/a><\/li>/iUs", $html, $matches);
//println($matches);
$article = array();
$novel_name = "神荒纪";
foreach ($matches[5] as $key=>$val){
	$article[$key]['novel_name'] = $novel_name;
	$article[$key]['title'] = $val;
	$article[$key]['link'] = $matches[3][$key];
	$article[$key]['createtime'] = date("Y-m-d H:i:s",time());
}
insertDB("qidian_article", $article,"array");

//vip
$vip_article = array();
if(preg_match_all("/<li style='width:25%;'><a rel=\"nofollow\" href=\"http:\/\/vipreader\.qidian\.com\/BookReader\/vip(.*)\" title='(.*)' target='\_blank'>(.*)<\/a><\/li>/iUs", $html, $vip_matches))
{
	foreach ($vip_matches[3] as $key=>$val){
		$vip_article[$key]['novel_name'] = $novel_name;
		$vip_article[$key]['title'] = $val;
		$vip_article[$key]['link'] = "http://vipreader.qidian.com/BookReader/vip".$vip_matches[1][$key];
		$vip_article[$key]['createtime'] = date("Y-m-d H:i:s",time());
	}
	insertDB("qidian_article", $vip_article,"array");
}
?>
<script type='text/javascript'>
		setTimeout("location.href='getlist.php'",1000*60*5);
</script>
