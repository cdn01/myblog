<?php
include(str_replace("\\", "/", dirname(__FILE__))."/wb/conn.php");   


echo $sql = "select * from article_joke where ispost = 0 order by gettime desc limit 1 ";
echo "<hr><br>";
$article_rs = query($sql);
if($article_rs){
	echo $sql = "update article_joke set ispost = 1 where id = ".$article_rs[0]['id'];
	echo "<hr><br>";
	mysql_query($sql);	
}

echo $sql = "select * from reply where ispost = 0 order by gettime desc limit 1 ";
echo "<hr><br>";
$reply_rs = query($sql);
if($reply_rs){
	echo $sql = "update reply set ispost = 1 where mid = ".$reply_rs[0]['mid'];
	echo "<hr><br>";
	mysql_query($sql);
}


echo $sql = "select * from account order by postnum desc limit 1";
echo "<hr><br>";
$account_rs = query($sql);
if($account_rs){
	echo $sql = "update account set postnum = postnum + 1 where id = ".$account_rs[0]['id'];
	echo "<hr><br>";
	mysql_query($sql);	
}
$wb = new weibo();
// 登录
$islogin = $wb->login(array('uname' => $account_rs[0]["user"], 'pwd' => $account_rs[0]["psw"] )); 
print_r($islogin);
$url = "http://mall0592.duapp.com/?_=".time()."&id=".$account_rs[0]["id"]."&uname=".urlencode($account_rs[0]["user"]);

$message = trim($article_rs[0]['title'])."mall0592.duapp.com" ;

// $sql = "select * from images where aid ='".$article_rs[0]['id']."' order by id asc limit 1 ";
// $img_rs = query($sql);
// if(@strlen($img_rs[0]['dir'])>0)
// {
// 	echo $img_rs[0]['dir']."\n";
// 	$result = $wb->sendWeibo($message." \n  详情:".$url,$img_rs[0]['dir']);
// }
// else
// 	$result = $wb->sendWeibo($message."  详情:".$url);
$html = $wb->reply($reply_rs[0]["mid"],$message);
print_r(json_decode(substr($html, strpos($html, '{"appkey"')),true));

















?>