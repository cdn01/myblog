<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed');


if ( ! function_exists('is_php'))
{
	function is_php($version = '5.0.0')
	{
		static $_is_php;
		$version = (string)$version;

		if ( ! isset($_is_php[$version]))
		{
			$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? false : true;
		}

		return $_is_php[$version];
	}
}

/**
 +----------------------------------------------------------
 * Strip Slashes
 +----------------------------------------------------------
 * Removes slashes contained in a string or in an array
 +----------------------------------------------------------
 * @param	mixed	string or array
 +----------------------------------------------------------
 * @return	mixed	string or array
 +----------------------------------------------------------
 */
if ( ! function_exists('strip_slashes'))
{
	function strip_slashes($str)
	{
		if (is_array($str))
		{
			foreach ($str as $key => $val)
			{
				$str[$key] = strip_slashes($val);
			}
		}
		else
		{
			$str = stripslashes($str);
		}

		return $str;
	}
}

/**
 +----------------------------------------------------------
 * Tests for file writability
 +----------------------------------------------------------
 * is_writable() returns true on Windows servers when you really can't write to
 * the file, based on the read-only attribute.  is_writable() is also unreliable
 * on Unix servers if safe_mode is on.
 +----------------------------------------------------------
 * @return void
 +----------------------------------------------------------
 */
if ( ! function_exists('is_really_writable'))
{
	function is_really_writable($file)
	{
		// If we're on a Unix server with safe_mode off we call is_writable
		if (DIRECTORY_SEPARATOR == '/' AND @ini_get("safe_mode") == false)
		{
			return is_writable($file);
		}

		// For windows servers and safe_mode "on" installations we'll actually
		// write a file then read it.  Bah...
		if (is_dir($file))
		{
			$file = rtrim($file, '/').'/'.md5(mt_rand(1,100).mt_rand(1,100));

			if (($fp = @fopen($file, FOPEN_WRITE_CREATE)) === false)
			{
				return false;
			}

			fclose($fp);
			@chmod($file, DIR_WRITE_MODE);
			@unlink($file);
			return true;
		}
		elseif ( ! is_file($file) OR ($fp = @fopen($file, FOPEN_WRITE_CREATE)) === false)
		{
			return false;
		}

		fclose($fp);
		return true;
	}
}

/**
 * 
 +----------------------------------------------------------
 * Remove Invisible Characters
 +----------------------------------------------------------
 * This prevents sandwiching null characters
 * between ascii characters, like Java\0script.
 +----------------------------------------------------------
 * @param	string $str
 * @param	boolean $url_encoded
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
if ( ! function_exists('remove_invisible_characters'))
{
	function remove_invisible_characters($str, $url_encoded = true)
	{
		$non_displayables = array();

		// every control character except newline (dec 10)
		// carriage return (dec 13), and horizontal tab (dec 09)

		if ($url_encoded)
		{
			$non_displayables[] = '/%0[0-8bcef]/';	// url encoded 00-08, 11, 12, 14, 15
			$non_displayables[] = '/%1[0-9a-f]/';	// url encoded 16-31
		}

		$non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';	// 00-08, 11, 12, 14-31, 127
		
		do
		{
			// 过滤不允许显示字符串
			// ----------------------------------------------------------
			$str = preg_replace($non_displayables, '', $str, -1, $count);
		}
		while ($count); 
		
		return $str;
	}
}

/**
 * 
 +----------------------------------------------------------
 * Exception Handler
 +----------------------------------------------------------
 * @param	Exception $exception
 +----------------------------------------------------------
 * @return	void
 +----------------------------------------------------------
 */
function ck_exception_handler($exception)
{
    $message = '错误发生于文件[' . $exception->getFile() . ']第' . $exception->getLine() . '行：' . $exception->getMessage();
	ck_blog::get_instance()->log($message, CK_LOGPATH . 'exception/');
}

/**
 +----------------------------------------------------------
 * Error Handler
 +----------------------------------------------------------
 * Register the PHP error handler. All PHP errors will fall into this
 * handler, which will convert the error into an ErrorException object
 * and pass the exception into the common exception handler.
 *
 * After all, there should never be any errors in our application. If
 * there are then we need to know about them and fix them - not ignore
 * them.
 *
 * Notice, this function will ignore the error if it is less than the
 * current error reporting level.
 +----------------------------------------------------------
 * @param	int $severity
 * @param	string $message
 * @param	string $file
 * @param	int $line
 +----------------------------------------------------------
 * @return	void
 +----------------------------------------------------------
 */
function ck_error_handler($severity, $message, $file, $line)
{
	if ($severity == E_STRICT) {
		return ;
	}

	if (($severity & error_reporting()) === 0) {
		return ;
	}

	ck_exception_handler(new ErrorException($message, $severity, 0, $file, $line));

	return ;
}

/**
 +----------------------------------------------------------
 * Exception Handler
 +----------------------------------------------------------
 * This function will be called
 * at the end of the PHP script or on a fatal PHP error. If an error
 * has occured, we will convert it to an ErrorException and pass it
 * to the common exception handler for the framework.
 +----------------------------------------------------------
 * @return void
 +----------------------------------------------------------
 */
function ck_shutdown_handler()
{
	if ($error = error_get_last())
	{
		extract($error, EXTR_SKIP);
		ck_exception_handler(new ErrorException($message, $type, 0, $file, $line));
	}
}

/* End of file ck_common.php */
/* Location: ./core/ck_common.php */