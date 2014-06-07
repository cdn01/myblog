<?php
header("Content-type:text/html;charset=utf-8");
include(str_replace("\\", "/", dirname(__FILE__))."/Snoopy.class.php"); 
include(str_replace("\\", "/", dirname(__FILE__))."/config.php"); 
 
 

$sql = "select * from qidian_article where isget =1 and ispost=0 order by id asc";
$cmd = mysql_query($sql);
while($rs = mysql_fetch_array($cmd))
{
	$admin_url = "http://www.shenhuangji.com/";
	$snoopy = new  Snoopy();
	login($admin_url);
	$snoopy->fetch($admin_url."wp-admin/");
	$snoopy->results;
	post($rs['title'],$rs['content'],$admin_url);
	$sql = "update qidian_article set ispost = '1'  where id = '".$rs['id']."'   ";
	mysql_query($sql);
	echo $rs['title']; 	
}

echo "end";

////////////////////////////////////////////////////////////////////////
function login($url)
	{
	/*
			 * */
		global $snoopy;
		$post_url = $url."wp-login.php";
		$post_data = array("log"=>"backcn",
							"pwd"=>"qingyu2007",
							"redirect_to"=>$url."wp-admin/",
							"testcookie"=>"1",
							"wp-submit"=>"登录");
		$snoopy->submit($post_url,$post_data);
	}
	function post($title,$content,$url)
	{
		global $snoopy;
		$new_post_url = $url."wp-admin/post-new.php";
		$snoopy->fetch($new_post_url); 
		preg_match("/id=\"_wpnonce\" name=\"_wpnonce\" value=\"(.*)\"/iUs", $snoopy->results,$_wpnonce_p);
		preg_match("/id='post_ID' name='post_ID' value='(.*)'/iUs", $snoopy->results,$post_ID_p); 
		$post_url = $url."wp-admin/post.php";
		$post_data  = array("_ajax_nonce-add-category"=>"04ab99043c",
							"_ajax_nonce-add-meta"=>"1e41de3bfb",
							"_wp_http_referer"=>"/wp-admin/post-new.php",
							"_wp_original_http_referer"=>$url."wp-admin/",
							"_wpnonce"=>$_wpnonce_p[1],
							"aa"=>"2013",
							"aiosp_description"=>$title,
							"aiosp_edit"=>"aiosp_edit",
							"aiosp_keywords"=>$title, 
							"action"=>"editpost",
							"advanced_view"=>"1",   
							"aiosp_title"=>"",
							"auto_draft"=>"0",
							"autosavenonce"=>"12f7b2e897",
							"closedpostboxesnonce"=>"c543f1b967",
							"comment_status"=>"open",
							"content"=>$content,
							"cur_aa"=>"2013",
							"cur_hh"=>"21",
							"cur_jj"=>"20",
							"cur_mm"=>"06",
							"cur_mn"=>"27",
							"excerpt"=>"",
							"hh"=>"21",
							"hidden_aa"=>"2013",
							"hidden_hh"=>"21",
							"hidden_jj"=>"20",
							"hidden_mm"=>"06",
							"hidden_mn"=>"27",
							"hidden_post_password"=>"",
							"hidden_post_status"=>"draft",
							"hidden_post_visibility"=>"public",
							"jj"=>"20",
							"length1"=>"0",
							"length2"=>"0",
							"meta-box-order-nonce"=>"f8a9e9376f",
							"metakeyinput"=>"",
							"metavalue"=>"",
							"mm"=>"06",
							"mn"=>"27",
							"newcategory"=>"新分类目录名",
							"newcategory_parent"=>"-1",
							"newtag[post_tag]"=>"",
							"nonce-aioseop-edit"=>"62123ec275",
							"original_post_status"=>"auto-draft",
							"original_publish"=>"发布",
							"originalaction"=>"editpost",
							"ping_status"=>"open",
							"post_ID"=>$post_ID_p[1],
							"post_author"=>"1",
							"post_author_override"=>"1",
							"post_category[]"=>"0",
							"post_name"=>"",
							"post_password"=>"",
							"post_status"=>"draft",
							"post_title"=>$title,
							"post_type"=>"post",
							"publish"=>"发布",
							"referredby"=>$url."wp-admin/",
							"samplepermalinknonce"=>"2be53aabad",
							"ss"=>"08",
							"tax_input[post_tag]"=>"",
							"trackback_url"=>"",
							"user_ID"=>"1",
							"visibility"=>"public",
							"wp-preview"=>"");
		$snoopy->submit($post_url,$post_data);
	}






?>







<script type='text/javascript'>
		setTimeout("location.href='post.php'",1000*60*5);
</script>











