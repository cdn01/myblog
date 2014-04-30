<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 
 
class ck_input 
{
	/**
	 +----------------------------------------------------------
	 * 客户端ip地址
	 +----------------------------------------------------------
	 * @var	string
	 +----------------------------------------------------------
	 */
	private $ip_address				= false;
	
	/**
	 +----------------------------------------------------------
	 * 客户端浏览器信息
	 +----------------------------------------------------------
	 * @var	string
	 +----------------------------------------------------------
	 */
	private $user_agent				= false;
	
	/**
	 +----------------------------------------------------------
	 * If false, then $_GET will be set to an empty array
	 +----------------------------------------------------------
	 * @var boolean
	 +----------------------------------------------------------
	 */
	private $_allow_get_array		= true;
	
	/**
	 +----------------------------------------------------------
	 * If true, then newlines are standardized
	 +----------------------------------------------------------
	 * @var boolean
	 +----------------------------------------------------------
	 */
	private $_standardize_newlines	= true;
	
	/**
	 +----------------------------------------------------------
	 * Determines whether the XSS filter is always active when GET, POST or COOKIE data is encountered
	 * Set automatically based on config setting
	 +----------------------------------------------------------
	 * @var boolean
	 +----------------------------------------------------------
	 */
	private $_enable_xss			= false;
	
	/**
	 +----------------------------------------------------------
	 * Enables a CSRF cookie token to be set.
	 * Set automatically based on config setting
	 +----------------------------------------------------------
	 * @var boolean
	 +----------------------------------------------------------
	 */
	private $_enable_csrf			= false;
	
	/**
	 +----------------------------------------------------------
	 * List of all HTTP request headers
	 +----------------------------------------------------------
	 * @var unknown_type
	 +----------------------------------------------------------
	 */
	protected $headers				= array();

	/**
	 +----------------------------------------------------------
	 * Sets whether to globally enable the XSS processing
	 * and whether to allow the $_GET array
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function __construct()
	{
		$this->_allow_get_array	= (ck_blog::get_instance()->config()->item('allow_get_array') === true);
		$this->_enable_xss		= (ck_blog::get_instance()->config()->item('global_xss_filtering') === true);
		$this->_enable_csrf		= (ck_blog::get_instance()->config()->item('csrf_protection') === true);

		// Sanitize global arrays
		// ------------------------
		$this->_sanitize_globals();
	}

	/**
	 +----------------------------------------------------------
	 * 数据获取统一入口，过滤非法数据
	 +----------------------------------------------------------
	 * @param	array $array
	 * @param	string $index
	 * @param	boolean $xss_clean
	 +----------------------------------------------------------
	 * @return	mixed
	 +----------------------------------------------------------
	 */
	function _fetch_from_array(&$array, $index = '', $xss_clean = false)
	{
		if (!isset($array[$index]))
		{
			// 不存在的数据
			// ----------
			return false;
		}

		if ($xss_clean === true)
		{
			// 数据清洁
			// ------------------------------------------------------------------
			return ck_blog::get_instance()->security()->xss_clean($array[$index]);
		}

		return $array[$index];
	}

	/**
	 +----------------------------------------------------------
	 * get数据获取统一入口
	 +----------------------------------------------------------
	 * @param	string $index
	 * @param	boolean $xss_clean
	 +----------------------------------------------------------
	 * @return	mixed
	 +----------------------------------------------------------
	 */
	function get($index = null, $xss_clean = false)
	{ 
		// Check if a field has been provided
		// -------------------------------------
		if ($index === null && ! empty($_GET))
		{
			$get = array();

			foreach (array_keys($_GET) as $key)
			{
				// loop through the full _GET array
				// ------------------------------------------------------------
				$get[$key] = $this->_fetch_from_array($_GET, $key, $xss_clean);
			}
			
			return $get;
		}

		return $this->_fetch_from_array($_GET, $index, $xss_clean);
	}

	/**
	 +----------------------------------------------------------
	 * post数据获取统一入口
	 +----------------------------------------------------------
	 * @param	string $index
	 * @param	boolean $xss_clean
	 +----------------------------------------------------------
	 * @return	mixed
	 +----------------------------------------------------------
	 */
	function post($index = null, $xss_clean = false)
	{
		// Check if a field has been provided
		// -------------------------------------
		if ($index === null && ! empty($_POST))
		{
			$get = array();

			foreach (array_keys($_POST) as $key)
			{
				// loop through the full _GET array
				// ------------------------------------------------------------
				$get[$key] = $this->_fetch_from_array($_POST, $key, $xss_clean);
			}
			
			return $get;
		}

		return $this->_fetch_from_array($_POST, $index, $xss_clean);
	}

