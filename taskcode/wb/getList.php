<?php

	include(str_replace("\\", "/", dirname(__FILE__))."/conn.php");

	











	

	// $preg_p = "/class=\"linkto\" href=\"http(.*)\.htm\">/iU";
	// $listArr = getMatchContent("http://news.qq.com/",$preg_p,"all",1);
	 
	$listUrl = getMatchContents("http://blog.sina.com.cn/lm/health/","/http:(.*)\/blog_(.*)\.html/iU","all");
	print_r($listUrl);	
	$insert_num = 0;
	foreach ($listUrl[0] as $key => $value) {
		$sql = "insert into article  (link) values ('".urlencode($value)."')";
		if(mysql_query($sql)){
			$insert_num++;
		}
	} 
	echo "insert ".$insert_num." records";
	die("over");
?>