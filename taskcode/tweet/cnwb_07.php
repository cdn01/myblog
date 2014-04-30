<?php  
	include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-6)."/wordpress/Wordpress.class.php"); 
	include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 
	include(str_replace("\\", "/", dirname(__FILE__))."/TweetBot.php");  
    $password='qingyu';
    $user = "cnwb_07" ;
	$username=  $user.'@126.com';   
	$bot = new TweetBot($user);    
	$html=$bot->login($username,$password);  


	$sql = "select * from en_article where  country ='en' and ispost = 0  and webid=0   order by articleid desc limit 1 ;";
	$en_article = query($sql);  
	$sql = "update en_article set ispost = 1 , replynum = replynum+1 where articleid = '".$en_article[0]['articleid']."'";
	mysql_query($sql); 


	//post to web
 	

	$host = "http://www.seois.org";
	$wb_user = "backcn";
	$wb_psw  = "qingyu2007!QAZ";
	//SELECT * from en_article where addweb=0 and ispost=1 and contentdiv is not NULL and webid !=0 ORDER BY gettime DESC LIMIT 1;
	$callback = "jQuery172011579657284872036_".time();
	$difbot = html("http://diffbot.com/api/article?token=diffbotcomtestdrive&format=json&tags=true&url=".urlencode($en_article[0]['link'])."&callback=".$callback."&_=".time());
	$difbot = substr(str_replace($callback."(", "", $difbot),0,-1);
	$dif_data = json_decode($difbot,true); 
	$tags = "";
	foreach ($dif_data["tags"] as $key => $value) {
		$tags .= $value.",";
	} 
	if(strlen($dif_data["html"])<100) {goto div;}
	echo $dif_data["html"];
	$wp = new Wordpress($host);
	$html = $wp->login($wb_user,$wb_psw); 
	$content = preg_replace("/<a([^>]*)>([^<]*)<\/a>/iUs",'<font color="red">\\2</font>',$dif_data["html"]); 
	$data = array("content"=>$content,"title"=>$dif_data['title'],"tags"=>str_replace('"', "", substr($tags, 0,-1))); 
	$post_ID = $wp->post($data); 
	$sql = "update en_article set webid = '".$post_ID."' where articleid = '".$en_article[0]['articleid']."'";
	mysql_query($sql); 
	//end

	//post tweet
	$tweetUrl = $host."/?p=".$post_ID."&_t=".date("Y_d_m_H_i_s",time());

	$reply_url = short_url($tweetUrl); 
	echo $msg = html_decode($en_article[0]['title'])."   ".$reply_url;  
	$html = $bot->create($msg);
	$response = json_decode(substr($html, strpos($html, '{"created_at"')),true); 
	if($response['id']){
		$sql = "insert into twitter_create (cid,postuser,posttime) values ('".$response['id']."','".$username."','".date("Y-m-d H:i:s",time())."');";
		mysql_query($sql);
	} 
	div:
?>
<script type='text/javascript'>
	 setTimeout("location.href='<?php echo $user;?>.php'",1000*60*<?php echo rand(15,30);?>);
</script>