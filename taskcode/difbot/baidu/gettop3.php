<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0)."/common.php"); 
echo "<br><hr>".date("H:i:s",time())."<br><hr>";
$url = "http://s.weibo.com/top/summary?cate=homepage";
$content = html($url);
// echo $content = str_replace("\/","/",str_replace('\"', '"', $content));
preg_match_all('~list_index_all\\\">(.*)<\\\/a>~iU', $content,$keywords_t1);
preg_match_all('~list_index_event\\\">(.*)<\\\/a>~iU', $content,$keywords_t2);
preg_match_all('~list_index_films\\\">(.*)<\\\/a>~iU', $content,$keywords_t3);
preg_match_all('~list_index_person\\\">(.*)<\\\/a>~iU', $content,$keywords_t4);
preg_match_all('~list_index_sports\\\">(.*)<\\\/a>~iU', $content,$keywords_t5);
preg_match_all('~list_index_finance\\\">(.*)<\\\/a>~iU', $content,$keywords_t6);

print_r($keywords_t1[1]);
print_r($keywords_t2[1]);
print_r($keywords_t3[1]);
print_r($keywords_t4[1]);
print_r($keywords_t6[1]);
$keyword_arr = array();

foreach ($keywords_t1[1] as $k1 => $v1) {
	if($k1<9)
		$keyword_arr[] = unicode_decode($v1);
}
foreach ($keywords_t2[1] as $k2 => $v2) {
	if($k2<6)
		$keyword_arr[] = unicode_decode($v2);
}
foreach ($keywords_t3[1] as $k3 => $v3) {
	if($k3<6)
		$keyword_arr[] = unicode_decode($v3);
}
foreach ($keywords_t4[1] as $k4 => $v4) {
	if($k4<6)
		$keyword_arr[] = unicode_decode($v4);
}
foreach ($keywords_t5[1] as $k5 => $v5) {
	if($k5<6)
		$keyword_arr[] = unicode_decode($v5);
}

print_r($keyword_arr);
$keyword_arr = array_reverse($keyword_arr);
foreach($keyword_arr as $ka=>$kv){
	$url = "http://m.baidu.com/ssid=0/from=0/bd_page_type=1/uid=0/pu=sz%40224_220%2Cta%40middle____/s?tn_1=webmain&tn_4=bdwns&rn=10&pn=0&st_1=111441&st_4=104441&vit=tj&pfr=3-11-bdindex-top-1-search-&word=".urlencode($kv)."&ct_4=%E6%90%9C%E6%96%B0%E9%97%BB";
	$baidu_news = html($url);
	preg_match_all("/<a href=\".\/t=news\/tc(.*)\">(.*)<\/a>/iUs", $baidu_news , $matches);
	print_r($matches);
	foreach ($matches[2] as $mkey => $mvalue) {
		preg_match("/src=(.*)/i", $matches[1][$mkey],$url_m);
		$url_t = urldecode($url_m[1]); 
		$search_sql = "select * from article where link = '".$url_t."'";
		$article = query($search_sql);
		if(empty($article[0]['id'])){
			$title = str_replace($kv, "#".$kv."#", $mvalue); 
			if(strpos($title, ".")>=1&&strpos($title, ".")<=2)	$title = substr($title, strpos($title, ".")+1);
			$title = preg_replace("/<\/?span(.*)>/iUs", "", $title);
			if(strpos($mvalue, "...")){
				$dif_url = "http://api.diffbot.com/v2/article?token=716d9dea9ea0fe94def7a8a484aaa298&url=".$url_m[1];
				$dif_data = html($dif_url);
				$data = json_decode($dif_data,true);
				$title = str_replace($kv, "#".$kv."#", $data['title']);
			} 
			if(strlen($title)>10){
				$insert_sql = "insert into article (link,title,gettime) values ('".$url_t."','".str_conv($title)."','".date("Y-m-d H:i:s",time())."')";
				mysql_query($insert_sql);	
			}
		}
		
	} 
	sleep(10); 
	
}
 

?>
<script type='text/javascript'>
	 setTimeout("location.href='gettop3.php'",1000*60*30);
</script>