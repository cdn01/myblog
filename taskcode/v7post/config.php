<?php 
include(str_replace("\\", "/", dirname(__FILE__))."/CnwbBot.php"); 
include(substr(str_replace("\\", "/", dirname(__FILE__)), 0,-7)."/config.php"); 
//config 
class v7 extends  CnwbBot{
	public $account = "backcn";
	public $psw = "qingyu2007";
	public $host = "http://localhost/v7/admin";	
	public function __construct($account,$psw,$host,$userid){
		$this->account = $account;
		$this->psw = $psw;
		$this->host = $host;	
		$this->_header = array();
        $this->_header[] = "Host:m.weibo.cn";
        $this->_header[] = "Referer:https://m.weibo.cn/login";
        $this->cookie_dir = "./cookie/cookie_".$userid.".txt";
        $this->cookie = str_replace("\\", "/", dirname(__FILE__))."/cookie/cookie_".$userid.".txt"; 
        if(!file_exists($this->cookie_dir)) {  
          file_put_contents($this->cookie_dir, "");
        }
	}
	public function login(){
		$post_url = $this->host."/admin/";
		$post_data = "loginname=".$this->account."&loginpwd=".$this->psw."&Submit.x=50&Submit.y=22";
		return $this->_html($post_url,$post_data); 
	}
	public function postA($fid,$title,$content,$picurl,$keywords,$post_top=""){
		$post_url = $this->host."/admin/index.php?lfj=post&job=postnew&step=post";
		$post_data = $post_top."ExplodePage=-1&PageNum=3000&Submit=".urlencode(" 提 交 ")."&aid=&fid=$fid&hiddenField=0&i_id=&mid=0&only=1&picHeight=225&picWidth=300&postdb[author]=&postdb[automakesmall]=1&postdb[bak_id]=&postdb[begintime]=&postdb[content]=$content&postdb[copyfrom]=&postdb[copyfromurl]=&postdb[description]=&postdb[endtime]=&postdb[hits]=&postdb[htmlname]=&postdb[keywords]=$keywords&postdb[money]=&postdb[passwd]=&postdb[picurl]=$picurl&postdb[posttime]=&postdb[smalltitle]=&postdb[style]=&postdb[subhead]=&postdb[title]=$title&postdb[titlecolor]=&postdb[tpl][bencandy]=&postdb[tpl][foot]=&postdb[tpl][head]=&postdb[yz]=1&rid=&select=&select2=&textfield=1&vote_db[about]=&vote_db[begintime]=&vote_db[endtime]=&vote_db[forbidguestvote]=0&vote_db[limitip]=0&vote_db[limittime]=&vote_db[name]=&vote_db[type]=1&vote_db[votetype]=0";
		$html = $this->_html($post_url,$post_data); 
		preg_match("/bencandy.php\?fid=(.*)&aid=(.*)'/iUs", $html, $matches);
		return array("fid"=>$matches[1],"aid"=>$matches[2]);
	}

}



function get_posttop($tag){
	$sql = "select * from auto_article where tag='".$tag."' and post_top = 1 ;";
	$exist = query($sql);
	$sql = "update auto_article set post_top = 1 where tag='".$tag."' ;";
	mysql_query($sql);
	if(isset($exist[0]['id'])){
		return "";
	}else{ 
		return "postdb[top]=1&";
	}
}
?>
 