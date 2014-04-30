<?php
include(str_replace("\\", "/", dirname(__FILE__))."/config.php"); 
$page = $_REQUEST["page"];

//$content = mb_convert_encoding(html("http://www.hxmryy.com/html/jtfx/$page.html"), "UTF-8" ,"GBK")  ;
$content = mb_convert_encoding(html("http://www.hxmryy.com/html/lbzx/$page.html"), "UTF-8" ,"GBK")  ;
// echo $content;
preg_match_all("/<dt><a href=\"(.*)\" title=\"(.*)\">(.*)<\/a><span>/iUs", $content,$match);
print_r($match);

foreach ($match[2] as $key => $value) {
	file_put_contents("data_lbzx.txt", $value."\n",FILE_APPEND);
}
 
?>
 <script type='text/javascript'>
		setTimeout("location.href='test2.php?page=<?php echo ++$page;?>'",200);
</script>