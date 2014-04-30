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
	// 登录
$islogin = $cnwb->login($param);

//char_length(title)>10 and gettime > '".date("Y-m-d",strtotime("-1 day"))." ' order by postnum asc , id desc  limit 1
$sql = "select * from ff_vod where vod_cid in (14,13,12,11,10,9,8) order by vod_wbnum asc , vod_year desc    limit 1";
$article = query($sql);
mysql_query("update ff_vod set vod_wbnum = vod_wbnum + 1 where vod_id = '".$article[0]['vod_id']."'");

//话题
$sql = "select * from huati where id < 500  order by postnum asc ,zhishu desc  limit 1";
$huati = query($sql);
mysql_query("update huati set postnum = postnum + 1 where id = '".$huati[0]['id']."'");

$comments = getComments($article[0]['vod_id'],trim($article[0]['vod_name'])); 

echo $message = "#".$huati[0]['huati']."#  电影==>《".trim($article[0]['vod_name'])."》 ".$comments."  qvod在线播放:http://www.auto825.com   "; 
$imgdir = "./images/".$article[0]['vod_id'].".jpg";
// file_put_contents($imgdir, file_get_contents($article[0]['vod_pic']));
// die($param['uname']."<br><hr>".$param['pwd']."<br><hr>login");
if(file_put_contents($imgdir, file_get_contents($article[0]['vod_pic']))){
	$image_dir = str_replace("\\", "/", dirname(__FILE__))."/images/".$article[0]['vod_id'].".jpg"; 
	$result = $cnwb->sendWeibo($message,$image_dir);
}else{
	 $result = $cnwb->sendWeibo($message);
}
$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
// print_r($log);
if($log["ok"]!="1"){
	 
	$cnwb->slog($param['uname']."----".$param['pwd']);  
 	sleep(30);
print <<<EOT
<script type='text/javascript'>
location.href="post_weibo_do.php";
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
		setTimeout("location.href='post_weibo_do.php'",1000*40*$rand);
</script>
EOT;
}

?>
