<?php 
 
function getId($key){
	$url = "http://movie.douban.com/subject_search?search_text=".urlencode($key)."&cat=1002";
	$html = html($url);
	preg_match("/<div class=\"pl2\">(.*)<a href=\"http:\/\/movie.douban.com\/subject\/(\d+)\/\"/iUs",$html,$matches);
	// print_r($matches); 
	// echo $html;
	return $matches[2];
}

function getComments($vid,$key){
	$id = getId($key);
	$url = "http://movie.douban.com/subject/".$id."/comments";
	$html = html($url);
	
    preg_match_all("/<p class=\"\">(.*)<\/p>/iUs", $html, $matches);
    // print_r($matches[1]);
    foreach ($matches[1] as $key => $value) {
    	$sql = "insert into ff_comments (vid,comments) values ('".$vid."','".preg_replace("/<(.*)>/iUs", "", $value)."')";
    	mysql_query($sql);
    }
    $sql = "select * from ff_comments where vid=".$vid." and CHAR_LENGTH(comments)<105 order by CHAR_LENGTH(comments) desc limit 1";
    $rs = query($sql);
	return  $rs[0]['comments'];
}


?>