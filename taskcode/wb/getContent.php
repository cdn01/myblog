<?php
include(str_replace("\\", "/", dirname(__FILE__))."/conn.php");

// for ($index=0;$index<5;$index++){

$sql = "select * from diffbot order by used desc limit 1";
$diffbot = query($sql);
print_r($diffbot);
$sql = "select * from article where isget!=999 order by isget asc  limit 1";
$rs = query($sql);

echo $url = "http://api.diffbot.com/v2/article?token=".$diffbot[0]["token"]."&url=".$rs[0]["link"]; 
$content = getHtml($url);
$content_arr = json_decode($content,true);
//print_r($content_arr);
echo "\n".strlen($content_arr['text'])."\n";
if(strlen($content_arr['text'])>200)
{
	$sql ="update article set isget=999,title  ='".str_conv($content_arr['title'])."' ,content ='".str_conv($content_arr['text'])."'  , gettime='".date("Y-m-d H:i:s",strtotime($content_arr['date']))."' where id ='".$rs[0]['id']."'";
	if(mysql_query($sql))
	{
		//更新diffbot使用
		$sql = "update diffbot set used = used +1 where id = ".$diffbot[0]['id'];
		mysql_query($sql);

	}	
	foreach (@$content_arr["images"] as $key => $value) {
		if($value['url']!="http://simg.sinajs.cn/blog7style/images/common/sg_trans.gif")
		{
			$sql = "insert into images (aid , src ) values ('".$rs[0]['id']."','".$value['url']."')" ;
			if(mysql_query($sql))
			{ 
				$img = file_get_contents($value['url']);
				$dir = WWW."image/aid_".$rs[0]['id']."_".$key.".jpg";
				file_put_contents($dir, $img);
				$sql = "update images set dir='".$dir."' where id=".mysql_insert_id();
				mysql_query($sql);	
			}
			
		}
	}
}else{
	$sql ="update article set isget=1 where id ='".$rs[0]['id']."'";
	mysql_query($sql);
}
sleep(20);
// }
die("over");
	

?>