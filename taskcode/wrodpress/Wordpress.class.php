<?php
/**
* 
*/
 
class Wordpress
{

	public $url;
	public $conn;
	public $user_agent;
	public $debug=false;
	public $cookie="";
	public $token;
	public $requestHeader;
	public $cookie_dir = "";
	public $sig = "";
	public $vdata = "";
	public $sid = "";
	public $pre_refer = "";
	public $host = "";
	function __construct($host)
	{    
		$this->cookie_dir = "./cookie/cookie_".urlencode($host).".txt";
		$this->cookie = str_replace("\\", "/", dirname(__FILE__))."/cookie/cookie_".urlencode($host).".txt"; 
		if(!file_exists($this->cookie_dir)) {  
			file_put_contents($this->cookie_dir, "");
		}
		$this->host=$host;
		$this->pre_refer = "";
		$this->conn=curl_init(); 
		$this->setUserAgent($this->getUserAgent()); 
	}

	public function request($post=false,$refer=false,$mul=false){ 
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
			if($mul){
				curl_setopt ($this->conn, CURLOPT_HTTPHEADER,array("Content-Type: multipart/form-data; boundary=---------------------------".time(),"Content-length: 13891"));
				echo "aa";
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
			// $this->setCookie(); 
			return $html;
		}
		return null;
	}
	function setCookie($sid){ 
		$cookie_content = file_get_contents($this->cookie_dir);
		if(strpos($cookie_content, "m5_csrf_tkn")===false){
			$cookie_content .= ".3g.qq.com	FALSE	/	FALSE	".(time()+60*60*24*365)."	mysid=$sid= ";
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
	public function getCookieContent(){
		return file_get_contents($this->cookie_dir);
	} 
	public function login($user,$psw){
		$cookie_file = $this->getCookieContent();
		if(strpos($cookie_file, "wp-admin\tFALSE")===false){
			$post_url = $this->setUrl($this->host."/wp-login.php");
			$html = $this->request("log=$user&pwd=".urlencode($psw)."&wp-submit=Log+In&redirect_to=".urlencode($this->host."/wp-admin/")."&testcookie=1");
			return $html;
		}        
	} 
	public function html($url){
		$this->setUrl($url);
		$html = $this->request();
		return $html;
	} 

	public function post($data){     
		$html = $this->html($this->host."/wp-admin/post-new.php"); 
		preg_match("/name=\"_ajax_nonce-add-category\" value=\"(.*)\"/iU", $html, $nonce_cat_m); 
		preg_match("/name=\"_wpnonce\" value=\"(.*)\"/iU", $html, $_wpnonce_m);
		preg_match("/name=\"_ajax_nonce-add-meta\" value=\"(.*)\"/iU", $html, $nonce_meta_m);
		preg_match("/name=\"meta-box-order-nonce\" value=\"(.*)\"/iU", $html, $meta_box_m);
		preg_match("/name='post_ID' value='(.*)'/iU", $html, $post_ID_m); 
		echo $nonce_meta = $nonce_meta_m[1];
		echo "-";
		echo $_wpnonce = $_wpnonce_m[1];
		echo "-";
		echo $nonce_meta = $nonce_meta_m[1];
		echo "-";
		echo $meta_box = $meta_box_m[1];
		echo "-";
		echo $post_ID = $post_ID_m[1];
	 
		$this->setUrl($this->host."/wp-admin/post.php");
		$post_data = "_wpnonce=".$_wpnonce."&_wp_http_referer=".urlencode($this->host."/wp-admin/post-new.php")."&user_ID=1&action=editpost&originalaction=editpost&post_author=1&post_type=post&original_post_status=auto-draft&referredby=".urlencode($this->host."/wp-admin/")."&_wp_original_http_referer=http%3A%2F%2Flocalhost%2Fwordpress%2Fwp-admin%2F&auto_draft=0&post_ID=".$post_ID."&autosavenonce=31464dce60&meta-box-order-nonce=".$meta_box."&closedpostboxesnonce=fb51f63b35&post_title=".$data["title"]."&samplepermalinknonce=67602b059c&content=".urlencode($data["content"])."&wp-preview=&hidden_post_status=draft&post_status=draft&hidden_post_password=&hidden_post_visibility=public&visibility=public&post_password=&mm=01&jj=25&aa=2014&hh=07&mn=18&ss=30&hidden_mm=01&cur_mm=01&hidden_jj=25&cur_jj=25&hidden_aa=2014&cur_aa=2014&hidden_hh=07&cur_hh=07&hidden_mn=18&cur_mn=18&original_publish=Publish&publish=Publish&post_format=0&post_category%5B%5D=0&post_category%5B%5D=1&newcategory=New+Category+Name&newcategory_parent=-1&_ajax_nonce-add-category=".$nonce_meta."&tax_input%5Bpost_tag%5D=".$data['tags']."&excerpt=&trackback_url=&metakeyinput=&metavalue=&_ajax_nonce-add-meta=".$nonce_meta."&advanced_view=1&comment_status=open&ping_status=open&post_name=&post_author_override=1";
		$this->request($post_data);
		return $post_ID;
	}

}

?> 