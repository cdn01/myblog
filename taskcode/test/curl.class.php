<?php
	class myCurl{
		private $ch;
		private $cookieFile;
		private $options;
		private $output;
		
		function __construct()
		{ 
			if(!extension_loaded("curl"))
			{
				die("系统未加载CURL扩展,程序无法执行!");
			} 
			$this->cookieFile = tempnam("./","jiraTempCookie");  
		}
		function openCurl($url,$postdate='')
		{
			$this->ch = curl_init($url); 
			curl_setopt ($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $this->cookieFile);
			curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $this->cookieFile);
			if(!empty($postdate))
			{
				curl_setopt ($this->ch, CURLOPT_POSTFIELDS, $postdate);
				curl_setopt ($this->ch, CURLOPT_POST, 1); 
			} 
			$this->output = curl_exec($this->ch);
			curl_close($this->ch);
		}
		function getOutput()
		{
			return $this->output;
		}
		function getCookieFile()
		{
			return $this->cookieFile;
		}
		function closeCurl()
		{
			unlink($this->cookieFile);
		}
	}
?>