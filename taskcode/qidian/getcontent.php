<?php
header("Content-type:text/html;charset=utf-8");
include(str_replace("\\", "/", dirname(__FILE__))."/config.php");
//include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-7)."/config.php");

$sql = "select * from qidian_article where isget = 0 limit 1";
$author_level = 5;
$article = query($sql);
if(!empty($article[0]['id'])){
	if(strpos($article[0]['link'], "BookReader/vip"))
	{
		println("Vip article");
		tmail($article[0]['novel_name']."\t".$article[0]['title']);
		die();
	}else{
		println($article);
		preg_match("/BookReader\/(.*),(.*)\./iUs", $article[0]['link'],$matches);
		$text = html("http://files.qidian.com/Author{$author_level}/{$matches[1]}/{$matches[2]}.txt");
		$text = mb_convert_encoding($text,"UTF-8","GB2312");
		//	println($text);
		if(preg_match("/document\.write\('(.*)'\);/iUs", $text,$body)){
			if(strpos($body[1], "<a href=http://www.qidian.com>")){
				$body[1] = str_replace("<a href=http://www.qidian.com>起点中文网www.qidian.com欢迎广大书友光临阅读，最新、最快、最火的连载作品尽在起点原创！&lt;/a&gt;&lt;a&gt;手机用户请到m.qidian.com阅读。&lt;/a&gt;", "", $body[1]);
				$body[1] = preg_replace("/<a(.*)>(.*)<\/a>/iUs", "", $body[1]);
			}
			$body = "<p>".str_replace("<p>", "</p><p>", $body[1])."</p>";
			if($body){
				$sql = "update qidian_article set isget=1 , content = '".str_conv($body)."' where id ='".$article[0]['id']."'";
				mysql_query($sql);
			}
		}
	}
}else{
	println("no update !") ;
}

?>
<script type='text/javascript'>
		setTimeout("location.href='getcontent.php'",1000*60*5);
</script>
