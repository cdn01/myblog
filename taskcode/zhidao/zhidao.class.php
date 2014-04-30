<?php
class zhidao{
	public $header,$cookie_dir,$cookie;
	public function __construct($username,$psw)
    {
        $this->header = array();
        $this->header[] = "Host:passport.baidu.com";
        $this->header[] = "Referer:https://passport.baidu.com/v2/?login&fr=old";
        $this->cookie_dir = "./cookie/cookie_".$username.".txt";
        $this->cookie = str_replace("\\", "/", dirname(__FILE__))."/cookie/cookie_".$username.".txt"; 
        if(!file_exists($this->cookie_dir)) {  
          file_put_contents($this->cookie_dir, "");
        }
        $this->login($username,$psw);
    }
    public function html($url,$data=false){
    	ob_start();
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        if ($data){
                curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        if ( strpos($url, 'https') !== false) {
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
        curl_exec($ch);
        curl_close($ch);
        $_str = ob_get_contents();  
        ob_end_clean();
        return $_str;
    }

	public function login($user,$psw){
		$url = "https://passport.baidu.com/v2/?login&fr=old";
		$data = "staticpage=https%3A%2F%2Fpassport.baidu.com%2Fstatic%2Fpasspc-account%2Fhtml%2Fv3Jump.html&charset=UTF-8&token=2c931702d8a4d325568ccf4dbd292d3e&tpl=pp&apiver=v3&tt=".time()."&codestring=&safeflg=0&u=https%3A%2F%2Fpassport.baidu.com%2F&isPhone=false&quick_user=0&logintype=basicLogin&logLoginType=pc_loginBasic&loginmerge=true&username=".$user."&password=".$psw."&verifycode=&mem_pass=on&ppui_logintime=15038&callback=parent.bd__pcbs__opxhjo";
		$html = $this->html($url,$data);
	}
	public function ask($message){

	}
	public function answer($id,$message){

	}
	public function choseBest($id){

	}
}

$zhidao = new zhidao("bqiaogoug692","zknwa44967");
print_r($zhidao);
$html = $zhidao->html("https://passport.baidu.com/v2/?login");
print_r($html);
?>