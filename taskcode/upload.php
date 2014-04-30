<?php
//$file = fopen("key.csv","r");
//$data = array();
//while(! feof($file))
//{
//	$row = fgetcsv($file);
//	$data[] = explode("\t", $row[0]);
//}
//
//fclose($file);
////print_r($data);
//print_r($_REQUEST);
if(!empty($_REQUEST['file'])&&isset($_REQUEST["file"])){
	print_r( $_REQUEST["file"]);
	if ($_FILES["file"]["error"] > 0)
	{
		echo "Error: " . $_FILES["file"]["error"] . "<br />";
	}
	else
	{
		echo "Upload: " . $_FILES["file"]["name"] . "<br />";
		echo "Type: " . $_FILES["file"]["type"] . "<br />";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
		echo "Stored in: " . $_FILES["file"]["tmp_name"];
	}
}

?>

<form action="./upload.php" method="post"
enctype="multipart/form-data">
<label for="file">Filename:</label>
<input type="file" name="file" id="file" /> 
<br />
<input type="submit" name="submit" value="Submit" />
</form>
