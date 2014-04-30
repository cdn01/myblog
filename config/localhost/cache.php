<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 

$config['redis_server']['hostname'] = '127.0.0.1';
$config['redis_server']['port'] = 6379;
$config['redis_server']['pconnect'] = false;

$config['memcached_servers'] = array(
	array('192.168.1.182', 12000, 1),
	array('192.168.1.183', 12000, 1),
	array('192.168.1.184', 12000, 1)
);

$config['cache_way'] = 'redis';

/* End of file cache.php */
/* Location: ./development/cache.php */