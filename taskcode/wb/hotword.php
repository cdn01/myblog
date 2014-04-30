<?php
	include(str_replace("\\", "/", dirname(__FILE__))."/conn.php");   
	
	$param = array();
	$param['uname'] = 'bfire_03@126.com';
	$param['pwd'] = 'qingyu';
	$wb = new weibo();
	// 登录
	$islogin = $wb->login($param);  
	for ($i=0;$i<10;$i++)
	{
		echo $i."\n";
		$hotword_content = $wb->fileGetContents("http://m.weibo.cn/trends/getRankTopic?t=1&page=".$i."&&_=".time());  
		$hotwordArr = json_decode(trim($hotword_content),true); 
		// print_r($hotwordArr);
		foreach ($hotwordArr["data"] as $key => $value) {
			$sql = "insert into hotword (hotword,gettime) values ('".$value["word"]."','".date("Y-m-d H:i:s",time())."')";
			mysql_query($sql);
		}
		sleep(20);
	}
	die("over");
?>