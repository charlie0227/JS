<?php
require_once "../sysconfig.php";
$data = new stdClass();

if(isset($_POST['way'])){
	$way = $_POST['way'];
	$member1_id = isset($_POST['member1_id'])?$_POST['member1_id']:'';
	$member2_id = isset($_POST['member2_id'])?$_POST['member2_id']:'';
	//update friend last seen 
	$myid = isset($_POST['myid'])?$_POST['myid']:'';
	
	//load chat record
	if($way=='load'){
		$sql = "SELECT * FROM `jangsc27_cs_js`.`chat_record` WHERE `member1_id` = ? AND `member2_id` = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($member1_id,$member2_id));
		//already exixts ?
		if($result = $sth->fetchObject())
			$data->text = $result->text;
		else
			$data->text = '';
		$data->type = "msg";
		$data->error = 0;
	}
	//save chat record
	if($way=='save'){
		$friendid = ($myid==$member1_id)?$member2_id:$member1_id;
		$text = isset($_POST['text'])?$_POST['text']:'';
		//me last seen chat
		$sql = "UPDATE `jangsc27_cs_js`.`friend` SET `last_chat` = ? WHERE `my_id` = ? AND `friend_id` = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($text,$myid,$friendid));
		//friend last seen chat
		$sql = "UPDATE `jangsc27_cs_js`.`friend` SET `last_chat` = ? WHERE `my_id` = ? AND `friend_id` = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($text,$friendid,$myid));
		//find record
		$sql_find = "SELECT * FROM `jangsc27_cs_js`.`chat_record` WHERE `member1_id` = ? AND `member2_id` = ?";
		$sth_find = $db->prepare($sql_find);
		$sth_find->execute(array($member1_id,$member2_id));
		//already exixts ?
		if($result = $sth_find->fetchObject()){
			if($result->text!='')
				$text = ",".$text;
			$sql = "UPDATE `jangsc27_cs_js`.`chat_record` SET `text` = CONCAT(`text`,?) WHERE `id` = ?";
			$sth = $db->prepare($sql);
			$sth->execute(array($text,$result->id));
			$data->error = 'Update chat record.';
		}
		else{
			$sql = "INSERT INTO `jangsc27_cs_js`.`chat_record` (`member1_id`,`member2_id`,`text`) VALUES (?,?,?)";
			$sth = $db->prepare($sql);
			$sth->execute(array($member1_id,$member2_id,$text));
			$data->error = 'Insert new chat record.';
		}
		
	}
	if($way=="unseen_add"){
		$friendid=isset($_POST['friendid'])?$_POST['friendid']:'';
		//dest unseen num+1
		$sql = "UPDATE `jangsc27_cs_js`.`friend` SET `num_unseen_chat` = `num_unseen_chat`+1 WHERE `my_id` = ? AND `friend_id` = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($myid,$friendid));
		$data->error = 'Unseen chat add';
	}
	if($way=="unseen_clear"){
		$friendid=isset($_POST['friendid'])?$_POST['friendid']:'';
		//my unseen clear
		$sql = "UPDATE `jangsc27_cs_js`.`friend` SET `num_unseen_chat` = 0 WHERE `my_id` = ? AND `friend_id` = ?";
		$sth = $db->prepare($sql);
		$sth->execute(array($myid,$friendid));
		$data->error = 'Unseen chat clear';
	}
	echo json_encode($data);
}
else{
	$data->error = "php didn't get POST";
	echo json_encode($data);
}
?>