<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 
/**
 +----------------------------------------------------------
 * Retrieve browse type base on user agent.<<根据浏览器信息获取浏览器版本>>
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_browse_version()
{
	$browses = array(
		'MSIE 9.0'	=> 'Internet Explorer 9.0',
		'MSIE 8.0'	=> 'Internet Explorer 8.0',
		'MSIE 7.0'	=> 'Internet Explorer 7.0',
		'MSIE 6.0'	=> 'Internet Explorer 6.0',
		'Firefox'	=> 'Firefox',
		'Chrome'	=> 'Google Chrome',
		'Safari'	=> 'Safari',
		'Opera'		=> 'Opera'
	);
	
	// Client User Agent <<浏览器信息>>
	$agent = ck_blog::get_instance()->user_agent();

	foreach ($browses as $f => $browse)
	{
		if (stripos($agent, $f) !== false) {
			return $browse;
		}
	}

	return 'Unknown Browse';
}

/**
 +----------------------------------------------------------
 * 概率抽算
 +----------------------------------------------------------
 * @param	array $rand_list
 * @param	int $base_rate 倍率
 +----------------------------------------------------------
 * @return	int
 +----------------------------------------------------------
 */
function ck_rand_index($rand_list, $base_rate = 100)
{
	// 产生随机数,1-100 * 倍率
	// -------------------------------------
	$r_result = mt_rand(1, 100 * $base_rate);

	// 抽中的目标
	// -----------
	$r_index = 0;

	// 目标范围
	// ---------
	$r_from = 0;
	$r_to = 0;
	
	foreach ($rand_list as $idx => $rv)
	{
		// 起始值
		// -------------------
		$r_from = ($r_to + 1);

		// 终止值
		// ----------
		$r_to += $rv;

		if ($r_result >= $r_from && $r_result <= $r_to)
		{
			// 命中的物品
			// ------------
			$r_index = $idx;
			break;
		}
	}

	return $r_index;
}

/**
 +----------------------------------------------------------
 * 是否手机浏览器访问
 +----------------------------------------------------------
 * @return	boolean
 +----------------------------------------------------------
 */
function ck_is_mobile()
{
	if (preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|playbook|sagem|sharp|sie-|silk|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT'])) {
		return true;
	} else {
		return false;
	}
}

/**
 +----------------------------------------------------------
 * 获取日期天数差
 +----------------------------------------------------------
 * @param	string $date1
 * @param	string $date2
 +----------------------------------------------------------
 * @return	int
 +----------------------------------------------------------
 */
function ck_date_diff($date1, $date2)
{
	return round(abs(strtotime("{$date1} 00:00:00") - strtotime("{$date2} 00:00:00")) / 86400, 0);
}

/**
 +----------------------------------------------------------
 * 返回清除各种标签后的内容
 +----------------------------------------------------------
 * @param	string $content
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_clear_tags($content)
{
	return str_replace(array("\r", "\n", "&nbsp;", ' ', "\t", '　'), '', strip_tags($content));
}

/**
 +----------------------------------------------------------
 * 返回换行符与指定数量的制表符
 +----------------------------------------------------------
 * @param	int $number
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_eol_and_tab($number = 0)
{
	return PHP_EOL . str_repeat("\t", $number);
}

/**
 +----------------------------------------------------------
 * Email Address
 +----------------------------------------------------------
 * Get address of email.<<获取邮箱的URL地址>>
 +----------------------------------------------------------
 * @param	string $email
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_email_address($email)
{	
	$email_addresses = array(
		'gmail.com'		=> 'http://mail.google.com/', 
		'yahoo.com'		=> 'http://mail.yahoo.com/',  
		'hotmail.com'	=> 'http://mail.live.com/',  
		'qq.com'		=> 'http://mail.qq.com/', 
		'163.com'		=> 'http://mail.163.com/',
		'126.com'		=> 'http://mail.126.com/', 
		'sina.com.cn'	=> 'http://mail.sina.com.cn/'
	);
	
	$email_domain = array_pop(explode('@', $email));
	if (isset($email_addresses[$email_domain])) {
		return $email_addresses[$email_domain];
	} else {
		return 'http://mail.' . $email_domain . '/';
	}
}

/**
 +----------------------------------------------------------
 * If ip is in allow ip list.<<是否在指定的IP列表中>>
 +----------------------------------------------------------
 * @param	string $ip
 * @param	array|string $allow_list
 +----------------------------------------------------------
 * @return	boolean
 +----------------------------------------------------------
 */
