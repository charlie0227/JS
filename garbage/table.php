<?php

require_once "../sysconfig.php";

$sql = "SELECT * FROM `jangsc27_cs_js`.`item_pick` WHERE `item_class` = ? and `item_location` = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array(5,8));
	while($result = $sth->fetchObject()){
		$r_lat = json_decode($result->geometry)->lat;
		$r_lng = json_decode($result->geometry)->lng;
		echo "<br>";
		echo distance(24.45,120.453,$r_lat,$r_lng);
	}
	
function distance($lng1,$lat1,$lng2,$lat2){
	//?角度??狐度
	$radLat1=deg2rad(floatval($lat1));//deg2rad()函??角度???弧度
	$radLat2=deg2rad(floatval($lat2));
	$radLng1=deg2rad(floatval($lng1));
	$radLng2=deg2rad(floatval($lng2));
	$a=$radLat1-$radLat2;
	$b=$radLng1-$radLng2;
	$s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
	return round($s,0);
}
?>	