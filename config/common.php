<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 

$config['charset'] = 'UTF-8';

$config['allow_get_array'] = true;
$config['global_xss_filtering'] = false;

$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= false;

$config['csrf_protection'] = false;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;

$config['mobile'] = array(
	'iphone4' 	=> array('width' => 460, 'height' => 257),
	'ipad'		=> array('width' => 981, 'height' => 547)
);

$config['modules'] = array('blog');
$config['targets'] = array('i' => 'ios', 'a' => 'android'); // i:ios,a:android

/* End of file common.php */
/* Location: ./config/common.php */