<?php
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
<html>
<head>
<script type="text/javascript" src="../js/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
    function loaded(){
		var content = document.getElementById('content');
		var socket = new WebSocket('ws://140.113.121.128:9377');
		socket.onopen = function () {
			var jsonmsg={
				type:'connect',
				from_id: '8',
				dest_id: '6',
				ip:'<?echo get_ip()?>'
			};
			socket.send(JSON.stringify(jsonmsg));
		};

		socket.onmessage = function (message) {
			//console.log(message.data);
			var o = JSON.parse(message.data);
			//console.log(message.data);
			if(o.type=='msg'){
				var a = JSON.parse('{"list":['+o.text+']}');
				for(var i=0;i<a.list.length;i++){
					$('#chat-box').append(a.list[i].time);
					if(a.list[i].from_id==6){//me
						$('#chat-box').append('<span style="color:blue"> NO.'+a.list[i].from_id+' </span>');
						$('#chat-box').append('<span>'+a.list[i].content+'</span><br>');
					}
					else{
						$('#chat-box').append('<span style="color:red"> NO.'+a.list[i].from_id+' </span>');
						$('#chat-box').append('<span>'+a.list[i].content+'</span><br>');
					}
				}
				var objDiv = document.getElementById("chat-box");
				objDiv.scrollTop = objDiv.scrollHeight;
			}
		};

		socket.onerror = function (error) {
			console.log('WebSocket error: ' + error);
		};
		 socket.onclose = function (event) {
			setTimeout(loaded, 5000);
		};
		$('#send').on('click',function(){
			var msg = document.getElementById("msg").value;
			var jsonmsg={
				type:'message',
				from_id: '8',
				dest_id:'6',
				content:msg,
				ip:'<?echo get_ip()?>'
			};

			socket.send(JSON.stringify(jsonmsg));

		});
	}
</script>
</head>
<body onload="loaded()">
<div id="chat-box" style="bordddder:1px solid #cccccc; width:400px; height:400px; overflow:scroll;"></div>
内容:<input type="text" id="msg"/><input type="button" id="send" value="发送"/>
</body>
</html>