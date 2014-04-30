<?php
	include("D:/Program Files (x86)/wamp/www/task/conn.php");

	$myCurl = new myCurl();
	$myCurl->openCurl("http://roll.news.qq.com/"); 
	echo $myCurl->getOutput();
	file_put_contents(WWW."qq.html", $myCurl->getOutput());
	$myCurl->openCurl("http://roll.news.qq.com/interface/roll.php?cata=&site=news&date=&page=1&mode=1&of=json");
	echo $myCurl->getOutput();
?>