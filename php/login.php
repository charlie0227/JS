<?php
require_once "../sysconfig.php";
$data = new stdClass();
function hash_password($password){
	return crypt($password,'$1$eSlWcNyAr');
}
$way=isset($_POST['way'])?$_POST['way']:'';
$name=isset($_POST['email'])?$_POST['email']:'';
$pass=isset($_POST['password'])?hash_password($_POST['password']):'';
if($way=="login"){
	$sql = "SELECT * FROM `jangsc27_cs_js`.`member` WHERE `email` = ? AND `password` = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($name,$pass));
	$result = $sth->fetchObject();
	if($result){
		$_SESSION['id']=$result->id;
		$data->id=$result->id;
		$data->message="Welcome !!";
		$data->error=0;
		write_log('Login Success','id: '.$result->id);
	}
	else{
		$data->message="Wrong Account or password !!";
		$data->error=1;
		write_log('Login Failed','');
	}
}
if($way=="check_email"){
	$sql = "SELECT * FROM `jangsc27_cs_js`.`member` WHERE `email` = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($name));
	$result = $sth->fetchObject();
	if($result){
		$data->error=1;
	}
	else{
		$data->error=0;
	}
}
echo json_encode($data);
?>

