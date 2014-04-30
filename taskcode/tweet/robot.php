<?php  
	// include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-6)."/wordpress/Wordpress.class.php"); 
	include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 
	include(str_replace("\\", "/", dirname(__FILE__))."/TweetBot.php");  

	$account_sql = "select * from twitter_account where useful =0  order by postnum asc limit 1";
	$account = query($account_sql);
	
    $username=  $account[0]["user"]; 
    
    $user = substr($account[0]["user"], 0,strpos($account[0]["user"],"@")) ;
	$password=	$account[0]["psw"];  

	
	$bot = new TweetBot($user);    
	$html=$bot->login($username,$password);  

	print_r($html);
	die();
	$sql = "select gid,title from emlog_blog where emlog_blog.gid not in ( select gid from twitter_create ) limit 1;";
	$article = query($sql);   

	$host = "http://www.seois.org";
	$tweetUrl = $host."/?post=".$article[0]["gid"]; 

	$msg = $article[0]['title']."  ".$tweetUrl;   
	$html = $bot->create($msg);
	$response = json_decode(substr($html, strpos($html, '{"created_at"')),true); 
	// print_r($response);
	$sql = "update twitter_account set postnum = postnum+1 where id ='".$account[0]["id"]."'";
	mysql_query($sql);
	if($response['id']){
		$sql = "insert into twitter_create (gid,pid,postuser,posttime) values ('".$article[0]['gid']."','".$response['id']."','".$username."','".date("Y-m-d H:i:s",time())."');";
		mysql_query($sql); 
		println($username); 
		println($password);
		println($msg);
		println("send success"); 
	}else{
		$sql = "update twitter_account set useful = 1 where id ='".$account[0]["id"]."'";
	mysql_query($sql);
		slog($username," send faile ");
	}
	 
	
?>
<script type='text/javascript'>
	 setTimeout("location.href='robot.php'",1000*60*<?php echo rand(20,30);?>);
</script>