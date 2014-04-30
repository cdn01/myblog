<?php 
include(str_replace("\\", "/", dirname(__FILE__))."/emblog.class.php"); 

echo $sql = "select * from auto_article where isweibo = 0 and ispost=0 and catid=3  and gettime > '".date("Y-m-d",strtotime("-1 day"))."' order by click desc limit 1";
$auto_article = query($sql);
if(!isset($auto_article[0]['id'])){
	echo $sql = "select * from auto_article where isweibo = 0 and ispost=0 and catid!=3  and gettime > '".date("Y-m-d",strtotime("-1 day"))."' order by click desc limit 1";
	$auto_article = query($sql);
}


// $sql = "update auto_account set postnum = postnum + 1 where id = '".$do_account[0]['id']."'";
// mysql_query($sql);

// print_r($auto_article); 

if(isset($auto_article[0]['id'])){
	$sql = "update auto_article set ispost=1 ,posttime = '".date("Y-m-d H:i:s",time())."' where id ='".$auto_article[0]['id']."'";
	mysql_query($sql);
	$emblog = new emblog("http://auto825.com"); 
	$emblog->postA($auto_article[0]['title'],$auto_article[0]['content'],$auto_article[0]['tag'],$auto_article[0]['catid']);
}

?>
<script type='text/javascript'>
	 setTimeout('location.href="post.php"',1000*9);
</script>