<?php
require_once "../sysconfig.php";
$way=$_POST['way'];
$item_class=$_POST['item_class'];
$item_location=$_POST['item_location'];
$location=$_POST['location'];
$item_content=$_POST['item_content'];
$time=date("Y-m-d H:i:s");
$data = new stdClass();

function find_item_drop_id($db,$item_class,$item_location,$location,$item_content,$time){
	$sql = "SELECT * FROM `jangsc27_cs_js`.`item_drop` where `item_class`=? and `item_location`=? and `location`=? and `descript`=? and `member_id`=? and `time`=?";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$item_location,$location,$item_content,$_SESSION['id'],$time));
	return $sth->fetchObject()->id;
}
function find_item_pick_id($db,$item_class,$item_location,$location,$item_content,$time){
	$sql = "SELECT * FROM `jangsc27_cs_js`.`item_pick` where `item_class`=? and `item_location`=? and `location`=? and `descript`=? and `member_id`=? and `time`=?";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$item_location,$location,$item_content,$_SESSION['id'],$time));
	return $sth->fetchObject()->id;
}
if($way=='pick'){
	//create new store
	$sql = "INSERT INTO `jangsc27_cs_js`.`item_drop` (item_class,item_location,location,descript,member_id,time) VALUES(?,?,?,?,?,?)";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$item_location,$location,$item_content,$_SESSION['id'],$time));
	$data->item_id=find_item_drop_id($db,$item_class,$item_location,$location,$item_content,$time);
}
if($way=='drop'){
	$sql = "INSERT INTO `jangsc27_cs_js`.`item_pick` (item_class,item_location,location,descript,member_id,time) VALUES(?,?,?,?,?,?)";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$item_location,$location,$item_content,$_SESSION['id'],$time));
	$data->item_id=find_item_pick_id($db,$item_class,$item_location,$location,$item_content,$time);
}


echo json_encode($data);

?>