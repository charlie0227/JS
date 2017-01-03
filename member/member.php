<?php
require_once "../sysconfig.php";
//ip
function get_ip(){
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
	  $ip=$_SERVER['HTTP_CLIENT_IP'];
	else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	else
	  $ip=$_SERVER['REMOTE_ADDR'];
	return $ip;
}
?>
<!DOCTYPE html>
<html>
<head>
	<script src="../js/jquery-3.1.1.min.js"></script>
	<?if(!isset($_SESSION['id'])){#if NOT LOGIN => LOGIN PAGE?>
	<link rel="stylesheet" type="text/css" href="../css/desktop.css" />
	<link rel="stylesheet" type="text/css" href="../css/normalize.css">
	<link rel="stylesheet" type="text/css" href="../css/demo.css">
	<link rel="stylesheet" type="text/css" href="../css/component.css">
	<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.2.0/css/font-awesome.min.css">
	<link href="../css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<script src="../js/classie.js"></script>
	<script src="../js/jquery-ui.min.js"></script>
	<script src="../js/member.js"></script>
	<?}else{#if LOGIN => MEMBER MANAGE?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<link rel='stylesheet prefetch' href='https://cdn.materialdesignicons.com/1.1.70/css/materialdesignicons.min.css'>
	<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:300'>
	<link rel="stylesheet" href="../css/chatroom.css">
	<?}?>
</head>
<body>

<style id="dynamic-styles"></style>
<div id="hangout">
	<?if(!isset($_SESSION['id'])){#if NOT LOGIN => LOGIN PAGE?>
		<div class="login_bar">
			<div class="input input--hoshi">
				<input class="input__field input__field--hoshi" type="text" id="email" autocomplete="new-password">
				<label class="input__label input__label--hoshi input__label--hoshi-color-2" for="email">
					<span class="input__label-content input__label-content--hoshi">Email</span>
				</label>
			</div>
			<div class="input input--hoshi">
				<input class="input__field input__field--hoshi" type="password" id="password" autocomplete="new-password">
				<label class="input__label input__label--hoshi input__label--hoshi-color-3" for="password">
					<span class="input__label-content input__label-content--hoshi">Password</span>
				</label>
			</div>
			<input class="login" id="sign_in" type="button" value="Sign in">
			<input class="login" id="sign_up" type="button" value="Sign up">
		</div>
		<?}
		else{#if LOGIN => MEMBER MANAGE?>
			<div id="head" class="style-bg">
				<h1>SSL加密聊天室</h1>
			</div>
			<div id="content">
				<div class="overlay"></div>
				<div class="list-account">
					<div class="meta-bar"><input class="nostyle search-filter" type="text" placeholder="Search" /></div>
					<ul class="list mat-ripple">
						<!--friend-->
					</ul>
				</div>
				<div class="list-text">
					<ul class="list mat-ripple">
						<!--chat view-->
					</ul>
				</div>
				<div class="list-phone">

					<ul class="list mat-ripple">
						<!--phone-->
						<li id="my_post">
							<p>我發佈的物品</p>
						</li>
						<li id="log_out">
							<p>Logout</p>
						</li>
					</ul>
				</div>
				<div class="list-chat">
					<ul class="chat">
						<!--chat-->
					</ul>
					<div class="meta-bar chat">
						<input class="nostyle chat-input" type="text" placeholder="Message..." />
						<i class="mdi mdi-send"></i>
					</div>
				</div>
				<ul class="nav control mat-ripple tiny">
					<li data-route=".list-account">
						<i class="mdi mdi-account-multiple"></i>
					</li>
					<li data-route=".list-text">
						<i class="mdi mdi-comment-text"></i>
					</li>
					<li data-route=".list-phone">
						<i class="mdi mdi-settings"></i>
					</li>
				</ul>
			</div>
			<div id="contact-modal" data-mode="add" class="card dialog">
				<h3>Add Contact</h3>
				<div class="i-group">
					<input type="text" id="new-user">
					<span class="bar"></span>
					<label>Name</label>
				</div>
				<div class="btn-container">
					<span class="btn cancel">Cancel</span>
					<span class="btn save">Save</span>
				</div>
			</div>
			<input type="hidden" id="ip" value="<?echo get_ip()?>">
			<input type="hidden" id="myid" value="<?echo $_SESSION['id']?>">
		<script src="../messenger/chat_client.js"></script>
		<script src="../js/chatroom.js"></script>
		<?}?>
	</div>
</body>
</html>
