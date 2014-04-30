<?php
	include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 
	include(str_replace("\\", "/", dirname(__FILE__))."/TweetBot.php"); 
    $password='qingyu';
	$username='cdn_01@126.com';  
	$msg = "happy new year sss ".date("Y-m-d H:i:s",time());
	$bot=new TweetBot("cdn_01");   
	$html=$bot->login($username,$password);  

	echo "<hr>discover<br>"; 
	echo $cursor = isset($_GET["next"])?$_GET["next"]:"";
	 for($key="a";$key<"z";$key++){
		$html = $bot->getSearch("a",$cursor);  
		foreach($html["modules"] as $k=>$v){
			$id = $v["status"]["data"]["id"];
			$username = $v["status"]["data"]["user"]["screen_name"];
			$nick = $v["status"]["data"]["user"]["name"];
			$userid = $v["status"]["data"]["user"]["id"];
			if($username!="" and $username !=null){
				echo $sql = "insert into twitter_reply (user,pid,gettime,nick,userid) values ('".$username."','".$id."','".date("Y-m-d H:i:s",time())."','".$nick."','".$userid."')";
				mysql_query($sql);	
			}
		}
		 sleep(10);
	}

?>
<script type='text/javascript'>
	setTimeout("location.href='getreply.php'",1000*60*30);
</script>