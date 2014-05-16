<?php
header ( "Content-type:text/html;charset=utf-8" );
$conn = mysql_connect ( "localhost", "root", "" );
mysql_select_db ( "task", $conn );
mysql_query ( "set names utf8" );
set_time_limit(0);
function query($sql) {
	$cmd = mysql_query ( $sql );
	$result = array ();
	while ( $rs = mysql_fetch_assoc ( $cmd ) ) {
		$result [] = $rs;
	}
	if (! $result) {
		return false;
	}
	return $result;
}
function println($str) {
	print_r ( $str );
	echo "<br><hr><br>";
}
function html($url, $post = false, $host = false, $refer = false) {
	$ch = curl_init ( $url );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $ch, CURLOPT_TIMEOUT, 60 );
	if ($post) {
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post );
	}
	if ($host) {
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				"Host: $host" 
		) );
	}
	if ($refer) {
		curl_setopt ( $ch, CURLOPT_REFERER, $refer );
	}
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0' );
	if (strpos ( $url, 'https' ) !== false) {
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
	}
	$_str = curl_exec ( $ch );
	curl_close ( $ch );
	return $_str;
}
function googleTranslate($msg, $to = "en") {
	$rs = "";
	switch ($to) {
		case 'en' :
			$url = "http://translate.google.cn/translate_a/t?client=webapp&sl=auto&tl=en&hl=en&sc=1&q=" . urlencode ( $msg );
			break;
		default :
			$url = "http://translate.google.cn/translate_a/t?client=webapp&sl=auto&tl=zh-CN&hl=en&sc=1&q=" . urlencode ( $msg );
			break;
	}
	$html = html ( $url );
	$rs_arr = json_decode ( $html, true );
	foreach ( $rs_arr ['sentences'] as $key => $value ) {
		$rs .= $value ['trans'];
	}
	return $rs;
}
function baiduTranslate($msg, $to = "en") {
	$url = "http://fanyi.baidu.com/v2transapi";
	switch ($to) {
		case 'en' :
			$data = "from=zh&to=en&query=" . urlencode ( $msg ) . "&transtype=trans";
			break;
		default :
			$data = "from=en&to=zh&query=" . urlencode ( $msg ) . "&transtype=trans";
			break;
	}
	
	$html = html ( $url, $data );
	$rs_arr = json_decode ( $html, true );
	return $rs_arr ['trans_result'] ['data'] [0] ['dst'];
}
function rewrite($msg) {
	$msg = preg_replace ( "/<(.*)>/iUs", "", $msg );
	$en = baiduTranslate ( $msg );
	return baiduTranslate ( $en, "ch" );
}
function rewrite_gl($msg) {
	$en = googleTranslate ( $msg );
	return baiduTranslate ( $en, "ch" );
}
function str_conv($str) {
	// $str = str_replace("\n", "<br>", $str);
	$str = addslashes ( $str );
	return $str;
}
function unicode_decode($name) {
	// 转换编码，将Unicode编码转换成可以浏览的utf-8编码
	$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
	preg_match_all ( $pattern, $name, $matches );
	if (! empty ( $matches )) {
		$name = '';
		for($j = 0; $j < count ( $matches [0] ); $j ++) {
			$str = $matches [0] [$j];
			if (strpos ( $str, '\\u' ) === 0) {
				$code = base_convert ( substr ( $str, 2, 2 ), 16, 10 );
				$code2 = base_convert ( substr ( $str, 4 ), 16, 10 );
				$c = chr ( $code ) . chr ( $code2 );
				$c = iconv ( 'UCS-2', 'UTF-8', $c );
				$name .= $c;
			} else {
				$name .= $str;
			}
		}
	}
	return $name;
}

function diffBot($url){ 
	$url = "http://www.diffbot.com/api/article?token=diffbotcomtestdrive&format=json&tags=true&url=".urlencode($url)."&callback=".time();
	$diffbot = html($url);
	return $diffbot;
}

function iaskbot($url){ 
	$url = "http://somtp.iask.cn/html2wml?url=".urlencode("http://gate.baidu.com/tc?from=opentc&src=".$url);
	$data = html($url); 
	return $data;
}
function baidugate($url){
	//http://gate.baidu.com/tc?bd_page_type=3&src=http://news.163.com/14/0508/10/9RNF1G3S00014JB6.html
	$url = "http://gate.baidu.com/tc?bd_page_type=3&src=".urlencode($url);
	$data = html($url);
	return $data;
}