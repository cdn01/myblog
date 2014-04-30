<?php

include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/qwb.php");  
$password='pjkbdpw1';
$user='2412390640';   
$bot = new QwbBot("2412390640");    

// $url = "http://ti.3g.qq.com/g/index.jsp?from=wap3g&sid=00";
// $html = $bot->html($url); 

// preg_match("/href=\"(.*)\"/iU", $html,$move_m);
// $html = $bot->html($move_m[1]);  



$html = $bot->login($user,$password);

// echo $bot->html("http://ti.3g.qq.com/pro/s?sid=".$bot->sid."&r=".rand(100000,999999)."&domain=bas&aid=ipost");
// print_r($html);

// $html = $bot->html("http://ti.3g.qq.com/ope/s?domain=bas&aid=i");
// echo $html; 
// $bot->getlist();

$sql = "select * from qq_reply  where ispost=0    limit 1 ;";
$qq_reply = query($sql);  
$sql = "update qq_reply set ispost = 1 where mid = '".$qq_reply[0]['mid']."' ";
mysql_query($sql); 
 


$sql = "select * from article  order by postnum asc ,gettime desc limit 1 ;";
$article = query($sql);  
$sql = "update article set  postnum = postnum+1 where id = '".$article[0]['id']."'";
mysql_query($sql); 

$msg = "每日新闻 ：".$article[0]["title"]." http://mall0592.duapp.com/index.php?_refer=qq&_t".time();
$html = $bot->create($qq_reply[0]["mid"],$msg); 
?>
<script type='text/javascript'>
	 setTimeout("location.href='q_2412390640.php'",1000*30*10);
</script>