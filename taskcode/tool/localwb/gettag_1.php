<?php
require 'header.php';

$ctg = (!empty($_REQUEST['ctg1'])&&isset($_REQUEST['ctg1']))?$_REQUEST['ctg1']:"1";
$url = "http://huati.weibo.com/aj_topiclist/big?_pv=1&ctg1={$ctg}&ctg2=1&prov=0&sort=time&p=1&_t=0&__rnd=".time();
$html = json_decode(html($url,false,true),true);

$data = $html['data']['html'];
preg_match_all("/#(.*)#/iUs",$data,$matches);
$tags = $matches[1];

preg_match_all("/<span class=\"num_info\">(.*)<\/span>/iUs", $data, $zhishu_m);
$zhishu = $zhishu_m[1];

println($tags); 
println($zhishu);
foreach ($tags as $k=>$t){
	if(strpos($t, "</")) continue;
	echo $sql = "insert into huati (huati,zhishu,gettime) values ('".$t."','".$zhishu[$k]."','".date("Y-m-d H:i:s",time())."')";
	mysql_query($sql);
}

?>
<script type='text/javascript'>		
 	setTimeout("location.href='gettag_1.php?ctg1=<?php echo ++$ctg; ?>'",1000*5);
 </script> 