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
function query($sql)
{
    $rs = array();
    $cmd = mysql_query($sql);
    while ($res = mysql_fetch_assoc($cmd)) {
        $rs[] = $res;
    }
    return $rs;
}
function unicode_decode($name)
{
// 转换编码，将Unicode编码转换成可以浏览的utf-8编码
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches))
    {
        $name = '';
        for($j = 0; $j < count($matches[0]); $j++)
        {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0)
            {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code).chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $name .= $c;
            }else{
                $name .= $str;
            }
        }
    }
    return $name;
}

function getImageDir(){   
    $dir = "image/".date("Y_m_d",time())."/";
    if(!is_dir($dir)){
        mkdir($dir, 0777);
    }
    return $dir.time().".jpg"; 
} 
?>