<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed');

class ck_menu{
	private $_menu = array();
	
	public function get_menu($parentid = 0){
		$sql = "select * from menu where parentid = '".$parentid."' order by position desc ; ";
		$temp = ck_blog::get_instance()->db()->get_results($sql,ARRAY_A); 
		foreach ($temp as &$val){
			$slq = "select * from menu where parentid = '".$val['id']."' order by position desc;";
			$child = ck_blog::get_instance()->db()->get_results($slq,ARRAY_A);
			if($child){ 
				$val['child'][] = $this->get_menu($val['id']); 
			}
		}
		return $this->_menu = $temp;
	}
}