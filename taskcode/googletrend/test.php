<?php
header("Content-type:text/html;charset=utf-8");

?>
<body></body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script> 

<script type="text/javascript">
$(document).ready(function(){
	$.get("google_trends.php?_t=<?php echo microtime() ;?>&c=<?php echo $_REQUEST['c'];?>", function(data){
		$("body").html("<?php echo $_REQUEST["c"];?>");
		setTimeout("location.href='test.php?_t=<?php echo time();?>&c=<?php echo $_REQUEST['c'];?>'",1000*60*<?php echo rand(30,60);?>);
	});
});
</script> 
