<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  

$param = array();
$param['uname'] = 'cnwb_06@126.com';
$param['pwd'] = 'qingyu';
$cnwb = new CnwbBot("cnwb_06");
	// 登录
$islogin = $cnwb->login($param);

$sql = "select * from article_joke where ispost=0 and picurl ='' and char_length(content)<110 limit 1";
$article = query($sql);
mysql_query("update article_joke set ispost = 1 where id = '".$article[0]['id']."'");

$sql = "SELECT * from hotword where gettime >'".date("Y-m-d",strtotime("-2 day"))."' order by  postnum asc limit 1;";
$hotword = query($sql);
mysql_query("update hotword set postnum = postnum+1 where id = '".$hotword[0]['id']."'");


$message = "#".$hotword[0]["hotword"]."#"."  ".trim($article[0]['content'])." 更多囧人囧事:http://mall0592.duapp.com/?_joke&id=".$article[0]['id'];
$result = $cnwb->sendWeibo($message);
$log = json_decode(substr($result,strpos($result, '{"id":"')),true);
if($log["ok"]!="1"){
	$cnwb->slog($log["msg"]);
}
echo date("Y-m-d H:i:s",time())."==>".$log["msg"];

?>
<script type='text/javascript'>
		setTimeout("location.href='cnwb_06.php'",1000*60*<?php echo rand(5,10);?>);
</script>