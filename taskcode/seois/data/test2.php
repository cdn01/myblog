<?php
include(str_replace("\\", "/", dirname(__FILE__)."/config.php")); 
$pagestartime=microtime();  
echo $sql = "select * from emlog_blog where isget =0 and sortid in (9,10,11,12,14) order by rand() limit 1";
$url = query($sql);
// print_r($url);
getPage($url);
function getPage($url){
	$t_title = "";
	$t_desc = "";
	$t_content = "";
	if(isset($url[0]['gid'])){ 
		$content = $url[0]["content"];  
		$t_content .= tojb(str_replace("www.39.net", "www.seois.org", $content));
		// echo "1111<br><hr>";
		// translate($t_content);
		// die();
	}

	if(!empty($t_content)){
		// echo "2222<br><hr>";
		$title = $url[0]['title'];
		$title_arr = explode(" ", $title);

		foreach ($title_arr as $key => $value) {
			if(!empty($value)){
				$t_title .= trim(preg_replace("/<\/?(.*)>/iUs", "", translate($value)))."、" ;
			}
		}  
		$t_title = substr($t_title, 0,-3); 
	} 
	if(!empty($t_content)&&!empty($t_title)){
		$sql = "update emlog_blog set isget = 1 , jp_title = '".str_conv($t_title)."' ,jp_content='".str_conv($t_content)."'  where gid='".$url[0]['gid']."'";
		mysql_query($sql);
	}

	echo date("Y-m-d H:i:s",time())."<br><hr>".$t_title."<br><hr>" ;
}




$pageendtime = microtime();
$starttime = explode(" ",$pagestartime);
$endtime = explode(" ",$pageendtime);
$totaltime = $endtime[0]-$starttime[0]+$endtime[1]-$starttime[1];
$timecost = sprintf("%s",$totaltime);
echo "<br><hr>";
echo "页面运行时间: $timecost 秒"; 

?>
<script type='text/javascript'>
	setTimeout('location.href="test2.php"',5000);
</script>