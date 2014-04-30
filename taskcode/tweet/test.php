<?php
/**
* 
*/
 
class tweet
{

	public $url;
	public $conn;
	public $user_agent;
	public $debug=false;
	public $cookie="";
	public $token;
	public $requestHeader;
	public $cookie_dir = "./cookie/cookie.txt";
	function __construct()
	{  
		$this->cookie = str_replace("\\", "/", dirname(__FILE__))."/cookie/cookie.txt";
		$this->conn=curl_init(); 
		$this->setUserAgent($this->getUserAgent());
	}

	public function request($post=false,$refer=false){ 
		if($this->conn!=null){
			curl_setopt($this->conn, CURLOPT_URL, $this->getUrl());
			if($post){
				curl_setopt($this->conn,CURLOPT_POSTFIELDS, $post);
				curl_setopt($this->conn, CURLOPT_POST, 1);
			}
			else{
				curl_setopt($this->conn, CURLOPT_POST, 0);
			}
			if($refer){
				curl_setopt ($this->conn, CURLOPT_REFERER, $refer);
			}
			curl_setopt($this->conn, CURLOPT_TIMEOUT, 60);
			curl_setopt($this->conn, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->conn, CURLOPT_COOKIEFILE, $this->cookie);
			curl_setopt($this->conn, CURLOPT_COOKIEJAR, $this->cookie);
			curl_setopt($this->conn, CURLOPT_HEADER, true);
			curl_setopt($this->conn, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($this->conn, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->conn, CURLOPT_USERAGENT, $this->user_agent);
			$html = curl_exec($this->conn);
			$this->requestHeader = curl_getinfo($this->conn); 
			$this->setCookie(); 
			return $html;
		}
		return null;
	}
	function setCookie(){ 
		$cookie_content = file_get_contents($this->cookie_dir);
		if(strpos($cookie_content, "m5_csrf_tkn")===false){
			$cookie_content .= "mobile.twitter.com	FALSE	/	FALSE	".(time()+60*60*24*365)."	m5_csrf_tkn	noksl3zeyv34fbjwh";
			file_put_contents($this->cookie_dir , $cookie_content); 
		}
		
	}
	public function setUserAgent($agent){
		$this->user_agent=$agent;
	}
	public function getUserAgent(){
		$agents = array(
			'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 800)'
		);
		return $agents[array_rand($agents)];
	}
	public function setUrl($url_input){
		$this->url=$url_input;
	}
	public function getUrl(){
		return $this->url;
	}
	function getCookieContent(){
		return file_get_contents($this->cookie_dir);
	}
	function getToken(){ 
		$this->setUrl("https://mobile.twitter.com/session/new");
		echo $html=$this->request();
		preg_match("/input name=\"authenticity_token\" type=\"hidden\" value=\"(.*?)\"/", $html, $authenticity_token);
		print_r($authenticity_token);
		$this->token = $authenticity_token[1];	
	}

	function login($user,$psw){
		if(strpos($this->getCookieContent(), "m5_csrf_tkn")===false){
			$this->getToken();
			echo $this->token;
			$this->setUrl("https://mobile.twitter.com/session");
			$authenticity_token = $this->token;
			return $html=$this->request("authenticity_token={$authenticity_token}&username=$user&password=$psw");
		}
	}
	// function create($msg){
	// 	$this->setUrl("https://mobile.twitter.com/");	
	// 	$authenticity_token = $this->token; 
	// 	return $html=$this->request("authenticity_token={$authenticity_token}&tweet[text]=$msg&commit=Tweet");
	// }
	function create($msg){  
		$this->setUrl("https://mobile.twitter.com/api/tweet");	
		$authenticity_token = $this->token; 
		return $html=$this->request("m5_csrf_tkn=noksl3zeyv34fbjwh&tweet[text]=$msg&commit=Tweet");
	}

	public function reply($id,$msg)
	{
		$this->setUrl("https://mobile.twitter.com/api/tweet");
		$post_data = "tweet[text]=".$msg."&tweet[in_reply_to_status_id]=".$id."&m5_csrf_tkn=noksl3zeyv34fbjwh";
		return $this->request($post_data);
	}
}

?> 
<?php
 	$password='qingyu';
	$username='cdn_01@126.com';  
	$msg = "abc ssss ".date("Y-m-d H:i:s",time());
	$bot=new tweet();   
	$html=$bot->login($username,$password);  
	// $html=$bot->create2($msg); 
	$msg = "test reply ".date("Y-m-d H:i:s",time());
	$html=$bot->reply("423164565828804609","@footballitalia ".$msg ); 	

?>