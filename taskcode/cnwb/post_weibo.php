<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  

$sql = "select * from do_account order by postnum asc limit 1";
$do_account = query($sql); 
$sql = "update do_account set postnum = postnum + 1 where id = '".$do_account[0]['id']."'";
mysql_query($sql);

$param = array();
$user = substr($do_account[0]["user"], 0,strpos($do_account[0]["user"], "@"));
$param['uname'] = $do_account[0]["user"];
$param['pwd'] = $do_account[0]["psw"];
$cnwb = new CnwbBot($user);
	// 登录
$islogin = $cnwb->login($param);

$sql = "select * from auto_article where isweibo = 0  and catid=3  and gettime > '".date("Y-m-d",strtotime("-1 day"))."' order by click desc limit 1";
$article = query($sql);
if(!isset($article[0]['id'])){
	$sql = "select * from auto_article where isweibo = 0  and catid!=3  and gettime > '".date("Y-m-d",strtotime("-1 day"))."' order by click desc limit 1";
	$article = query($sql);
}
if(isset($article[0]['id'])){
	mysql_query("update auto_article set isweibo =  1 where id = '".$article[0]['id']."'");


	$message = "#".$article[0]['tag']."#  ".trim($article[0]['title'])." 详情:http://auto825.com/?tag=".urlencode($article[0]['tag']);
	// if($article[0]['image_dir']){
	// $image_dir = substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/difbot/baidu/".$article[0]['image_dir']; 
	// $result = $cnwb->sendWeibo($message,$image_dir);
	// }else{
	//  $result = $cnwb->sendWeibo($message);
	// }
	$result = $cnwb->sendWeibo($message);
	$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
	// print_r($log);
	if($log["ok"]!="1"){
		mysql_query("update auto_article set isweibo = 0 where id = '".$article[0]['id']."'");
		$cnwb->slog($param['uname']); 
		 tmail($param['uname']); 
	 	sleep(50);
print <<<EOT
	<script type='text/javascript'>
	location.href="post_weibo.php";
	</script>
EOT;

	}
	echo date("Y-m-d H:i:s",time())."<br><hr>用户名:&nbsp;&nbsp;&nbsp;".$param['uname']."<br><hr>密码 :&nbsp;&nbsp;&nbsp;".$param['pwd']."<br><hr>".$message."<br><hr>".$log["msg"];
	if($log['id']){
		$insert_sql = "insert into auto_weibo_create (cid,postuser,posttime) values ('".$log['id']."','".$user."','".date("Y-m-d H:i:s",time())."')";
		mysql_query($insert_sql);
	}
}
$rand = rand(1,3);
echo "<br><hr>".($rand*40)."  秒后跳转";

?>
<script type='text/javascript'>
		setTimeout("location.href='post_weibo.php'",1000*40*<?php echo $rand;?>);
</script>