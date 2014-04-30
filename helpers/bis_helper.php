<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 

/**
 +----------------------------------------------------------
 * 获取活动地址
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function base_url()
{
	return xt_box::get_instance()->config()->item('base_url');
}

class xt_static
{
	/**
	 +----------------------------------------------------------
	 * 活动模板地址
	 +----------------------------------------------------------
	 * @return	string
	 +----------------------------------------------------------
	 */
	public static function theme_url($type, $files, $version = '131207')
	{
		$theme_version = xt_box::get_instance()->config()->item('theme_ver');
		
		if ($theme_version == '') {
			$base_url = xt_box::get_instance()->config()->item('resource_url') . 'themes/';
		} else {
			$base_url = xt_box::get_instance()->config()->item('resource_url') . 'themes/' . $theme_version . '/';
		}
		
		// 如果是本地环境
		// -----------------------------
		if (ENVIRONMENT == 'localhost')
		{
			foreach ($files as $item)
			{
				if ($type == 'css') {
					echo '<link href="' . $base_url . $item . '?v=' . $version . '" rel="stylesheet" />' . xt_eol_and_tab(1);
				} elseif ($type == 'js') {
					echo '<script src="' . $base_url . $item . '?v=' . $version . '"></script>' . xt_eol_and_tab(1);
				}
			}
		}
		// 如果是开发环境
		// ----------------------------------
		elseif (ENVIRONMENT == 'development')
		{
			if ($type == 'css') {
				echo '<link href="' .$base_url . '??' . implode(',', $files) . '?v=' . $version . '" rel="stylesheet" />' . xt_eol_and_tab(1);
			} elseif ($type == 'js') {
				echo '<script src="' .$base_url . '??' . implode(',', $files) . '?v=' . $version . '"></script>' . xt_eol_and_tab(1);
			}
		}
		else
		{
			if ($type == 'css') {
				echo '<link href="' .$base_url . '??' . implode(',', $files) . '?v=' . $version . '" rel="stylesheet" />' . xt_eol_and_tab(1);
			} elseif ($type == 'js') {
				echo '<script src="' .$base_url . '??' . implode(',', $files) . '?v=' . $version . '"></script>' . xt_eol_and_tab(1);
			}
		}
	}
}

/**
 +----------------------------------------------------------
 * 活动请求模拟
 +----------------------------------------------------------
 * @param	string $module
 * @param	string $controller
 * @param	int $player_id
 +----------------------------------------------------------
 * @return	string
 +----------------------------------------------------------
 */
function doing_simulate($module, $controller, $player_id)
{
	// 活动参数密钥
	// -----------------------------------------
	$key = get_metadata('game', 1, 'doing_key');
	
	// 角色标识
	// -----------------------------
	$player_id = intval($player_id);
	
	// 设置一个默认的角色
	// ----------------------------------------------
	$player_id = ($player_id ? $player_id : 136352);

	// 大区标识
	// ------------
	$zone_id = 255;

	// 渠道标识
	// -------------
	$channel_id = 1;

	// 设备标识
	// -----------------------------------------------------
	$device_id = 'f1fdf9fbe1e8bdd1dadac096cd39353f2524792d';

	// 签名
	// -----------------------------------------------------
	$sign = md5($player_id.'0'.$channel_id.$device_id.$key);

	// 模拟终端传递过来的参数
	// ----------------------------------------------------------------------------------------------------------------------------------------------
	return "/index.php?m={$module}&c={$controller}&mg_userid={$player_id}&mg_zoneid={$zone_id}&mg_ch={$channel_id}&mg_dev={$device_id}&sign={$sign}";
}

/* End of file bis_helper.php */
/* Location: ./helpers/bis_helper.php */