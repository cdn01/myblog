<?php
include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 
$sql = "select * from send  order by posttime desc ,heart asc , review asc , forward asc , collect asc limit 5 ;";
$rs = query($sql);
if(@$rs[0]['id']>0)
{ 
	
	$paramArr = array(
		array("uname"=>'cmd_05@126.com',"pwd"=>"qingyu"),
		array("uname"=>'cmd_06@126.com',"pwd"=>"qingyu"),
		array("uname"=>'cmd_07@126.com',"pwd"=>"qingyu"),
		array("uname"=>'cmd_08@126.com',"pwd"=>"qingyu"),
		array("uname"=>'cmd_09@126.com',"pwd"=>"qingyu"),
		array("uname"=>'cmd_10@126.com',"pwd"=>"qingyu") 
		); 

	$wb = new weibo();
	// 登录
	foreach ($paramArr as $key => $param) {
	 
		$islogin = $wb->login($param);
		// 发送  
		foreach ($rs as $key => $send) {
			echo "\n===============send[id]=======================\n";
			echo $send["id"];

			$type = "heart";
			$result = $wb->sendFollow($send["id"],$type);
			echo "\n===============result=======================\n";
			print_r($result); 
			preg_match("/\{(.*)\}/iUs", $result,$response_p);
			echo "\n===============response_p[1]=======================\n";
			echo "{".$response_p[1]."}";
			print_r($response_p);
			echo "\n===============response=======================\n";
			$response = json_decode(trim("{".$response_p[1]."}"),true);
			print_r($response);
			echo "\n===============response=======================\n"; 
			if($response["ok"]==1)
			{ 
				$update_send = "update send set ".$type." = ".$type." +1 where id ='".$send["id"]."'";
				mysql_query($update_send); 
			}
			sleep(10);
		}
		sleep(5);
	}
	 
}
 
die("over");
?>