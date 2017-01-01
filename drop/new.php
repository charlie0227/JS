<?php
require_once "../sysconfig.php";
?>
<html>
<head>
	<link href="../css/desktop.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../css/ssi-uploader.css"/>
	<script src="../js/jquery-3.1.1.min.js"></script>
	<script src="../js/drop.js"></script>
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
		<!--AJAX upload-->
		<p>物品描述</p>
		<textarea id="item_content"></textarea><br>
		<span>※發布後系統會自動匹配之後撿到的可能物品並發送訊息至註冊信箱</span><br>
		<input class="button" type="button" value="送出" onclick="drop_submit()">
	</div>	
	<?}else{?>
		<script>
		alert('請先登入確定身分');
		parent.location.href='../index.php?q=member';
		</script>
	<?}?>
	</body>
</html>