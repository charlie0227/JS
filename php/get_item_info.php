<?php
require_once "../sysconfig.php";
$data = new stdClass();

$item_id =isset($_POST['id'])?encrypt($_POST['id']):'';

$sql = "SELECT * FROM `jangsc27_cs_js`.`member` WHERE `id` = ?";
$sth = $db->prepare($sql);
$sth->execute(array($item_id));
$result = $sth->fetchObject();
	if($result){
		$data->error=1;
		$data->result=$result;
	}
	else{
		$data->error=0;
	}
	
echo json_encode($data);

?>