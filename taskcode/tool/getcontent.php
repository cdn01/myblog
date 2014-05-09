<?php
require 'header.php';
$sql = "select * from auto_tag where  getlink = 0 order by pagenum asc ,zhishu desc limit 1 ";
$auto_tag = query($sql);
print_r($auto_tag[0]);

 

function get_link_sogou($source,$tag){
	$url = "http://news.sogou.com/news?query=site:$source".urlencode(" ".$tag)."&manual=true&mode=1&sort=0";
	println($url);
	$html = mb_convert_encoding(html($url), "UTF-8","GBK") ;
	preg_match("/<a class=\"pp\" href=\"(.*)\" id=\"uigs_0\" target=\"_blank\">(.*)<\/a>(.*)201(.*)-(.*)</iUs", $html,$matches);
	println($matches);
	if(empty($matches[1])){
		return ;
	} 
	$link[] = $matches[1];
	$link[] = "201{$matches[4]}-".$matches[5];
	return $link;
	
} 
function get_link_baidu($source,$tag){
	$url = "http://news.baidu.com/ns?ct=0&rn=20&ie=utf-8&rsv_bp=1&sr=0&cl=2&f=8&prevct=1&word=".urlencode($tag)."+site%3A".$source."&tn=newstitle&inputT=0";
	println($url);
	$html = html($url);
	preg_match("/<h3 class=\"c\-title\"><a href=\"(.*)\"(.*)data\-click/iUs", $html,$matches);
	preg_match("/&nbsp;201(.*)\-(.*)<\/span>/iUs", $html,$matches_d);
	if(empty($matches[1])) return;
	$link[] = $matches[1];
	$link[] = "201{$matches_d[1]}-".$matches_d[2];
	return $link;
}
$link_arr = array();
//$auto_tag[0]['tag']
$link_arr[] = get_link_baidu("sohu.com",$auto_tag[0]['tag']);
$link_arr[] = get_link_baidu("sina.com.cn", $auto_tag[0]['tag']);
$link_arr[] = get_link_baidu("163.com", $auto_tag[0]['tag']);
$link_arr[] = get_link_sogou("sohu.com",$auto_tag[0]['tag']);
$link_arr[] = get_link_sogou("sina.com.cn",$auto_tag[0]['tag']);
$link_arr[] = get_link_sogou("163.com", $auto_tag[0]['tag']);

println($link_arr);

$link =array("xx","2014-01-01");

foreach ($link_arr as $val){
	if($val[1]>$link[1]&&!empty($val[1])){
		$link = $val;
	}
} 
println($link);

$iaskbot = baidugate($link[0]);  

if(!empty($auto_tag[0]['tag'])){
	$sql = "insert into auto_article (link,tag,zhishu,content) values ('{$link[0]}','".str_conv($auto_tag[0]['tag'])."','{$auto_tag[0]['zhishu']}','".str_conv($iaskbot)."')";
	println($sql);
	mysql_query($sql);
	$sql = "update auto_tag set getlink = 1 where id='{$auto_tag[0]['id']}'";
	mysql_query($sql);
}

println($link);
?>
<script type='text/javascript'>
		setTimeout("location.href='getcontent.php'",1000*10);
</script>