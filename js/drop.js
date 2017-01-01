$(function(){
	$('#search_bar').hide();
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
	
	//icon click function
	$("#search").on('click',function(){
		$("#search_bar").fadeIn('slow');
	});
	$("#clear").on('click',function(){
		parent.location.href='../index.php?q=drop';
	});
	$("#new").on('click',function(){
		location.href='new.php';
	});
	$("#wrapper").on('mousedown tap',function(){
		$("#search_bar").fadeOut('slow');
	});
	//search GET url
	var sql_search="";
	$("#search_submit").on('click',function(){
		if($('select[name="item_time"]').val())
		 	sql_search+='&stime='+$('select[name="item_time"]').val();
		if($('select[name="item_class"]').val())
		 	sql_search+='&sclass='+$('select[name="item_class"]').val();
		if($('select[name="item_location"]').val())
		 	sql_search+='&slocation='+$('select[name="item_location"]').val();
		parent.location.href='../index.php?q=drop'+sql_search;
	});
	
});
//parse GET url
function getQueryVariable(variable) {
    var query = parent.location.search.substring(1);
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == variable) {
            return decodeURIComponent(pair[1]);
        }
    }
   	return ""; 
}

var items_per_page = 10;
var scroll_in_progress = false;
var myScroll;

load_content = function(refresh, next_page) {
	var stime = getQueryVariable('stime');
	var sclass = getQueryVariable('sclass');
	var slocation = getQueryVariable('slocation');
	
	// This is a DEMO function which generates DEMO content into the scroller.
	// Here you should place your AJAX request to fetch the relevant content (e.g. $.post(...))

	//console.log(refresh, next_page);
	setTimeout(function() { // This immitates the CALLBACK of your AJAX function
		if (!refresh) {
			// Loading the initial content
			$.post('../php/get_item_list.php',{
				way:'drop_item_list',
				stime:stime,
				sclass:sclass,
				slocation:slocation,
				dataType: 'json',
				},function(data){
					var obj = JSON.parse(data);
					//have data or no
					if(obj.error==1){
						$('#wrapper > #scroller > ul').html('');
						$('#wrapper > #scroller > ul').append('<li>查無資料</li>');
					}
					else{
						list = obj.result;
						//clear
						$('#wrapper > #scroller > ul').html('');
						//add top10
						for(var i=0 ; i<items_per_page && i<list.length; i++)
							$('#wrapper > #scroller > ul').append('<li onclick="view_item('+list[i].id+')"><span>種類: '+list[i].class+'</span><span>地點: '+list[i].location+' 時間'+list[i].time+'</span></li>');
					}
					//callback function
					if (myScroll) {
						myScroll.destroy();
						$(myScroll.scroller).attr('style', ''); // Required since the styles applied by IScroll might conflict with transitions of parent layers.
						myScroll = null;
					}
					trigger_myScroll();

					//jquery show
					$('#wrapper > #scroller > ul > li').hide();
					$('#wrapper > #scroller > ul > li').first().show( 200, function showNext() {
						$( this ).next( "li" ).show( 200, showNext );
					});
				}
			);
		} else if (refresh && !next_page) {
			// Refreshing the content
			$.post('../php/get_item_list.php',{
				way:'drop_item_list',
				stime:stime,
				sclass:sclass,
				slocation:slocation,
				dataType: 'json',
				},function(data){
					var obj = JSON.parse(data);
					//have data or no
					if(obj.error==1){
						$('#wrapper > #scroller > ul').html('');
						$('#wrapper > #scroller > ul').append('<li>查無資料</li>');
					}
					else{
						list = obj.result;
						//clear
						$('#wrapper > #scroller > ul').html('');
						//add top10
						for(var i=0 ; i<items_per_page && i<list.length; i++)
							$('#wrapper > #scroller > ul').append('<li onclick="view_item('+list[i].id+')"><div><span>種類: '+list[i].class+'</span><span>地點: '+list[i].location+' 時間'+list[i].time+'</span></div><div></div></li>');
					}
					//callback function
					myScroll.refresh();
					pullActionCallback();
					//jquery show
					$('#wrapper > #scroller > ul > li').hide();
					$('#wrapper > #scroller > ul > li').first().show( 100, function showNext() {
						$( this ).next( "li" ).show( 100, showNext );
					});
				}
			);
		} else if (refresh && next_page) {
			// Loading the next-page content and refreshing
			//add next 10
			for(var i=(next_page-1)*items_per_page ; i<next_page*items_per_page && i<list.length; i++)
				$('#wrapper > #scroller > ul').append('<li onclick="view_item('+list[i].id+')"><span>種類: '+list[i].class+'</span><span>地點: '+list[i].location+' 時間'+list[i].time+'</span></li>');
			//callback function
			myScroll.refresh();
			pullActionCallback();
		}
	}, 200);

};

