<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0)."/common.php"); 
echo "<br><hr>".date("H:i:s",time())."<br><hr>";
$url = "http://s.weibo.com/ajax/flash/hotband";
$content = json_decode(html($url),true);
// print_r($content);
$keyword_arr = array();
foreach ($content["data"] as $key => $value) {
	$keyword_arr[]= $value["word"];
}
print_r($keyword_arr);
die();
 
// print_r($keyword_arr); 
$keyword_arr = array_reverse($keyword_arr);
foreach($keyword_arr as $ka=>$kv){ 

	$url = "http://news.baidu.com/ns?word=".urlencode($kv)."&tn=news&from=news&cl=2&rn=20&ct=1";
	$baidu_news = html($url,false,"news.baidu.com");
	
	preg_match_all("/<div class=\"c-summary\">(.*)<img src=\"(.*)\"(.*)<\/div>/iUs", $baidu_news , $matches);
	 
	$img_url_arr = $matches[2];
	$img_arr = array();
	foreach ($img_url_arr as $pkey => $pval) { 
		sleep(1);
		$img_arr[$pkey] = getImageDir();
		file_put_contents($img_arr[$pkey], html(htmlspecialchars_decode($pval),false,false,"http://news.baidu.com/ns?word=%B1%A3%D6%D8%C9%ED%CC%E5&tn=news&from=news&cl=2&rn=20&ct=1"));
	} 
	preg_match_all("/<h3 class=\"c-title\"><a href=\"(.*)\"(.*)>(.*)<\/a>/iUs", $baidu_news , $matches);
	 
	$title_arr = $matches[3];
	$link_arr = $matches[1];
	foreach ($title_arr as $mkey => $mvalue) { 
		$link = $link_arr[$mkey]; 
		$search_sql = "select * from article where link = '".$link."'";
		$article = query($search_sql);
		if(empty($article[0]['id'])){
			$title = str_replace("</em>", "",str_replace("<em>", "", $mvalue)); 
			if(strpos($mvalue, "...")){
				$dif_url = "http://api.diffbot.com/v2/article?token=716d9dea9ea0fe94def7a8a484aaa298&url=".$link;
				$dif_data = html($dif_url);
				$data = json_decode($dif_data,true);
				$title = $data['title'];
			} 
			$title = "#".$kv."# ".$title;
			if(strlen($title)>20){
				$insert_sql = "insert into article (link,title,gettime) values ('".$link."','".str_conv($title)."','".date("Y-m-d H:i:s",time())."')";
				if($img_url_arr[$mkey]){
					$insert_sql = "insert into article (link,title,gettime,image_dir) values ('".$link."','".str_conv($title)."','".date("Y-m-d H:i:s",time())."','".$img_arr[$mkey]."')";
				}
				echo $insert_sql; 
				mysql_query($insert_sql);	
			}
		} 
	} 
	sleep(10);  
}
 

?>
<script type='text/javascript'>
	 setTimeout('location.href="gethotband.php"',1000*60*30);
</script>