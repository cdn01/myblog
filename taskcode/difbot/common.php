<?php
define("DB_ROOT", "localhost");
define("DB_USER", "root");
define("DB_PWD", "");
define("DB_DATA", "task"); 
define("DB_CHAR", "utf8");

define("WWW", "D:/wamp/www/gitsvn/trunk/difbot/");
mysql_connect(DB_ROOT,DB_USER,DB_PWD);
mysql_select_db(DB_DATA);
mysql_query("set names ".DB_CHAR);
header("Content-type:text/html;charset=utf-8");
date_default_timezone_set("Asia/Chongqing");
set_time_limit(0);


function html($url,$post=false){
	$ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    if ($post){
            curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }

    if ( strpos($url, 'https') !== false) {
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    } 
    $_str = curl_exec($ch);
    curl_close($ch); 
    return $_str;
}
function println($str) {
	print_r ( $str );
	echo "<br><hr><br>";
}
function str_conv($str)
{
	// $str = str_replace("\n", "<br>", $str);
	$str = addslashes ($str);
	return $str;
}

function diffBot($url){
    $url = "http://diffbot.com/api/article?token=diffbotcomtestdrive&format=json&tags=true&url=".urlencode($url);
    $diffbot = html($url);
    return $diffbot;
}

?>