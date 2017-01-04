<?
require_once "../sysconfig.php";
$way=isset($_POST['way'])?$_POST['way']:'';
$item_id=isset($_POST['item_id'])?decrypt($_POST['item_id']):'';
if($way=='drop')
	$sql = "DELETE FROM `item_drop` WHERE id = ?";
if($way=='pick')
	$sql = "DELETE FROM `item_pick` WHERE id = ?";
$sth = $db->prepare($sql);
$sth->execute(array($item_id));
echo "OK";
?>