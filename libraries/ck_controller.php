<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed');

class ck_controller
{   
	/**
	 +----------------------------------------------------------
	 * 视图文件
	 +----------------------------------------------------------
	 * @var string
	 +----------------------------------------------------------
	 */
	private $_tpl = '';
	
	/**
	 +----------------------------------------------------------
	 * 将玩家标识唯一到控制器层
	 +----------------------------------------------------------
	 * @var int
	 +----------------------------------------------------------
	 */
	private $_player_id = 0;
	
	/**
	 +----------------------------------------------------------
	 * 将大区标识唯一到控制器层
	 +----------------------------------------------------------
	 * @var int
	 +----------------------------------------------------------
	 */
	private $_zone_id = 0;
	
	/**
	 +----------------------------------------------------------
	 * 视图数据存储
	 +----------------------------------------------------------
	 * @var array
	 +----------------------------------------------------------
	 */
	private $_data = array();
	
	/**
	 +----------------------------------------------------------
	 * 活动地址
	 +----------------------------------------------------------
	 * @var string
	 +----------------------------------------------------------
	 */
	protected $doing_page = '';
	
	/**
	 +----------------------------------------------------------
	 * 活动业务执行
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function run()
	{
		$this->init();
		$this->execute();
		$this->render();
		$this->finish();
	}
	
	/**
	 +----------------------------------------------------------
	 * 预处理
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function init()
	{
		
	}
	
	/**
	 +----------------------------------------------------------
	 * 处理活动信息
	 +----------------------------------------------------------
	 * @param	int $game_id
	 * @param	string $flag
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function doing($game_id, $flag)
	{
		// 活动参数密钥
		// ------------------------------------------------
		$key = get_metadata('game', $game_id, 'doing_key');
		if (!$key)
		{
			// 未配置的游戏活动密钥
			// ------------------------------------------------
			$this->error_responser('doing key isn\'t config.');
		}
		
		// 加载活动信息
		// --------------------------------------------------
		ck_blog::get_instance()->doing()->load($flag, 'flag');
		if (!ck_blog::get_instance()->doing()->is_load())
		{
			// 未配置的活动信息
			// --------------------------------------------
			$this->error_responser('doing isn\'t config.');
		}
		
		// 获取角色标识
		// --------------------------------------------------------------------------
		$this->_player_id = intval(ck_blog::get_instance()->input()->get('mg_userid'));
		if (!$this->_player_id)
		{
			// 玩家参数必须，否则无法访问
			// ------------------------------------------
			$this->error_responser('player id invalid.');
		}
		
		ck_blog::get_instance()->player()->load($this->_player_id);
		if (!ck_blog::get_instance()->player()->is_load())
		{
			// 玩家信息无法读取
			// ---------------------------------------
			$this->error_responser('player invalid.');
		}
		
		// 获取大区标识
		// ------------------------------------------------------------------
		$this->_zone_id = intval(ck_blog::get_instance()->input()->get('mg_zoneid'));
		if (!$this->_zone_id)
		{
			// 读取大区标识
			// -------------------------------------------------------------------
			$this->_zone_id = intval(ck_blog::get_instance()->player()->zone_id());
		}
		ck_blog::get_instance()->server()->load($this->_zone_id);
		
// 		if (!ck_blog::get_instance()->server()->is_load())
// 		{
// 			// 大区信息无法读取
// 			// -------------------------------------
// 			$this->error_responser('zone invalid.');
// 		}
		
		// 获取渠道标识
		// -----------------------------------------------------------------
		$channel_id = intval(ck_blog::get_instance()->input()->get('mg_ch'));
		
		// 获取设备标识
		// ---------------------------------------------------------------------
		$device_id = trim(ck_blog::get_instance()->input()->get('mg_dev', true));
		
		// 签名
		// --------------------------------------------------------------
		$sign = trim(ck_blog::get_instance()->input()->get('sign', true));
		
		// 校验签名
		// ------------------------------------------------------------------------------------------------------------------------------------------------
		if ($sign != md5($this->_player_id.$this->_zone_id.$channel_id.$device_id.$key) && $sign != md5($this->_player_id.'0'.$channel_id.$device_id.$key))
		{
			// 无效签名，不允许访问
			// ------------------------------------
			$this->error_responser('sign invalid.');
		}
		
		// 请求模块
		// ----------------------------------------
		$module = ck_blog::get_instance()->module();
		
		// 请求目标
		// ------------------------------------------------------
		$target = substr(ck_blog::get_instance()->target(), 0, 1);
		
		// 请求地址
		// -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		$this->assign('doing_api', "/index.php?m={$module}&c={$flag}api&mg_userid={$this->_player_id}&mg_zoneid={$this->_zone_id}&mg_ch={$channel_id}&mg_dev={$device_id}&sign={$sign}&o={$target}");
		$this->doing_page = "/index.php?m={$module}&c={$flag}&mg_userid={$this->_player_id}&mg_zoneid={$this->_zone_id}&mg_ch={$channel_id}&mg_dev={$device_id}&sign={$sign}&o={$target}";
		$this->assign('doing_enter', str_replace($flag, 'enter', $this->doing_page));
		$this->assign('doing_page', $this->doing_page);
		
		// 加载当天活动信息
		// -------------------------------------------------------
		ck_blog::get_instance()->doing_note()->load(date('Y-m-d'));
		
		// 接口初始化
		// -------------------------------------------------------------------
		ck_blog::get_instance()->api_register()->set_player($this->_player_id);
		ck_blog::get_instance()->api_register()->set_zone($this->_zone_id);
		
		// 所有确认操作的记录
		// --------------------------------------------------------------
		$confirm = ck_blog::get_instance()->player()->get_meta('confirm');
		if (!$confirm) 
		{
			// 初始化以防止重复请求数据库
			// ------------------------------------------------------------------------
			ck_blog::get_instance()->player()->update_meta('confirm', array(1000 => 1));
		}
		
		// 确认操作的记录
		// ------------------------------------------------------
		$cf = intval(ck_blog::get_instance()->input()->get('cf'));
		if ($cf > 0) 
		{
			$confirm[$cf] = 1;
			ck_blog::get_instance()->player()->update_meta('confirm', $confirm);
		} 
		$this->assign('confirm', json_encode($confirm));
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取玩家标识
	 +----------------------------------------------------------
	 * @return	int
	 +----------------------------------------------------------
	 */	
	public function player_id()
	{
		return intval($this->_player_id);
	}
	
