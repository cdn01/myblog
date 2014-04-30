<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed'); 
/**
 +----------------------------------------------------------------------+
 CREATE TABLE `xt_[name]_meta` (
 `meta_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
 `rel_[name]` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '[name]标识',
 `meta_key` VARCHAR(255) DEFAULT '' COMMENT '属性',
 `meta_value` LONGTEXT COMMENT '值',
 PRIMARY KEY (`meta_id`),
 KEY `idx_[name]_lookup` (`rel_[name]`),
 KEY `idx_key_lookup` (`meta_key`)
 ) ENGINE=MYISAM DEFAULT CHARSET=utf8 COMMENT='[name]扩展信息';
 +----------------------------------------------------------------------+
 */

/**
 +----------------------------------------------------------
 * Add Meta Data
 +----------------------------------------------------------
 * Add metadata for the specified object.<<添加扩展数据>>
 +----------------------------------------------------------
 * @param	string $meta_type Type of object metadata is for (e.g., user)
 * @param	int $object_id ID of the object metadata is for
 * @param	string $meta_key Metadata key
 * @param	string $meta_value Metadata value
 +----------------------------------------------------------
 * @return	bool True on successful update, false on failure.
 */
function add_metadata($meta_type, $object_id, $meta_key, $meta_value) 
{
	if (!$meta_type || !$meta_key) {
		return false;
	}

	if (!$object_id = wp_absint($object_id)) {
		return false;
	}

	$table = 'xt_' . $meta_type . '_meta';
	$column = 'rel_' . $meta_type;
	
	$meta_key = stripslashes($meta_key);
	
	if (xt_box::get_instance()->db()->get_row("SELECT 1 FROM {$table} WHERE meta_key='{$meta_key}' AND {$column}={$object_id};")) {
		return false;
	}
	
	xt_box::get_instance()->db()->insert($table, array(
		$column 	=> $object_id,
		'meta_key' 	=> $meta_key,
		'meta_value'=> wp_maybe_serialize(wp_stripslashes_deep($meta_value))
	));
	
	$meta_id = intval(xt_box::get_instance()->db()->insert_id());
	if (!$meta_id) {
		return false;
	}
	
	xt_box::get_instance()->cache()->delete("{$meta_type}_meta:{$object_id}:{$meta_key}");
	
	return $meta_id;
}

/**
 +----------------------------------------------------------
 * Update Meta Data
 +----------------------------------------------------------
 * Update metadata for the specified object. <<更新扩展数据>>
 +----------------------------------------------------------
 * If no value already exists for the specified object ID and metadata key, the metadata will be added.<<如果扩展数据不存在，将添加>>
 +----------------------------------------------------------
 * @param	string $meta_type Type of object metadata is for (e.g., user)
 * @param	int $object_id ID of the object metadata is for
 * @param	string $meta_key Metadata key
 * @param	string $meta_value Metadata value
 +----------------------------------------------------------
 * @return	bool True on successful update, false on failure.
 +----------------------------------------------------------
 */
function update_metadata($meta_type, $object_id, $meta_key, $meta_value) 
{
	if (!$meta_type || !$meta_key) {
		return false;
	}

	if (!$object_id = wp_absint($object_id)) {
		return false;
	}		

	$table = 'xt_' . $meta_type . '_meta';
	$column = 'rel_' . $meta_type;
	
	$meta_key = stripslashes($meta_key);
	
	if (!xt_box::get_instance()->db()->get_row("SELECT 1 FROM {$table} WHERE {$column}={$object_id} AND meta_key='{$meta_key}';")) {
		return add_metadata($meta_type, $object_id, $meta_key, $meta_value);
	}
	
	$data  = array('meta_value' => wp_maybe_serialize(wp_stripslashes_deep($meta_value)));
	$where = array($column => $object_id, 'meta_key' => $meta_key);

	if (!xt_box::get_instance()->db()->update($table, $data, $where)) {
		return false;
	}
	
	xt_box::get_instance()->cache()->delete("{$meta_type}_meta:{$object_id}:{$meta_key}");

	return true;
}

/**
 +----------------------------------------------------------
 * Delete Meta Data
 +----------------------------------------------------------
 * Delete metadata for the specified object.<<删除扩展数据>>
 +----------------------------------------------------------
 * @param	string $meta_type Type of object metadata is for (e.g., player, game, doing)
 * @param	int $object_id ID of the object metadata is for
 * @param	string $meta_key Metadata key metadata entries for the specified object_id.
 +----------------------------------------------------------
 * @return	bool True on successful delete, false on failure.
 +----------------------------------------------------------
 */
function delete_metadata($meta_type, $object_id, $meta_key)
{
	if (!$meta_type || !$meta_key) {
		return false;
	}

	if (!$object_id = wp_absint($object_id)) {
		return false;
	}
	
	$table = 'xt_' . $meta_type . '_meta';
	$column = 'rel_' . $meta_type;
	
	$meta_key = stripslashes($meta_key);
	xt_box::get_instance()->db()->query("DELETE FROM {$table} WHERE {$column}={$object_id} AND meta_key='{$meta_key}';");
	if (xt_box::get_instance()->db()->rows_affected() <= 0) {
		return false;
	}
	
	xt_box::get_instance()->cache()->delete("{$meta_type}_meta:{$object_id}:{$meta_key}");

	return true;
}

/**
 +----------------------------------------------------------
 * Get Meta data
 +----------------------------------------------------------
 * Retrieve metadata for the specified object.<<获取扩展数据>>
 +----------------------------------------------------------
 * @param	string $meta_type Type of object metadata is for (e.g., player, game, doing)
 * @param	int $object_id ID of the object metadata is for
 * @param	string $meta_key Metadata key metadata entries for the specified object_id.
 +----------------------------------------------------------
 * @return	mixed
 +----------------------------------------------------------
 */
function get_metadata($meta_type, $object_id, $meta_key)
{
	if (!$meta_type || !$meta_key) {
		return false;
	}

	if (!$object_id = wp_absint($object_id)) {
		return false;
	}
	
	$meta_key = stripslashes($meta_key);
	$meta_value = xt_box::get_instance()->cache()->get("{$meta_type}_meta:{$object_id}:{$meta_key}");

	if (!$meta_value) 
	{
		$table = 'xt_' . $meta_type . '_meta';
		$column = 'rel_' . $meta_type;
		
		// Get meta info
		$item = xt_box::get_instance()->db()->get_row("SELECT meta_value FROM {$table} WHERE {$column}={$object_id} AND meta_key='{$meta_key}';");
		if (!$item) {
			return false; 
		}
		
		$meta_value = wp_maybe_unserialize($item->meta_value);
		
		xt_box::get_instance()->cache()->set("{$meta_type}_meta:{$object_id}:{$meta_key}", $meta_value);
	}
	
	return $meta_value;
}

/* End of file meta_helper.php */
/* Location: ./helpers/meta_helper.php */