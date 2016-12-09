
var server = require('websocket').server, http = require('http');
var req = require("request");
var port = 1377;
var socket = new server({
    httpServer: http.createServer().listen(1377)
});
console.log("Server listening on port*"+port);

var connections = new Array();


socket.on('request', function(request) {
    var connection = request.accept(null, request.origin);

	connection.addListener('message',function(msg){
		//console.log(msg.utf8Data);
		var o = JSON.parse(msg.utf8Data);
		var d = new Date();
        var date = '['+d.getFullYear()+'-'
        +('0'+(d.getMonth()+1)).slice(-2)+'-'
        +('0'+d.getDate()).slice(-2)+' '
        +('0'+d.getHours()).slice(-2)+':'
        +('0'+d.getMinutes()).slice(-2)+':'
        +('0'+d.getSeconds()).slice(-2)+'] ';
        var ip = '['+o.ip+']';
        //when conenction establish
		if(o.type=="connect"){
			//save from_id into connection
            connection.id=o.from_id;
            //push connection into connections array
            connections.push(connection);
	        console.log(date+ip+"<Connection> position: "+connections.length+" from_id: "+ o.from_id+" established.");
		}
        if(o.type=="prepare"){
            
            //prepare the chat record
			req('http://140.113.121.128:9080/~charlie27/messenger/chat_record.php',{
                method:"POST",
                form:{
                    way:'load',
                    member1_id:o.from_id<o.dest_id?o.from_id:o.dest_id,//smaller
                    member2_id:o.from_id>o.dest_id?o.from_id:o.dest_id,//bigger
                }
                }, function (error, response, body) {
                if(error) {
                    console.log(date+ip+error);
                } else {
                    //console.log(response.statusCode, body);
                    var jsonmsg=JSON.parse(body);
                    //send chat record to from_id 
                    from_send = send_to_id(o.from_id,jsonmsg);
                    console.log(date+ip+"<Prepare> from_id: "+ connection.id+" dest_id: "+o.dest_id+" chat record prepared.");
                }
            });
			//my unseen chat clear
			unseen_clear(date,ip,o.from_id,o.dest_id);
        }
        //recieve the message
        if(o.type=="message"){
            var msg = {
                time:date,
                from_id:o.from_id,
                dest_id:o.dest_id,
                content:o.content
                };
            var jsonmsg={text:JSON.stringify(msg),type:'msg'};
            //console.log(JSON.stringify(jsonmsg));
            //send back himself
            var from_send = send_to_id(o.from_id,jsonmsg);
            //send to destination
            var dest_send = send_to_id(o.dest_id,jsonmsg);
	        //online message
			if(dest_send)
                console.log(date+ip+"<Online Message> from_id: "+ o.from_id+" dest_id: "+o.dest_id+" content: "+o.content);
            //offline message
			else
                console.log(date+ip+"<Offline Message> from_id: "+ o.from_id+" dest_id: "+o.dest_id+" content: "+o.content);
			
			//dest unseen num+1
			unseen_add(date,ip,o.dest_id,o.from_id);
            //go DB update chat record
            req('http://140.113.121.128:9080/~charlie27/messenger/chat_record.php',{
                method:"POST",
                form:{
                    way:'save',
					myid:o.from_id,
                    member1_id:o.from_id<o.dest_id?o.from_id:o.dest_id,//smaller
                    member2_id:o.from_id>o.dest_id?o.from_id:o.dest_id,//bigger
                    text:JSON.stringify(msg),
                }
                }, function (error, response, body) {
                if(error) {
                    console.log(date+ip+error);
                } else {
                    var obj = JSON.parse(body);
                    console.log(date+ip+"<Database> Status: "+response.statusCode, obj.error);
                }
            });
        }
	});
	
    connection.on('close', function(code,reason) {
        ;
    });
	
});
//function use dest_id to find target connection 
function send_to_id(id,jsonmsg){
    var flag=false;
    for(var i=0; i<connections.length; i++)
        if(connections[i].id==id){
            connections[i].send(JSON.stringify(jsonmsg));
            flag=1;
        }
    return flag;
}
//function set unseen num = 0
function unseen_clear(date,ip,from_id,dest_id){
	req('http://140.113.121.128:9080/~charlie27/messenger/chat_record.php',{
		method:"POST",
		form:{
			way:'unseen_clear',
			myid:from_id,
			friendid:dest_id
		}
	}, function (error, response, body) {
		if(error) {
			console.log(date+ip+error);
		} else {
			//console.log(body);
			var obj = JSON.parse(body);
			console.log(date+ip+"<Database> Status: "+response.statusCode,'from_id: ',from_id,'dest_id: ',dest_id,obj.error);
		}
	});
}
function unseen_add(date,ip,from_id,dest_id){
	req('http://140.113.121.128:9080/~charlie27/messenger/chat_record.php',{
		method:"POST",
		form:{
			way:'unseen_add',
			myid:from_id,
			friendid:dest_id
		}
	}, function (error, response, body) {
		if(error) {
			console.log(date+ip+error);
		} else {
			var obj = JSON.parse(body);
			console.log(date+ip+"<Database> Status: "+response.statusCode,'from_id: ',from_id,'dest_id: ',dest_id,obj.error);
		}
	});
}