function ck_in_ips($ip, $allow_list)
{
	if (is_array($allow_list))
	{
		foreach ($allow_list as $allow_ip)
		{
			$allow = ck_in_ips($ip, $allow_ip);
			if ($allow === true) {
				return true;
			}
		}
		return false;
	}
	elseif (strpos($allow_list, '*') !== false)
	{
		$allow = true;
		$ip_arr = explode('.', $ip);
		$allow_ip_arr = explode('.', $allow_list);
		for ($i = 0; $i < 4; $i++) {
			if ($ip_arr[$i] != $allow_ip_arr[$i] && $allow_ip_arr[$i] != '*') {
				$allow = false;
				break;
			}
		}
		return $allow;
	}
	else 
	{
		return ($ip == $allow_list);
	}
}

/**
 +----------------------------------------------------------
 * Generate a random string. <<生产随机串>>
 +----------------------------------------------------------
 * @param	int $type
 * @param	int $length
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_verify_code($type = 'digit', $length = 6)
{
    $pool = '0123456789';
    if ($type == 'alpha') {
    	$pool = 'abcdefghjkmnpqrstuvwxyz';
    } elseif ($type == 'alnum') {
    	$pool = '0123456789abcdefghjkmnpqrstuvwxyz';
    }
    
    $pl = strlen($pool);
    
    $verify_code = '';
	for ($i = 0; $i < $length; $i++) {
		$verify_code .= substr($pool, mt_rand(0, $pl - 1), 1);
	}
	
	return $verify_code;
}

/**
 +----------------------------------------------------------
 * Generate a serial Number <<生成唯一序列号>>
 +----------------------------------------------------------
 * @param	string $prefix
 * @param	int $rand_num
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_serial_number($prefix = '', $rand_num = 4)
{
	return $prefix . date('y') . time() . ck_verify_code('digit', $rand_num);
}

/**
 +----------------------------------------------------------
 * Set string to fuzzy.<<将部分信息打上星号>>
 +----------------------------------------------------------
 * @param	string $str
 * @param	string $type
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_fuzzy_string($str, $type = 'account')
{
	if ($type == 'account')
	{
		if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,}$/ix", $str)) {
			$type = 'email';
		} elseif (preg_match("/^1[3458]\d{9}$/ix", $str)) {
			$type = 'mobile';
		}
	}
	
	switch ($type)
	{
		case 'account':
			return jm_utf8_substr($str, 0, 4) . '*****';
		case 'email':
			$sub_c = strpos($str, '@');
			if ($sub_c + 1 <= 3) {
				return jm_utf8_substr($str, 0, 1) . '***' . jm_utf8_substr($str, $sub_c);
			} else {
				return jm_utf8_substr($str, 0, 3) . '***' . jm_utf8_substr($str, $sub_c);
			}
		case 'mobile':
			return jm_utf8_substr($str, 0, 4) . '*****';
		case 'name':
			return '***';
		case 'identity':
			return jm_utf8_substr($str, 0, 4) . str_repeat('*', strlen($str) - 4);
		case 'qq':
			return jm_utf8_substr($str, 0, 4) . '*****';
		case 'ecard':
			return jm_utf8_substr($str, 0, 4) . str_repeat('*', strlen($str) - 4);
	}
}

/**
 +----------------------------------------------------------
 * Get dot string to omit something.<<省略字符串>>
 +----------------------------------------------------------
 * @param	string $str
 * @param	int $length
 * @param	string $dot
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_omit_string($str, $length, $dot = '...')
{
	if( ! preg_match("/[\x{4e00}-\x{9fa5}]+/u", $str) ) {
		$length *= 2;
	}
	
	if ($length < mb_strlen($str)) {
		$substr = jm_utf8_substr($str, 0, $length);
		if ($substr == $str) {
			return $substr;
		} else {
			return $substr . $dot;
		}
	} else {
		return $str;
	}
}

/**
 +----------------------------------------------------------
 * Get strength of password.<<获取用户密码强度>>
 +----------------------------------------------------------
 * @param	string $password
 +----------------------------------------------------------
 * @return	int
 +----------------------------------------------------------
 */
function ck_password_strength($password)
{
	if (preg_match("/^([A-Z])+$/", $password) || preg_match( "/^([a-z])+$/", $password) || preg_match( "/^([0-9])+$/", $password)) {
		return 1;
	} elseif (preg_match("/^([a-z0-9])+$/", $password) || preg_match("/^([A-Z0-9])+$/", $password) || preg_match("/^([A-Za-z])+$/", $password) || preg_match("/^(?!.*[A-Za-z]).+$/", $password) || preg_match("/^(?!.*[A-Z0-9]).+$/", $password) || preg_match("/^(?!.*[a-z0-9]).+$/", $password)) {
		return 2;
	} elseif (strlen($password) < 9) {
		return 2;
	} else {
		return 3;
	}
}

