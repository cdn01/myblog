<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  

$search_sql = "select * from wowa_account where useful = 0  order by postnum asc limit 1";
$wowa_account = query($search_sql);
$update_sql = "update wowa_account set postnum=postnum +1 where id='".$wowa_account[0]['id']."'";
mysql_query($update_sql);
print_r($wowa_account);

echo $search_sql = "select * from wowa_article order by ispost asc  limit 1";
$w_article = query($search_sql);
$update_sql = "update wowa_article set ispost=ispost+1 ,posttime='".date("Y-m-d H:i:s",time())."' where id='".$w_article[0]['id']."' ";
mysql_query($update_sql);
// print_r($w_article);

$param = array(); 
$user = "wowa_".$wowa_account[0]["id"];
echo $param['uname'] = $wowa_account[0]["user"];
$param['pwd'] = $wowa_account[0]["psw"];
$cnwb = new CnwbBot($user); 
$islogin = $cnwb->login($param); 

echo $message = "#减肥# ".trim($w_article[0]['title'])." 详情:http://mall0592.duapp.com/404.htm?_u=".$user."&_r=search&_t=".date("m_d_H_i_s",time());
$result = $cnwb->sendWeibo($message);

$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
print_r($log);
if($log["ok"]!="1"){
	$cnwb->slog($param['uname']); 
	 tmail("wowa==>".$param['uname']);  
}else{
	$insert_sql = "insert into weibo_create (cid,postuser,posttime,type) values ('".$log['id']."','".$user."','".date("Y-m-d H:i:s",time())."','2')";
	mysql_query($insert_sql);
}
?>
<script type='text/javascript'> 
</script>