	/**
	 +----------------------------------------------------------
	 * Fetch an item from either the GET array or the POST
	 +----------------------------------------------------------
	 * @param	string $index
	 * @param	boolean $xss_clean
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function get_post($index = '', $xss_clean = false)
	{
		return !isset($_POST[$index]) ? $this->get($index, $xss_clean) : $this->post($index, $xss_clean);
	}

	/**
	 +----------------------------------------------------------
	 * Fetch an item from the COOKIE array
	 +----------------------------------------------------------
	 * @param	string $index
	 * @param	boolean $xss_clean
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function cookie($index = '', $xss_clean = false)
	{
		return $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
	}

	/**
	 +----------------------------------------------------------
	 * Accepts six parameter, or you can submit an associative
	 * array in the first parameter containing all the values.
	 +----------------------------------------------------------
	 * @param	mixed
	 * @param	string	the value of the cookie
 	 * @param	string	the number of seconds until expiration
 	 * @param	string	the cookie domain.  Usually:  .yourdomain.com
	 * @param	string	the cookie path
	 * @param	string	the cookie prefix
	 * @param	bool	true makes the cookie secure
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function set_cookie($name = '', $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = false)
	{
		if (is_array($name))
		{
			// always leave 'name' in last place, as the loop will break otherwise, due to $$item
			foreach (array('value', 'expire', 'domain', 'path', 'prefix', 'secure', 'name') as $item)
			{
				if (isset($name[$item]))
				{
					${$item} = $name[$item];
				}
			}
		}

		if ($prefix == '' && ck_blog::get_instance()->config()->item('cookie_prefix') != '')
		{
			$prefix = ck_blog::get_instance()->config()->item('cookie_prefix');
		}
		
		if ($domain == '' && ck_blog::get_instance()->config()->item('cookie_domain') != '')
		{
			$domain = ck_blog::get_instance()->config()->item('cookie_domain');
		}
		
		if ($path == '/' && ck_blog::get_instance()->config()->item('cookie_path') != '/')
		{
			$path = ck_blog::get_instance()->config()->item('cookie_path');
		}
		
		if ($secure == false && ck_blog::get_instance()->config()->item('cookie_secure') != false)
		{
			$secure = ck_blog::get_instance()->config()->item('cookie_secure');
		}

		if (!is_numeric($expire))
		{
			$expire = time() - 86500;
		}
		else
		{
			$expire = ($expire > 0) ? time() + $expire : 0;
		}

		setcookie($prefix.$name, $value, $expire, $path, $domain, $secure);
	}

	/**
	 +----------------------------------------------------------
	 * Fetch an item from the SERVER array
	 +----------------------------------------------------------
	 * @param	string $index
	 * @param	boolean $xss_clean
	 +----------------------------------------------------------
	 * @return	mixed
	 +----------------------------------------------------------
	 */
	function server($index = '', $xss_clean = false)
	{
		return $this->_fetch_from_array($_SERVER, $index, $xss_clean);
	}

	/**
	 +----------------------------------------------------------
	 * Fetch the IP Address.<<获取客户端IP地址>>
	 +----------------------------------------------------------
	 * @param	boolean $is_long
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function ip_address($is_long = false)
	{
		if ($this->ip_address !== false)
		{
			// 已经获取，直接返回
			// ----------------------------------------------------------------
			return ($is_long ? ip2long($this->ip_address) : $this->ip_address);
		}

		if (ck_blog::get_instance()->config()->item('proxy_ips') != '' && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['REMOTE_ADDR']))
		{
			$proxies = preg_split('/[\s,]/', ck_blog::get_instance()->config()->item('proxy_ips'), -1, PREG_SPLIT_NO_EMPTY);
			$proxies = (is_array($proxies) ? $proxies : array($proxies));

			$this->ip_address = in_array($_SERVER['REMOTE_ADDR'], $proxies) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		}
		elseif (!empty($_SERVER['REMOTE_ADDR']) AND !empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$this->ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['REMOTE_ADDR']))
		{
			$this->ip_address = $_SERVER['REMOTE_ADDR'];
		}
		elseif (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$this->ip_address = $_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$this->ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		if ($this->ip_address === false)
		{
			$this->ip_address = '0.0.0.0';
			return ($is_long ? ip2long($this->ip_address) : $this->ip_address);
		}

		if (strpos($this->ip_address, ',') !== false)
		{
			$x = explode(',', $this->ip_address);
			$this->ip_address = trim(end($x));
		}

		if ( ! $this->valid_ip($this->ip_address))
		{
			$this->ip_address = '0.0.0.0';
		}

		return ($is_long ? ip2long($this->ip_address) : $this->ip_address);
	}

	/**
	 +----------------------------------------------------------
	 * Check if valid of ip.<<校验IP地址有效性>>
	 +----------------------------------------------------------
	 * @param	string $ip
	 +----------------------------------------------------------
	 * @return	boolean
	 +----------------------------------------------------------
	 */
	function valid_ip($ip)
	{
		$ip_segments = explode('.', $ip);

		// Always 4 segments needed
		if (count($ip_segments) != 4)
		{
			return false;
		}
		// IP can not start with 0
		if ($ip_segments[0][0] == '0')
		{
			return false;
		}
		// Check each segment
		foreach ($ip_segments as $segment)
		{
			// IP segments must be digits and can not be
			// longer than 3 digits or greater then 255
			if ($segment == '' OR preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3)
			{
				return false;
			}
		}

		return true;
	}

