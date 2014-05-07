<?php
header("Content-type:text/html;charset=utf-8");
$conn = mysql_connect("localhost","root","");
mysql_select_db("task",$conn);
mysql_query("set names utf8");

function query($sql){
	$cmd = mysql_query($sql);
	$result = array();
	while($rs = mysql_fetch_assoc($cmd))
	{
		$result[] = $rs;
	}
	if(!$result){
		return false;
	}
	return $result;
}

function println($str){
	print_r($str); 
	echo "<br><hr><br>";
}

function html($url,$post=false,$host=false,$refer=false){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	if ($post){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if($host){
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: $host"));
	}
	if($refer){
		curl_setopt($ch, CURLOPT_REFERER, $refer);
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0');
	if ( strpos($url, 'https') !== false) {
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	}
	$_str = curl_exec($ch);
	curl_close($ch);
	return $_str;
}