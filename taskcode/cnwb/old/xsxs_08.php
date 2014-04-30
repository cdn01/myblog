<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  

$param = array();
$user = "xsxs_08";
$param['uname'] = $user.'@yeah.net';
$param['pwd'] = 'qingyu';
$cnwb = new CnwbBot($user);
	// 登录
$islogin = $cnwb->login($param);

$sql = "select * from article where char_length(title)>10 order by postnum asc , id desc  limit 1";
$article = query($sql);
mysql_query("update article set postnum = postnum+ 1 where id = '".$article[0]['id']."'");


$message = trim($article[0]['title'])." 详情:".$aimama_url;
$result = $cnwb->sendWeibo($message);
$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
print_r($log);
if($log["ok"]!="1"){
	$cnwb->slog($param['uname']); 
	 tmail($param['uname']);
}
echo date("Y-m-d H:i:s",time())."==>".$log["msg"];
if($log['id']){
	$insert_sql = "insert into weibo_create (cid,postuser,posttime) values ('".$log['id']."','".$user."','".date("Y-m-d H:i:s",time())."')";
	mysql_query($insert_sql);
}


?>
<script type='text/javascript'>
		setTimeout("location.href='<?php echo $user;?>.php'",1000*60*<?php echo rand(15,25);?>);
</script>