	/**
	 +----------------------------------------------------------
	 * Fetch the User Agent.<<获取客户端浏览器>>
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function user_agent()
	{
		if ($this->user_agent !== false)
		{
			return $this->user_agent;
		}

		$this->user_agent = (!isset($_SERVER['HTTP_USER_AGENT'])) ? false : $_SERVER['HTTP_USER_AGENT'];

		return $this->user_agent;
	}

	/**
	 +----------------------------------------------------------
	 * Sanitize Globals
	 +----------------------------------------------------------
	 * 1、Unsets $_GET data (if query strings are not enabled)
	 * 2、Unsets all globals if register_globals is enabled
	 * 3、Standardizes newline characters to \n
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function _sanitize_globals()
	{
		// It would be "wrong" to unset any of these GLOBALS.
		$protected = array('_SERVER', '_GET', '_POST', '_FILES', '_REQUEST','_SESSION', '_ENV', 'GLOBALS', 'HTTP_RAW_POST_DATA', 'system_folder', 'application_folder', 'BM', 'EXT', 'CFG', 'URI', 'RTR', 'OUT', 'IN');

		// Unset globals for securiy.
		// This is effectively the same as register_globals = off
		foreach (array($_GET, $_POST, $_COOKIE) as $global)
		{
			if ( ! is_array($global))
			{
				if ( ! in_array($global, $protected))
				{
					global ${$global};
					${$global} = null;
				}
			}
			else
			{
				foreach ($global as $key => $val)
				{
					if ( ! in_array($key, $protected))
					{
						global $$key;
						$$key = null;
					}
				}
			}
		}

		// Is $_GET data allowed? If not we'll set the $_GET to an empty array
		if ($this->_allow_get_array == false)
		{
			$_GET = array();
		}
		else
		{
			if (is_array($_GET) && count($_GET) > 0)
			{
				foreach ($_GET as $key => $val)
				{
					$_GET[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
				}
			}
		}

		// Clean $_POST Data
		if (is_array($_POST) && count($_POST) > 0)
		{
			foreach ($_POST as $key => $val)
			{
				$_POST[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
		}

		// Clean $_COOKIE Data
		if (is_array($_COOKIE) && count($_COOKIE) > 0)
		{
			// Also get rid of specially treated cookies that might be set by a server
			// or silly application, that are of no use to a CI application anyway
			// but that when present will trip our 'Disallowed Key Characters' alarm
			// http://www.ietf.org/rfc/rfc2109.txt
			// note that the key names below are single quoted strings, and are not PHP variables
			unset($_COOKIE['$Version']);
			unset($_COOKIE['$Path']);
			unset($_COOKIE['$Domain']);

			foreach ($_COOKIE as $key => $val)
			{
				$_COOKIE[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
		}

		// Sanitize PHP_SELF
		$_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);


		// CSRF Protection check
		if ($this->_enable_csrf == true)
		{
			ck_blog::get_instance()->security()->csrf_verify();
		}
	}

	/**
	 +----------------------------------------------------------
	 * Clean Input Data
	 +----------------------------------------------------------
	 * This is a helper function. It escapes data and
	 * standardizes newline characters to \n
	 +----------------------------------------------------------
	 * @param	string $str
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function _clean_input_data($str)
	{
		if (is_array($str))
		{
			$new_array = array();
			foreach ($str as $key => $val)
			{
				$new_array[$this->_clean_input_keys($key)] = $this->_clean_input_data($val);
			}
			
			return $new_array;
		}

		/* We strip slashes if magic quotes is on to keep things consistent

		   NOTE: In PHP 5.4 get_magic_quotes_gpc() will always return 0 and
			 it will probably not exist in future versions at all.
		*/
		if ( ! is_php('5.4') && get_magic_quotes_gpc())
		{
			$str = stripslashes($str);
		}

