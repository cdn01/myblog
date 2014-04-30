<?php
/**
* 
*/
 
class QwbBot
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
	function __construct($userid)
	{    
		$this->cookie_dir = "./cookie/cookie_".$userid.".txt";
		$this->cookie = str_replace("\\", "/", dirname(__FILE__))."/cookie/cookie_".$userid.".txt"; 
		if(!file_exists($this->cookie_dir)) {  
			file_put_contents($this->cookie_dir, "");
		}
		$this->pre_refer = "";
		$this->conn=curl_init(); 
		$this->setUserAgent($this->getUserAgent());
		$this->sid = $this->get_sid() ? $this->get_sid() : ""; 
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
	public function getToken(){ 
		$this->setUrl("https://mobile.twitter.com/session/new");
		echo $html=$this->request();
		preg_match("/input name=\"authenticity_token\" type=\"hidden\" value=\"(.*?)\"/", $html, $authenticity_token);
		print_r($authenticity_token);
		$this->token = $authenticity_token[1];	
	} 
	public function get_sid(){
		$cookie = $this->getCookieContent();
		preg_match("/mysid=(.*)=/iUs", $cookie, $sid_m);

		if(empty($sid_m[1])) return false; 
		return $this->sid = $sid_m[1];
	}
	public function login($user,$psw){
		$cookie_file = $this->getCookieContent();
		if(strpos($cookie_file, "mysid")===false){
			$html = $this->html("http://pt.3g.qq.com/s?aid=nLoginmb&g_ut=2&go_url=http%3A%2F%2Fti.3g.qq.com%2Fg%2Fs%3Faid%3Dloginjump%26g_ut%3D2%26coid%3D"); 
			preg_match("/action=\"(.*)\"/iU", $html,$action_m);
			$post_url = $this->setUrl($action_m[1]);
			$html = $this->request("qq=$user&pwd=$psw&goUrl=http=//ti.3g.qq.com/g/s?aid=loginjump&g_ut=2&coid=&q_from=mblog&r_sid=&sidtype=1&login_url=http=//pt.3g.qq.com/s?aid=nLogionmb&sid=AcM0Lhl651saAxB7gqEXaSK7&q_from=mblog&goUrl=http%3A%2F%2Fti.3g.qq.com%2Fg%2Fs%3Faid%3Dloginjump%26g_ut%3D2%26coid%3D&loginTitle=%E8%85%BE%E8%AE%AF%E5%BE%AE%E5%8D%9A");
			preg_match("/sid=(.*)&/iU", $html,$sid_m);  
			if(!empty($sid_m[1])){
				$this->setCookie($sid_m[1]);
				return $this->html("http://ti2.3g.qq.com/g/s?sid=".$sid_m[1]."&aid=3gen&g_f=1810&icfa=home_navi");
			}
			return false;
		}
	} 
	public function html($url){
		$this->setUrl($url);
		$html = $this->request();
		return $html;
	}
	public function getlist(){ 
		// echo "http://ti.3g.qq.com/ope/s?sid=".$this->sid."&r=".time()."&domain=ope&aid=i";
		$this->setUrl("http://ti.3g.qq.com/ope/s?sid=".$this->sid."&r=".time()."&domain=ope&aid=i");
		$html = $this->request();
		preg_match_all("/comment\_(.*)\_i/iU", $html, $mid_m);
		$mid = $mid_m[1];
		if(count($mid)<1){
			preg_match_all("/<a href=\"(.*)mid=(.*)&(.*)\">评论/iUs", $html, $mid_m);
			$mid = $mid_m[2];
		}
		foreach ($mid as $key => $value) {
				$sql = "insert into qq_reply (mid) values ('".$value."')";
				mysql_query($sql);
		} 
		return $mid; 
	}


	public function create($mid,$msg){   
		// $html = $this->html("http://ti.3g.qq.com/ope/s?sid=".$this->sig."&r=".rand(11111,99999)."&domain=bas&aid=i");
		echo $url = "http://ti.3g.qq.com/g/s?sid=".$this->sid."&r=".rand(11111,99999)."&domain=ope&aid=mfh&omid=".$mid."&obid=i&lp=440,440,3,0,3&";
		// $this->setUrl($url);
		echo $html = $this->html($url);
		// preg_match("/action=\"(.*)\"/iUs", $html, $maches); 
		// $post_url = "http://ti.3g.qq.com".$maches[1]; 
		$post_url = "http://ti.3g.qq.com/g/s?sid=".$this->sid."&r=".rand(10000,999999)."&lp=440,440,3,0,3,f&aid=amsg&bid=i#spt_domain#ope|mfh#".$mid."#_#_#_#_#_&fraid=i#spt_domain#ope&mdr=false"; 
		$this->setUrl($post_url);
		$post_data = "msg=".$msg."&ac=54&mid=".$mid."&fu=qwerty2436711715&confirm=评论并转播";
		return $this->request($post_data); 
		// echo $post_url = "http://ti.3g.qq.com/g/s?sid=".$this->sig."&r=".rand(10000,999999)."&lp=440,440,3,0,3,f&aid=amsg&bid=i#spt_domain#ope|mfh#".$mid."#_#_#_#_#_&fraid=i#spt_domain#ope&mdr=false"; 
		// echo "\n";
		// echo $post_data = "msg=".$msg."&ac=54&mid=".$mid."&fu=qwerty2436711715&confirm=评论并转播";
		// return $this->request($post_data);
	}

}

?> 