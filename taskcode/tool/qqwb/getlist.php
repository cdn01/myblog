<?php
require 'header.php';
set_time_limit(0);
$url = "http://icare.qq.com/dj/timeList.php?cid=1&subid=0&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com";
$data = json_decode(html($url),true);
$talk = $data["info"]["talk"];
println($talk);
//http://icare.qq.com/dj/getWbData.php?id=47735&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com 
//http://icare.qq.com/dj/djakMessageRelay.php?id=410922034863719&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com 

/* 

println($data);

*/

function insertContent($t){
	$pic_arr = "";
	if(!empty($t['wbdata']['pic'])&&isset($t['wbdata']['pic'])){
		$pic_arr = implode("@@@@@@",$t['wbdata']['pic']);
	}
	$sql = "insert into qqwb (pic_arr,content,hot,qid,img,summary,timestamp,title,wbid) 
	values ('{$pic_arr}','".str_conv($t['wbdata']['content'])."','{$t['akdata']['hot']}','{$t['akdata']['id']}','{$t['akdata']['img']}','".str_conv($t['akdata']['summary'])."','{$t['timestamp']}','".str_conv($t['akdata']['title'])."','{$t['akdata']['wbid']}')";
	mysql_query($sql);
	return mysql_insert_id();
}

function replyMessage($comments,$qid){
	foreach ($comments as $c){
		if(!empty($c)&&isset($c)){
			$sql = "insert into qqwb_comments (account,content,mid,nick,time,qid) values (
			'".str_conv($c['account'])."','".str_conv($c['content'])."','{$c['mid']}','".str_conv($c['nick'])."','{$c['time']}','{$qid}')";
			mysql_query($sql);
		}
	}
}

foreach ($talk as $t){
	$url = "http://icare.qq.com/dj/getWbData.php?id=".$t['id']."&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com";
	$content = json_decode(html($url),true);
	$url = "http://icare.qq.com/dj/djakMessageRelay.php?id=".$t['wbid']."&apiType=16&apiHost=http%3A%2F%2Fapi.t.qq.com";
	$comments = json_decode(html($url),true);
	$pid = insertContent($content["info"]);
	replyMessage($comments['replyMessage'],$pid);
	sleep(3);
}
 










/* 

CREATE TABLE `qqwb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hot` int(11) DEFAULT NULL,
  `qid` bigint(20) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `summary` text,
  `timestamp` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `wbid` bigint(20) DEFAULT NULL,
  `ispost` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wbid` (`wbid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `qqwb_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) DEFAULT NULL,
  `content` text,
  `mid` bigint(11) DEFAULT NULL,
  `nick` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `qid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




 */