/**
 +----------------------------------------------------------
 * Get readable time.<<根据时间戳获取可读性的时间>>
 +----------------------------------------------------------
 * @param	int $time
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_readable_time($time)
{
	$time = time() - strtotime($time);
	
	$day = floor($time / 60 / 60 / 24);
	$time -= $day * 60 * 60 * 24;
	
	$hour = floor($time / 60 / 60);
	$time -= $hour * 60 * 60;
	
	$minute = floor($time / 60);
	$time -= $minute * 60;
	
	$second = $time;
	$elapse = '';
	
	$units = array('天' => 'day','小时' => 'hour', '分钟' => 'minute', '秒' => 'second');
	foreach ($units as $cn => $u)
	{
		if ($u == 'day') 
		{
			if (${$u} > 0 && ${$u} <= 5) {
				return $elapse = ${$u} . $cn . '前';	
			} elseif (${$u} > 5) {
				return date('Y-m-d H:i', strtotime($time));
			}			
		} 
		elseif (${$u} > 0) 
		{
			$elapse = ${$u} . $cn . '前';
			break;
		}
	}
	
	return $elapse;
}

/**
 +----------------------------------------------------------
 * Format hex to rgb.<<将16进制颜色值转换为RGB值>>
 +----------------------------------------------------------
 * @param	string $color
 * @param	string $default
 +----------------------------------------------------------
 * @return	array
 +----------------------------------------------------------
 */
function ck_hex2rgb($color, $default = 'ffffff')
{
	$color = strtolower($color);
	if (substr($color, 0, 2) == '0x') {
		$color = substr($color, 2);
	} elseif (substr($color, 0, 1) == '#') {
		$color = substr($color, 1);
	}
	
	$l = strlen($color);
	if ($l == 3) {
		$r = hexdec(substr($color, 0, 1));
		$g = hexdec(substr($color, 1, 1));
		$b = hexdec(substr($color, 2, 1));
		return array($r, $g, $b);
	} elseif ($l != 6) {
		$color = $defualt;
	}
	
	$r = hexdec(substr($color, 0, 2));
	$g = hexdec(substr($color, 2, 2));
	$b = hexdec(substr($color, 4, 2));
	
	return array($r, $g, $b);
}

/**
 +----------------------------------------------------------
 * 加密字符串
 +----------------------------------------------------------
 * @param	string $data
 * @param	string $key
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function ck_hmac_md5($data, $key)
{
	// RFC 2104 HMAC implementation for php.
	// Creates an md5 HMAC.
	// Eliminates the need to install mhash to compute a HMAC
	// Hacked by Lance Rushing(NOTE: Hacked means written)

	$b = 64;
	if (strlen($key) > $b) {
		$key = pack("H*", md5($key));
	}

	$key = str_pad($key, $b, chr(0x00));
	$ipad = str_pad('', $b, chr(0x36));
	$opad = str_pad('', $b, chr(0x5c));

	$k_ipad = $key ^ $ipad ;
	$k_opad = $key ^ $opad;

	return md5($k_opad . pack("H*", md5($k_ipad . $data)));
}

// 来自开源系统代码
// 所有不属于系统的代码扩展增加到这里
// ------------------------------------------------------------------------

// Joomla code
// ========================================================================

/**
 * UTF-8 aware alternative to substr
 * Return part of a string given character offset (and optionally length)
 * Note: supports use of negative offsets and lengths but will be slower
 * when doing so
 * @param string
 * @param integer number of UTF-8 characters offset (from left)
 * @param integer (optional) length in UTF-8 characters from offset
 * @return mixed string or false if failure
 * @package utf8
 * @subpackage strings
 */
function jm_utf8_substr($str, $offset, $length = NULL) {

    if ( $offset >= 0 && $length >= 0 ) {

        if ( $length === NULL ) {
            $length = '*';
        } else {
            if ( !preg_match('/^[0-9]+$/', $length) ) {
                return false;
            }

            $strlen = strlen(utf8_decode($str));
            if ( $offset > $strlen ) {
                return '';
            }

            if ( ( $offset + $length ) > $strlen ) {
            	$length = '*';
            } else {
            	$length = '{'.$length.'}';
            }
        }

        if ( !preg_match('/^[0-9]+$/', $offset) ) {
            return false;
        }

        $pattern = '/^.{' . $offset . '}(.' . $length . ')/us';

        preg_match($pattern, $str, $matches);

        if ( isset($matches[1]) ) {
            return $matches[1];
        }

        return false;

    } else {

        // Handle negatives using different, slower technique
        // From: http://www.php.net/manual/en/function.substr.php#44838
        preg_match_all('/./u', $str, $ar);
        if( $length !== NULL ) {
            return join('', array_slice($ar[0], $offset, $length));
        } else {
            return join('', array_slice($ar[0], $offset));
        }
        
    }
}

