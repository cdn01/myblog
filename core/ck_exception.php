<?php if ( ! defined('ck_VERSION')) exit('No direct script access allowed');

class ck_exception
{
	/**
	 +----------------------------------------------------------
	 * 异常列表
	 +----------------------------------------------------------
	 * @var array
	 +----------------------------------------------------------
	 */
	private $_exceptions = array();

	/**
	 +----------------------------------------------------------
	 * 收集异常
	 +----------------------------------------------------------
	 * @param	Exception $e
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function collect(Exception $e)
	{
		$this->_exceptions[] = $e;
	}

	/**
	 +----------------------------------------------------------
	 * 记录异常
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function log()
	{
		if ($this->_exceptions)
		{
			foreach ($this->_exceptions as $e)
			{
				$message = '错误发生于文件[' . $e->getFile() . ']第' . $e->getLine() . '行：' . $e->getMessage();
				ck_blog::get_instance()->log($message, CK_LOGPATH . 'exception/');
			}
		}
	}

	/**
	 +----------------------------------------------------------
	 * 输出异常
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function render()
	{
		if ($this->_exceptions)
		{
			foreach ($this->_exceptions as $e)
			{
				echo $e->getMessage() . '<br />';
			}
		}
	}
}

/* End of file ck_exception.php */
/* Location: ./core/ck_exception.php */