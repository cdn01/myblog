<?php
include(str_replace("\\", "/", dirname(__FILE__))."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/Wordpress.class.php");  

$host = "http://www.seois.org";
$user = "backcn";
$psw  = "qingyu2007!QAZ";
//SELECT * from en_article where addweb=0 and ispost=1 and contentdiv is not NULL and webid !=0 ORDER BY gettime DESC LIMIT 1;
$wp = new Wordpress($host);
$html = $wp->login($user,$psw);
$data = array("content"=>"test 2014","title"=>"123test","tags"=>"a,bc,dd");
$html = $wp->post($data);
echo $html;
?>