<?php
	include(str_replace("\\", "/", dirname(__FILE__))."/lib/curl.class.php");
    include(str_replace("\\", "/", dirname(__FILE__))."/lib/config.php");
	date_default_timezone_set("Asia/Chongqing"); 
	$country = array("p30"=>"阿根廷","p29"=>"埃及","p44"=>"奥地利","p8"=>"澳大利亚","p18"=>"巴西","p41"=>"比利时","p31"=>"波兰","p49"=>"丹麦","p15"=>"德国","p14"=>"俄罗斯","p16"=>"法国","p25"=>"菲律宾","p50"=>"芬兰","p32"=>"哥伦比亚","p23"=>"韩国","p17"=>"荷兰","p13"=>"加拿大","p43"=>"捷克共和国","p37"=>"肯尼亚","p39"=>"罗马尼亚","p34"=>"马来西亚","p1"=>"美国","p21"=>"墨西哥","p40"=>"南非","p52"=>"尼日利亚","p51"=>"挪威","p47"=>"葡萄牙","p4"=>"日本","p42"=>"瑞典","p46"=>"瑞士","p36"=>"沙特阿拉伯","p12"=>"台湾","p33"=>"泰国","p24"=>"土耳其","p35"=>"乌克兰","p48"=>"希腊","p26"=>"西班牙","p10"=>"香港","p5"=>"新加坡","p45"=>"匈牙利","p6"=>"以色列","p27"=>"意大利","p3"=>"印度","p19"=>"印度尼西亚","p9"=>"英国","p28"=>"越南","p38"=>"智利");
	$j = 1; 
	for($i=0;$i<3;$i++)
	{
		echo $htd = date("Ymd",strtotime("-".$i." day"));
		echo "\r\n";
		
		$url = "http://www.google.com/trends/hottrends/hotItems";
		$my_curl = new myCurl();
		$my_curl->openCurl($url,"ajax=1&htd=".$htd."&pn=".$_REQUEST['c']."&htv=l");
		//print_r($my_curl->getOutput());
		$response = json_decode($my_curl->getOutput(),true);
		print_r($response['trendsByDateList']);
		$article = array();
		try{
			foreach($response['trendsByDateList'] as $_k=>$_v)
			{
				foreach($_v['trendsList'] as $__k=>$__v)
				{
					$sql = "insert into yahoo_key (title,relatedSearchesList,gettime,country,trafficBucketLowerBound) values
							 ('".str_conv($__v['title'])."','".str_conv(implode(",", $__v['relatedSearchesList']))."','".str_conv($__v['startTime'])."','".$country[$_REQUEST['c']]."','".$__v['trafficBucketLowerBound']."' )";
					mysql_query($sql);
					/* 
					foreach($__v['newsArticlesList'] as $____v)
					{
						$article[]= $____v;
						echo $sql = "insert into google_trends (title,link,source,snippet,gettime,country) values ('".str_conv($____v['title'])."','".str_conv($____v['link'])."','".str_conv($____v['source'])."','".str_conv($____v['snippet'])."','".$__v['startTime']."', '".$_REQUEST['c']."')";
						mysql_query($sql);
					}
					 */
				}
			}
		}catch(Exception $e){
			sleep(20); 
		}
		$my_curl->closeCurl(); 
	}
	$sql = "update google_trends_country set updatetime = '".date("Y-m-d H:i:s",time())."' where dataid = '".$_REQUEST['c']."'"; 
	mysql_query($sql);
?>
















































 