		// Remove control characters
		$str = remove_invisible_characters($str);

		// Should we filter the input data?
		if ($this->_enable_xss === true)
		{
			$str = ck_blog::get_instance()->security()->xss_clean($str);
		}

		// Standardize newlines if needed
		if ($this->_standardize_newlines == true)
		{
			if (strpos($str, "\r") !== false)
			{
				$str = str_replace(array("\r\n", "\r", "\r\n\n"), PHP_EOL, $str);
			}
		}

		return $str;
	}

	/**
	 +----------------------------------------------------------
	 * Clean Keys
	 +----------------------------------------------------------
	 * This is a helper function. To prevent malicious users
	 * from trying to exploit keys we make sure that keys are
	 * only named with alpha-numeric text and a few other items.
	 +----------------------------------------------------------
	 * @param	string $str
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function _clean_input_keys($str)
	{
		// modify by fugen.xie@qq.com at 2012-01-06
		/*if ( ! preg_match("/^[a-z0-9:_\/-]+$/i", $str))
		{
			exit('Disallowed Key Characters.');
		}*/
		if ( ! preg_match("/^[a-z 0-9~%.:_\-]+$/i", rawurlencode($str)))
		{
			log_message('error', 'disallowed key: ' . $str);
			exit('Access Deined.');
		}
		// end modify

		return $str;
	}

	/**
	 +----------------------------------------------------------
	 * Request Headers
	 +----------------------------------------------------------
	 * In Apache, you can simply call apache_request_headers(), however for
	 * people running other webservers the function is undefined.
	 +----------------------------------------------------------
	 * @param	boolean $xss_clean
	 +----------------------------------------------------------
	 * @return	return_type
	 +----------------------------------------------------------
	 */
	public function request_headers($xss_clean = false)
	{
		// Look at Apache go!
		if (function_exists('apache_request_headers'))
		{
			$headers = apache_request_headers();
		}
		else
		{
			$headers['Content-Type'] = (isset($_SERVER['CONTENT_TYPE'])) ? $_SERVER['CONTENT_TYPE'] : @getenv('CONTENT_TYPE');

			foreach ($_SERVER as $key => $val)
			{
				if (strncmp($key, 'HTTP_', 5) === 0)
				{
					$headers[substr($key, 5)] = $this->_fetch_from_array($_SERVER, $key, $xss_clean);
				}
			}
		}

		// take SOME_HEADER and turn it into Some-Header
		foreach ($headers as $key => $val)
		{
			$key = str_replace('_', ' ', strtolower($key));
			$key = str_replace(' ', '-', ucwords($key));

			$this->headers[$key] = $val;
		}

		return $this->headers;
	}

	/**
	 * Get Request Header
	 *
	 * Returns the value of a single member of the headers class member
	 *
	 * @param 	string		array key for $this->headers
	 * @param	boolean		XSS Clean or not
	 * @return 	mixed		false on failure, string on success
	 */
	/**
	 +----------------------------------------------------------
	 * Get Request Header
	 +----------------------------------------------------------
	 * Returns the value of a single member of the headers class member
	 +----------------------------------------------------------
	 * @param	string $index
	 * @param	boolean $xss_clean
	 +----------------------------------------------------------
	 * @return	mixed false on failure, string on success
	 +----------------------------------------------------------
	 */
	public function get_request_header($index, $xss_clean = false)
	{
		if (empty($this->headers))
		{
			$this->request_headers();
		}

		if ( ! isset($this->headers[$index]))
		{
			return false;
		}

		if ($xss_clean === true)
		{
			return ck_blog::get_instance()->security()->xss_clean($this->headers[$index]);
		}

		return $this->headers[$index];
	}

	/**
	 +----------------------------------------------------------
	 * Test to see if a request contains the HTTP_X_REQUESTED_WITH header
	 +----------------------------------------------------------
	 * @return	boolean
	 +----------------------------------------------------------
	 */
	public function is_ajax_request()
	{
		return ($this->server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest');
	}

	/**
	 +----------------------------------------------------------
	 * Test to see if a request was made from the command line
	 +----------------------------------------------------------
	 * @return	boolean
	 +----------------------------------------------------------
	 */
	public function is_cli_request()
	{
		return (php_sapi_name() == 'cli') or defined('STDIN');
	}
}

/* End of file ck_input.php */
/* Location: ./core/ck_input.php */