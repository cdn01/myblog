<?php
	function getHtml($url)
    {
        ob_start();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);  
        curl_exec($ch);
        curl_close($ch);
        $_str = ob_get_contents();   
        ob_end_clean();
        return $_str;
    }
	function getMatchContents($url,$preg_p,$all="",$key=-1)
	{ 
		$content = getHtml($url); 
		$rs = array();
		if( $all == "all")
		{
			preg_match_all($preg_p, $content, $rs);
		}else{
			preg_match($preg_p, $content, $rs);
		} 
		if( $key > 0 )
		{
			return $rs[$key];
		}else
			return $rs;
	}

	function query($sql)
	{
		$rs = array();
		$cmd = mysql_query($sql);
		while ($res = mysql_fetch_assoc($cmd)) {
			$rs[] = $res;
		}
		return $rs;
	}

	function str_conv($str)
	{
		$str = str_replace("\n", "<br>", $str);
		$str = addslashes ($str);
		return $str;
	}

	function shortUrl($url)
	{ 
		return urlencode(); 
	}
	// echo shoutUrl("http://www.php.net/manual/zh/function.date.php");

	function tlog($info,$type=1)
	{
		switch ($type) {
			case 1:
				//info log
				file_put_contents("info_log".date("Y-m-d",time()).".txt", "===================================\n".date("Y-m-d H:i:s",time())."\n".$info."\n",FILE_APPEND);
				break;
			case 2:
				//error log
				file_put_contents("error_log".date("Y-m-d",time()).".txt", "===================================\n".date("Y-m-d H:i:s",time())."\n".$info."\n",FILE_APPEND);
				# code...
				break;
			default: 
				break;
		}
		
	} 


	function getHtml2($source,$url)
    { 
    	ob_start();
        $header[] = "Host:roll.news.qq.com";
        $header[] = "Referer:http://roll.news.qq.com/";
        $cookie = "";
        $ch = curl_init($source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1) ;
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_exec($ch);
        curl_close($ch); 
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0) ;
        curl_setopt($ch, CURLOPT_HEADER, false); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
        curl_exec($ch);
        curl_close($ch);  
        $_str = ob_get_contents(); 
        ob_end_clean(); 
        return $_str;
    }
    function getQQList()
    {
    	$qqnws = getHtml2("http://roll.news.qq.com/","http://roll.news.qq.com/interface/roll.php?".rand()."&cata=&site=news&date=&page=1&mode=2&of=json");
		$qqnws = mb_convert_encoding($qqnws, "UTF-8","GBK");
		$qqArr = json_decode(trim($qqnws),true); 
		$dataList = $qqArr['data']['article_info'];
		$p= "/<div class=\"listT c\"><span class=\"t-tit\">\[(.*)\]<\/span><dl><dt><span class=\"t-time\">(.*)<\/span><a target=\"_blank\" href=\"(.*)\" >(.*)<\/a><\/dt><dd>(.*)...<a/U";
		preg_match_all($p, $dataList, $matches);
		$qqlistArr = array();
		foreach($matches[4] as $key=>$val)
		{
			$qqlistArr[$key]["title"] = $val;
			$qqlistArr[$key]["type"] = $matches[1][$key];
			$qqlistArr[$key]["time"] = "2013-".$matches[2][$key].":00";
			$qqlistArr[$key]["link"] = $matches[3][$key];
			$img_p = "/src=\"(.*)\"/iU";
			preg_match($img_p, $matches[5][$key],$img_m);
			if(@$img_m[1])
			{
				$qqlistArr[$key]["image"] = $img_m[1];
			}
			$qqlistArr[$key]["content"] = trim(preg_replace("/<\/?(.*)>/i", "", $matches[5][$key]));  
		}
		return $qqlistArr;
    } 

    function tmail($accoutn,$err="账号异常")
    {
    	mail('backcn@126.com', '微博账号异常', mb_convert_encoding("账号：".$accoutn."    ".$err."    ".date("Y-m-d H:i:s",time()), "GBK","UTF-8"));
    }
?>