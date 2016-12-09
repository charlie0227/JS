<?php
require_once "../sysconfig.php";
?>
<html>
<head>
	<link href="../css/desktop.css" rel="stylesheet" type="text/css" />
	<script src="../js/jquery-3.1.1.min.js"></script>
	<script src="../js/pick.js"></script>
</head>
<body>
	<?if(isset($_SESSION['id'])){?>
	<div class="pick_bar">
		<p>物品種類</p>
		<select name="item_class" ></select>
		<p>所在縣市</p>
		<select name="item_location" ></select>
		<p>詳細地點</p>
		<input class="text" type="text" id="location"><input class="image" type="image" src="../image/location.png">
		<!--Google GPS-->
		<p>圖片</p>
		<!--AJAX upload-->
		<p>物品描述</p>
		<textarea></textarea><br>
		<input class="button" type="button" value="送出">
	</div>	
	<?}else{?>
		<script>
		alert('請先登入確定身分');
		parent.location.href='../index.php?q=member';
		</script>
	<?}?>
	</body>
</html>