<?php   
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-5)."/common.php"); 

function getQB($offset=1){
	$url = "http://wap3.qiushibaike.com";
	$url = $offset==1?$url:$url."/8hr/page/".$offset;
	$pagefile = html($url);  
	$list_p_1 = "/<p class=\"user\">(.*)<p class=\"vote\">/iUs";
	preg_match_all($list_p_1, $pagefile, $matches);

	$list_p_2 = "/id='d(.*)'/iUs";
	preg_match_all($list_p_2, $pagefile, $ids);

	// print_r($matches[0][1]);
	foreach ($matches[1] as $key => $value) {
		preg_match("/<img (.*)>(.*)<\/a> <\/p>/iUs",$value,$m_user);
		$user_name = $m_user[2];
		$picurl = "";
		if(strpos($value, "medium")){
			preg_match("/<a href=\"(.*)\/medium\/(.*)\.jpg\">/iU", $value , $m_picurl);
			// print_r($m_picurl);
			$picurl = $m_picurl[1]."/medium/".$m_picurl[2].".jpg";
		}

		$no_a_img = preg_replace("/<\/?(a|img)+(.*?)>/i",'',$value);
		preg_match("/<\/p>(.*)/is", $no_a_img, $m_content);
		$content = str_replace("<br>", "...", $m_content[1]); 
		$content = str_replace("<br/>", "...", $m_content[1]); 

		// echo $content;
		$id = $ids[1][$key];

		echo $sql = "insert into article_joke (id,user,content,picurl,gettime) values ('".$id."','".str_conv($user_name)."','".str_conv($content)."','".$picurl."','".date("Y-m-d H:i:s",time())."')";
		mysql_query($sql);
	}

	$next_page = "/<a href=\"(.*)\">下一页<\/a>/i";
	preg_match($next_page, $pagefile, $next_m);
	print_r($next_m);
	if($offset<20)
	{
		// $next_url = "http://wap3.qiushibaike.com".$next_m[1];	
		sleep(5);
		getQB(++$offset);
	}else{
print <<<EOT
	<script type='text/javascript'>
		setTimeout("location.href='getlist.php'",1000*60*20);
	</script>
EOT;

	}	
}

getQB();


?>