// Discuz code
// ========================================================================

/**
 * Authcode
 * 
 * 加密解密函数
 * 
 * @param string $string
 * @param string $operation encode|decode
 * @param string $key
 * @return string
 */
function dz_authcode($string, $operation, $key = '') 
{
	$key = md5($key);
    $key_length = strlen($key);
    $string = $operation == 'decode' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;

    $string_length = strlen($string);

    $result = '';
    $rndkey = $box = array();

    for ($i = 0; $i <= 255; $i++) {
    	$rndkey[$i] = ord($key[$i % $key_length]);        
    	$box[$i] = $i;
    }

    for ($j = $i = 0; $i < 256; $i++) {
    	$j = ($j + $box[$i] + $rndkey[$i]) % 256;        
    	$tmp = $box[$i];        
    	$box[$i] = $box[$j];        
    	$box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
    	$a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'decode') {    
        if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
        	return substr($result, 8);
        } else {
        	return '';
        }
    } else {
    	return str_replace('=', '', base64_encode($result));
    }     
}

// Wordpress code
// ========================================================================

/**
 * Convert number of bytes largest unit bytes will fit into.
 *
 * It is easier to read 1kB than 1024 bytes and 1MB than 1048576 bytes. Converts
 * number of bytes to human readable number by taking the number of that unit
 * that the bytes will go into it. Supports TB value.
 *
 * Please note that integers in PHP are limited to 32 bits, unless they are on
 * 64 bit architecture, then they have 64 bit size. If you need to place the
 * larger size then what PHP integer type will hold, then use a string. It will
 * be converted to a double, which should always have 64 bit length.
 *
 * Technically the correct unit names for powers of 1024 are KiB, MiB etc.
 * @link http://en.wikipedia.org/wiki/Byte
 *
 * @since 2.3.0
 *
 * @param int|string $bytes Number of bytes. Note max integer size for integers.
 * @param int $decimals Precision of number of decimal places. Deprecated.
 * @return bool|string False on failure. Number string on success.
 */
function wp_size_format( $bytes, $decimals = 0 ) {
	$quant = array(
		// ========================= Origin ====
		'TB' => 1099511627776,  // pow( 1024, 4)
		'GB' => 1073741824,     // pow( 1024, 3)
		'MB' => 1048576,        // pow( 1024, 2)
		'kB' => 1024,           // pow( 1024, 1)
		'B ' => 1,              // pow( 1024, 0)
	);
	foreach ( $quant as $unit => $mag )
		if ( doubleval($bytes) >= $mag )
			return number_format_i18n( $bytes / $mag, $decimals ) . ' ' . $unit;

	return false;
}

// ------------------------------------------------------------------------

/**
 * Unserialize value only if it was serialized.
 *
 * @since 2.0.0
 *
 * @param string $original Maybe unserialized original, if is needed.
 * @return mixed Unserialized data can be any type.
 */
function wp_maybe_unserialize( $original ) {
	if ( wp_is_serialized( $original ) ) // don't attempt to unserialize data that wasn't serialized going in
		return @unserialize( $original );
	return $original;
}

// ------------------------------------------------------------------------

/**
 * Check value to find if it was serialized.
 *
 * If $data is not an string, then returned value will always be false.
 * Serialized data is always a string.
 *
 * @since 2.0.5
 *
 * @param mixed $data Value to check to see if was serialized.
 * @return bool False if not serialized and true if it was.
 */
function wp_is_serialized( $data ) {
	// if it isn't a string, it isn't serialized
	if ( ! is_string( $data ) )
		return false;
	$data = trim( $data );
 	if ( 'N;' == $data )
		return true;
	$length = strlen( $data );
	if ( $length < 4 )
		return false;
	if ( ':' !== $data[1] )
		return false;
	$lastc = $data[$length-1];
	if ( ';' !== $lastc && '}' !== $lastc )
		return false;
	$token = $data[0];
	switch ( $token ) {
		case 's' :
			if ( '"' !== $data[$length-2] )
				return false;
		case 'a' :
		case 'O' :
			return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
		case 'b' :
		case 'i' :
		case 'd' :
			return (bool) preg_match( "/^{$token}:[0-9.E-]+;\$/", $data );
	}
	return false;
}

