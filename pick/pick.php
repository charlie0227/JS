<?php
require_once "../sysconfig.php";
?>
<html>
<head>
	<link href="../css/desktop.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../css/ssi-uploader.css"/>
	<script src="../js/jquery-3.1.1.min.js"></script>
	<script src="../js/pick.js"></script>
	<script src="../js/ssi-uploader.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiauOm3OUKekSdpdCA9fRhZQUKArBSBoI&libraries=places"async defer></script>
</head>
<body>
	<?if(isset($_SESSION['id'])){?>
	<div class="pick_bar">
		<p>物品種類</p>
		<select name="item_class" id="item_class"></select>
		<p>所在縣市</p>
		<select name="item_location" id="item_location"></select>
		<p>詳細地點</p>
		<input class="text" type="text" id="location"><input class="image" type="image" src="../image/location.png" onclick="get_position()">
		<!--Google GPS-->
		<p>圖片</p><p id="ps1">最多只能上傳三張圖片<p>
		<input type="file" name="file" multiple id="ssi-upload"/>
		<!--AJAX upload-->
		<p>物品描述</p>
		<textarea id="item_content"></textarea><br>
		<input type="hidden" id="item_id" value="">
		<input class="button" type="button" value="送出" onclick="pick_submit()">
	</div>	
	<?}else{?>
		<script>
		alert('請先登入確定身分');
		parent.location.href='../index.php?q=member';
		</script>
	<?}?>
	</body>
</html>