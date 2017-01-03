<?php
require_once "../sysconfig.php";
$way=$_POST['way'];
$geometry=$_POST['geometry'];
$item_class=$_POST['item_class'];
$item_location=$_POST['item_location'];
$location=$_POST['location'];
$item_content=$_POST['item_content'];
$time=date("Y-m-d H:i:s");
$data = new stdClass();

function find_item_drop_id($db,$item_class,$item_location,$location,$item_content,$time){
	$sql = "SELECT * FROM `jangsc27_cs_js`.`item_drop` 
	where `item_class`=? 
	and `item_location`=? 
	and `location`=? 
	and `descript`=? 
	and `member_id`=? 
	and `time`=?";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$item_location,$location,$item_content,$_SESSION['id'],$time));
	return $sth->fetchObject()->id;
}
function find_item_pick_id($db,$item_class,$item_location,$location,$item_content,$time){
	$sql = "SELECT * FROM `jangsc27_cs_js`.`item_pick` 
	where `item_class`=? 
	and `item_location`=? 
	and `location`=? 
	and `descript`=? 
	and `member_id`=? 
	and `time`=?";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$item_location,$location,$item_content,$_SESSION['id'],$time));
	return $sth->fetchObject()->id;
}
if($way=='pick'){
	$sql = "INSERT INTO `jangsc27_cs_js`.`item_drop`
	(item_class,geometry,item_location,location,descript,member_id,time) 
	VALUES(?,?,?,?,?,?,?)";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$geometry,$item_location,$location,$item_content,$_SESSION['id'],$time));
	$data->item_id=find_item_drop_id($db,$item_class,$item_location,$location,$item_content,$time);
	match_email_send($db,$item_class,$item_location,$geometry,$data->item_id);
}
if($way=='drop'){
	$sql = "INSERT INTO `jangsc27_cs_js`.`item_pick`
	(item_class,geometry,item_location,location,descript,member_id,time) 
	VALUES(?,?,?,?,?,?,?)";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$geometry,$item_location,$location,$item_content,$_SESSION['id'],$time));
	$data->item_id=find_item_pick_id($db,$item_class,$item_location,$location,$item_content,$time);
}

function distance($lng1,$lat1,$lng2,$lat2){
	//将角度转为狐度
	$radLat1=deg2rad(floatval($lat1));//deg2rad()函数将角度转换为弧度
	$radLat2=deg2rad(floatval($lat2));
	$radLng1=deg2rad(floatval($lng1));
	$radLng2=deg2rad(floatval($lng2));
	$a=$radLat1-$radLat2;
	$b=$radLng1-$radLng2;
	$s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
	return round($s,0);
}
function parse_dis($dis){
	$unit = '公尺';
	$dis = floatval($dis);
	if($dis>=1000){
		$dis=round($dis/1000,1);
		$unit = '公里';
	}
	return $dis.$unit;
}
function match_email_send($db,$item_class,$item_location,$geometry,$id){
	$sql = "SELECT *,a.id as item 
	FROM `jangsc27_cs_js`.`item_pick` a 
	JOIN `member` b 
	ON a.member_id = b.id 
	WHERE a.item_class = ? 
	and a.item_location = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($item_class,$item_location));
	while($result = $sth->fetchObject()){
		$url = "https://www.charlie27.me/~test123/index.php?q=drop&s=".encrypt($id);
		$mail_subject="有人發布與你遺失物相符的項目";
		$r_lat = json_decode($result->geometry)->lat;
		$r_lng = json_decode($result->geometry)->lng;
		$u_lat = json_decode($geometry)->lat;
		$u_lng = json_decode($geometry)->lng;
		$distance = parse_dis(distance($u_lng,$u_lat,$r_lng,$r_lat));
		
		$mail_content = '有人撿到與你相符的遺失物，距離你'.$distance.'，如果要查看請點下列網址 \n'.$url;
		//$command = 'echo -e"' . $mail_content . '" | mail -s "' . $mail_subject . '" ' . 'weicent.cs02@g2.nctu.edu.tw';//測試用
		$command = 'echo -e"' . $mail_content . '" | mail -s "' . $mail_subject . '" ' . $result->email;
		$results = shell_exec($command);
	}
}

echo json_encode($data);

?>