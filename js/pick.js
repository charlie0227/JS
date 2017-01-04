var geometry='';
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
	//prepare uploading image
	$('#ssi-upload').ssi_uploader({
        url: '../php/uploadimage.php',
        maxFileSize: 6,
		id:55,
        allowed: ['jpg', 'gif', 'txt', 'png', 'pdf'],
		maxNumberOfFiles: 3,
		onEachUpload:function(file){
			//alert('上傳完成'); 
		},
		onUpload:function(file){
			alert('上傳完成'); 
			parent.location.href="../index.php?q=drop";
		}
    });
	//jquery listen
	$('#location').on('blur',function(){
		address_to_geometry($(this).val());
	});
});

function img_num() {
	return $('#ssi-previewBox table').length;
}
function get_position(){
	var geocoder;
	if (window.navigator.geolocation==undefined) {
		alert("此瀏覽器不支援地理定位功能!");
	}
	else {
		var geolocation=window.navigator.geolocation; //取得 Geolocation 物件
		//地理定位程式碼
		var option={
		  enableAcuracy:false,
		  maximumAge:0,
		  timeout:600000
		  };
		geolocation.getCurrentPosition(successCallback,
								   errorCallback,
								   option
								   );
		}
		function successCallback(position) {
			geocoder = new google.maps.Geocoder();
			geometry = {
				lat:position.coords.latitude,
				lng:position.coords.longitude
			};
			geocoder.geocode({
			  'latLng': {lat: position.coords.latitude, lng: position.coords.longitude}
			}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					if (results) {
						var address = results[0].formatted_address;
						document.getElementById('location').value = address;
						var x = document.getElementById('item_location');
						for(var i=0;i<x.options.length;i++){
							if(address.match(x.options[i].text)==x.options[i].text && x.options[i].text != ""){
								x.options[i].selected = true;
							}
						}
					}
				} else {
					alert("Reverse Geocoding failed because: " + status);
				}
			});
		}
		
		function errorCallback(error) {
			var errorTypes={
				0:"不明原因錯誤",
				1:"使用者拒絕提供位置資訊",
				2:"無法取得位置資訊",
				3:"位置查詢逾時"
				};
			alert(errorTypes[error.code]);
		}
}
function address_to_geometry(address){
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
			var result = JSON.parse(xmlHttp.responseText);
			if(result.status=="OK"){
				document.getElementById("location").value = result.results[0].formatted_address;
				var x = document.getElementById('item_location');
				for(var i=0;i<x.options.length;i++){
					if(result.results[0].formatted_address.match(x.options[i].text)==x.options[i].text && x.options[i].text != ""){
						x.options[i].selected = true;
					}
				}
				var temp = {
					lat:result.results[0].geometry.location.lat.toFixed(6),
					lng:result.results[0].geometry.location.lng.toFixed(6)
				};
				geometry = temp;
			}
			else
				geometry='';
		}
    }
    xmlHttp.open( "GET","https://maps.google.com/maps/api/geocode/json?address="+address+"&sensor=false" ); // false for synchronous request
    xmlHttp.send();
}
function pick_submit(){
			test();
	var item_class,item_location,location,item_content;
	item_class = document.getElementById("item_class").value;
	item_location = document.getElementById("item_location").value;
	location = document.getElementById("location").value;
	item_content = document.getElementById("item_content").value;
	if(item_class!='' && item_location!='' && location!='' && item_content!=''){
		$.post("../php/uploaditem.php",
		{
			way:'pick',
			datatype:'json',
			item_class:item_class,
			item_location:item_location,
			location:location,
			item_content:item_content,
			geometry:JSON.stringify(geometry)
		},
		function(data){
			var obj = JSON.parse(data);
			var x = document.getElementById('item_id');
			x.value = obj.item_id;
			if(img_num>0)
				$("#ssi-uploadBtn").click();
			else
				parent.location.href="../index.php?q=drop";
		});
	}
	else{
		alert('請填入物品詳細資料')
	}
}