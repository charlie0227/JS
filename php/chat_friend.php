<?php
require_once "../sysconfig.php";
$data = new stdClass();

$way=isset($_POST['way'])?$_POST['way']:'';
$id=isset($_SESSION['id'])?$_SESSION['id']:'';
if($way=="friend_list"){
	$sql = "SELECT * FROM `jangsc27_cs_js`.`friend` WHERE `my_id` = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($id));
	$result = $sth->fetchAll();
	if($result){
		$data->result=$result;
		$data->message="OK";
		$data->error=0;
	}
	else{
		$data->result = "";
		$data->message="No Friends found";
		$data->error=1;
	}
}
if($way=="save_friend_name"){
	$new_value=isset($_POST['value'])?$_POST['value']:'';
	$friend_id=isset($_POST['friend_id'])?$_POST['friend_id']:'';
	$sql = "UPDATE `jangsc27_cs_js`.`friend` SET `friend_name` = ? WHERE `my_id` = ? AND `friend_id` = ?";
	$sth = $db->prepare($sql);
	if($sth->execute(array($new_value,$id,$friend_id))){
		$data->message = "Update Success";
		$data->error=0;
	}
	else{
		$data->message = "Update Failed";
		$data->error=1;
	}
}
if($way=="delete_friend"){
	$friend_id=isset($_POST['friend_id'])?$_POST['friend_id']:'';
	$sql = "DELETE FROM `jangsc27_cs_js`.`friend` WHERE `my_id` = ? AND `friend_id` = ?";
	$sth = $db->prepare($sql);
	if($sth->execute(array($id,$friend_id))){
		$data->message = "Delete Success";
		$data->error=0;
	}
	else{
		$data->message = "Delete Failed";
		$data->error=1;
	}
}
echo json_encode($data);
?>

