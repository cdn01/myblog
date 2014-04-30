<?php 
require 'ck_loader.php'; 
$module = ck_blog::get_instance()->input()->get("m",true);  //admin blog 前后台模块
ck_blog::get_instance()->register($module);
$control = ck_blog::get_instance()->input()->get("c",true);  //控制器
ck_blog::get_instance()->run($control); 

 