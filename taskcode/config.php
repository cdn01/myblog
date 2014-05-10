<?php
// define("DB_ROOT", "192.168.153.128");
// define("DB_USER", "root");
// define("DB_PWD", "zijidelu");
// define("DB_DATA", "task"); 
// define("DB_CHAR", "utf8");
include(str_replace("\\", "/", dirname(__FILE__))."/mail/class.phpmailer.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/mail/class.smtp.php"); 

define("DB_ROOT", "localhost");
define("DB_USER", "root");
define("DB_PWD", "");
define("DB_DATA", "task"); 
define("DB_CHAR", "utf8");

define("WWW", "D:/wamp/www/gitsvn/trunk/difbot/");

function tmail($message){
	$mail  = new PHPMailer();  
	$mail->CharSet    ="UTF-8";                 //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
	$mail->IsSMTP();                            // 设定使用SMTP服务
	$mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
	$mail->SMTPSecure = "ssl";                  // SMTP 安全协议
	$mail->Host       = "smtp.126.com";       // SMTP 服务器
	$mail->Port       = 465;                    // SMTP服务器的端口号
	$mail->Username   = "cdn_02@126.com";  // SMTP服务器用户名
	$mail->Password   = "qingyu";        // SMTP服务器密码
	$mail->SetFrom('cdn_02@126.com', 'cdn_02');    // 设置发件人地址和名称
	$mail->AddReplyTo("cdn_02@126.com","cdn_02"); 
												// 设置邮件回复人地址和名称
	$mail->Subject    = $message."发送失败";                     // 设置邮件标题
	$mail->AltBody    = "为了查看该邮件，请切换到支持 HTML 的邮件客户端"; 
												// 可选项，向下兼容考虑
	$mail->MsgHTML($message);                         // 设置邮件内容
	$mail->AddAddress('cdn_02@126.com', "cdn_02");
	//$mail->AddAttachment("images/phpmailer.gif"); // 附件 
	if(!$mail->Send()) {
		echo "发送失败：" . $mail->ErrorInfo;
	} else {
		echo "恭喜，邮件发送成功！";
	}
}
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
function getHuati($url){
	$ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Accept: */*',
    'Accept-Charset: UTF-8,*;q=0.5',
    'Accept-Encoding: gzip,deflate,sdch',
    'Accept-Language: zh-CN,zh;q=0.8',
    'Connection: keep-alive',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'Referer: http://huati.weibo.com/?ctg1=99&ctg2=0&prov=0&sort=day&p=1&t=1',
    'User-Agent: Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.95 Safari/537.11',
    'X-Requested-With: XMLHttpRequest',
)); 
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0'); 
     
    $_str = curl_exec($ch);
    curl_close($ch); 
    return $_str;
}	
function str_conv($str)
{ 
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
function short_url($url){
		$post_url = "http://is.gd/create.php";
		$post_data = "url=".urlencode($url)."&shorturl=&opt=0" ;  
		$html = html($post_url,$post_data); 
		preg_match("/load_qrcode\('(.*)'\)/iU", $html , $matches);
		return $matches[1];
} 
function slog($accoutn,$msg)
{  
	file_put_contents("./log/log_".date("Y_m_d",time()).".txt", "-----------".date("Y-d-m H:i:s",time())."-----------\n".$accoutn."-->".$msg , FILE_APPEND);
}
function html_decode($str){
		$str = str_replace("&#39;", "'", $str);
		$str = str_replace("&quot;", '"', $str);
		$str = str_replace("&nbsp;", ' ', $str);
		$str = preg_replace("/<\/?(.*)>/iU", "", $str);
		return $str;
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
 
function println($str) {
	print_r ( $str );
	echo "<br><hr><br>";
}
function saveImg($src){
	$file = file_get_contents($src);
	$img_name = time().".jpg";
	file_put_contents("./images/".$img_name, $file);
	return $img_name;
}
$aimama_url = "http://redirect.simba.taobao.com/rd?w=unionnojs&f=http%3A%2F%2Fre.taobao.com%2Feauction%3Fe%3D7yz2kKA55Wwv5jEtdFQGvhaSDMBVO0lUqklVR2qet6HlL1tPWpvWRNSY3YY37eLzoAY2eQvj%252Bx4qdWKFRBhE7piUYGvTpfXZjYjAuDby3FTLeDEbMH2uIQ%253D%253D%26ptype%3D100010&k=e2e107a2b72ca1b1&c=un&b=alimm_0&p=mm_17339625_5528706_17112601";
?>