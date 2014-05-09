<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  
include(str_replace("\\", "/", dirname(__FILE__))."/douban.php");  

$sql = "select * from auto_account order by postnum asc limit 1";
$do_account = query($sql); 
$sql = "update auto_account set postnum = postnum + 1 where id = '".$do_account[0]['id']."'";
mysql_query($sql);

$param = array();
$user = substr($do_account[0]["user"], 0,strpos($do_account[0]["user"], "@"));
$param['uname'] = $do_account[0]["user"];
$param['pwd'] = $do_account[0]["psw"];
$cnwb = new CnwbBot($user);
$islogin = $cnwb->login($param);

$sql = "select * from qqwb  order by ispost asc , hot desc    limit 1";
$article = query($sql);
mysql_query("update qqwb set ispost = ispost + 1 where id = '".$article[0]['id']."'");

$sql = "select * from huati order by postnum asc ,zhishu desc  limit 1";
$huati = query($sql);
mysql_query("update huati set postnum = postnum + 1 where id = '".$huati[0]['id']."'");


$message = "#".$huati[0]['huati']."#  ".trim($article[0]['title'])." \r\n ".trim($article[0]['summary'])."  更多:http://pp1024.duapp.com/404.html   "; 
println($message);  
$imgsrc = $article[0]['img'];
if(substr($article[0]['pic_arr'], 0,strpos($article[0]['pic_arr'], "@@@@@@"))){
	$imgsrc = substr($article[0]['pic_arr'], 0,strpos($article[0]['pic_arr'], "@@@@@@"))."/460";
}
$imgdir = saveImg($imgsrc);
  

$image_dir = str_replace("\\", "/", dirname(__FILE__))."/images/".$imgdir;
$result = $cnwb->sendWeibo($message,$image_dir);

$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
if($log["ok"]!="1"){
	$cnwb->slog($param['uname']."----".$param['pwd']);  
 	sleep(30);
print <<<EOT
<script type='text/javascript'>
location.href="post_weibo.php";
</script>
EOT;

}else{
	echo "<br><hr>".date("Y-m-d H:i:s",time())."<br><hr>用户名:&nbsp;&nbsp;&nbsp;".$param['uname']."<br><hr>密码 :&nbsp;&nbsp;&nbsp;".$param['pwd']."<br><hr>".$message."<br><hr>".$log["msg"];
	if($log['id']){
		$insert_sql = "insert into weibo_create (cid,postuser,posttime) values ('".$log['id']."','".$user."','".date("Y-m-d H:i:s",time())."')";
		mysql_query($insert_sql);
	}
	$rand = rand(1,3);
	echo "<br><hr>".($rand*20)."  秒后跳转";
print <<<EOT
<script type='text/javascript'>
		setTimeout("location.href='post_weibo.php'",1000*40*$rand);
</script>
EOT;
}

?>