// ------------------------------------------------------------------------

/**
 * Check whether serialized data is of string type.
 *
 * @since 2.0.5
 *
 * @param mixed $data Serialized data
 * @return bool False if not a serialized string, true if it is.
 */
function wp_is_serialized_string( $data ) {
	// if it isn't a string, it isn't a serialized string
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	if ( preg_match( '/^s:[0-9]+:.*;$/s', $data ) ) // this should fetch all serialized strings
		return true;
	return false;
}

// ------------------------------------------------------------------------

/**
 * Serialize data, if needed.
 *
 * @since 2.0.5
 *
 * @param mixed $data Data that might be serialized.
 * @return mixed A scalar data
 */
function wp_maybe_serialize( $data ) {
	if ( is_array( $data ) || is_object( $data ) )
		return serialize( $data );

	if ( wp_is_serialized( $data ) )
		return serialize( $data );

	return $data;
}

// ------------------------------------------------------------------------

/**
 * Gets the header information to prevent caching.
 *
 * The several different headers cover the different ways cache prevention is handled
 * by different browsers
 *
 * @since 2.8.0
 *
 * @uses apply_filters()
 * @return array The associative array of header names and field values.
 */
function wp_get_nocache_headers() {
	$headers = array(
		'Expires' => 'Wed, 11 Jan 1984 05:00:00 GMT',
		'Last-Modified' => gmdate( 'D, d M Y H:i:s' ) . ' GMT',
		'Cache-Control' => 'no-cache, must-revalidate, max-age=0',
		'Pragma' => 'no-cache',
	);

	return $headers;
}

// ------------------------------------------------------------------------

/**
 * Sets the headers to prevent caching for the different browsers.
 *
 * Different browsers support different nocache headers, so several headers must
 * be sent so that all of them get the point that no caching should occur.
 *
 * @since 2.0.0
 * @uses wp_get_nocache_headers()
 */
function wp_nocache_headers() {
	$headers = wp_get_nocache_headers();
	foreach( $headers as $name => $field_value )
		@header("{$name}: {$field_value}");
}

// ------------------------------------------------------------------------

/**
 * Checks to see if a string is utf8 encoded.
 *
 * NOTE: This function checks for 5-Byte sequences, UTF8
 *       has Bytes Sequences with a maximum length of 4.
 *
 * @author bmorel at ssi dot fr (modified)
 * @since 1.2.1
 *
 * @param string $str The string to be checked
 * @return bool True if $str fits a UTF-8 model, false otherwise.
 */
function wp_seems_utf8($str) {
	$length = strlen($str);
	for ($i=0; $i < $length; $i++) {
		$c = ord($str[$i]);
		if ($c < 0x80) $n = 0; # 0bbbbbbb
		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
				return false;
		}
	}
	return true;
}

// ------------------------------------------------------------------------

/**
 * Navigates through an array and encodes the values to be used in a URL.
 *
 * Uses a callback to pass the value of the array back to the function as a
 * string.
 *
 * @since 2.2.0
 *
 * @param array|string $value The array or string to be encoded.
 * @return array|string $value The encoded array (or string from the callback).
 */
function wp_urlencode_deep($value) {
	$value = is_array($value) ? array_map('wp_urlencode_deep', $value) : urlencode($value);
	return $value;
}

// ------------------------------------------------------------------------

/**
 * Navigates through an array and removes slashes from the values.
 *
 * If an array is passed, the array_map() function causes a callback to pass the
 * value back to the function. The slashes from this value will removed.
 *
 * @since 2.0.0
 *
 * @param array|string $value The array or string to be stripped.
 * @return array|string Stripped array (or string in the callback).
 */
function wp_stripslashes_deep($value) {
	if ( is_array($value) ) {
		$value = array_map('wp_stripslashes_deep', $value);
	} elseif ( is_object($value) ) {
		$vars = get_object_vars( $value );
		foreach ($vars as $key=>$data) {
			$value->{$key} = wp_stripslashes_deep( $data );
		}
	} else {
		$value = stripslashes($value);
	}

	return $value;
}

// ------------------------------------------------------------------------

/**
 * Converts email addresses characters to HTML entities to block spam bots.
 *
 * @since 0.71
 *
 * @param string $emailaddy Email address.
 * @param int $mailto Optional. Range from 0 to 1. Used for encoding.
 * @return string Converted email address.
 */
