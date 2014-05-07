<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed');

class ck_blog{

	protected static $_instance = null;
	protected $_config = null;
	protected $_file = null;
	protected $_exception = null;
	protected $_security = null;
	protected $_input = null;
	protected $_module = null;
	protected $_db = array();
	private $_copen = false;
	protected $_cache = null;
	protected $_redis = null;
	protected $_menu = array();
	
	protected function __construct(){
	}

	protected function __clone()
	{
	}

	public static function get_instance(){
		if(self::$_instance === null){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function register($module){
		if(!$module){
			return $this->_module = "blog";
		}
		$this->_module = $module;
	}
	
	public function config(){
		if(!is_object($this->_config)){
			ck_blog::get_instance()->load("core/ck_config");
			$this->_config = new ck_config();
		}
		return $this->_config;
	}

	public function load($file){
		if(is_array($file)){
			foreach ($file as $t_file){
				self::get_instance()->load($file);
			}
		}
		elseif(!in_array($file, $haystack)){
			require (CK_ABSPATH .$file.EXT);
			array_push($this->_file, $file);
		}
	}

	public function exception()
	{
		if (!is_object($this->_exception))
		{
			ck_blog::get_instance()->load('core/ck_exception');

			// construct a exception instance
			// ------------------------------------
			$this->_exception = new ck_exception();
		}

		return $this->_exception;
	}
	
	public function security()
	{
		if (!is_object($this->_security))
		{
			ck_blog::get_instance()->load('core/ck_security');

			// construct a security instance
			// ----------------------------------
			$this->_security = new ck_security();
		}

		return $this->_security;
	}
	
	public function input()
	{ 
		if (!is_object($this->_input))
		{
			ck_blog::get_instance()->load('core/ck_input');

			// construct a security instance
			// ----------------------------------
			$this->_input = new ck_input();
		}

		return $this->_input;
	}
	
	public function log($msg, $log_path = '', $rv = 100)
	{
		if (ENVIRONMENT === 'development' || ENVIRONMENT === 'localhost')
		{
			// 本地
			// -----
			$rv = 1;
		}
		
		if (!$log_path)
		{
			// 日志目录
			// --------------------
			$log_path = CK_LOGPATH;
		}
		
		// 概率记录
		// ----------------------------------
		if (mt_rand(1, $rv) > 1) return true;
		
		if ( ! is_dir($log_path) OR ! is_really_writable($log_path))
		{
			return false;
		}
	
		$file_path = $log_path . 'log-' . date('Y-m-d') . '.php';
		$message  = '';
	
		if ( ! $fp = @fopen($file_path, FOPEN_WRITE_CREATE))
		{
			return false;
		}
	
		if (is_array($msg)) 
		{
			$msg_arr = array();
			
			foreach ($msg as $k => $v) 
			{
				array_push($msg_arr, "{$k}:{$v}");
			}
			
			$msg = implode(',', $msg_arr);
		}
	
		$message .= date('Y-m-d H:i:s') . ' - ' . $msg . "\n";
	
		$start_time = microtime();
		
		do 
		{
			// 是否文件可读
			// --------------------------------------
			$can_write = flock($fp, LOCK_EX|LOCK_NB);
			
			if (!$can_write) 
			{
				// 等待
				// ------------------------------
				usleep(round(rand(0, 100)*1000));
			}
		} 
		// 获得文件锁超时
		// -----------------------------------------------------------
		while ((!$can_write) && ((microtime() - $start_time) > 1000));
		
		if ($can_write) 
		{
			// 写入文件
			// ------------------
			fwrite($fp, $message);
		}
		
		fclose($fp);
	
		@chmod($file_path, FILE_WRITE_MODE);
		return true;
	}
	
	public function module()
	{
		return $this->_module;
	}
	
	public function run($name)
	{ 
		// construct a doing instance
		 $cls_name = empty($name)?"index":$name;
		ck_blog::get_instance()->load('libraries/ck_controller');
		ck_blog::get_instance()->load("{$this->module()}/{$cls_name}"); 
		$instance = new $cls_name();
		$instance->run();
	}
	
	public function close()
	{
		// 数据库实例列表
		// ---------------------------------
		$instances = array_keys($this->_db);
		
		foreach ($instances as $instance)
		{
			if (!empty($this->_db[$instance]) && is_object($this->_db[$instance]))
			{
				// 关闭数据库链接
				// ---------------------------------
				$this->_db[$instance]->disconnect();
				unset($this->_db[$instance]);
			}
		}
		
		if ($this->_copen)
		{
			// 关闭缓存对象链接
			// ---------------------
			$this->cache()->close();
			$this->_cache = null;
		}
	}
	
	public function cache()
    {
    	if (!is_object($this->_cache)) 
        {
            // Construct a icache_saver Instance
			ck_blog::get_instance()->load('libraries/ck_cache');
			ck_blog::get_instance()->config()->load('cache');
			
			$name = ck_blog::get_instance()->config()->item('cache_way');
			if (!$name) {
				$name = 'redis';
			}
			
			$cls_name = 'ck_' . $name;
			$this->_cache = new $cls_name();
			$this->_copen = true;
        }
        
        return $this->_cache;
    }
    
    public function db($instance = 'default_db')
    {
    	if (empty($this->_db) || empty($this->_db[$instance]))
    	{
    		ck_blog::get_instance()->load('libraries/easy_db');
    			
    		// 实例化数据库操作对象
    		// ------------------------------------------------------------------------------------
    		$this->_db[$instance] = new easy_db(ck_blog::get_instance()->config()->item($instance));
    		$this->_db[$instance]->connect();
    		$this->_db[$instance]->select();
    	}
    	return $this->_db[$instance];
    }
    
    public function menu(){
    	if(empty($this->_menu)){
    		ck_blog::get_instance()->load("libraries/ck_menu");
    		$this->_menu = new ck_menu(); 
    	}
    	return $this->_menu;
    }
    
    public function redis()
    {
    	if (!is_object($this->_redis))
    	{
    		ck_blog::get_instance()->config()->load('cache');
    		 
    		// Construct a Redis Instance
    		// --------------------------
    		$this->_redis = new Redis();
    		 
    		// 加载配置
    		// --------------------------------------------------------------
    		$server = ck_blog::get_instance()->config()->item('redis_server');
    		if (empty($server))
    		{
    			// default to localhost
    			$servers = array('hostname' => '127.0.0.1', 'port' => 80, 'pconnect' => false);
    		}
    		 
    		if ($server['pconnect']) {
    			$this->_redis->pconnect($server['hostname'], $server['port']);
    		} else {
    			$this->_redis->connect($server['hostname'], $server['port']);
    		}
    		 
    		$this->_redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);
    		$this->_redis->setOption(Redis::OPT_PREFIX, 'myblog:');
    	}
    
    	return $this->_redis;
    }
     
}