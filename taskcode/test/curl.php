<?php
	header("Content-type:text/html;charset=utf-8");
/**
 * 新浪微博接口WAP
 * 
 * @author phbin
 * @link http://phpbin.sinaapp.com/archives/247
 *
 */
class weibo
{
	/**
	 * PHP curl头部分
	 * 
	 * @var array
	 */
	private $_header;
	
	/**
	 * 通讯cookie
	 * 
	 * @var string
	 */
	private $_cookie;
	
	
	/**
	 * 初始化，设置header
	 */
	public function __construct()
	{
		$this->_header = array();
		$this->_header[] = "Host:m.weibo.cn";
		$this->_header[] = "Referer:https://m.weibo.cn/login";
	}
	
	/**
	 * 用户登录
	 * 结构 $param = array('uname'=>'', 'pwd'=>'');
	 * 
	 * @param array $param
	 * @return boolean
	 */
	public function login($param)
	{
		$url = 'https://m.weibo.cn/login';
		$post = 'check=1&uname='.urlencode($param['uname']).'&pwd='.$param['pwd'].'&backURL=&autoLogin=1';
		$stream = $this->_html($url, $post);
		
		$this->_cookie($stream);
		// 判断是不是已经登录
		return strpos($this->_cookie, 'gsid_CTandWM=deleted') === FALSE;
	}
	
	/**
	 * 搜索用户信息
	 * 
	 * @param string $uname  昵称
	 * @return array | bool
	 */
	public function searchUser($uname)
	{
		$url = 'http://m.weibo.cn/searchs/user?q='.urlencode($uname);
		$stream = $this->_html($url, false);
		
		// 提取JSON
		preg_match("/\{(.*?)\}$/i", $stream, $matches);
		$arr = json_decode($matches[0], true);
		
		// 搜索结果为空
		if ( $arr['total_number'] == 0 ) return false;
		
		// 获取UID
		$uid = (int)$arr['data'][0]['uid'];
		return $this->getUserByUid($uid);
	}
	
	/**
	 * 按UID取用户信息
	 * 
	 * @param integer $uid
	 * @return array
	 */
	public function getUserByUid($uid)
	{
		$url = 'http://m.weibo.cn/users/'.$uid;
		$stream = $this->_html($url, false);
		
		// 提取相关信息
		$uInfo = array();
		$uInfo['uid'] = $uid;
		preg_match_all('/<div class="item-info-page"><span>(.*?)<\/span><p>(.*?)<\/p><\/div>/is', $stream, $matches);
		foreach ( $matches[2] as $key=>$val)
		{
			if ( $val) {
				$index = trim(strip_tags($matches[1][$key]));
				
				// 博客地址处理
				if ( strpos($index, 'QQ') !== false)  {
					$index = 'QQ';
				}
				
				$uInfo[$index] = $val;
			}
		}
		return $uInfo;
	}
	
	/**
	 * 从Stream中提取cookie
	 * 
	 * @param string $stream
	 */
	private function _cookie($stream)
	{
		preg_match_all("/Set-Cookie: (.*?);/is", $stream, $matches);		
		$this->_cookie = @implode(";", $matches[1]);
	}
	
	/**
	 * 获取Stream
	 * 
	 * @param string $url
	 * @param string $post
	 * @return mixed
	 */
	private function _html($url, $post = FALSE)
	{
		ob_start();
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->_header);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		if ( $post){
			curl_setopt($ch, CURLOPT_POST, true);
		  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		
		if ( strpos($url, 'https') !== false) {
		  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		}
		curl_setopt($ch, CURLOPT_COOKIE, $this->_cookie);
		curl_exec($ch);
		curl_close($ch);
		$_str = ob_get_contents();
		$_str = str_replace("script", "", $_str);
		
		ob_end_clean();
		return $_str;
	}
	public function sendWeibo($message)
	{ 
		$url = "http://m.weibo.cn/mblogDeal/addAMblog?uicode=20000060";
		$stream = $this->_html($url, "content=".urlencode($message)."&pic=");
		
	}
}
?>
<?php
$param = array();
$param['uname'] = 'cdn_01@126.com';
$param['pwd'] = 'qingyu';
$wb = new weibo();
// 登录
$wb->login($param);
// 获取用户信息
$rs = $wb->searchUser("phpbin");
print_r($rs);
$wb->sendWeibo("1032 重新开始啦啦啦");
?>
