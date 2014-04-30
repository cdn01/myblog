<?php 
/**
 * 新浪微博接口WAP
 *
 * @author phbin
 * @link http://phpbin.sinaapp.com/archives/247
 *
 */
class CnwbBot
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
        public $cookie=""; 
        public $cookie_dir = "";

        /**
         * 初始化，设置header
         */
        public function __construct($userid)
        {
                $this->_header = array();
                $this->_header[] = "Host:m.weibo.cn";
                $this->_header[] = "Referer:https://m.weibo.cn/login";
                $this->cookie_dir = "./cookie/cookie_".$userid.".txt";
                $this->cookie = str_replace("\\", "/", dirname(__FILE__))."/cookie/cookie_".$userid.".txt"; 
                if(!file_exists($this->cookie_dir)) {  
                  file_put_contents($this->cookie_dir, "");
                }
        }
        public function getCookieContent(){
          return file_get_contents($this->cookie_dir);
        }
        function slog($accoutn)
        {  
          file_put_contents("./log/log_".date("Y_d_m",time()).".txt", "\n-----------".date("Y-d-m H:i:s",time())."-----------\n".$accoutn , FILE_APPEND);
        }
        public function login($param)
        {
                $cookie_file = $this->getCookieContent();
                if(strpos($cookie_file, "gsid_CTandWM\tdeleted")||$cookie_file==""){ 
                  $this->slog($param['uname']); 
                  $url = 'https://m.weibo.cn/login'; 
                  $post = 'check=1&backURL=https%3A%2F%2Fm.weibo.cn%2F&uname='.urlencode($param['uname']).'&pwd='.$param['pwd'].'&autoLogin=1';
                  $stream = $this->_html($url, $post); 
                  // $this->_cookie($stream);
                  // 判断是不是已经登录
                  // return strpos($this->_cookie, 'gsid_CTandWM=deleted') === FALSE;
              } 
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
        public function _html($url, $post = FALSE)
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
                curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookie);
                curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookie);
                curl_exec($ch);
                curl_close($ch);
                $_str = ob_get_contents(); 
                $_str = str_replace("script", "", $_str);

                ob_end_clean();
                return $_str;
        }
        public function fileGetContents($url, $post = FALSE)
        {
                ob_start();
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER,0);
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
        public function sendWeibo($message,$pic="")
        {
          $sendMessageUrl = "http://m.weibo.cn/mblogDeal/addAMblog?uicode=20000060";
            // echo $pic;
            // echo "\n";
            $stream = "";
            if($pic=="")
            { 
                  $stream = $this->_html($sendMessageUrl, "content=".urlencode($message)."&pic=".$pic);
            }else{
                $picPostUrl = "http://m.weibo.cn/mblogDeal/addPic?id=0";
                echo $data["pic"] = '@'.$pic;
                    echo "\n";
                $stream = $this->_html($picPostUrl, $data);
                    sleep(2);
                preg_match("/preview\(1,'(.*)'/iUs", $stream,$pic_p);
                echo $picFile = $pic_p[1];
                    echo "\n";
                $picArr = explode("/", $pic);
                    echo $picArr[count($picArr)-1];
                    echo "\n";
                $stream = $this->_html($sendMessageUrl, "content=".urlencode($message)."&picFile=".$picFile."&pic=".$picArr[count($picArr)-1]);
            } 
            return $stream;
        }


        public function sendHeart($id){
           $url = "http://m.weibo.cn/attitudesDeal/add?uicode=10000011&ext=sourceType%3A";
           $postdata = "id=".$id."&attitude=heart";
           return $this->_html($url,$postdata);
        }

        public function sendFollow($id,$type="heart")
        {
            $url = "";
            $postdata = "";
            switch ($type) {
                case 'heart':
                    $url = "http://m.weibo.cn/attitudesDeal/add?uicode=10000011&ext=sourceType%3A";
                    $postdata = "id=".$id."&attitude=heart";
                    break;
                case 'heart':
                    
                    break;
                default: 
                    break;
            }
            return $this->_html($url,$postdata);
        } 
        public function reply($id,$message){
            echo $url = "http://m.weibo.cn/commentDeal/addCmt?id=$id&uicode=20000060";
            echo $postdata = "content=".urlencode($message)."&rt=1";
            return $this->_html($url,$postdata);
        }
        public function get_uid(){
            $url = "https://m.weibo.cn/";
            echo  $home_content =  $this->_html($url);    
            preg_match("/href=\"\/u\/(.*)\"/iU", $home_content , $uid_m);
            return $uid_m[1];
        }
        public function huati(){
           echo "  <hr>"; 
           echo  $url = "http://huati.weibo.com/aj_topiclist/small?_pv=1&ctg1=99&ctg2=0&prov=0&sort=day&p=1&t=1&_t=0&__rnd=".time();
           echo "  <hr>";
            echo $this->_html($url); 
        }
}
?>
