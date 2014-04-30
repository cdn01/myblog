<?php
include(str_replace("\\", "/", dirname(__FILE__))."/config.php"); 


////-------------------------------WEI BO-----------------------------------------
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
echo "<br><hr>"; 
/////------------------------------------------------------------------------


$sql = "select * from auto_article where isweibo = 0 and gettime > '".date("Y-m-d",strtotime("-1 day"))."' order by click desc limit 1";
$auto_article = query($sql);
if(isset($auto_article[0]['id'])){
	$article = $auto_article[0];
	//----------------------Post To WebSite--------------------------
	// $sql = "update auto_article set ispost=1 ,posttime = '".date("Y-m-d H:i:s",time())."' where id ='".$auto_article[0]['id']."'";
	// mysql_query($sql);
	// $post_top = get_posttop($article['tag']);
	// $v7 = new v7("backcn","qingyu2007","http://auto825.com","auto825");
	// $res_login = $v7->login(); 
	// $res_post = $v7->postA($article['catid'], mb_convert_encoding($article['title'], "GBK","UTF-8") ,mb_convert_encoding($article['content'], "GBK","UTF-8"),"" ,mb_convert_encoding($article['tag'], "GBK","UTF-8"),$post_top);
	// print_r($res_post); 
	//-------------------------End-----------------------
	$message = "#".$article['tag']."# ".$article['title']." 详情:http://www.wuditianxia.com/index.php?_u=".$user."&tag=1&_t=".date("H_i_s",time());
	$result = $cnwb->sendWeibo($message);
	$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
	if($log["ok"]!="1"){
		$cnwb->slog($param['uname']); 
	 	tmail($param['uname']);  
	 	sleep(20);
print <<<EOT
<script type='text/javascript'>
location.href="test_post_wb.php";
</script>
EOT;
	}else{ 
		$insert_sql = "insert into auto_weibo_create (cid,postuser,posttime) values ('".$log['id']."','".$user."','".date("Y-m-d H:i:s",time())."')";
		mysql_query($insert_sql);
		$update_sql = "update auto_article set isweibo=1 , weibotime='".date("Y-m-d H:i:s",time())."' where id='".$article['id']."' ; ";
		mysql_query($update_sql);
	}
	echo date("Y-m-d H:i:s",time())."<br><hr>用户名:&nbsp;&nbsp;&nbsp;".$param['uname']."<br><hr>密码 :&nbsp;&nbsp;&nbsp;".$param['pwd']."<br><hr>".$message."<br><hr>发布成功<br><hr>";
}else{
	echo "no article post";
	//转发。点赞
}
$rand = rand(1,3);
echo "<br><hr>".($rand*60)."  秒后跳转";
?>
<script type='text/javascript'>
		setTimeout("location.href='test_post_wb.php'",1000*250*<?php echo $rand;?>);
</script>