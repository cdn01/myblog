<?php

include 'header.php';
$offset = isset($_REQUEST["offset"])?$_REQUEST["offset"]:0;
$sql = "select * from yahoo_article order by id limit $offset,1";
$article = query($sql);

println("<a href='article.php?offset=".($offset+1)."'>Next</a>");
println($article[0]['title']);
println($article[0]['content']);
$content = format($article[0]['content'],$article[0]['link']);
println("<a href='".$article[0]['link']."' target='_blank'>".$article[0]['link']."</a>");

function format($article,$link){
	$article = preg_replace("/<a(.*)data-uuid(.*)data-pos(.*)data-ylk(.*)>(.*)<div class=\"img-wrap\">(.*)<\/a>/iUs", "", $article);
	$article = preg_replace("/style=[\'|\"](.*)[\'|\"]/iUs", "", $article);
	$article = preg_replace("/<div(.*)>/iUs", "<div>", $article);
// 	$article = preg_replace("/<div(.*)>/iUs", "<p>", $article);
// 	$article = preg_replace("/<\/div(.*)>/iUs", "</p>", $article); 
	$article = preg_replace("/<ul(.*)id=\"topics\"(.*)>(.*)<\/ul>/iUs", "", $article);
	$article = preg_replace("/class=[\'|\"](.*)[\'|\"]/iUs", "", $article);
	$article = preg_replace("/id=[\'|\"](.*)[\'|\"]/iUs", "", $article);
	$article = preg_replace("/<a(.*)>/iUs", "<b class='a_focus'>", $article);
	$article = preg_replace("/<\/a(.*)>/iUs", "</b>", $article);
	$host = substr($link, 0,strpos($link, ".com")+4);
	$article = preg_replace("/src=\"\//iUs", 'src="'.$host.'/', $article);
	
	$image = "";
	if(preg_match("/<meta itemprop=\"image\" content=\"(.*)\"\/>/iUs", $article,$matches)){
		$image = $matches[1];
	}
	
// 	$article = preg_replace("/<img(.*)>/iUs", "", $article);
	$article = preg_replace("/<meta(.*)>/iUs", "", $article);
	$article = trim($article);
	if(strpos($article, "<b>More Stories From")){
		$article = substr($article, 0,strpos($article, "<b>More Stories From"));
	}
	if(strpos($article, "<h3>Read more from")){
		$article = substr($article, 0,strpos($article, "<h3>Read more from"));
	}
	println($image);
	println($article);
	return $article;
}