<?php

	define("DB_ROOT", "localhost");
	define("DB_USER", "root");
	define("DB_PWD", "");
	define("DB_DATA", "task"); 
	define("DB_CHAR", "utf8");
	define("WWW", "/var/www/html/task/trunk/");
	mysql_connect(DB_ROOT,DB_USER,DB_PWD);
	mysql_select_db(DB_DATA);
	mysql_query("set names ".DB_CHAR);
	header("Content-type:text/html;charset=utf-8");
	date_default_timezone_set("Asia/Chongqing");
	set_time_limit(0);
	// require_once(WWW."wb.php");
	// require_once(WWW."curl.class.php");
	// require_once(WWW."common.php");

	function query($sql)
	{
		$rs = array();
		$cmd = mysql_query($sql);
		while ($res = mysql_fetch_assoc($cmd)) {
			$rs[] = $res;
		}
		return $rs;
	}
	
	function html_decode($str){
		$str = str_replace("&#39;", "'", $str);
		$str = str_replace("&quot;", '"', $str);
		$str = str_replace("&nbsp;", ' ', $str);
		$str = preg_replace("/<\/?(.*)>/iU", "", $str);
		return $str;
	}

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

	function short_url($url){
		$post_url = "http://is.gd/create.php";
		$post_data = "url=".urlencode($url)."&shorturl=&opt=0" ;  
		$html = html($post_url,$post_data); 
		preg_match("/load_qrcode\('(.*)'\)/iU", $html , $matches);
		return $matches[1];
	} 
	function slog($accoutn,$msg)
    {  
    	file_put_contents("./log/log_".date("Y_d_m",time()).".txt", "-----------".date("Y-d-m H:i:s",time())."-----------\n".$accoutn."-->".$msg , FILE_APPEND);
    }
    function println($msg){
    	echo $msg."<br><hr>";
    }
?>