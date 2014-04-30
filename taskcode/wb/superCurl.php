<?php 
/**
 * 新浪微博接口WAP
 *
 * @author phbin
 * @link http://phpbin.sinaapp.com/archives/247
 *
 */
class SuperCurl
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
        public $_cookie;


        /**
         * 初始化，设置header
         */
        public function __construct($Host,$Referer)
        {
                $this->_header = array();
                $this->_header[] = $Host;
                $this->_header[] = $Referer;
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
                $post = 'check=1&backURL=https%3A%2F%2Fm.weibo.cn%2F&uname='.urlencode($param['uname']).'&pwd='.$param['pwd'].'&autoLogin=1';
                echo $stream = $this->_html($url, $post); 
                $this->_cookie($stream);
                // 判断是不是已经登录
                return strpos($this->_cookie, 'gsid_CTandWM=deleted') === FALSE;
        }

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
        public function html($url, $post = FALSE)
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
                ob_end_clean();
                return $_str;
        }

}
?>
