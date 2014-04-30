<?php
// define("DB_ROOT", "192.168.153.128");
// define("DB_USER", "root");
// define("DB_PWD", "zijidelu");
// define("DB_DATA", "task"); 
// define("DB_CHAR", "utf8"); 
include(substr(str_replace("\\", "/", dirname(__FILE__)),0,-11)."/mail/class.phpmailer.php"); 
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-11)."/mail/class.smtp.php"); 

define("DB_ROOT", "localhost");
define("DB_USER", "root");
define("DB_PWD", "");
define("DB_DATA", "emblog_2007"); 
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
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; c8650 Build/GWK74) AppleWebKit/533.1 (KHTML, like Gecko)Version/4.0 MQQBrowser/4.5 Mobile Safari/533.1s'); 
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
	file_put_contents("./log/log_".date("Y_d_m",time()).".txt", "-----------".date("Y-d-m H:i:s",time())."-----------\n".$accoutn."-->".$msg , FILE_APPEND);
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

function translate($message){
	$url = "http://www.excite.co.jp/world/chinese/";
	$data = "_qf__formTrans=&_token=0881090a86e49&auto_detect_flg=1&wb_lp=CHJA&swb_lp=JACH&big5=no&before_lang=CH&after_lang=JA&big5_lang=no&auto_detect=off&auto_detect=on&before=".urlencode($message)."&after=".urlencode($message);
	// echo "<br><hr>";
	$html = html($url,$data);
	sleep(3);
	preg_match("/<h1 class=\"title-bar\">訳文<\/h1>(.*)<\/section>/iUs", $html, $match);
	
	if(!isset($match[1])){
		return false;
	}
	$rs = str_replace("

","、",htmlspecialchars_decode($match[1]));
	 
	return $rs;
}

function fanyi($message){
	$url = "http://fanyi.baidu.com/v2transapi";
	$data = "from=zh&to=jp&query=".urlencode($message)."&transtype=trans";
	$html = html($url,$data);
	print_r($html); 
}

function tojb($value){
	$t_content = "";
	$content_dot = explode("，", $value);
	// echo count($content_dot);die();	
	foreach ($content_dot as $kd => $vd) {
		if(strlen(trim($vd))>0){
			$vd = $vd."@@@";
			if(strpos($vd , "。")){
				$content_dot_2 = explode("。", $vd);
				foreach ($content_dot_2 as $kd2 => $vd2) {
					if(strlen(trim($vd2))>0){
						$vd2 = $vd2."@@@"; 
						$t_content .=  $vd2;
					}
				}
			}else{ 
				$t_content .=  $vd;
			}
		}
	}
	echo "@@@@<br><hr>";
	$t_content = str_replace("@@@@@@","@@@",$t_content);
	$content_d = explode("@@@", $t_content);
	// echo count($content_d)."<br><hr>";
	$temp = "";
	$rs = "";
	$sum = 0;
	foreach ($content_d as $key => $value) {
		// echo $sum."<br>";
		if($sum>5||$sum==(count($content_d)-1)){
			$sum = 0;
			$rs.=translate($temp); 
			$temp = "";
		}else{
			if(strlen($value)>10){
				$sum++;
				$temp .= $value."

";

			}	
		}
		
	}
	return $rs;
}

?>