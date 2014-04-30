<?php
set_time_limit(0);
        date_default_timezone_set('Asia/Shanghai');
        include "Snoopy.class.php";
        $snoopy = new Snoopy();
        include "curl.class.php";
        $mycurl = new myCurl();

        //$snoopy->fetch("http://m.weibo.cn");
        //print_r($snoopy->headers[4]);
        /*$res = $snoopy->headers[4];
        preg_match("/Location:(.*)/iUs",$snoopy->headers[4],$location_p);
        $location = trim(str_replace("Location:","",$snoopy->headers[4]));
        //$snoopy->fetch($location);
        //echo $snoopy->results;
        //$mycurl->openCurl($location);
        //echo $mycurl->getOutput();
        try{
        $mycurl->openCurl($location,"check=1&backURL=http%3A%2F%2Fm.weibo.cn%2F&uname=cdn_01%40126.com&pwd=qingyu&autoLogin=1");
        echo $mycurl->getOutput();

        }catch(Exception $e){
                print_r($e);
        } 
		
		//$mycurl->openCurl("https://m.weibo.cn/","check=1&backURL=https%3A%2F%2Fm.weibo.cn%2F&uname=cdn_01%40126.com&pwd=qingyu&autoLogin=1");
		//print_r($mycurl->getOutput());
		$cookieFile = tempnam("./","jiraTempCookie");  
		$ch = curl_init("https://m.weibo.cn/");  
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (  "Accept-Encoding:gzip, deflate", 
"Connection:keep-alive",
"Host:m.weibo.cn", 
"User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64; rv:25.0) Gecko/20100101 Firefox/25.0"
) );
		curl_setopt($ch, CURLOPT_REFERER, "https://m.weibo.cn/login");      
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_COOKIEJAR, $cookieFile);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, $cookieFile);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, "check=1&backURL=https%3A%2F%2Fm.weibo.cn%2F&uname=cdn_01%40126.com&pwd=qingyu&autoLogin=1");
		curl_setopt ($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$output = curl_exec($ch);
		curl_close($ch);
		
		print_r($output);
	*/
		
		$cookieFile = tempnam("./","jiraTempCookie"); 
	$curl = curl_init();  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($curl, CURLOPT_HEADER, 1);  
    curl_setopt($curl, CURLOPT_POST, true);  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");  
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);  
    //curl_setopt($curl, CURLOPT_COOKIE, "s_cc=true; s_sq={$oPCC->s_sq}; MUID={$oPCC->MUID}; MSPRequ={$strCookieMSPRequ}; MSPOK={$strCookieMSPOK}; CkTst={$CkTst}");  
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookieFile); # SAME cookiefile  
    $base_url = "https://m.weibo.cn/login";  
    curl_setopt($curl, CURLOPT_URL, $base_url); # this is where you are requesting POST-method form results (working with secure connection using cookies after auth)  
    curl_setopt($curl, CURLOPT_POSTFIELDS, "check=1&backURL=https%3A%2F%2Fm.weibo.cn%2F&uname=cdn_01%40126.com&pwd=qingyu&autoLogin=1"); # form params that'll be used to get form results  
    $strContent = curl_exec($curl);      //从msn中所取得的回复  
    curl_close ($curl);  
		

?>