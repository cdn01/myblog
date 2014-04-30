<?php

header("Content-type:text/html;charset=utf-8");

define('CK_VERSION', '1.1.0');

define('CK_ABSPATH', dirname(__FILE__) . '/' );

define('EXT', '.php');

$env = 'localhost';

define('ENVIRONMENT', $env);

error_reporting(E_ALL);

define('CK_LOGPATH', CK_ABSPATH . 'logs/');

include CK_ABSPATH . 'core/ck_blog' . EXT;

require(CK_ABSPATH . 'core/ck_constants.php');

require(CK_ABSPATH . 'core/ck_common.php');

set_exception_handler('ck_exception_handler');

set_error_handler('ck_error_handler');

register_shutdown_function('ck_shutdown_handler');

if ( ! is_php('5.3'))
{
	@set_magic_quotes_runtime(0);
}
 
	
ck_blog::get_instance()->load('helpers/common_helper');
ck_blog::get_instance()->load('helpers/resp_helper');
ck_blog::get_instance()->load('helpers/sms_helper');
ck_blog::get_instance()->load('helpers/meta_helper');
ck_blog::get_instance()->load('helpers/bis_helper');

ck_blog::get_instance()->config()->load('cache');
ck_blog::get_instance()->config()->load('common');
ck_blog::get_instance()->config()->load('config');
 
ck_blog::get_instance()->config()->load('database');