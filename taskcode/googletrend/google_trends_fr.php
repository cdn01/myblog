<?php
	include(str_replace("\\", "/", dirname(__FILE__))."/lib/curl.class.php");
    include(str_replace("\\", "/", dirname(__FILE__))."/lib/config.php");
	date_default_timezone_set("Asia/Chongqing"); 
	$j = 1;
	for($i=0;$i<2;$i++)
	{
		echo $htd = date("Ymd",strtotime("-".$i." day"));
		echo "\r\n";
		
		$url = "http://www.google.com/trends/hottrends/hotItems";
		$my_curl = new myCurl();
		$my_curl->openCurl($url,"ajax=1&htd=".$htd."&pn=p16&htv=l");
		//print_r($my_curl->getOutput());
		$response = json_decode($my_curl->getOutput(),true);
		// print_r($response['trendsByDateList']);
		$article = array();
		try{
			foreach($response['trendsByDateList'] as $_k=>$_v)
			{
				foreach($_v['trendsList'] as $__k=>$__v)
				{
					foreach($__v['newsArticlesList'] as $____v)
					{
						echo $____v["title"]."<br><hr>";
						$article[]= $____v;
						echo $sql = "insert into en_article (title,link,source,snippet,gettime,gtime,country) values ('".str_conv($____v['title'])."','".str_conv($____v['link'])."','".str_conv($____v['source'])."','".str_conv($____v['snippet'])."','".time()."','".date("Y-m-d H:i:s",time())."','fr')";		
						mysql_query($sql); 
					}
				}
			}
		}catch(Exception $e){
			sleep(20); 
		}
		$my_curl->closeCurl(); 
	} 
?>
<script type='text/javascript'>
	 setTimeout("location.href='google_trends.php'",1000*60*30);
</script>