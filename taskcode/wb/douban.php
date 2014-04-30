<?php

include(str_replace("\\", "/", dirname(__FILE__))."/superCurl.php"); 

$scurl = new SuperCurl("www.douban.com
","http://www.douban.com/accounts/login");
/*   */
$login_res=$scurl->html("http://www.douban.com/accounts/login");             
$login_res = $scurl->html("https://www.douban.com/accounts/login","source=simple&redir=http%3A%2F%2Fwww.douban.com&form_email=cmd_01%40126.com&form_password=qingyu2007&user_login=%E7%99%BB%E5%BD%95");
print_r($login_res);
$login_res = $scurl->html("http://www.douban.com/mine/");
print_r($login_res);
?>