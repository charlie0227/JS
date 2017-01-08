<?php
require_once "../sysconfig.php";
$item_id =isset($_GET['id'])?$_GET['id']:'';
?>
<html>
	<head>
		<link href="../css/desktop.css" rel="stylesheet" type="text/css" />
		<script src="../js/jquery-3.1.1.min.js"></script>
		<script src="../js/item.js"></script>
	</head>
	<body onload="load('<?echo $item_id?>')">
		<div id="hangout">
			<div class="pick_bar">
				<p>上傳時間</p>
				<span id="time"></span>
				<p>物品種類</p>
				<span id="item_class"></span>
				<p>所在縣市</p>
				<span id="item_location"></span>
				<p>詳細地點</p>
				<span id="location"></span>
				<p>圖片</p>
				<span id="img"></span>
				<p>物品描述</p>
				<span id="descript"></span>
				<br><br>
				<span id="display"></span>
				<div class="other_possible">
				</div>
				<br><br>
			</div>	
		</div>
	</body>
</html>
