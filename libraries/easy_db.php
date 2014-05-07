<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 
/**
 +----------------------------------------------------------------------+
 * 简要数据库处理类，TODO，待完善
 +----------------------------------------------------------------------+
 * Copyright (c) 2006-2013 http://www.737.com/ All rights reserved.
 +----------------------------------------------------------------------+
 * Author: xiefg <fugen.xie@qq.com>
 +----------------------------------------------------------------------+
 * $Id: easy_db.php 2013-8-3 737 $
 +----------------------------------------------------------------------+
 */

define('OBJECT',	'OBJECT',	true);
define('ARRAY_A',	'ARRAY_A',	true);
define('ARRAY_N',	'ARRAY_N',	true);

/**
 +------------------------------------------------------------------------------
 * Easy DB class
 +------------------------------------------------------------------------------
 * @author    fugen.xie@737.com
 * @version   v1.0
 +------------------------------------------------------------------------------
 */
class easy_db 
{
	/**
	 +----------------------------------------------------------
	 * 测试模式
	 +----------------------------------------------------------
	 * @var boolean
	 +----------------------------------------------------------
	 */
	private $debug = false;
	
	/**
	 +----------------------------------------------------------
	 * connect resource for mysql
	 +----------------------------------------------------------
	 * @var	resource
	 +----------------------------------------------------------
	 */
	private $db;
	
	/**
	 +----------------------------------------------------------
	 * 数据库连接配置
	 +----------------------------------------------------------
	 */
	private $dbuser = false;
	private $dbpassword = false;
	private $dbname = false;
	private $dbhost = 'localhost';
	private $encoding = 'UTF-8';
	private $pconnect = false;
	
	/**
	 +----------------------------------------------------------
	 * 影响行数
	 +----------------------------------------------------------
	 * @var int
	 +----------------------------------------------------------
	 */
	private $rows_affected = 0;
	
	/**
	 +----------------------------------------------------------
	 * 语句执行次数
	 +----------------------------------------------------------
	 * @var int
	 +----------------------------------------------------------
	 */
	private $num_queries = 0;
	
	/**
	 +----------------------------------------------------------
	 * 语句执行结果
	 +----------------------------------------------------------
	 * @var	mixed
	 +----------------------------------------------------------
	 */
	private $result = false;
	
	/**
	 +----------------------------------------------------------
	 * 最后插入自增长ID
	 +----------------------------------------------------------
	 * @var	int
	 +----------------------------------------------------------
	 */
	private $insert_id = 0;
	
	/**
	 +----------------------------------------------------------
	 * 结果数
	 +----------------------------------------------------------
	 * @var	int 
	 +----------------------------------------------------------
	 */
	private $num_rows = 0;
	
	/**
	 +----------------------------------------------------------
	 * 字段列表
	 +----------------------------------------------------------
	 * @var array
	 +----------------------------------------------------------
	 */
	private $col_info = array();
	
	/**
	 +----------------------------------------------------------
	 * 最后查询结果
	 +----------------------------------------------------------
	 * @var array
	 +----------------------------------------------------------
	 */
	private $last_result = array();

	/**
	 +----------------------------------------------------------
	 * 构造函数
	 +----------------------------------------------------------
	 * @param	array $params
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function __construct($params)
	{
		// 配置连接参数
		// --------------------------------------------------------------------------------------
		foreach (array('dbuser', 'dbpassword', 'dbname','dbhost', 'encoding', 'pconnect') as $key)
		{
			if (isset($params[$key])) {
				$this->{$key} = $params[$key];
			}
		}
	}

	/**
	 +----------------------------------------------------------
	 * 连接数据库操作
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function connect() 
	{
		if ($this->pconnect) {
			$this->db = mysql_pconnect($this->dbhost, $this->dbuser, $this->dbpassword);
		} else {
			$this->db = mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
		}

		if (!$this->db) 
		{
			$message = 'Database upgrade, understanding!';
			$this->log_error($message, true);
			return false;
		}
		
		return true;
	}
	
	/**
	 +----------------------------------------------------------
	 * 选择数据库
	 +----------------------------------------------------------
	 * @return	boolean
	 +----------------------------------------------------------
	 */
	function select()
	{
		$return_val = false;
		
		if (!$this->db)
		{
			$this->log_error('数据库未连接，无法选择！');
		}
		elseif (!@mysql_select_db($this->dbname, $this->db))
		{
			if (!$str = @mysql_error($this->db)) {
				$str = 'Unexpected error while trying to select database';
			}
			
			$this->log_error($str);
		}
		else
		{
			if ($this->encoding != '')
			{
				$this->encoding = strtolower(str_replace('-', '', $this->encoding));
				
				$charsets = array();
				$result = mysql_query('SHOW CHARACTER SET');
				while ($row = mysql_fetch_array($result, MYSQL_ASSOC))
				{
					$charsets[] = $row['Charset'];
				}
				
				if (in_array($this->encoding, $charsets)) {
					mysql_query("SET NAMES '" . $this->encoding . "'");						
				}
			}
			
			$return_val = true;
		}

		return $return_val;
	}
	
