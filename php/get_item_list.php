<?php
require_once "../sysconfig.php";
$data = new stdClass();

$way=isset($_POST['way'])?$_POST['way']:'';
$stime=isset($_POST['stime'])?$_POST['stime']:'';
$sclass=isset($_POST['sclass'])?$_POST['sclass']:'';
$slocation=isset($_POST['slocation'])?$_POST['slocation']:'';
//handle search method
$search_sql="";
if($stime!="" || $sclass!="" || $slocation!="")
	$search_sql.=' WHERE ';
if($stime!="")
	$search_sql.="TIMESTAMPDIFF(DAY,'".date("Y-m-d H:i:s",time())."',item_drop.time)<".$stime;
if($sclass!="" || $slocation!="")
	$search_sql.=" AND ";
if($sclass!="")
	$search_sql.='item_drop.item_class='.$sclass;
if($slocation!="")
	$search_sql.=" AND ";
if($slocation!="")
	$search_sql.='item_drop.item_location='.$slocation;
if($way=="drop_item"){
	$sql = "SELECT * FROM `jangsc27_cs_js`.`item_drop` ".$search_sql." ORDER BY `time` DESC";
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

