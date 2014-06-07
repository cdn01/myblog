<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-7)."/config.php"); 
$PageIndex = @$_REQUEST['PageIndex']?$_REQUEST['PageIndex']:1;
$url = "http://all.qidian.com/Book/BookStore.aspx?ChannelId=-1&SubCategoryId=-1&Tag=all&Size=-1&Action=1&OrderId=7&P=all&PageIndex=$PageIndex&update=-1&Vip=-1&Boutique=-1&SignStatus=-1";
println($url);
$html = html($url);
preg_match_all("/<span class=\"swbt\"><a(.*)href=\"(.*)\"(.*)>(.*)<\/a> <\/span><a(.*)class=\"hui2\">(.*)<\/a>(.*)<\/div>       <div class=\"swc\"><div class='column4'><ul><li>(.*)<\/li><li>(.*)<\/li><\/ul><\/div><\/div><div class=\"swd\"><a(.*)href=\"(.*)\"(.*)>(.*)<\/a>/iUs", $html, $matches);
//println($matches);
$novel = array();
foreach ($matches[4] as $key=>$val){
	$novel[$key]['name'] = $val;
	$novel[$key]['last_content'] = $matches[6][$key];
	$novel[$key]['author'] = $matches[13][$key];
	$novel[$key]['author_link'] = $matches[11][$key];
	$novel[$key]['novel_link'] = $matches[2][$key];
	$novel[$key]['zishu'] = $matches[8][$key];
	$novel[$key]['weekly'] = $matches[9][$key];
	$novel[$key]['createtime'] = date("Y-m-d H:i:s",time());
}
//println($novel);
insertDB("qidian_novel", $novel,"array");
?>
<script type='text/javascript'>
		setTimeout("location.href='analyse.php?PageIndex=<?php echo ++$PageIndex;?>'",1000*30);
</script>