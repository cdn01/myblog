<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  


$search_sql = "select * from wowa_account where useful = 0 order by zan asc limit 1";
$assi_account = query($search_sql);
$update_sql = "update wowa_account set zan=zan +1 where id='".$assi_account[0]['id']."'";
mysql_query($update_sql);
print_r($assi_account);

echo $search_sql = "select * from weibo_create where posttime > '".date("Y-m-d",strtotime("-1 day"))."' order by zannum asc, id desc limit 1 ";
$weibo_create = query($search_sql);
$update_sql = "update weibo_create set zannum=zannum +1 where id='".$assi_account[0]['id']."'";
mysql_query($update_sql);
print_r($weibo_create);

$param = array(); 
$user = "assi_".$assi_account[0]["id"];
$param['uname'] = $assi_account[0]["user"];
$param['pwd'] = $assi_account[0]["psw"];
$cnwb = new CnwbBot($user); 
$islogin = $cnwb->login($param); 
$result = $cnwb->sendHeart($weibo_create[0]['cid']);
if(strpos($result, '{"ok":1')){ 
	
}else{
	$update_sql = "update wowa_account set zan=zan -1 where id='".$assi_account[0]['id']."'";
	mysql_query($update_sql);
	$update_sql = "update weibo_create set zannum=zannum -1 where id='".$assi_account[0]['id']."'";
	mysql_query($update_sql);
	tmail($param['uname']."_wowa_assi_".time());
}
print_r($result);

?> 
<script type='text/javascript'>
	 setTimeout('location.href="sendheart.php"',1000*60*5);
</script>