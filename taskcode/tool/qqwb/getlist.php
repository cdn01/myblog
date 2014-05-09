<?php
require 'header.php';
set_time_limit(0);
$url = "http://icare.qq.com/dj/timeList.php?cid=1&subid=0&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com";
$data = json_decode(html($url),true);
$talk = $data["info"]["talk"];
println($talk);
//http://icare.qq.com/dj/getWbData.php?id=47735&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com 
//http://icare.qq.com/dj/djakMessageRelay.php?id=410922034863719&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com 
foreach ($talk as $t){
	$url = "http://icare.qq.com/dj/getWbData.php?id=".$t['id']."&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com";
	$content = json_decode(html($url),true);
	$url = "http://icare.qq.com/dj/djakMessageRelay.php?id=".$t['wbid']."&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com";
	$comments = json_decode(html($url),true);
	println($content);
	println($comments);
	sleep(3);
}
println($data);