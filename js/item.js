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
			$('#img').append(obj.result.img_url);
		}
	);
}
function add_friend(){
	$.post('../php/add_friend.php',{
		item_id:document.getElementById('item_id').val()
		},function(data){
			//console.log(data);
			var obj=JSON.parse(data);
		}
	);
}