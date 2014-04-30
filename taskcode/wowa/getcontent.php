<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php"); 

$search_sql = "select * from wowa_article where isget=0 limit 1";
$article = query($search_sql);

foreach ($article as $key => $value) { 
	$content = mb_convert_encoding(html($value['link']), "utf-8" ,"gb2312") ; 
	$content_p = "/<div class=\"xx\_con\">(.*)<div style/iUs";
	preg_match($content_p, $content ,$match);
	$ct = preg_replace("/<\/?a(.*)>/iU", "", $match[1]); 
	print_r($ct);
	echo $update_sql = "update wowa_article set content = '".str_conv($ct)."' , isget =1 where id = '".$value['id']."' ;";
	mysql_query($update_sql);
}


?>
<script type='text/javascript'>
	 setTimeout('location.href="getcontent.php"',1000*30);
</script>