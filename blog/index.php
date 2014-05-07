<?php if ( ! defined('CK_ABSPATH')) exit('No direct script access allowed');

class index extends ck_controller 
{   
	function execute() 
	{  
		$item = trim(ck_blog::get_instance()->input()->get('i', true)); 
		$item = empty($item)?"index":$item;
		
		$blog_menu = ck_blog::get_instance()->menu()->get_menu();
		print_r($blog_menu);
		$this->tpl("{$item}");
	}
}
 