function pullDownAction() {
	load_content('refresh');
	$('#wrapper > #scroller > ul').data('page', 1);

	// Since "topOffset" is not supported with iscroll-5
	$('#wrapper > .scroller').css({top:0});

}
function pullUpAction(callback) {
	if ($('#wrapper > #scroller > ul').data('page')) {
		var next_page = parseInt($('#wrapper > #scroller > ul').data('page'), 10) + 1;
	} else {
		var next_page = 2;
	}
	load_content('refresh', next_page);
	$('#wrapper > #scroller > ul').data('page', next_page);

	if (callback) {
		callback();
	}
}
function pullActionCallback() {
	if (pullDownEl && pullDownEl.className.match('loading')) {

		pullDownEl.className = 'pullDown';
		pullDownEl.querySelector('.pullDownLabel').innerHTML = '向下拖曳更新';

		myScroll.scrollTo(0, parseInt(pullUpOffset)*(-1), 200);

	} else if (pullUpEl && pullUpEl.className.match('loading')) {

		$('.pullUp').removeClass('loading').html('');

	}
}

var pullActionDetect = {
	count:0,
	limit:10,
	check:function(count) {
		if (count) {
			pullActionDetect.count = 0;
		}
		// Detects whether the momentum has stopped, and if it has reached the end - 200px of the scroller - it trigger the pullUpAction
		setTimeout(function() {
			if (myScroll.y <= (myScroll.maxScrollY + 200) && pullUpEl && !pullUpEl.className.match('loading')) {
				$('.pullUp').addClass('loading').html('<span class="pullUpIcon">&nbsp;</span><span class="pullUpLabel">Loading...</span>');
				pullUpAction();
			} else if (pullActionDetect.count < pullActionDetect.limit) {
				pullActionDetect.check();
				pullActionDetect.count++;
			}
		}, 200);
	}
}

function trigger_myScroll(offset) {
	pullDownEl = document.querySelector('#wrapper .pullDown');
	if (pullDownEl) {
		pullDownOffset = pullDownEl.offsetHeight;
	} else {
		pullDownOffset = 0;
	}
	pullUpEl = document.querySelector('#wrapper .pullUp');	
	if (pullUpEl) {
		pullUpOffset = pullUpEl.offsetHeight;
	} else {
		pullUpOffset = 0;
	}

	if ($('#wrapper ul > li').length < items_per_page) {
		// If we have only 1 page of result - we hide the pullup and pulldown indicators.
		$('#wrapper .pullDown').hide();
		$('#wrapper .pullUp span').hide();
		offset = 0;
	} else if (!offset) {
		// If we have more than 1 page of results and offset is not manually defined - we set it to be the pullUpOffset.
		offset = pullUpOffset;
	}

	myScroll = new IScroll('#wrapper', {
		probeType:1, tap:true, click:false, preventDefaultException:{tagName:/.*/}, mouseWheel:true, scrollbars:true, fadeScrollbars:true, interactiveScrollbars:false, keyBindings:false,
		deceleration:0.0002,
		startY:(parseInt(offset)*(-1))
	});

	myScroll.on('scrollStart', function () {
		scroll_in_progress = true;
	});
	myScroll.on('scroll', function () {

		scroll_in_progress = true;

		if ($('#wrapper ul > li').length >= items_per_page) {
			if (this.y >= 5 && pullDownEl && !pullDownEl.className.match('flip')) {
				pullDownEl.className = 'pullDown flip';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '放開以更新';
				this.minScrollY = 0;
			} else if (this.y <= 5 && pullDownEl && pullDownEl.className.match('flip')) {
				pullDownEl.className = 'pullDown';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = '向下拖曳更新';
				this.minScrollY = -pullDownOffset;
			}

			//console.log(this.y);
			pullActionDetect.check(0);

		}
	});
	myScroll.on('scrollEnd', function () {
		//console.log('scroll ended');
		setTimeout(function() {
			scroll_in_progress = false;
		}, 100);
		if ($('#wrapper ul > li').length >= items_per_page) {
			if (pullDownEl && pullDownEl.className.match('flip')) {
				pullDownEl.className = 'pullDown loading';
				pullDownEl.querySelector('.pullDownLabel').innerHTML = 'Loading...';
				pullDownAction();
			}
			// We let the momentum scroll finish, and if reached the end - loading the next page
			pullActionDetect.check(0);
		}
	});

	// In order to prevent seeing the "pull down to refresh" before the iScoll is trigger - the wrapper is located at left:-9999px and returned to left:0 after the iScoll is initiated
	setTimeout(function() {
		$('#wrapper').css({left:0});
	}, 1000);
}

function loaded(id) {
	load_content();
}

document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
function view_item(id){
	location.href='../drop/item.php?id='+id;
}
function get_position(){
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
			var geocoder;
			geocoder = new google.maps.Geocoder();
			
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
function drop_submit(){
	var item_class,item_location,location,item_content;
	item_class = document.getElementById("item_class").value;
	item_location = document.getElementById("item_location").value;
	location = document.getElementById("location").value;
	item_content = document.getElementById("item_content").value;
	if(item_class!='' || item_location!='' || location!='' || item_content!=''){
		$.post("../php/uploaditem.php",
		{
			way:'drop',
			datatype:'json',
			item_class:item_class,
			item_location:item_location,
			location:location,
			item_content:item_content
		},
		function(data){
			var obj = JSON.parse(data);
			if(data)
				alert("發佈成功")
			location.href('../drop/drop.php');
		});
	}
	else{
		alert('請填入物品詳細資料')
	}
		
		
}