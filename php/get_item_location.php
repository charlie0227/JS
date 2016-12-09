<?php
require_once "../sysconfig.php";
$data = new stdClass();

$sql = "SELECT * FROM `jangsc27_cs_js`.`item_location`";
$sth = $db->prepare($sql);
$sth->execute();
$result = $sth->fetchAll();
if($result){
	$data->result=$result;
	$data->message="OK";
	$data->error=0;
}
else{
	$data->message="Connection Error";
	$data->error=1;
}
echo json_encode($data);
?>