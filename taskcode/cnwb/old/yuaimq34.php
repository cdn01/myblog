<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  

$param = array();
$user = "yuaimq34";
$param['uname'] = $user.'@126.com';
$param['pwd'] = '6859122';
$cnwb = new CnwbBot($user);
	// 登录
$islogin = $cnwb->login($param);

$sql = "select * from article where char_length(title)>10 and gettime > '".date("Y-m-d",strtotime("-1 day"))." ' order by postnum asc , id desc  limit 1";
$article = query($sql);
mysql_query("update article set postnum = postnum+ 1 where id = '".$article[0]['id']."'");


$message = trim($article[0]['title'])." 详情:http://mall0592.duapp.com/404.htm?_u=".$user."&_r=search&_t=".date("H_i_s",time());
if($article[0]['image_dir']){
$image_dir = substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/difbot/baidu/".$article[0]['image_dir']; 
$result = $cnwb->sendWeibo($message,$image_dir);
}else{
 $result = $cnwb->sendWeibo($message);
}
$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
print_r($log);
if($log["ok"]!="1"){
	$cnwb->slog($param['uname']); 
	 tmail($param['uname']);
sleep(90);
if($_REQUEST['valid']){  
tmail($param['uname']."====>结束");die("over");

}
print <<<EOT
<script type='text/javascript'>
location.href="$user.php?valid=1";
</script>
EOT;

}
echo date("Y-m-d H:i:s",time())."==>".$log["msg"];
if($log['id']){
	$insert_sql = "insert into weibo_create (cid,postuser,posttime) values ('".$log['id']."','".$user."','".date("Y-m-d H:i:s",time())."')";
	mysql_query($insert_sql);
}


?>
<script type='text/javascript'>
		setTimeout("location.href='<?php echo $user;?>.php'",1000*60*<?php echo rand(20,30);?>);
</script>