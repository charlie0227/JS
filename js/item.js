function load(drop_id){
	//console.log(drop_id);
	$.post('../php/get_item_list.php',{
		way:'drop_item',
		drop_id:encodeURIComponent(drop_id)
		},function(data){
			//console.log(data);
			var obj=JSON.parse(data);
			$('#time').append(obj.result.time);
			$('#item_class').append(obj.result.item_class);
			$('#item_location').append(obj.result.item_location);
			$('#location').append(obj.result.location);
			if(obj.img_url!=''){
				for(var i=0;i<obj.img_url.length;i++)
					$('#img').append('<img src="https://www.charlie27.me/~test123'+obj.img_url[i].url+'">');
			}
			$('#descript').append(obj.result.descript);
			//console.log('display',obj.display);
			//0 for no login,1 for normal, 2 for you are owner
			if(obj.display==0)
				$('#display').append('<input class="button" type="button" value="連絡拾獲人" onclick="alert('+"'請先登入確定身分'"+');parent.location.href='+"'../index.php?q=member'"+'">');
			if(obj.display==1)
				$('#display').append('<input class="button" type="button" value="連絡拾獲人" onclick="add_friend('+"'"+decodeURIComponent(obj.result.id)+"'"+');">');
			$('#display').append('<input class="button" type="button" value="返回物品列表" onclick="location.href='+"'../drop/drop.html'"+'">');
		}
	);
}
function add_friend(drop_id){
	$.post('../php/add_friend.php',{
		drop_id:encodeURIComponent(drop_id)
		},function(data){
			//console.log(data);
			var obj = JSON.parse(data);
			//console.log(encodeURIComponent(obj.friend_name));	
			parent.location.href='../index.php?q=member&s='+encodeURIComponent(obj.friend_name);
		}
	);
}