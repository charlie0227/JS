<?php
$data = new stdClass();
/****/
if($_file["file"]["name"]!=NULL){
	//add image 
	$target_dir = "uploads/store/";
	$target_file = $target_dir . basename($_file["file"]["name"]);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	// Check if image file is a actual image or fake image
	/*
	if(isset($_POST["submit"])) {
		$check = getimagesize($_file["file"]["tmp_name"]);
		if($check !== false) {
			$uploadOk = 1;
		} else {
			$data->error="File is not an image.";
			$uploadOk = 0;
		}
	}
	*/
	// Check if file already exists
	if (file_exists($target_file)) {
		date_default_timezone_set("Asia/Taipei");
		$target_file=str_replace(".".$imageFileType,"-".date(ymdhis)."-".rand(0,9).".".$imageFileType,$target_file);
		$uploadOk = 1;
	}
	// Check file size
	if ($_file["file"]["size"] > 5000000) {
		$data->error=$data->error."Sorry, your file is too large.";
		$uploadOk = 0;
	}
	
	//url to DB
	if($uploadOk==1){
		$size = getimagesize($_file['file']['tmp_name']);
		$size = $size[3];
		$name = $_file['file']['name'];
		$imgfp = $target_file;
		
		
		$sql = "INSERT INTO `jangsc27_cs_project`.`store_image` (store_id,image_url,image_size,image_name) VALUES(?,?,?,?)";
		$sth = $db->prepare($sql);
		
		$sth->bindParam(1, $store_id);
		$sth->bindParam(2, $imgfp);
		$sth->bindParam(3, $size);
		$sth->bindParam(4, $name);
		
		$sth->execute(array($store_id,$imgfp,$size,$name));

	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		$data->error=$data->error."Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_file["file"]["tmp_name"],$root_dir.$target_file)) {
			chmod($root_dir.$target_file,0755); 
			$data->message="The file ". basename( $_file["file"]["name"]). " has been uploaded.";
		} else {
			$data->error=$data->error."Sorry, there was an error uploading your file.";
		}
	}
}

/****/
$data->name=$_file['file']['name'];
$data->error="File is not an image.";
echo json_encode($data);
?>