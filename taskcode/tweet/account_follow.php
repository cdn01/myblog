<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-6)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/TweetBot.php");  

function get_uid(){
		$sql = "select * from twitter_account where uid is null ;";
		$account = query($sql);
		 
		foreach ($account as $key => $value) {
			$bot = new TweetBot(substr($value["user"], 0,strpos($value["user"], "@")));	
			$html=$bot->login($value["user"],$value["psw"]);
			$html = $bot->html("https://mobile.twitter.com/account");  
			// echo $html;
			preg_match("/user_id\":\"(.*)\"/iU", $html , $uid_m);
			if($uid_m[1]){
				$sql = "update twitter_account set uid = '".$uid_m[1]."' where id='".$value['id']."';";
				mysql_query($sql);
			}
			sleep(5);
		}
}
get_uid();  
$sql = "select * from twitter_account where follownum =1 ;";
$account = query($sql);

foreach ($account as $key => $value) {
	$bot = new TweetBot(substr($value["user"], 0,strpos($value["user"], "@")));	
	$html=$bot->login($value["user"],$value["psw"]);
	$sql = "select * from twitter_account where follownum > 1 and user != '".$value['user']."'  ;";
	$other_account = query($sql);
	foreach ($other_account as $key2 => $value2) { 
			$html = $bot->follow($value2['uid']); 
			$sql = "update twitter_account set follownum = follownum+1 where id='".$value['id']."';";
			mysql_query($sql);

			$bot2 = new TweetBot(substr($value2["user"], 0,strpos($value2["user"], "@")));	
			$html2 =$bot2->login($value2["user"],$value2["psw"]);
			$html2 = $bot2->follow($value['uid']); 
			$sql = "update twitter_account set follownum = follownum+1 where id='".$value2['id']."';";
			mysql_query($sql);

	}
}





?>