<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 
 
class ck_config 
{
	/**
	 +----------------------------------------------------------
	 * List of all loaded config values
	 +----------------------------------------------------------
	 * @var array
	 +----------------------------------------------------------
	 */
	var $config = array();
	
	/**
	 +----------------------------------------------------------
	 * List of all loaded config files
	 +----------------------------------------------------------
	 * @var array
	 +----------------------------------------------------------
	 */
	var $is_loaded = array();
	
	/**
	 +----------------------------------------------------------
	 * List of paths to search when trying to load a config file
	 +----------------------------------------------------------
	 * @var array
	 +----------------------------------------------------------
	 */
	var $_config_paths = array(CK_ABSPATH);

	/**
	 +----------------------------------------------------------
	 * Constructor
	 +----------------------------------------------------------
	 * @return  void
	 +----------------------------------------------------------
	 */
	function __construct()
	{
		
	}

	// --------------------------------------------------------------------
	
	/**
	 +----------------------------------------------------------
	 * Load Config File
	 +----------------------------------------------------------
	 * @param	string	the config file name
	 * @param   boolean	if configuration values should be loaded into their own section
	 * @param   boolean	true if errors should just return false, false if an error message should be displayed
	 +----------------------------------------------------------
	 * @return	boolean
	 +----------------------------------------------------------
	 */
	function load($file = '', $use_sections = false, $fail_gracefully = false)
	{
		$file = ($file == '') ? 'config' : str_replace('.php', '', $file);
		$found = false;
		$loaded = false;

		foreach ($this->_config_paths as $path)
		{
			$check_locations = defined('ENVIRONMENT') ? array(ENVIRONMENT . '/' . $file, $file) : array($file);

			foreach ($check_locations as $location)
			{
				$file_path = $path . 'config/' . $location . '.php';

				if (in_array($file_path, $this->is_loaded, true))
				{
					$loaded = true; 
					continue 2;
				}

				if (file_exists($file_path))
				{ 
					$found = true; 
					break;
				}
			}

			if ($found === false) {
				
				continue;
			}

			include($file_path);

			if (!isset($config) OR ! is_array($config))
			{
				if ($fail_gracefully === true) {
					return false;
				}
				
				die('Your ' . $file_path . ' file does not appear to contain a valid configuration array.');
			}

			if ($use_sections === true)
			{
				if (isset($this->config[$file])) {
					$this->config[$file] = array_merge($this->config[$file], $config);
				} else {
					$this->config[$file] = $config;
				}
			}
			else {
				$this->config = array_merge($this->config, $config);
			}

			$this->is_loaded[] = $file_path; 
			unset($config);  
			$loaded = true;
			break;
		}

		if ($loaded === false)
		{
			if ($fail_gracefully === true) {
				return false;
			}
			
			die('The configuration file ' . $file . '.php does not exist.');
		}

		return true;
	}

	// --------------------------------------------------------------------

	/**
	 +----------------------------------------------------------
	 * Fetch a config file item
	 +----------------------------------------------------------
	 * @param	string	the config item name
	 * @param	string	the index name
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function item($item, $index = '')
	{
		if ($index == '')
		{
			if (!isset($this->config[$item])) {
				return false;
			}

			$pref = $this->config[$item];
		}
		else
		{
			if (!isset($this->config[$index])) {
				return false;
			}

			if (!isset($this->config[$index][$item])) {
				return false;
			}

			$pref = $this->config[$index][$item];
		}

		return $pref;
	}

	// --------------------------------------------------------------------

	/**
	 * 
	 +----------------------------------------------------------
	 * Fetch a config file item - adds slash after item (if item is not empty)
	 +----------------------------------------------------------
	 * @param	string	the config item name
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function slash_item($item)
	{
		if (!isset($this->config[$item])) {
			return false;
		}
		
		if( trim($this->config[$item]) == '') {
			return '';
		}

		return rtrim($this->config[$item], '/') . '/';
	}

	// --------------------------------------------------------------------

	/**
	 +----------------------------------------------------------
	 * Set a config file item
	 +----------------------------------------------------------
	 * @param	string	the config item key
	 * @param	string	the config item value
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function set_item($item, $value)
	{
		$this->config[$item] = $value;
	}
}
// END ck_cofig class

/* End of file ck_config.php */
/* Location: ./core/ck_config.php */