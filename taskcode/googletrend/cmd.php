<?php
	include(str_replace("\\", "/", dirname(__FILE__))."/lib/curl.class.php");
    include(str_replace("\\", "/", dirname(__FILE__))."/lib/config.php");
    
    $sql = "select * from google_trends_country order by dataid asc ";
    $country_arr = query($sql);
    $_t = 1;
?>
<html>
<head>
<style type="text/css">
	.cmd{ width:100%;height:50px;float:left;border: 1px solid #000;margin:0 auto 3px;}
	.cmd iframe{width:100%;height:100%;border:none;}
	.pannel{width:100%;height:100%; }
</style>
</head>
<body>

<div class="list_country">
<?php 
	foreach ($country_arr as $key=>$val){
?>
	<input type="checkbox" name="dataid" value="<?php echo $val["dataid"];?>" /><span id="country_<?php echo $val["dataid"];?>"><?php echo $val['country'];?></span>
<?php 	
	}
?>
	<br>
	<input type="button" name="" value="全选" id="all_btn" />
	<input type="button" name="" value="撤销" id="none_btn"/>
	<br>
	<input type="button" name="" value="启动" id="start_btn"/>
</div>
<div class="pannel"></div>
</body>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js" type="text/javascript"></script> 
<script type="text/javascript">
$(function(){
	var cmd_dom = "";
	var all_btn = $("#all_btn");
	var none_btn = $("#none_btn");
	var start_btn = $("#start_btn"); 
	var list_country = $(".list_country");
	var dataid = $(".list_country input");
	var country = $(".list_country span");
	country.each(function(i){
		var index = i; 
		$(this).bind("click",function(){
			var dataid_t = $(".list_country input:eq("+index+")");
			dataid_t.attr("checked")==true?dataid_t.attr("checked",''):dataid_t.attr("checked",'true');
		});
	});
	all_btn.bind("click",function(){
		$("[name='dataid']").attr("checked",'true');//全选
	});
	none_btn.bind("click",function(){
		$("[name='dataid']").attr("checked",'');//全选
	});
	start_btn.bind("click",function(){
		cmd_dom = "";
		$("input[name='dataid'][checked]").each(function(index){
			var c = $(this).val(); 
			var timestamp = Date.parse(new Date()); 
			cmd_dom += "<div class=\"cmd\"><iframe src=\"test.php?_t="+index+"&c="+c+"\"></iframe></div>";
			$(".pannel").html(cmd_dom)
		}); 
		$(".pannel").html(cmd_dom);
	});
	$(document).ready(function(){  
	});
})
</script>
</html>