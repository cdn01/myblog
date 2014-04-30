<?php

include(str_replace("\\", "/", dirname(__FILE__))."/conn.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/qwb.php");  
$password='qingyu';
$user='723181190';   
$bot = new QwbBot("723181190");    
$mid = $bot->getlist(); 
print_r($mid);
?>

<script type='text/javascript'>
	 setTimeout("location.href='get_reply.php'",1000*30);
</script>