	/**
	 +----------------------------------------------------------
	 * 记录错误日志
	 +----------------------------------------------------------
	 * @param	string $message
	 * @param	boolean $important
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function log_error($message, $important = false)
	{
		if ($important) 
		{
			// 发送短消息
			// ---------------------------
			send_sms($telphone, $message);
		}
		
		ck_blog::get_instance()->log($message);
	}
	
	/**
	 +----------------------------------------------------------
	 * 记录执行语句
	 +----------------------------------------------------------
	 * @param	string $query
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function log_query($query)
	{
		if ($this->debug) 
		{
			// 开启测试模式，记录执行的语句
			// -----------------------------------------------------------
			ck_blog::get_instance()->log($query, XT_LOGPATH . 'query/', 1);
		}
	}

	/**
	 +----------------------------------------------------------
	 * 快速连接到数据库，同时选择指定库
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function quick_connect()
	{
		$return_val = false;
		if (!$this->connect()) ;
		else if (!$this->select()) ;
		else $return_val = true;
		return $return_val;
	}
	
	/**
	 +----------------------------------------------------------
	 * 断开连接
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function disconnect()
	{
		$this->log_query('disconnect');
		@mysql_close($this->db);
		unset($this->db);
	}

	/**
	 +----------------------------------------------------------
	 * Format a mySQL string correctly for safe mySQL insert
	 +----------------------------------------------------------
	 * @param	string $str
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	function escape($str)
	{
		// If there is no existing database connection then try to connect
		if (!isset($this->db) || !$this->db) 
		{
			$this->connect();
			$this->select();
		}

		return mysql_real_escape_string(stripslashes($str));
	}
	
	function flush()
	{
		$this->last_result = array();
		$this->col_info = array();
	}
	
	function get_var($query = null, $x = 0, $y = 0)
	{
		if ($query)
		{
			$this->query($query);
		}

		if ($this->last_result[$y])
		{
			$values = array_values(get_object_vars($this->last_result[$y]));
		}
	
		return (isset($values[$x]) && $values[$x] !=='' ? $values[$x] : null);
	}
	
	function get_row($query = null, $output = OBJECT, $y = 0)
	{
		if ($query)
		{
			$this->query($query);
		}
		
		if (empty($this->last_result)) return null;
	
		if ($output == OBJECT)
		{
			return ($this->last_result[$y] ? $this->last_result[$y] : null);
		}
		elseif ($output == ARRAY_A)
		{
			return ($this->last_result[$y] ? get_object_vars($this->last_result[$y]) : null);
		}
		elseif ($output == ARRAY_N)
		{
			return ($this->last_result[$y] ? array_values(get_object_vars($this->last_result[$y])) : null);
		}
		else
		{
			return ($this->last_result[$y] ? $this->last_result[$y] : null);
		}
	}
	
	function get_col($query = null, $x = 0)
	{
		$new_array = array();

		if ($query)
		{
			$this->query($query);
		}
		
		if (empty($this->last_result)) return $new_array;
	
		for ($i = 0; $i < count($this->last_result); $i++)
		{
			$new_array[$i] = $this->get_var(null, $x, $i);
		}
	
		return $new_array;
	}

	function get_results($query = null, $output = OBJECT)
	{
		if ($query) 
		{
			$this->query($query);
		}
		
		if (empty($this->last_result)) return null;
	
		if ($output == OBJECT) 
		{
			return $this->last_result;
		} 
		elseif ($output == ARRAY_A || $output == ARRAY_N) 
		{
			if ($this->last_result)
			{
				$i = 0;
				foreach($this->last_result as $row)
				{
					$new_array[$i] = get_object_vars($row);
					if ($output == ARRAY_N)
					{
						$new_array[$i] = array_values($new_array[$i]);
					}
	
					$i++;
				}
	
				return $new_array;
			}
			else
			{
				return array();
			}
		}
	}

	function get_col_info($info_type = 'name', $col_offset = -1)
	{
		if ($this->col_info)
		{
			if ($col_offset == -1)
			{
				$i = 0;
				foreach ($this->col_info as $col)
				{
					$new_array[$i] = $col->{$info_type};
					$i++;
				}
				return $new_array;
			}
			else
			{
				return $this->col_info[$col_offset]->{$info_type};
			}
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取结果行数
	 +----------------------------------------------------------
	 * @return	int
	 +----------------------------------------------------------
	 */
	function num_rows()
	{
		return $this->num_rows;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取影响行数
	 +----------------------------------------------------------
	 * @return	int
	 +----------------------------------------------------------
	 */
	function rows_affected()
	{
		return $this->rows_affected;
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取最后插入自增长id
	 +----------------------------------------------------------
	 * @return	int
	 +----------------------------------------------------------
	 */
	function insert_id()
	{
		return $this->insert_id;
	}

	/**
	 +----------------------------------------------------------
	 * 多记录插入
	 +----------------------------------------------------------
	 * @param	string $table
	 * @param	array $data
	 +----------------------------------------------------------
	 * @return	boolean
	 +----------------------------------------------------------
	 */
	function multi_insert($table, $data)
	{
		if (empty($data))
		{
			// 没有数据
			// ----------
			return false;
		}
	
		// 取得字段列表
		// ------------------------
		$ks = array_keys($data[0]);
	
		// 值列表
		// -----------
		$vs = array();
	
		foreach ($data as $item)
		{
			$values = array();
			foreach ($ks as $k) { $values[] = $this->escape($item[$k]); }
			$vs[] = implode('", "', $values);
		}
	
		$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $ks) . ') VALUES ("' . implode('"), ("', $vs) . '");';
		return $this->query($sql);
	}
	
	/**
	 +----------------------------------------------------------
	 * insert data into table
	 +----------------------------------------------------------
	 * @param	string $table
	 * @param	array $data
	 +----------------------------------------------------------
	 * @return	int
	 +----------------------------------------------------------
	 */
	function insert($table, $data)
	{
		$key = $value = array();
		foreach ($data as $k => $v) { $key[] = "`{$k}`"; $value[] = $this->escape($v); }
		$this->query('INSERT INTO ' . $table . ' (' . implode(', ', $key) . ') VALUES ("' . implode('", "', $value) . '");');
		return $this->insert_id();
	}
	
	/**
	 +----------------------------------------------------------
	 * update data to table
	 +----------------------------------------------------------
	 * @param	string $table
	 * @param	array $data
	 * @param	array $where
	 +----------------------------------------------------------
	 * @return	boolean
	 +----------------------------------------------------------
	 */
	function update($table, $data, $options = array(1 => 1))
	{
		$set = $where = array();
		foreach ($data as $key => $value) { $set[] = "`{$key}`='" . $this->escape($value) . "'"; }
		foreach ($options as $key => $value) { $where[] = "`{$key}`='" . $this->escape($value) . "'";}
		$this->query('UPDATE ' . $table . ' SET ' . implode(', ', $set) . ' WHERE ' . implode(' AND ', $where) . ';');
		return ($this->rows_affected() >= 0);
	}

	/**
	 +----------------------------------------------------------
	 * 执行sql语句，并赋值给相关类变量
	 +----------------------------------------------------------
	 * @param	string $query
	 +----------------------------------------------------------
	 * @return	boolean|number
	 +----------------------------------------------------------
	 */
	function query($query)
	{
		if ($this->num_queries >= 500)
		{
			// 长时间连接数据库，重新连接
			// --------------------
			$this->num_queries = 0;
			$this->disconnect();
			$this->quick_connect();
		}

		// 影响行数初始化
		// -------------
		$return_val = 0;

		// Flush cached values..
		$this->flush();

		// 预处理语句
		// -------------------
		$query = trim($query);

		// 记录执行语句数
		// ------------------
		$this->num_queries++;

		// 如果未连接，重新连接
		// ---------------------------------
		if (!isset($this->db) || !$this->db) 
		{
			$this->connect();
			$this->select();
		}
		
		// 记录执行的语句
		// ----------------------
		$this->log_query($query);

		// 执行语句
		// ---------------------------------------------
		$this->result = @mysql_query($query, $this->db);
		if ($str = @mysql_error($this->db))
		{
			$this->log_error($query.$str);
			return false;
		}

		$is_insert = false;
		
		// Query was an insert, delete, update, replace
		if (preg_match("/^(insert|delete|update|replace|truncate|drop|create|alter|set)\s+/i", $query))
		{
			$this->rows_affected = @mysql_affected_rows($this->db);

			// Take note of the insert_id
			if (preg_match("/^(insert|replace)\s+/i",$query))
			{
				$this->insert_id = @mysql_insert_id($this->db);
			}

			// Return number fo rows affected
			$return_val = $this->rows_affected;
		}
		// Query was a select
		else
		{
			// Take note of column info
			$i = 0;
			while ($i < @mysql_num_fields($this->result))
			{
				$this->col_info[$i] = @mysql_fetch_field($this->result);
				$i++;
			}

			// Store Query Results
			$num_rows = 0;
			while ($row = @mysql_fetch_object($this->result))
			{
				// Store relults as an objects within main array
				$this->last_result[$num_rows] = $row;
				$num_rows++;
			}

			@mysql_free_result($this->result);

			// Log number of rows the query returned
			$this->num_rows = $num_rows;

			// Return number of rows selected
			$return_val = $this->num_rows;
		}

		return $return_val;
	}
}

/* End of file easy_db.php */
/* Location: ./easy_db.php */