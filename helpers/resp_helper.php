<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 

interface iresponser 
{
    /**
     +----------------------------------------------------------
     * Performs the responser.
     +----------------------------------------------------------
     * @param	array $data
     +----------------------------------------------------------
     * @return	void
     +----------------------------------------------------------
     */
	static function format($data);
}

abstract class xt_responser implements iresponser
{
	/**
	 +----------------------------------------------------------
	 * HTTP response codes and messages
	 +----------------------------------------------------------
	 * @var array
	 +----------------------------------------------------------
	 */
    protected static $messages = array(
    	//Successful 0
    	0	=> '200 OK',
        //Informational 1xx
        100 => '100 Continue',
        101 => '101 Switching Protocols',
        //Successful 2xx
        200 => '200 OK',
        201 => '201 Created',
        202 => '202 Accepted',
        203 => '203 Non-Authoritative Information',
        204 => '204 No Content',
        205 => '205 Reset Content',
        206 => '206 Partial Content',
        //Redirection 3xx
        300 => '300 Multiple Choices',
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        304 => '304 Not Modified',
        305 => '305 Use Proxy',
        306 => '306 (Unused)',
        307 => '307 Temporary Redirect',
        //Client Error 4xx
        400 => '400 Bad Request',
        401 => '401 Unauthorized',
        402 => '402 Payment Required',
        403 => '403 Forbidden',
        404 => '404 Not Found',
        405 => '405 Method Not Allowed',
        406 => '406 Not Acceptable',
        407 => '407 Proxy Authentication Required',
        408 => '408 Request Timeout',
        409 => '409 Conflict',
        410 => '410 Gone',
        411 => '411 Length Required',
        412 => '412 Precondition Failed',
        413 => '413 Request Entity Too Large',
        414 => '414 Request-URI Too Long',
        415 => '415 Unsupported Media Type',
        416 => '416 Requested Range Not Satisfiable',
        417 => '417 Expectation Failed',
        422 => '422 Unprocessable Entity',
        423 => '423 Locked',
        //Server Error 5xx
        500 => '500 Internal Server Error',
        501 => '501 Not Implemented',
        502 => '502 Bad Gateway',
        503 => '503 Service Unavailable',
        504 => '504 Gateway Timeout',
        505 => '505 HTTP Version Not Supported'
    );

    /**
     +----------------------------------------------------------
     * Get message for HTTP status code
     +----------------------------------------------------------
     * @param	int $status
     +----------------------------------------------------------
     * @return	string
     +----------------------------------------------------------
     */
    public static function message( $status ) 
    {
        if ( isset(self::$messages[$status]) ) {
            return self::$messages[$status];
        } else {
            return '';
        }
    }
}

// JSON Responser <<json格式响应处理>>
class json_responser extends xt_responser
{
	public static function format($data)
	{
		if (!isset($data['code'])) {
			$data['code'] = '0';
		}
		
		if (!isset($data['desc']))
		{
			$message = xt_responser::message($data['code']);
			if ($message) {
				$data['desc'] = $message;
			}
		}
		
		if ($data['code'] >= 100 && $data['code'] < 1000) {
			set_status_header($data['code']);
		}
		
		die(json_encode($data));
	}
}

// XML Responser <<xml格式响应处理>>
class xml_responser extends xt_responser
{
	public static function format($data)
	{
		
	}
}

// Time Responser <<时间友好格式化>>
class time_responser
{
	public static function format($time)
	{
		// 无效的时间戳
		// ---------------------------------------
		if (empty($time) || !is_numeric($time)) {
			return '';
		}
		
		$s = time() - $time;
		
		if ($s < 0) {
			return '';
		}
		
		if ($s < 60) {
			return '刚刚';
		}
		
		if ($s < 300) {
			return '1分钟前';
		}
		
		if ($s < 900) {
			return '5分钟前';
		}
		
		if ($s < 1800) {
			return '15分钟前';
		}
		
		if ($s < 3600) {
			return '30分钟前';
		}
		
		if ($s < 86400) {
			return floor($s / 3600) . '小时前';
		}
		
		if ($s < 259200) {
			return floor($s / 86400) . '天前';
		}
		
		return date('Y-m-d H:i:s', $time);
	}
}

interface ipage
{
	static function show($data = array());
}

// Access Deined <<访问拒绝>>
class access_deined_page implements ipage
{
	public static function show($data = array())
	{
		die('Access Deined.');
	}
}

class text_page implements ipage
{
	public static function show($data = array())
	{
		header('Content-Type: text/plain');
		print_r($data);
		die();
	}
}

/* End of file resp_helper.php */
/* Location: ./helpers/resp_helper.php */