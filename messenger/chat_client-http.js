var socket;
var myip;
var friendid;
var myid;
function loaded(){
	socket = new WebSocket('ws://140.113.121.128:9377');
	socket.onopen = function () {
		var jsonmsg={
			type:'connect',
			from_id: document.getElementById("myid").value,
			ip:document.getElementById("ip").value,
		};
		socket.send(JSON.stringify(jsonmsg));
	};

	socket.onmessage = function (message) {
		//console.log(message.data);
		var o = JSON.parse(message.data);
		//console.log(message.data);
		if(o.type=='msg'){
			//prepare text message
			var a = JSON.parse('{"list":['+o.text+']}');
			//outside chatroom
			//ask DB for unseen and last chat
			if(!$('.list-chat').attr('class').includes('shown')){

				var b = JSON.parse(o.text).from_id;
				var t = JSON.parse(o.text).content;
				console.log(b);
				sessionStorage.setItem('from_id',b);
				sessionStorage.setItem('content',t);
				setTimeout(function() {
					$.post('../php/chat_friend.php',{
						way:'friend_list',
						dataType:'json',
						async: false,
					},function(data){
						var l = JSON.parse(data).result;
						var from_id = sessionStorage.getItem('from_id');
						var txt =  sessionStorage.getItem('content');
						var $target = $('#chat'+from_id);
						for(var j=0;j<l.length;j++){
							console.log(l[j].friend_id,from_id);
							if(l[j].friend_id==from_id){
								var num = parseInt(l[j].num_unseen_chat);
								//var txt = JSON.parse(l[j].last_chat).content;
								console.log(num);
								$target.find('.unseen').text('('+num+')');
								$target.find('.txt').text(txt);
							}
						}
						sessionStorage.removeItem('from_id');
					});
				},500);
			}
			for(var i=0;i<a.list.length;i++){
				//into chatroom
				if(a.list[i].from_id==document.getElementById("myid").value){//me
					$('ul.chat').append('<li class="me_msg">\
        				<img src="../image/user-icon.png">\
          				<div class="message">'+a.list[i].content+'</div>\
        				</li>');
				}
				else{
					$('ul.chat').append('<li>\
        				<img src="../image/user-icon.png">\
          				<div class="message">'+a.list[i].content+'</div>\
        				</li>');
				}
			}
			//scroll to bottom
			var $target = $('.list-chat');
			$target.animate({scrollTop: $target.height()*999}, 1000);
		}
	};

	socket.onerror = function (error) {
		console.log('WebSocket error: ' + error);
	};
	 socket.onclose = function (event) {
		setTimeout(loaded, 5000);
	};
}
function prepare_chat_record(friend_id){
	var jsonmsg={
		type:'prepare',
		from_id:document.getElementById("myid").value,
		dest_id:friend_id,
		ip:document.getElementById("ip").value
	};
	//console.log(JSON.stringify(jsonmsg));
	socket.send(JSON.stringify(jsonmsg));
}
function chat_send(friend_id,msg){
	var jsonmsg={
		type:'message',
		from_id:document.getElementById("myid").value,
		dest_id:friend_id,
		content:msg,
		ip:document.getElementById("ip").value
	};
	socket.send(JSON.stringify(jsonmsg));
}
