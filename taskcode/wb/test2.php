<?php
	date_default_timezone_set("Asia/Chongqing");
	$date = "\n==================================================\ntest2      ".date("Y-m-d H:i:s",time())."\n";
	file_put_contents("/usr/local/php/log", $date,FILE_APPEND)
?>