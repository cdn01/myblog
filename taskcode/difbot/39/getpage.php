<?php
include(str_replace("\\", "/", dirname(__FILE__)."/config.php")); 
$pagestartime=microtime(); 

$sql = "select * from 39_article where isget =0 limit 1";
$url = query($sql);
$t_title = "";
$t_desc = "";
$t_content = "";
if(isset($url[0]['id'])){
	$html = mb_convert_encoding(html($url[0]['link']), "UTF-8","GBK") ;
	preg_match("/<p class=\"summary\">(.*)<\/p/iUs", $html ,$desc_m);
	$desc = $desc_m[1];
	$t_desc .= tojb($desc);
	preg_match("/<div class=\"art_con\" id=\"contentText\">(.*)<div class=\"art_page\">/iUs", $html,$content_m);
	$content = $content_m[1];
	$content = preg_replace("/<\!-- AFP Control Code\/Caption\.左下竖幅-->(.*)<\!-- AFP Control Code End\/No\.200-->/iUs", "", $content);
	$content = preg_replace("/<style(.*)>(.*)<\/style>/iUs", "", $content);
	$content = preg_replace("/<script(.*)>(.*)<\/script>/iUs", "", $content);
	$content = preg_replace("/<\/?div(.*)>/iUs", "", $content);
	$content_arr = explode("</p>", $content);
	// print_r($content_arr);
	foreach ($content_arr as $key => $value) {
		if(!empty($value)){
			$value = $value."</p>";
			$t_content .= tojb($value);
		}
	}
}

if(!empty($t_content)){
	$title = $url[0]['title'];
	$title_arr = explode(" ", $title);

	foreach ($title_arr as $key => $value) {
		if(!empty($value)){
			$t_title .= translate($value);
		}
	}
}

if(!empty($t_content)&&!empty($t_title)){
	$sql = "update 39_article set isget = 1 , jp_title = '".str_conv($t_title)."' ,jp_content='".str_conv($t_content)."', content='".str_conv($t_content)."' , description ='".str_conv($desc)."' , jp_desc='".str_conv($t_desc)."' where id='".$url[0]['id']."'";
	mysql_query($sql);
}

echo date("Y-m-d H:i:s",time())."<br><hr>".$url[0]['link']."<br><hr>".$t_title."<br><hr>".$t_desc."<br><hr>".$t_content."<br><hr>";



$pageendtime = microtime();
$starttime = explode(" ",$pagestartime);
$endtime = explode(" ",$pageendtime);
$totaltime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
$timecost = sprintf("%s",$totaltime);
echo "页面运行时间: $timecost 秒"; 

?>
<script type='text/javascript'>
	 setTimeout('location.href="getpage.php"',10);
</script>