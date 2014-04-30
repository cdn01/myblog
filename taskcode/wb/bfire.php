<?php
include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 
$sql = "select * from article where isget=999 or  description is not null  order by postnum asc ,id desc limit 1 ;";
$rs = query($sql);
if(@$rs[0]['id']>0) 
{
	$hotword_sql = "select * from hotword order by postnum  asc ,id asc limit 1";
	$hotword_rs = query($hotword_sql);
	$param = array();
	$param['uname'] = 'bfire_01@126.com';
	$param['pwd'] = 'qingyu';
	$wb = new weibo();
	// 登录
	$islogin = $wb->login($param);
	// 获取用户信息  
	$home = $wb->_html("https://m.weibo.cn/"); 
	$title_len = strlen($rs[0]['title']);
	if(strlen($rs[0]['description'])>10)
	{
		$message = $rs[0]['title']."--".substr($rs[0]["description"], 0,260-$title_len); 
	}else
		$message = $rs[0]['title'].substr($rs[0]["content"], 0,260-$title_len); 
	$message = "#".$hotword_rs[0]["hotword"]."#"."  ".trim($rs[0]['title']);
	$url = shortUrl("http://mall0592.duapp.com/?_=".time()."&id=".$rs[0]["id"]."&uname=".$param["uname"]);
	$sql = "select * from images where aid ='".$rs[0]['id']."' order by id asc limit 1 ";
	$img_rs = query($sql);
	if(@strlen($img_rs[0]['dir'])>0)
	{
		echo $img_rs[0]['dir']."\n";
		$result = $wb->sendWeibo($message." \n  详情:".$url,$img_rs[0]['dir']);
	}
	else
		$result = $wb->sendWeibo($message."  详情:".$url);
	echo "\n===============result=======================\n";
	print_r($result); 
	preg_match("/\{(.*)\}/iUs", $result,$response_p);
	echo "\n===============response_p[1]=======================\n";
	echo "{".$response_p[1]."}";
	print_r($response_p);
	echo "\n===============response=======================\n";
	$response = json_decode(trim("{".$response_p[1]."}"),true);
	print_r($response);
	if($response["ok"]==1)
	{ 
		$insert_send = "insert into send (username,postid,posttime) values ('".$param['uname']."','".$response['id']."','".date("Y-m-d H:i:s",time())."')";
		mysql_query($insert_send);
		mysql_query("update article set postnum = postnum +1 ,ispost=1 where id ='".$rs[0]['id']."'");
		mysql_query("update hotword set postnum = postnum +1 where id = '".$hotword_rs[0]["id"]."'");	
	}else{
		tmail($param['uname'],$response["msg"]);
		die("over");
	}
}
 die("over");
?>