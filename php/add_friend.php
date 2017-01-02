<?php
require_once "../sysconfig.php";
$data = new stdClass();
$drop_id=isset($_POST['drop_id'])?decrypt($_POST['drop_id']):'';
if($drop_id!=''){
	$sql = "SELECT a.time as time,a.member_id as friend_id,b.thing as class,c.location as location 
	FROM `item_drop` a 
	JOIN `item_class` b
	JOIN `item_location` c
	ON  a.item_class = b.id
	AND a.item_location = c.id
	AND a.id = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($drop_id));
	$target = $sth->fetchObject();
	$friend_id = $target->friend_id;
	$friend_name = '';
	$friend_name.= $target->location.' ';
	$friend_name.= $target->class.' ';
	$friend_name.= $target->time;
	
	$sql = "INSERT INTO `friend` (my_id,friend_id,friend_name,last_chat,num_unseen_chat) VALUES (?,?,?,'','0'), (?,?,?,'','0')";
	$sth = $db->prepare($sql);
	$sth->execute(array($_SESSION['id'],$friend_id,$friend_id.$friend_name,$friend_id,$_SESSION['id'],$_SESSION['id'].$friend_name));
	print_r($friend_name);
}
?>