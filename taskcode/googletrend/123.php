<?php
include(str_replace("\\", "/", dirname(__FILE__))."/lib/config.php");
include(str_replace("\\", "/", dirname(__FILE__))."/lib/curl.class.php"); 
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

function googleTranslate($msg,$to="en"){
	$rs = "";
	switch ($to) {
		case 'en': 
			$url = "http://translate.google.cn/translate_a/t?client=webapp&sl=auto&tl=en&hl=en&sc=1&q=".urlencode($msg); 
			break; 
		default: 
			$url = "http://translate.google.cn/translate_a/t?client=webapp&sl=auto&tl=zh-CN&hl=en&sc=1&q=".urlencode($msg); 
			break;
	}
	$html = html($url);
	$rs_arr = json_decode($html,true) ; 
	foreach ($rs_arr['sentences'] as $key => $value) {
		$rs .= $value['trans'];
	} 
	return $rs;
}

function baiduTranslate($msg,$flag="zh"){
	$url = "http://fanyi.baidu.com/v2transapi";
	$data = "from=zh&to=en&query=".urlencode($msg)."&transtype=trans";
	if($flag=="en"){
		$data = "from=en&to=zh&query=".urlencode($msg)."&transtype=realtime";
	}
	$html = html($url,$data);
	$rs_arr = json_decode($html,true) ;   
	$rs = "";
	foreach ($rs_arr['trans_result']['data'] as $val){
		$rs .= $val["dst"];
	}
	return $rs;
}

function rewrite($msg,$flag="ch"){
	if($flag=="ch"){
		$en = baiduTranslate($msg);
		return googleTranslate($en,"ch");	
	}else{ 
		$en_c = baiduTranslate($msg,"en");
		echo $en_c."<br><hr>";
		return baiduTranslate($en_c);
	}
	
}
$url = "http://www.diffbot.com/api/article?token=diffbotcomtestdrive&format=json&tags=true&url=http%3A%2F%2Fwww.cnn.com%2F2014%2F04%2F24%2Fpolitics%2Fbundy-and-race%2F";
$html = json_decode(html($url),true) ;
//print_r($html);

$text = $html["text"];
echo $text."<br><hr>";


echo $text_0."<br><hr>";
echo $text_1."<br><hr>";






















































?> 