	/**
	 +----------------------------------------------------------
	 * 获取玩家所在大区
	 +----------------------------------------------------------
	 * @return	int
	 +----------------------------------------------------------
	 */	
	public function zone_id()
	{
		return intval($this->_zone_id);
	}
	
	/**
	 +----------------------------------------------------------
	 * 回跳消息
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function message()
	{
		$m = intval(ck_blog::get_instance()->input()->get('msg'));
		if ($m === 1000)
		{
			// 设置微博转发回跳后弹出提示层消息
			// -----------------------------------------
			$this->assign('message', '转发成功，奖励已发放！');
			return ;
		}
		
		if ($m > 0 && !empty($this->messages) && $m < count($this->messages))
		{
			// 设置回跳后弹出提示层消息
			// -------------------------------------------
			$this->assign('message', $this->messages[$m]);
			return ;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 回放副本战斗录像
	 +----------------------------------------------------------
	 * @param	string $id
	 * @param	string $forward
	 * @param	boolean $return
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function play($id, $forward, $return = false)
	{
		// 进入游戏副本
		// ---------------------------------------------------------
		$url = "maxgame:battle?id={$id}&url=" . urlencode($forward);
		if (ENVIRONMENT === 'development' || ENVIRONMENT === 'localhost')
 		{
 			// 本地模拟测试
 			// ---------------------------------------------------------------------
 			$url = base_url() . 'index.php?m=jxy&c=play&url=' . urlencode($forward);
 		}
 		
		if ($return) return $url;
		
		// 执行完成，回收更新信息
		// ---------------
		$this->finish();
		
		if (ENVIRONMENT === 'development' || ENVIRONMENT === 'localhost') {
			print_r($id, $return);
			die();
		}
		?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<script type="text/javascript">
		window.location.href = "<?php echo $url; ?>";
		</script>
	</head>
	<body></body>
</html>
		<?php
		die();
	}
	
	/**
	 +----------------------------------------------------------
	 * 异常错误处理
	 +----------------------------------------------------------
	 * @param	string $message
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	function error_responser($message = '')
	{
		if (ck_blog::get_instance()->input()->is_ajax_request()) 
		{
			// 异步请求，使用异步方式响应
			// -----------------------------------------------------------
			$this->json_responser(array('code' => 1, 'desc' => $message));
		}
		
		// 记录错误信息
		// ------------------------------------------
		ck_blog::get_instance()->log($message, '', 1);
		
		// 执行完成，回收更新信息
		// ---------------
		$this->finish();
		
		if (ENVIRONMENT === 'development' || ENVIRONMENT === 'localhost') {
			die($message);
		}
		?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<script type="text/javascript">
		window.location.href = "maxgame:close";
		</script>
	</head>
	<body></body>
</html>
		<?php
		die();
	}
    
	/**
	 +----------------------------------------------------------
	 * 执行完成
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function finish()
	{ 
		ck_blog::get_instance()->close();
	}

    /**
	 +----------------------------------------------------------
     * Clear all data of view.<<清除所有视图数据>>
	 +----------------------------------------------------------
     * @return	void
	 +----------------------------------------------------------
     */
    public function clear()
	{
		$this->_data = array();
	}
	
	/**
	 +----------------------------------------------------------
	 * 设置模板文件
	 +----------------------------------------------------------
	 * @param 	string $tpl
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function tpl($tpl)
	{
		$this->_tpl = $tpl;
	}
	
	/**
	 +----------------------------------------------------------
	 * 存储视图变量
	 +----------------------------------------------------------
	 * @param	string $key
	 * @param	mixed $value
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function assign($key, $value)
	{
		$this->_data[$key] = $value;
	}

	/**
	 +----------------------------------------------------------
	 * Render view.<<加载视图文件>>
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	public function render()
	{
		extract($this->_data); 
		include CK_ABSPATH . 'views/' . ck_blog::get_instance()->module() . '/' . $this->_tpl . EXT;
	}
	
	/**
	 +----------------------------------------------------------
	 * 响应text/json消息
	 +----------------------------------------------------------
	 * @param	array $message
	 * @param	boolean $simple
	 +----------------------------------------------------------
	 * @return	void
	 +----------------------------------------------------------
	 */
	public function json_responser($message, $simple = false)
	{
		// 执行完成，回收更新信息
		// ---------------
		$this->finish();
		
		if ($simple)
		{
			// 简单响应
			// ------------------------
			die(json_encode($message));
		}
		else 
		{
			// 响应客户端
			// ------------------------------
			json_responser::format($message);
		}
	}
}
// end ck_controller class

/* End of file ck_controller.php */
/* Location: ./libraries/ck_controller.php */