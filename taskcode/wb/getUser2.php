<?php
include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 

$param = array();
$param['uname'] = 'cmd_12@126.com';
$param['pwd'] = 'qingyu';
$wb = new weibo();
// 登录
$islogin = $wb->login($param);

for($i=1;$i<=10;$i++)
{
	$page = "http://m.weibo.cn/news/mblog?&page=".$i;
	$userlist = $wb->_html($page);
	preg_match("/\{(.*)\}/is", $userlist , $matches);
	$userArr = json_decode($matches[0],true);
	foreach ($userArr["mblogList"] as $key => $value) {
		 $username =  $value["user"]["screen_name"]; 
		 $userid  = $value["user"]["id"]; 
		 $fansNum = $value["user"]["fansNum"]; 
		 $statuses_count = $value["user"]["statuses_count"]; 
		$sql  = "insert into getuser (userid , screen_name,fansNum,statuses_count) value ('".$userid."','".$username."','".$fansNum."','".$statuses_count."')";
		if(mysql_query($sql))
		{
			echo $username =  $value["user"]["screen_name"];
			echo "-";
			echo $userid  = $value["user"]["id"];
			echo "-";
			echo $fansNum = $value["user"]["fansNum"];
			echo "-";
			echo $statuses_count = $value["user"]["statuses_count"];
			echo "\n";
		}else
		echo "pass\n";
	}
	sleep(10);	
}
  
?>