<?php
require_once "../sysconfig.php";
$data = new stdClass();

$way=isset($_POST['way'])?$_POST['way']:'';
$drop_id=isset($_POST['drop_id'])?decrypt($_POST['drop_id']):'';
$stime=isset($_POST['stime'])?$_POST['stime']:'';
$sclass=isset($_POST['sclass'])?$_POST['sclass']:'';
$slocation=isset($_POST['slocation'])?$_POST['slocation']:'';
$ssort=isset($_POST['ssort'])?$_POST['ssort']:'';
//handle search method
$search_sql="";
if($stime!="")
	$search_sql.=" AND TIMESTAMPDIFF(DAY,a.time,'".date("Y-m-d H:i:s",time())."')<=".$stime;
if($sclass!="")
	$search_sql.=' AND a.item_class='.$sclass;
if($slocation!="")
	$search_sql.=' AND a.item_location='.$slocation;
if($way=="drop_item_list"){
	$u_lat = isset($_POST['lat'])?$_POST['lat']:'';
	$u_lng = isset($_POST['lng'])?$_POST['lng']:'';
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
		//count distance
		for($i=0;$i<count($result);$i++){
			if($result[$i]['geometry']!='' && $u_lat!='' && $u_lng!=''){
				$r_lat = json_decode($result[$i]['geometry'])->lat;
				$r_lng = json_decode($result[$i]['geometry'])->lng;
				$result[$i]['distance']=distance($u_lng,$u_lat,$r_lng,$r_lat);
			}
			else{
				$result[$i]['distance']='';
			}
			$result[$i]['id'] = encrypt($result[$i]['id']);
		}
		//sort distance 
		if($ssort='dis'){
			usort($result,function($x,$y){
				//descending
				if($x['distance']=='' && $x['distance']!=0)
					return 1;
				else if($y['distance']=='' && $y['distance']!=0)
					return -1;
				else
					return $x['distance'] > $y['distance'];
			});
		}
		//distance display
		for($i=0;$i<count($result);$i++){
			if($result[$i]['distance']!='' || $result[$i]['distance']=='0')
				$result[$i]['distance']=parse_dis($result[$i]['distance']);
		}
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
	$sql = "SELECT * FROM `item_drop_img` WHERE `item_id` = ?";
	$sth = $db->prepare($sql);
	$sth->execute(array($drop_id));
	if($result_img=$sth->fetchAll())
		$data->img_url=$result_img;
	else
		$data->img_url='';
	
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
?>

