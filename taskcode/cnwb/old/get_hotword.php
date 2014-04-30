<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/config.php");    
$html = html("http://huati.weibo.cn/?pos=65");
// echo $html;
preg_match_all("/>#(.*)#/iUs", $html, $matches);
foreach ($matches[1] as $key => $value) {
	$sql = "insert into hotword (hotword,gettime) values ('".$value."','".date("Y-m-d H:i:s",time())."')";
	if(mysql_query($sql)) echo $sql;
}
print_r($matches);
echo date("Y-m-d H:i:s",time());

?>
<script type='text/javascript'>
		setTimeout("location.href='get_hotword.php'",1000*60*<?php echo rand(5,10);?>);
</script>