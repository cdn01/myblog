<?php
header("Content-type:text/html;charset=utf-8");
$conn = mysql_connect("localhost","root","");
mysql_select_db("task");
mysql_query("set names utf8");
set_time_limit(0);
date_default_timezone_set('Asia/Chongqing');

function str_conv($str)
{
	// $str = str_replace("\n", "<br>", $str);
	$str = preg_replace("/<a(.*)>/iUs","<b style='color:red'>",$str);
	$str = preg_replace("/<\/a>/iUs","</b>",$str);
	$str = addslashes ($str);
	return $str;
}

function query($sql)
{
	$rs = array();
	$cmd = mysql_query($sql);
	while ($res = mysql_fetch_assoc($cmd)) {
		$rs[] = $res;
	}
	return $rs;
}

?>
