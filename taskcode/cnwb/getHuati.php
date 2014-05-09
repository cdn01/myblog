<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/date.php"); 
$now = date("Y-m-d",time());
if($now != $yesterday){
	file_put_contents("date.php",'<?php $yesterday ="'.$now.'"; ?>');
	mysql_query(" truncate huati "); 
print <<<EOT
<script type='text/javascript'>
		setTimeout("location.href='getHuati.php?p=1'",1000*10);
</script>
EOT;
}else{ 
	$p = @$_REQUEST['p']?$_REQUEST['p']:1;
	$url = "http://huati.weibo.com/aj_topiclist/big?ctg1=99&ctg2=0&prov=0&sort=day&p=$p&t=1&_t=0&__rnd=".time();
	$page = getHuati($url);
	$page_json = json_decode($page,true) ;
	$data = $page_json['data']['html'];  
	preg_match_all("/#(.*)#(.*)<span class=\"num_info\">(.*)<\/span>/iUs", $data, $matches);
	print_r($matches[1]);
	if(count($matches[1])<1){
		echo "<br><hr>";
		echo date("Y-m-d H:i:s",time());
		echo "<br><hr>";
		echo "30分钟后...下次运行";
print <<<EOT
<script type='text/javascript'>
		setTimeout("location.href='getHuati.php?p=1'",1000*60*30);
</script>
EOT;
	}else{ 
		foreach ($matches[1] as $key => $value) {
			echo $sql = "insert into huati (huati,zhishu,type,gettime) values ('".$value."','".$matches[3][$key]."','1','".date("Y-m-d H:i:s",time())."')";
			if(mysql_query($sql)){

			}else{
				$sql = "update huati set";
			}
		}
		$p_next = $p+1;
print <<<EOT
 <script type='text/javascript'>		
 	setTimeout("location.href='getHuati.php?p=$p_next'",1000*10);
 </script> 
EOT;
	}

}
/*
  
CREATE TABLE `huati` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `huati` varchar(255) DEFAULT NULL,
  `zhishu` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `gettime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `huati` (`huati`)
) ENGINE=MyISAM AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;

*/
?> 
















