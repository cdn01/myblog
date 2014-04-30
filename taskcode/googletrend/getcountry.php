<?php
include(str_replace("\\", "/", dirname(__FILE__))."/lib/curl.class.php");
include(str_replace("\\", "/", dirname(__FILE__))."/lib/config.php");

$country = file_get_contents("http://www.google.com/trends/hottrends");

preg_match_all("/data-id='(.*)'data-button-caption='(.*)'/iUs", $country, $matches);

print_r($matches);

foreach ($matches[1] as  $key => $val){
	$sql = "insert into google_trends_country (dataid,country) values ('".$val."','".$matches[2][$key]."')";
	mysql_query($sql);
}