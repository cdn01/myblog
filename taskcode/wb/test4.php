<?php
include("/usr/local/php/wb.php");
$param = array();
$param['uname'] = 'cdn_01@126.com';
$param['pwd'] = 'qingyu';
$wb = new weibo();
// 登录
$lg = $wb->login($param);
print_r($lg);
die();
// 获取用户信息
// $rs = $wb->searchUser("phpbin");
// print_r($rs);
// $wb->sendWeibo("1032 重新开始啦啦啦".date("Y-m-d H:i:s",time()));
$picurl = $wb->sendWeibo("dai tu ping fa bu cheng gong lala ....","/usr/local/php/a.jpg");
print_r($picurl);





















































?>