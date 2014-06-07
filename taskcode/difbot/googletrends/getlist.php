<?php
	include(str_replace("\\", "/", dirname(__FILE__))."/curl.class.php");
    include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-13)."/common.php"); 
	date_default_timezone_set("Asia/Chongqing"); 
	$j = 1;
	for($i=0;$i<2;$i++)
	{
		echo $htd = date("Ymd",strtotime("-".$i." day"));
		echo "\r\n";
		
		$url = "http://www.google.com/trends/hottrends/hotItems";
		$my_curl = new myCurl();
		$my_curl->openCurl($url,"ajax=1&htd=".$htd."&pn=p1&htv=l");
		//print_r($my_curl->getOutput());
		$response = json_decode($my_curl->getOutput(),true);
		println($response['trendsByDateList']);
		die();
		$article = array();
		try{
			foreach($response['trendsByDateList'] as $_k=>$_v)
			{
				foreach($_v['trendsList'] as $__k=>$__v)
				{
					foreach($__v['newsArticlesList'] as $____v)
					{
						$article[]= $____v;
						echo $sql = "insert into en_article (title,link,source,snippet,gettime) values ('".str_conv($____v['title'])."','".str_conv($____v['link'])."','".str_conv($____v['source'])."','".str_conv($____v['snippet'])."','".time()."')";		
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