function wp_antispambot($emailaddy, $mailto=0) {
	$emailNOSPAMaddy = '';
	srand ((float) microtime() * 1000000);
	for ($i = 0; $i < strlen($emailaddy); $i = $i + 1) {
		$j = floor(rand(0, 1+$mailto));
		if ($j==0) {
			$emailNOSPAMaddy .= '&#'.ord(substr($emailaddy,$i,1)).';';
		} elseif ($j==1) {
			$emailNOSPAMaddy .= substr($emailaddy,$i,1);
		} elseif ($j==2) {
			$emailNOSPAMaddy .= '%'.zeroise(dechex(ord(substr($emailaddy, $i, 1))), 2);
		}
	}
	$emailNOSPAMaddy = str_replace('@','&#64;',$emailNOSPAMaddy);
	return $emailNOSPAMaddy;
}

/**
 * Converts value to nonnegative integer.
 *
 * @since 2.5.0
 *
 * @param mixed $maybeint Data you wish to have converted to a nonnegative integer
 * @return int An nonnegative integer
 */
function wp_absint( $maybeint ) {
	return abs( intval( $maybeint ) );
}

/**
 * Replaces double line-breaks with paragraph elements.
 *
 * A group of regex replaces used to identify text formatted with newlines and
 * replace double line-breaks with HTML paragraph tags. The remaining
 * line-breaks after conversion become <<br />> tags, unless $br is set to '0'
 * or 'false'.
 *
 * @since 0.71
 *
 * @param string $pee The text which has to be formatted.
 * @param bool $br Optional. If set, this will convert all remaining line-breaks after paragraphing. Default true.
 * @return string Text which has been converted into correct paragraph tags.
 */
function wp_autop($pee, $br = true) {
	$pre_tags = array();

	if ( trim($pee) === '' )
		return '';

	$pee = $pee . "\n"; // just to make things a little easier, pad the end

	if ( strpos($pee, '<pre') !== false ) {
		$pee_parts = explode( '</pre>', $pee );
		$last_pee = array_pop($pee_parts);
		$pee = '';
		$i = 0;

		foreach ( $pee_parts as $pee_part ) {
			$start = strpos($pee_part, '<pre');

			// Malformed html?
			if ( $start === false ) {
				$pee .= $pee_part;
				continue;
			}

			$name = "<pre wp-pre-tag-$i></pre>";
			$pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

			$pee .= substr( $pee_part, 0, $start ) . $name;
			$i++;
		}

		$pee .= $last_pee;
	}

	$pee = preg_replace('|<br />\s*<br />|', "\n\n", $pee);
	// Space things out a little
	$allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|select|option|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';
	$pee = preg_replace('!(<' . $allblocks . '[^>]*>)!', "\n$1", $pee);
	$pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);
	$pee = str_replace(array("\r\n", "\r"), "\n", $pee); // cross-platform newlines
	if ( strpos($pee, '<object') !== false ) {
		$pee = preg_replace('|\s*<param([^>]*)>\s*|', "<param$1>", $pee); // no pee inside object/embed
		$pee = preg_replace('|\s*</embed>\s*|', '</embed>', $pee);
	}
	$pee = preg_replace("/\n\n+/", "\n\n", $pee); // take care of duplicates
	// make paragraphs, including one at the end
	$pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);
	$pee = '';
	foreach ( $pees as $tinkle )
		$pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
	$pee = preg_replace('|<p>\s*</p>|', '', $pee); // under certain strange conditions it could create a P of entirely whitespace
	$pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee); // don't pee all over a tag
	$pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee); // problem with nested lists
	$pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
	$pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);
	$pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);
	if ( $br ) {
		$pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);
		$pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee); // optionally make line breaks
		$pee = str_replace('<WPPreserveNewline />', "\n", $pee);
	}
	$pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);
	$pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
	$pee = preg_replace( "|\n</p>$|", '</p>', $pee );

	if ( !empty($pre_tags) )
		$pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

	return $pee;
}

/**
 * Properly strip all HTML tags including script and style
 *
 * @since 2.9.0
 *
 * @param string $string String containing HTML tags
 * @param bool $remove_breaks optional Whether to remove left over line breaks and white space chars
 * @return string The processed string.
 */
function wp_strip_all_tags($string, $remove_breaks = false) {
	$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
	$string = strip_tags($string);

	if ( $remove_breaks )
		$string = preg_replace('/[\r\n\t ]+/', ' ', $string);

	return trim( $string );
}

