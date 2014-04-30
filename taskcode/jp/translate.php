<?php

include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-3)."/config.php"); 
$url = "http://www.excite.co.jp/world/chinese/";
$str = "<strong>　误区一：控油是终生使命</strong>";
$data = "_qf__formTrans=&_token=00f987dc02224&auto_detect_flg=1&wb_lp=CHJA&swb_lp=JACH&big5=no&before_lang=CH&after_lang=JA&big5_lang=no&auto_detect=off&auto_detect=on&before=".urlencode($str)."&after=".urlencode($str);

$html = html($url,$data);
preg_match("/<textarea id=\"after\" cols=\"37\" rows=\"13\" name=\"after\">(.*)<\/text/iUs", $html, $match);
print_r($match[1]);































































?>