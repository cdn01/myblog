<?php
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-7)."/config.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php"); 
class emblog extends CnwbBot{
	public $account = "backcn";
	public $psw = "qingyu2007";
	public $host = "http://localhost/emlog/admin";	
	public function __construct($host="http://localhost/emlog",$account="backcn",$psw="qingyu2007",$userid="emblog_Login"){
		$this->account = $account;
		$this->psw = $psw;
		$this->host = $host;	
		$this->_header = array();
        $this->_header[] = $host."/admin/";
        $this->_header[] = "Referer:".$host;
        $this->cookie_dir = "./cookie/cookie_".$userid.".txt";
        $this->cookie = str_replace("\\", "/", dirname(__FILE__))."/cookie/cookie_".$userid.".txt"; 
        if(!file_exists($this->cookie_dir)) {  
          file_put_contents($this->cookie_dir, "");
        }
        $login = $this->login();
        print_r($login);
        print_r($this->_cookie);
	}
	public function login(){ 
		echo "<br><hr>";
		echo $post_url = $this->host."/admin/index.php?action=login";
		echo "<br><hr>";
		echo $post_data = "user=".$this->account."&pw=".$this->psw."&ispersis=1";
		echo "<br><hr>";
		return $this->_html($post_url,$post_data); 
	}
	public function postA($title,$content,$tag,$cat=1,$istop="",$desc=""){
		$content = preg_replace("/<div(.*)>/iUs", "<div>", $content);
		$post_url = $this->host."/admin/save_log.php?action=add";
		$post_data = "title=".$title."&as_logid=-1&content=".$content."&tag=".$tag."&sort=".$cat."&postdate=".date("Y-m-d H:i:s",time())."&date=&excerpt=".$desc."&alias=&password=&top=".$istop."&allow_remark=y&ishide=&author=1";
		$html = $this->_html($post_url,$post_data); 
		print_r($html); 
		// preg_match("/bencandy.php\?fid=(.*)&aid=(.*)'/iUs", $html, $matches);
		// return array("fid"=>$matches[1],"aid"=>$matches[2]);
	}
}

?>