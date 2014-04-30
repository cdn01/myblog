<?php

include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-3)."/config.php"); 
$url = "http://www.worldlingo.com/S3704.3/texttranslate"; 
$data = "wl_url=http%3A%2F%2Fwww.worldlingo.com%2Fko%2Fmicrosoft%2Fcomputer_translation.html&wl_srcenc=utf-8&wl_trgenc=utf-8&langCode=ko&wl_srclang=ZH_CN&wl_trglang=KO&Submit=%EB%B2%88%EC%97%AD&wl_text=%E4%B8%AD%E6%96%87%E7%BF%BB%E8%AF%91%E9%9F%A9%E6%96%87&wl_text_result=";

$html = html($url,$data);
print_r($html);die();
preg_match("/<textarea id=\"after\" cols=\"37\" rows=\"13\" name=\"after\">(.*)<\/text/iUs", $html, $match);
print_r($match[1]);
































































?>