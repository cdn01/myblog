<?php
include(str_replace("\\", "/", dirname(__FILE__))."/lib/config.php");
include(str_replace("\\", "/", dirname(__FILE__))."/lib/curl.class.php");
include(str_replace("\\", "/", dirname(__FILE__))."/lib/google_translate.php");
 $begin_time= microtime();

$sql = "select * from google_trends where 1=1 and isgetcontent=0 and country='p1' order by gettime desc, articleid asc limit 1 ; ";
$article_arr = query($sql);
$url = "http://www.diffbot.com/api/article?token=diffbotcomtestdrive&format=json&tags=true&url=";
echo $url .= urlencode($article_arr[0]['link']);
$my_curl = new myCurl();
$my_curl->openCurl($url);
$response = json_decode($my_curl->getOutput(),true);
$my_curl->closeCurl();
/*
if(strlen($response["text"])<200) die(); 
*/
$content = rewrite($response["text"],"en");

	
$tag = "";
foreach ($response["tags"] as $key=>$val){
	if($val&&$key===0){
		$tag .= $val;
	}
	if($val&&$key>0){
		$tag .= ",".$val;
	}
}
$media = "";
$imgI = 0;
foreach ($response["media"] as $val){
	if(!empty($val["link"])&&isset($val['link'])){
		$img_info = @getimagesize($val['link']);
		if($img_info[0]>350&&$img_info[1]>150){
			$media .= $imgI==0?$val['link']:"@@@@@".$val["link"];
		}
	}
	$imgI++;
}
$sql = "update google_trends set isgetcontent=1  , gtime='".date("Y-m-d H:i:s",time())."' ,tag='".str_conv($tag)."', media ='".$media."',content = '".str_conv($response["html"])."' , contentdiv = '".str_conv($content)."' where articleid='".$article_arr[0]['articleid']."' ;";
if(strlen($content)<100){
	$sql = "update google_trends set isgetcontent=2 ,gtime='".date("Y-m-d H:i:s",time())."' where articleid='".$article_arr[0]['articleid']."' ;";
}

mysql_query($sql);

$end_time = microtime(); 
$starttime = explode(" ",$begin_time);
$endtime = explode(" ",$end_time);
$totaltime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
$timecost = sprintf("%s",$totaltime);
echo "<br><hr>页面运行时间: $timecost 秒"; 
?>
<script type='text/javascript'>
		setTimeout("location.href='diffbot.php'",1000*30);
</script>