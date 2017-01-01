<?php
require_once "../sysconfig.php";
$data = new stdClass();

$sql = "INSERT INTO `friend` (my_id,friend_id,friend_name,last_chat,num_unseen_chat) VALUES (?,?,?,'','0');";
$sth = $db->prepare($sql);
$sth->execute(array($drop_id));
?>