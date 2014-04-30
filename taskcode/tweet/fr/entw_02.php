<?php 
	include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-3)."/conn.php"); 
	include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-3)."/TweetBot.php");  
    $password='qingyu';
    $user = "entw_02" ;
	$username=  $user.'@126.com';   
	$bot = new TweetBot($user);    
	$html=$bot->login($username,$password);  

	echo $sql = "select * from en_article where gtime >'".date("Y-m-d",strtotime("-1 day"))."' and country ='fr'  order by gettime desc,replynum asc limit 1 ;";
	$en_article = query($sql);  
	$sql = "update en_article set ispost = 1 , replynum = replynum+1 where articleid = '".$en_article[0]['articleid']."'";
	mysql_query($sql);  

	echo "\n";
	$reply_url = short_url("http://mall0592.duapp.com/404_fr.htm?_t=".date("Y_d_m_H_i_s",time())."&country=fr"); 
	echo $msg = "Nouvelles du jour: ".html_decode($en_article[0]['title'])."   ==>cliquez sur le lien ".$reply_url;  
	$html = $bot->create($msg);
	$response = json_decode(substr($html, strpos($html, '{"created_at"')),true); 
	if($response['id']){
		$sql = "insert into twitter_create (cid,postuser,posttime) values ('".$response['id']."','".$username."','".date("Y-m-d H:i:s",time())."');";
		mysql_query($sql);
	}
	// $html = $bot->reply($reply[0]['pid'],$msg); 
	// if(strpos($html, '{"error":"403"')){
	// 	$response = json_decode(substr($html, strpos($html, '{"error":"403"')),true); 
	// 	slog($username,$response['message']);
	// }
?>
<script type='text/javascript'>
	 setTimeout("location.href='<?php echo $user;?>.php'",1000*60*10);
</script>