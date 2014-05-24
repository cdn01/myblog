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
mysql_query("update qqwb set ispost = ispost + 1 ,updatetime = '".date("Y-m-d H:i:s",time())."' where id = '".$article[0]['id']."'");

function getHeadMsg($flag="huati"){
	switch ($flag){
		case "huati":
			$sql = "select * from huati where id<60 order by postnum asc ,zhishu desc  limit 1";
			$huati = query($sql);
			mysql_query("update huati set postnum = postnum + 1 where id = '".$huati[0]['id']."'");
			return $huati[0]['huati'];
			break;
		default:
			$sql = "select * from auto_tag   order by postnum asc ,zhishu desc  limit 1";
			$huati = query($sql);
			mysql_query("update auto_tag set postnum = postnum + 1 where id = '".$huati[0]['id']."'");
			return $huati[0]['tag'];
			break;
	}
	println($sql);
}

$sql = "select * from huati where id<60 order by postnum asc ,zhishu desc  limit 1";
$flag = array("huati","auto_tag");
$flag_msg = array("话题","热门搜索");
$flag_i = rand(0, 1);
println($flag_i);
println($flag_msg[$flag_i]);
$huati = query($sql);
mysql_query("update huati set postnum = postnum + 1 where id = '".$huati[0]['id']."'");

$huati = getHeadMsg($flag[$flag_i]);
$message = "#".$huati."#  ".trim($article[0]['title'])." \r\n ".trim($article[0]['summary'])."  更多:http://pp1024.duapp.com/404.html   "; 
println($message);  
$imgdir = $article[0]['local_img'];
if(!$imgdir){
	$imgsrc = $article[0]['img'];
	if(substr($article[0]['pic_arr'], 0,strpos($article[0]['pic_arr'], "@@@@@@"))){
		$imgsrc = substr($article[0]['pic_arr'], 0,strpos($article[0]['pic_arr'], "@@@@@@"))."/460";
	}
	$imgdir = saveImg($imgsrc);
	$imgdir = str_replace("\\", "/", dirname(__FILE__))."/images/".$imgdir;
	$sql = "update qqwb set local_img = '".$imgdir."' where id = '".$article[0]['id']."' ";
	mysql_query($sql);
}

  


$result = $cnwb->sendWeibo($message,$imgdir);

$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
if($log["ok"]!="1"){
	$cnwb->slog($param['uname']."----".$param['pwd']);  
 	sleep(300);
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
	$rand = rand(5,10);
	echo "<br><hr>".($rand*5)."  秒钟后跳转";
print <<<EOT
<script type='text/javascript'>
		setTimeout("location.href='post_weibo.php'",1000*5*$rand);
</script>
EOT;
}

?>
