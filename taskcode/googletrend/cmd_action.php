<?php
header("Content-type:text/html;charset=utf-8");

?>
<body></body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script> 

<script type="text/javascript">
$(document).ready(function(){
	$.get("http://localhost/myblog/taskcode/yahoo/get.php", function(data){
		$("body").html(data);
		setTimeout("location.href='cmd_action.php'",1000*30);
	});
});
</script> 
