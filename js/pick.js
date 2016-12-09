$(function(){
	//prepare search item class
	$.post('../php/get_item_class.php',{
		dataType: 'json',
		},function(data){
			var obj = JSON.parse(data);
			list = obj.result;
			$('select[name="item_class"]').append('<option value=""></option>');
			for(var i=0;i<list.length;i++)
				$('select[name="item_class"]').append('<option value="'+list[i].id+'">'+list[i].thing+'</option>');
		}
	);
	//prepare search item class
	$.post('../php/get_item_location.php',{
		dataType: 'json',
		},function(data){
			var obj = JSON.parse(data);
			list = obj.result;
			$('select[name="item_location"]').append('<option value=""></option>');
			for(var i=0;i<list.length;i++)
				$('select[name="item_location"]').append('<option value="'+list[i].id+'">'+list[i].location+'</option>');
		}
	);
});
function form_submit(){
	
}