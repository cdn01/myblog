<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 
// include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php");  
echo $page = empty($_REQUEST["page"])?1:$_REQUEST["page"];
$url = "http://www.wowawowa.cn/jianfeifangfa/list-2-$page.html";

$content = mb_convert_encoding(html($url), "utf-8" ,"gb2312") ;
$title_p = "/<h3><a href=\"(.*)\" target=\"_blank\">(.*)<\/a><\/h3>/iUs";
preg_match_all($title_p, $content, $title_m);
// print_r($title_m);
$desc_p = "/导读：(.*)...<\/div>/iUs";
preg_match_all($desc_p, $content, $desc_m);
// print_r($desc_m);
// die();
// echo $content;
foreach ($title_m[1] as $key => $value) {
	echo $insert_sql = "insert into wowa_article (link,title,description,gettime) values ('".$value."','".str_conv($title_m[2][$key])."','".str_conv($desc_m[1][$key])."','".date("Y-m-d H:i:s",time())."')";	
	mysql_query($insert_sql);
}

?> 
<script type='text/javascript'>
	 setTimeout('location.href="http://localhost/google_code/wowa/getlist.php?page=<?php echo ++$page;?>"',1000*40);
</script>