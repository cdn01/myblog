<?php

	define("DB_ROOT", "192.168.26.141");
	define("DB_USER", "root");
	define("DB_PWD", "123456");
	define("DB_DATA", "task"); 
	define("DB_CHAR", "utf8");
	define("WWW", "D:/wamp/www/gitsvn/trunk/wb/");
	mysql_connect(DB_ROOT,DB_USER,DB_PWD);
	mysql_select_db(DB_DATA);
	mysql_query("set names ".DB_CHAR);
	header("Content-type:text/html;charset=utf-8");
	date_default_timezone_set("Asia/Chongqing");
	set_time_limit(0);
	require_once(WWW."wb.php");
	require_once(WWW."curl.class.php");
	require_once(WWW."common.php");

	function redirect($page,$second){
		echo "<script type='text/javascript'>setTimeout(\"location.href='".$page."'\",".($second*1000).");</script>" ;
	}
	
	function dwz($url){
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,"http://dwz.cn/create.php");
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$data=array('url'=> $url);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
		$strRes=curl_exec($ch);
		curl_close($ch);
		$arrResponse=json_decode($strRes,true);
		if($arrResponse['status']==0)
		{
		/**错误处理*/
		echo iconv('UTF-8','GBK',$arrResponse['err_msg'])."\n";
		}
		/** tinyurl */
		print_r($arrResponse);
		echo $arrResponse['tinyurl'];
		return  $arrResponse['tinyurl'];

	}
	// mylog("sdf");s
?>