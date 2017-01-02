function load(drop_id){
	$.post('../php/get_item_list.php',{
		way:'drop_item',
		drop_id:drop_id
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
		
		}
	);
}
function add_friend(drop_id){
	$.post('../php/add_friend.php',{
		drop_id:drop_id
		},function(data){
			parent.location.href='../index.php?q=member';
		}
	);
}