<?php
require_once "../sysconfig.php";
$data = new stdClass();
/****/
function get_basename($filename){
    return preg_replace('/^.+[\\\\\\/]/', '', $filename);
}

if($_FILES["file"]["name"]!=NULL){
	//add image 
	$target_dir = "/uploads_img/";
	$target_file = $target_dir . get_basename(rtrim($_FILES["file"]["name"], '/'));
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	/*
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["file"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$data->error="File is not an image.";
			$uploadOk = 0;
		}
	}
	*/
	// Check if file already exists
	if (file_exists($root_dir.$target_file)) {
		date_default_timezone_set("Asia/Taipei");
		$target_file=str_replace(".".$imageFileType,"-".date("Y-m-d H:i:s")."-".rand(0,9).".".$imageFileType,$target_file);
		$uploadOk = 1;
	}
	// Check file size
	if ($_FILES["file"]["size"] > 5000000) {
		$data->error=$data->error."Sorry, your file is too large.";
		$uploadOk = 0;
	}
	$data->DBname = $_FILES['file']['name'];
	$data->DBimg_url = $target_file;
	$data->DBitem_id = $_POST["item_id"];
	//url to DB
	if($uploadOk==1){	
		$sql = "INSERT INTO `jangsc27_cs_js`.`item_drop_img` (item_id,url) VALUES(?,?)";
		$sth = $db->prepare($sql);
		
		$sth->bindParam(1, $_POST["item_id"]);
		$sth->bindParam(2, $target_file);
		
		$sth->execute(array($_POST["item_id"],$target_file));

	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$data->error=$data->error."Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["file"]["tmp_name"],$root_dir.$target_file)) {
			chmod($root_dir.$target_file,0755); 
			$data->message="The file ". basename( $_FILES["file"]["tmp_name"]). " has been uploaded.";
		} else {
			$data->error=$data->error."Sorry, there was an error uploading your file.";
		}
	}
}

/****/
$data->name=$_FILES['file']['name'];
//$data->error="File is not an image.";
echo json_encode($data);
?>