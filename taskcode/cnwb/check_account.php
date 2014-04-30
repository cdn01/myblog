<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0, -5) . "/config.php");
include(str_replace("\\", "/", dirname(__FILE__)) . "/CnwbBot.php");

$sql        = "select * from do_account where postnum = 99999   limit 1";
$do_account = query($sql);


$param          = array();
$user           = substr($do_account[0]["user"], 0, strpos($do_account[0]["user"], "@"));
$param['uname'] = $do_account[0]["user"];
$param['pwd']   = $do_account[0]["psw"];
$cnwb           = new CnwbBot($user);
// 登录
$islogin        = $cnwb->login($param);

$sql     = "select * from article where char_length(title)>10 and gettime > '" . date("Y-m-d", strtotime("-1 day")) . " ' order by postnum asc , id desc  limit 1";
$article = query($sql);
mysql_query("update article set postnum = postnum+ 1 where id = '" . $article[0]['id'] . "'");


$message = trim($article[0]['title']) . " 详情:http://mall0592.duapp.com/404.htm?_u=" . $user . "&_r=search&_t=" . date("H_i_s", time());
if ($article[0]['image_dir']) {
    $image_dir = substr(str_replace("\\", "/", dirname(__FILE__)), 0, -5) . "/difbot/baidu/" . $article[0]['image_dir'];
    $result    = $cnwb->sendWeibo($message, $image_dir);
} else {
    $result = $cnwb->sendWeibo($message);
}
$log = json_decode(substr($result, strpos($result, '{"id":"')), true);
// print_r($log);
if ($log["ok"] == "1") { 
 $sql        = "select * from do_account where postnum != 99999 order by postnum desc  limit 1";
 $do_account_o = query($sql);
 $sql        = "update do_account set postnum = '".$do_account_o[0]['postnum']."' where id = '" . $do_account[0]['id'] . "'";
 mysql_query($sql);
}
echo date("Y-m-d H:i:s", time()) . "<br><hr>用户名:&nbsp;&nbsp;&nbsp;" . $param['uname'] . "<br><hr>密码 :&nbsp;&nbsp;&nbsp;" . $param['pwd'] . "<br><hr>" . $message . "<br><hr>" . $log["msg"];
if ($log['id']) {
    $insert_sql = "insert into weibo_create (cid,postuser,posttime) values ('" . $log['id'] . "','" . $user . "','" . date("Y-m-d H:i:s", time()) . "')";
    mysql_query($insert_sql);
}


?> 