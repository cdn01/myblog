<?php
	include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 
	include(str_replace("\\", "/", dirname(__FILE__))."/TweetBot.php");  
    
	$total_account = "select count(*) total from twitter_account";
	$total = query($total_account);
    $sql = "select * from twitter_create where retweetnum = 1; ";
    $tc = query($sql);
    print_r($tc);

    foreach ($tc as $key => $value) {
    	echo $sql = "select * from twitter_account where user !='".$value['postuser']."' order by rand() limit  ".rand(1,2);
    	$twitter_account = query($sql);	
    	foreach ($twitter_account as $kta => $vta) {
    		$bot = new TweetBot(substr($vta["user"], 0,strpos($vta["user"], "@")));    
			$html=$bot->login($vta["user"],$vta["psw"]);  
			$html = $bot->retweet($value["cid"]);
			// echo $html;
			if(strpos($html, '{"id":"')){
				$sql = "update twitter_create set retweetnum = retweetnum +1 where id ='".$value["id"]."';";
				mysql_query($sql);
			}
			
    	} 
    	sleep(55);
    }
    
?>
<script type='text/javascript'>
	 setTimeout("location.href='retweet.php'",1000*60*15);
</script>