/**
 * Converts all accent characters to ASCII characters.
 *
 * If there are no accent characters, then the string given is just returned.
 *
 * @since 1.2.1
 *
 * @param string $string Text that might have accent characters
 * @return string Filtered string with replaced "nice" characters.
 */
function wp_remove_accents($string) {
	if ( !preg_match('/[\x80-\xff]/', $string) )
		return $string;

	if (wp_seems_utf8($string)) {
		$chars = array(
		// Decompositions for Latin-1 Supplement
		chr(194).chr(170) => 'a', chr(194).chr(186) => 'o',
		chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
		chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
		chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
		chr(195).chr(134) => 'AE',chr(195).chr(135) => 'C',
		chr(195).chr(136) => 'E', chr(195).chr(137) => 'E',
		chr(195).chr(138) => 'E', chr(195).chr(139) => 'E',
		chr(195).chr(140) => 'I', chr(195).chr(141) => 'I',
		chr(195).chr(142) => 'I', chr(195).chr(143) => 'I',
		chr(195).chr(144) => 'D', chr(195).chr(145) => 'N',
		chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
		chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
		chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
		chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
		chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
		chr(195).chr(158) => 'TH',chr(195).chr(159) => 's',
		chr(195).chr(160) => 'a', chr(195).chr(161) => 'a',
		chr(195).chr(162) => 'a', chr(195).chr(163) => 'a',
		chr(195).chr(164) => 'a', chr(195).chr(165) => 'a',
		chr(195).chr(166) => 'ae',chr(195).chr(167) => 'c',
		chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
		chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
		chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
		chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
		chr(195).chr(176) => 'd', chr(195).chr(177) => 'n',
		chr(195).chr(178) => 'o', chr(195).chr(179) => 'o',
		chr(195).chr(180) => 'o', chr(195).chr(181) => 'o',
		chr(195).chr(182) => 'o', chr(195).chr(184) => 'o',
		chr(195).chr(185) => 'u', chr(195).chr(186) => 'u',
		chr(195).chr(187) => 'u', chr(195).chr(188) => 'u',
		chr(195).chr(189) => 'y', chr(195).chr(190) => 'th',
		chr(195).chr(191) => 'y', chr(195).chr(152) => 'O',
		// Decompositions for Latin Extended-A
		chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
		chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
		chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
		chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
		chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
		chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
		chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
		chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
		chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
		chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
		chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
		chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
		chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
		chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
		chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
		chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
		chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
		chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
		chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
		chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
		chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
		chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
		chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
		chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
		chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
		chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
		chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
		chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
		chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
		chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
		chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
		chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
		chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
		chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
		chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
		chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
		chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
		chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
		chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
		chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
		chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
		chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
		chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
		chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
		chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
		chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
		chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
		chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
		chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
		chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
		chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
		chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
		chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
		chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
		chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
		chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
		chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
		chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
		chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
		chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
		chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
		chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
		chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
		chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
		// Decompositions for Latin Extended-B
		chr(200).chr(152) => 'S', chr(200).chr(153) => 's',
		chr(200).chr(154) => 'T', chr(200).chr(155) => 't',
		// Euro Sign
		chr(226).chr(130).chr(172) => 'E',
		// GBP (Pound) Sign
		chr(194).chr(163) => '',
		// Vowels with diacritic (Vietnamese)
		// unmarked
		chr(198).chr(160) => 'O', chr(198).chr(161) => 'o',
		chr(198).chr(175) => 'U', chr(198).chr(176) => 'u',
		// grave accent
		chr(225).chr(186).chr(166) => 'A', chr(225).chr(186).chr(167) => 'a',
		chr(225).chr(186).chr(176) => 'A', chr(225).chr(186).chr(177) => 'a',
		chr(225).chr(187).chr(128) => 'E', chr(225).chr(187).chr(129) => 'e',
		chr(225).chr(187).chr(146) => 'O', chr(225).chr(187).chr(147) => 'o',
		chr(225).chr(187).chr(156) => 'O', chr(225).chr(187).chr(157) => 'o',
		chr(225).chr(187).chr(170) => 'U', chr(225).chr(187).chr(171) => 'u',
		chr(225).chr(187).chr(178) => 'Y', chr(225).chr(187).chr(179) => 'y',
		// hook
		chr(225).chr(186).chr(162) => 'A', chr(225).chr(186).chr(163) => 'a',
		chr(225).chr(186).chr(168) => 'A', chr(225).chr(186).chr(169) => 'a',
		chr(225).chr(186).chr(178) => 'A', chr(225).chr(186).chr(179) => 'a',
		chr(225).chr(186).chr(186) => 'E', chr(225).chr(186).chr(187) => 'e',
		chr(225).chr(187).chr(130) => 'E', chr(225).chr(187).chr(131) => 'e',
		chr(225).chr(187).chr(136) => 'I', chr(225).chr(187).chr(137) => 'i',
		chr(225).chr(187).chr(142) => 'O', chr(225).chr(187).chr(143) => 'o',
		chr(225).chr(187).chr(148) => 'O', chr(225).chr(187).chr(149) => 'o',
		chr(225).chr(187).chr(158) => 'O', chr(225).chr(187).chr(159) => 'o',
		chr(225).chr(187).chr(166) => 'U', chr(225).chr(187).chr(167) => 'u',
		chr(225).chr(187).chr(172) => 'U', chr(225).chr(187).chr(173) => 'u',
		chr(225).chr(187).chr(182) => 'Y', chr(225).chr(187).chr(183) => 'y',
		// tilde
		chr(225).chr(186).chr(170) => 'A', chr(225).chr(186).chr(171) => 'a',
		chr(225).chr(186).chr(180) => 'A', chr(225).chr(186).chr(181) => 'a',
		chr(225).chr(186).chr(188) => 'E', chr(225).chr(186).chr(189) => 'e',
		chr(225).chr(187).chr(132) => 'E', chr(225).chr(187).chr(133) => 'e',
		chr(225).chr(187).chr(150) => 'O', chr(225).chr(187).chr(151) => 'o',
		chr(225).chr(187).chr(160) => 'O', chr(225).chr(187).chr(161) => 'o',
		chr(225).chr(187).chr(174) => 'U', chr(225).chr(187).chr(175) => 'u',
		chr(225).chr(187).chr(184) => 'Y', chr(225).chr(187).chr(185) => 'y',
		// acute accent
		chr(225).chr(186).chr(164) => 'A', chr(225).chr(186).chr(165) => 'a',
		chr(225).chr(186).chr(174) => 'A', chr(225).chr(186).chr(175) => 'a',
		chr(225).chr(186).chr(190) => 'E', chr(225).chr(186).chr(191) => 'e',
		chr(225).chr(187).chr(144) => 'O', chr(225).chr(187).chr(145) => 'o',
		chr(225).chr(187).chr(154) => 'O', chr(225).chr(187).chr(155) => 'o',
		chr(225).chr(187).chr(168) => 'U', chr(225).chr(187).chr(169) => 'u',
		// dot below
		chr(225).chr(186).chr(160) => 'A', chr(225).chr(186).chr(161) => 'a',
		chr(225).chr(186).chr(172) => 'A', chr(225).chr(186).chr(173) => 'a',
		chr(225).chr(186).chr(182) => 'A', chr(225).chr(186).chr(183) => 'a',
		chr(225).chr(186).chr(184) => 'E', chr(225).chr(186).chr(185) => 'e',
		chr(225).chr(187).chr(134) => 'E', chr(225).chr(187).chr(135) => 'e',
		chr(225).chr(187).chr(138) => 'I', chr(225).chr(187).chr(139) => 'i',
		chr(225).chr(187).chr(140) => 'O', chr(225).chr(187).chr(141) => 'o',
		chr(225).chr(187).chr(152) => 'O', chr(225).chr(187).chr(153) => 'o',
		chr(225).chr(187).chr(162) => 'O', chr(225).chr(187).chr(163) => 'o',
		chr(225).chr(187).chr(164) => 'U', chr(225).chr(187).chr(165) => 'u',
		chr(225).chr(187).chr(176) => 'U', chr(225).chr(187).chr(177) => 'u',
		chr(225).chr(187).chr(180) => 'Y', chr(225).chr(187).chr(181) => 'y',
		);

		$string = strtr($string, $chars);
	} else {
		// Assume ISO-8859-1 if not UTF-8
		$chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
			.chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
			.chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
			.chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
			.chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
			.chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
			.chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
			.chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
			.chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
			.chr(252).chr(253).chr(255);

		$chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

		$string = strtr($string, $chars['in'], $chars['out']);
		$double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
		$double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
		$string = str_replace($double_chars['in'], $double_chars['out'], $string);
	}

	return $string;
}

/* End of file common_helper.php */
/* Location: ./helpers/common_helper.php */