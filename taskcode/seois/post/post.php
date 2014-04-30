<?php 
include(str_replace("\\", "/", dirname(__FILE__))."/emblog.class.php"); 
echo "<br><hr>";
echo date("Y-m-d H:i:s",time()); 
echo "<br><hr>";
$sql = "select * from emlog_blog where   ispost=0  and isget=1  and sortid in (9,10,11,12,14)     limit 1";
$auto_article = query($sql); 
echo $auto_article[0]["jp_title"];
echo "<br><hr>";
//echo $auto_article[0]["jp_content"]; 
// die("post over");
// $sql = "update auto_account set postnum = postnum + 1 where id = '".$do_account[0]['id']."'";
// mysql_query($sql);

// print_r($auto_article); 
/*
8 新词 =>
*/

if(isset($auto_article[0]['gid'])){ 
	$sql = "update emlog_blog set ispost=1 ,posttime = '".date("Y-m-d H:i:s",time())."' where gid ='".$auto_article[0]['gid']."'";
	mysql_query($sql);
	$emblog = new emblog("http://seois.org"); 
	$emblog->postA($auto_article[0]['jp_title'],$auto_article[0]['jp_content'],"",$auto_article[0]['sortid']);
}

?>
<script type='text/javascript'>
	setTimeout('location.href="post.php"',1000*30);
</script>