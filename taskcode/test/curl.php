<?php
	header("Content-type:text/html;charset=utf-8");
/**
 * ����΢���ӿ�WAP
 * 
 * @author phbin
 * @link http://phpbin.sinaapp.com/archives/247
 *
 */
class weibo
{
	/**
	 * PHP curlͷ����
	 * 
	 * @var array
	 */
	private $_header;
	
	/**
	 * ͨѶcookie
	 * 
	 * @var string
	 */
	private $_cookie;
	
	
	/**
	 * ��ʼ��������header
	 */
	public function __construct()
	{
		$this->_header = array();
		$this->_header[] = "Host:m.weibo.cn";
		$this->_header[] = "Referer:https://m.weibo.cn/login";
	}
	
	/**
	 * �û���¼
	 * �ṹ $param = array('uname'=>'', 'pwd'=>'');
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
		// �ж��ǲ����Ѿ���¼
		return strpos($this->_cookie, 'gsid_CTandWM=deleted') === FALSE;
	}
	
	/**
	 * �����û���Ϣ
	 * 
	 * @param string $uname  �ǳ�
	 * @return array | bool
	 */
	public function searchUser($uname)
	{
		$url = 'http://m.weibo.cn/searchs/user?q='.urlencode($uname);
		$stream = $this->_html($url, false);
		
		// ��ȡJSON
		preg_match("/\{(.*?)\}$/i", $stream, $matches);
		$arr = json_decode($matches[0], true);
		
		// �������Ϊ��
		if ( $arr['total_number'] == 0 ) return false;
		
		// ��ȡUID
		$uid = (int)$arr['data'][0]['uid'];
		return $this->getUserByUid($uid);
	}
	
	/**
	 * ��UIDȡ�û���Ϣ
	 * 
	 * @param integer $uid
	 * @return array
	 */
	public function getUserByUid($uid)
	{
		$url = 'http://m.weibo.cn/users/'.$uid;
		$stream = $this->_html($url, false);
		
		// ��ȡ�����Ϣ
		$uInfo = array();
		$uInfo['uid'] = $uid;
		preg_match_all('/<div class="item-info-page"><span>(.*?)<\/span><p>(.*?)<\/p><\/div>/is', $stream, $matches);
		foreach ( $matches[2] as $key=>$val)
		{
			if ( $val) {
				$index = trim(strip_tags($matches[1][$key]));
				
				// ���͵�ַ����
				if ( strpos($index, 'QQ') !== false)  {
					$index = 'QQ';
				}
				
				$uInfo[$index] = $val;
			}
		}
		return $uInfo;
	}
	
	/**
	 * ��Stream����ȡcookie
	 * 
	 * @param string $stream
	 */
	private function _cookie($stream)
	{
		preg_match_all("/Set-Cookie: (.*?);/is", $stream, $matches);		
		$this->_cookie = @implode(";", $matches[1]);
	}
	
	/**
	 * ��ȡStream
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
// ��¼
$wb->login($param);
// ��ȡ�û���Ϣ
$rs = $wb->searchUser("phpbin");
print_r($rs);
$wb->sendWeibo("1032 ���¿�ʼ������");
?>
