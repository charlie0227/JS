<?php
require_once "../sysconfig.php";
function hash_password($password){
	return crypt($password,'$1$eSlWcNyAr');
}
$email=isset($_POST['email'])?$_POST['email']:'';
$password=isset($_POST['password'])?hash_password($_POST['password']):'';
$phone=isset($_POST['phone'])?$_POST['phone']:'';
if(isset($_POST['email'])){
	$sql = "INSERT INTO `jangsc27_cs_js`.`member` (email,password,phone) VALUES(?,?,?)";
	$sth = $db->prepare($sql);
	$sth->execute(array($email,$password,$phone));
	write_log('New Account','email:'.$email.'phone:'.$phone);
	echo '註冊成功！請重新登入！';
}
?>