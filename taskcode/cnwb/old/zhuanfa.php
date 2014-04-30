<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  

$param = array();
$user = "fzcj_07";
$param['uname'] = $user.'@126.com';
$param['pwd'] = 'qingyu';
$cnwb = new CnwbBot($user);
	// 登录
$islogin = $cnwb->login($param);

$uid = $cnwb->get_uid();

print_r($uid);

// function set_uids(){
// 	$sql = "select * from account where uid is null and useful = 0 ";
// 	$account = query($sql); 
// 	foreach ($account as $key => $value) {
// 			$param = array();
// 			$param['uname'] = $value['user'];
// 			$param['pwd'] = $value['psw'];
// 			$cnwb = new CnwbBot(substr($param['uname'], 0,strpos($param['uname'], "@")));
// 				// 登录
// 			$islogin = $cnwb->login($param);
// 			print_r($value); 
// 			echo $uid = $cnwb->get_uid();
// 			die();
// 			if($uid){
// 				$sql = "update account set uid = '".$uid."' where id ='".$value['id']."'";
// 				mysql_query($sql);
// 			}
// 		}	
// }
// set_uids();
?>
 