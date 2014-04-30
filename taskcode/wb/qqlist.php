<?php
	include(str_replace("\\", "/", dirname(__FILE__))."/conn.php");
	$page = empty($_REQUEST['page'])?1:($_REQUEST['page']>10?1:$_REQUEST['page']); 
	$qqnws = getHtml2("http://roll.news.qq.com/","http://roll.news.qq.com/interface/roll.php?".rand()."&cata=&site=news&date=&page=$page&mode=2&of=json");
	$qqnws = mb_convert_encoding($qqnws, "UTF-8","GBK");
	$qqArr = json_decode(trim($qqnws),true);  
	$dataList = $qqArr['data']['article_info'];
	$p= "/<div class=\"listT c\"><span class=\"t-tit\">\[(.*)\]<\/span><dl><dt><span class=\"t-time\">(.*)<\/span><a target=\"_blank\" href=\"(.*)\" >(.*)<\/a><\/dt><dd>(.*)...<a/U";
	preg_match_all($p, $dataList, $matches);
	$qqlistArr = array();

	foreach($matches[4] as $key=>$val)
	{
		$qqlistArr[$key]["title"] = str_conv($val);
		$qqlistArr[$key]["type"] = $matches[1][$key];
		$qqlistArr[$key]["time"] = "2013-".$matches[2][$key].":00";
		$qqlistArr[$key]["link"] = urlencode($matches[3][$key]);
		$img_p = "/src=\"(.*)\"/iU";
		preg_match($img_p, $matches[5][$key],$img_m);
		if(@$img_m[1])
		{
			$qqlistArr[$key]["image"] = $img_m[1];
		}else{
			$qqlistArr[$key]["image"] = "";
		}
		$qqlistArr[$key]["content"] = str_conv(trim(preg_replace("/<\/?(.*)>/i", "", $matches[5][$key]))); 

		echo $sql = "insert into article (title,type,gettime,link,description) values ('".$qqlistArr[$key]["title"]."','".$qqlistArr[$key]["type"]."','".$qqlistArr[$key]["time"]."','".$qqlistArr[$key]["link"]."','".$qqlistArr[$key]["content"]."')";
		if(mysql_query($sql))
		{ 

			if($qqlistArr[$key]["image"]!="")
			{
				$articleid = mysql_insert_id();
				echo $sql = "insert into images (aid , src ) values ('".$articleid."','".$qqlistArr[$key]["image"]."')" ;
				echo "\n";
				if(mysql_query($sql))
				{ 
					$imagesid = mysql_insert_id();

					$img = getHtml2("http://roll.news.qq.com/",$qqlistArr[$key]["image"]);
					$dir = WWW."image/aid_".$articleid."_0.jpg";
					file_put_contents($dir, $img);
					$sql = "update images set dir='".$dir."' where id=".$imagesid;
					mysql_query($sql);	
				}
				sleep(20);	
			}
		}
	}
	$nextpage = $page+1;
	redirect("qqlist.php?page=$nextpage",20);
?>