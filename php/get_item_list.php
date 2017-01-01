<?php
require_once "../sysconfig.php";
$data = new stdClass();

$way=isset($_POST['way'])?$_POST['way']:'';
$drop_id=isset($_POST['drop_id'])?decrypt($_POST['drop_id']):'';
$stime=isset($_POST['stime'])?$_POST['stime']:'';
$sclass=isset($_POST['sclass'])?$_POST['sclass']:'';
$slocation=isset($_POST['slocation'])?$_POST['slocation']:'';
//handle search method
$search_sql="";
if($stime!="" || $sclass!="" || $slocation!="")
	$search_sql.=' AND ';
if($stime!="")
	$search_sql.="TIMESTAMPDIFF(DAY,'".date("Y-m-d H:i:s",time())."',a.time)<".$stime;
if($sclass!="" || $slocation!="")
	$search_sql.=" AND ";
if($sclass!="")
	$search_sql.='a.item_class='.$sclass;
if($slocation!="")
	$search_sql.=" AND ";
if($slocation!="")
	$search_sql.='a.item_location='.$slocation;
if($way=="drop_item_list"){
	$sql = "SELECT *,a.id as id,b.thing as class,c.location as location FROM `item_drop` a 
	JOIN `item_class` b
	JOIN `item_location` c
	ON  a.item_class = b.id
	AND a.item_location = c.id
	".$search_sql." ORDER BY `time` DESC";
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
if($way=="drop_item"){
	$sql = "SELECT *,a.id as id,b.thing as item_class,c.location as item_location, a.location as location
	FROM `item_drop` a 
	JOIN `item_class` b
	JOIN `item_location` c
	ON  a.item_class = b.id
	AND a.item_location = c.id
	AND	a.id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($drop_id));
	$result=$sth->fetchObject();
	$data->result=$result;
	//fetch img
	$sql = "SELECT * FROM `item_drop_img` a WHERE a.item_id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($drop_id));
	$result=$sth->fetchObject();
	$data->img=$result;
	
	$sql = "SELECT *,a.id as id,b.thing as item_class,c.location as item_location, a.location as location
	FROM `item_drop` a 
	JOIN `item_class` b
	JOIN `item_location` c
	ON  a.item_class = b.id
	AND a.item_location = c.id 
	AND `item_location` = ? 
	AND `item_class` = ? 
	ORDER BY `time` DESC LIMIT 3";
	$sth = $db->prepare($sql);
	$sth->execute(array($result->item_location,$result->item_class));
	$result = $sth->fetchAll();
	$data->possible=$result;
	$data->message="OK";
	$data->error=0;
}
echo json_encode($data);
?>

