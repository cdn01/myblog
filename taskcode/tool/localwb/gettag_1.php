<?php
require 'header.php';
if((!empty($_REQUEST['p'])&&isset($_REQUEST['p']))){
	$page = $_REQUEST['p'];
	if($page>7){
print <<<EOT
<script type='text/javascript'>		
 	setTimeout("location.href='gettag_1.php?p=1'",1000*60*30);
 </script> 
EOT;
exit(0);
	}
}else{
	$page = 1;
}
$url = "http://huati.weibo.com/aj_topiclist/small?_pv=1&ctg1=99&ctg2=0&prov=0&sort=time&p={$page}&_t=0&__rnd=".time();
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
 	setTimeout("location.href='gettag_1.php?p=<?php echo ++$page; ?>'",1000*10);
 </script> 