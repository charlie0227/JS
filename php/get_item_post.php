<?php
require_once "../sysconfig.php";

$data = new stdClass();
$sql = "
SELECT 'drop' as type, a.id as id, b.thing as class, c.location as location, a.time as time 
FROM `item_drop` a
JOIN `item_class` b
JOIN `item_location` c
ON  a.item_class = b.id
AND a.item_location = c.id
AND a.member_id = ?
UNION
SELECT 'pick' as type, a.id as id, b.thing as class, c.location as location, a.time as time 
FROM `item_pick` a
JOIN `item_class` b
JOIN `item_location` c
ON  a.item_class = b.id
AND a.item_location = c.id
AND a.member_id = ?
ORDER BY time DESC
";
$sth = $db->prepare($sql);
$sth->execute(array($_SESSION['id'],$_SESSION['id']));
$result = $sth->fetchAll();
if($result){
	for($i=0;$i<count($result);$i++){
		$result[$i]['id']=encrypt($result[$i]['id']);
	}
}
$data->result = $result;
echo json_encode($data);
?>
