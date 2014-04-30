<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 

/**
 +----------------------------------------------------------
 * Send Sms <<发送短信>>
 +----------------------------------------------------------
 * @param	array $phones
 * @param	string $message
 +----------------------------------------------------------
 * @return	boolean
 +----------------------------------------------------------
 */
function send_sms($phones, $message)
{
	// 转换新模式, 2013-11-22
	// ---------------------------------
	//return ysgj_send($phones, $message);
	
	// 手机号码
	// -------------------------------------------------------------
	$phones = (is_array($phones) ? implode(',', $phones) : $phones);

	// 短消息内容
	// ------------------------
	$msg = urlencode($message);

	// 运营商配置
	// ------------------
	$sdk = '13779953612';
	$code = 'qtk88888';
	$sub_code = '2278';

	// 调用服务端接口发送校验码
	// ---------------------------------------------------------------------------------------------------------------------------------------------------
	$result = @file_get_contents("http://vip.4001185185.com/sdk/smssdk!mt.action?sdk={$sdk}&code={$code}&phones={$phones}&msg={$msg}&subcode={$sub_code}");

	if ($result == '发送成功') 
	{
		// 发送成功
		// ---------
		return true;
	}

	// 发送失败，显示消息
	// ------------
	return $result;
}

/**
 +----------------------------------------------------------
 * 营商国际 <<发送短信>> - 新方式
 +----------------------------------------------------------
 * @param	array $phones
 * @param	string $message
 * @param	string $datetime 定时发送，格式：Y-m-d H:i:s
 +----------------------------------------------------------
 * @return	boolean
 +----------------------------------------------------------
 */
function ysgj_send($phones, $message, $datetime = '')
{
	// 手机号码
	// -------------------------------------------------------------
	$phones = (is_array($phones) ? implode(',', $phones) : $phones);
	
	// 运营商配置
	// -----------------------
	$user_id = 9414;
	$username = '厦门游力';
	$password = '123456';
	
	// api地址
	// ---------------------------------------------
	$api_url = 'http://124.74.138.182:8888/sms.aspx';
	
	// 调用参数
	// --------------------------
	$params = array(
		'userid'	=> $user_id, 
		'account'	=> $username, 	
		'password'	=> $password, 
		'mobile'	=> $phones, 
		'content'	=> $message,
		'sendTime'	=> $datetime, 
		'action'	=> 'send', 
		'extno'		=> '' 
	);
	
	$curl = curl_init();
	
	curl_setopt($curl, CURLOPT_URL, $api_url);
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	
	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 显示输出结果
	
	curl_setopt($curl, CURLOPT_POST, true); // post传输数据
	curl_setopt($curl, CURLOPT_POSTFIELDS, $params); // post传输数据
	
	$response = curl_exec($curl);
	
	curl_close($curl);
	
	$doc = new DOMDocument();
	$doc->loadXML($response);
	
	if ($doc->getElementsByTagName('returnstatus')->item(0)->nodeValue == 'Success') return true;
	
	return $doc->getElementsByTagName('message')->item(0)->nodeValue;
}

/* End of file sms_helper.php */
/* Location: ./helpers/sms_helper.php */