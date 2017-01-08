$(function(){
   $('#log_out').on('click',function(){
		$.ajax({
			type: 'POST',
			url: '../php/logout.php',
			success: function(){
				location.reload();
			},
		});
	});

	$.post('../php/chat_friend.php',{
		way:'friend_list',
		dataType:'json',
		},function(data){
			var obj = JSON.parse(data);
			if(!obj.error){
				var list = obj.result;
				//prepare friend list
				for(var i=0;i<list.length;i++){
					$('.list-account > ul').append('<li id="fri'+list[i].friend_id+'">\
						<img src="../image/user-icon.png">\
						<span class="name">'+list[i].friend_name+'</span>\
						<i class="mdi mdi-menu-down"></i>\
						<input type="hidden" value="'+list[i].friend_id+'">\
						</li>');
				}
				//prepare chat view list
				for(var i=0;i<list.length;i++){
					var chat_content = '';
					var chat_time = '';
					var unseen = '';
					if(list[i].last_chat){
						var chat_obj = JSON.parse(list[i].last_chat);
						chat_content = chat_obj.content;
						chat_time = chat_obj.time;
					}
					if(list[i].num_unseen_chat!=0)
						unseen = '('+list[i].num_unseen_chat+')';
					$('.list-text > ul').append('<li id="chat'+list[i].friend_id+'">\
					<img src="../image/user-icon.png">\
					<div class="content-container">\
						<span class="name">'+list[i].friend_name+'</span>\
						<span class="unseen">'+unseen+'</span>\
						<span class="txt">'+chat_content+'</span>\
					</div>\
					<span class="time">'+chat_time+'</span>\
					<input type="hidden" value="'+list[i].friend_id+'">\
					</li>');
				}
			}
		loaded();
		function_listen();
		}
	);


});
//parse GET url
function getQueryVariable(variable) {
    var query = location.search.substring(1);
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == variable) {
            return decodeURIComponent(pair[1]);
        }
    }
   	return "";
}
function function_listen(){
	var friend_id;
	// First route to show
    var GLOBALSTATE = {
        route: '.list-account'
    };


    // Set first Route
    setRoute(GLOBALSTATE.route);
    $('.nav > li[data-route="' + GLOBALSTATE.route + '"]').addClass('active');


      if(getQueryVariable('t')=='setting'){
        //var $target_li = $('.list-setting > ul > li : firstchild');
    		var $target_nav = $('.nav > li[data-route=".list-setting"]');
    		//nav to list-text
    		$target_nav.parent().children().removeClass('active');
            $target_nav.addClass('active');
    		setRoute('.list-post');
    		//change title name
    		$("#head").find('h1').html('設定');
    		// timeout just for eyecandy...
            setTimeout(function() {
                $('.shown').removeClass('shown');
                $('.list-post').addClass('shown');
            		$.post('../php/get_item_post.php',{
            			datatype:'json'
            		},function(data){
            			var obj = JSON.parse(data);
            			//console.log(obj.list.length);
            			for(var i=0;i<obj.result.length;i++){
            				var url = 'https://www.charlie27.me/~test123/index.php?q=drop&s='+obj.result[i].id;
            				var post_name = obj.result[i].location+' '+obj.result[i].class+' '+obj.result[i].time;
            			    if(obj.result[i].type=='drop')
            				  $('.list-post > ul').append('<li><input type="button" value="撿到" class="post_drop"><p onclick="parent.location.href='+"'"+url+"'"+'">'+post_name+'</p><div class="delete_icon"><img src="../image/garbage.png"></div><input type="hidden" name="drop" value="'+obj.result[i].id+'"></li>')
            			    if(obj.result[i].type=='pick')
            				  $('.list-post > ul').append('<li><input type="button" value="遺失" class="post_pick"><p>'+post_name+'</p><div class="delete_icon"><img src="../image/garbage.png"></div><input type="hidden" name="pick" value="'+obj.result[i].id+'"></li>')
            			}
            		});
            		setRoute('.list-post');
            }, 300);
      }

	//when add new friend opening
	if(getQueryVariable('s')!=''){
		var f_name = getQueryVariable('s');
		var $target_li = $('.list-text span:contains('+f_name+')').parent().parent();
		var $target_nav = $('.nav > li[data-route=".list-text"]');
		//nav to list-text
		$target_nav.parent().children().removeClass('active');
        $target_nav.addClass('active');
		setRoute('.list-text');
		//change title name
		$("#head").find('h1').html(f_name);
		//prepare chat record
		$('ul.chat').html('');
		var friend_id = $target_li.find('input').val();
		// timeout just for eyecandy...
        setTimeout(function() {
            $('.shown').removeClass('shown');
            $('.list-chat').addClass('shown');
			prepare_chat_record(friend_id);
        	$target_li.find('.txt').text('');
            setRoute('.list-chat');
            $('.chat-input').focus();
        }, 300);
	}
    // Have to Delegate ripple due to dom manipulation (add)
    $('ul.mat-ripple').on('click', 'li', function(event) {
        if ($(this).parent().hasClass('tiny')) {
            var $ripple = $('<div class="ripple tiny"></div>');
        } else {
            var $ripple = $('<div class="ripple"></div>');
        }
        var x = event.offsetX;
        var y = event.offsetY;

        var $me = $(this);

        $ripple.css({
            top: y,
            left: x
        });

        $(this).append($ripple);

        setTimeout(function() {
            $me.find('.ripple').remove();
        }, 530)
    });

    // Stylechanger
    $(function(){
        var x = 'rgba(51,102,255,1)';
		//console.log(x);
        $('#dynamic-styles').text('.dialog h3 {color: ' + x + '} .i-group input:focus ~ label,.i-group input.used ~ label {color: ' + x + ';} .bar:before,.bar:after {background:' + x + '} .i-group label {color: ' + x + ';} ul.nav > li.active {color:' + x + '} .style-tx {color: ' + x + ';}.style-bg {background:' + x + ';color: white;}@keyframes navgrow {100% {width: 100%;background-color: ' + x + ';}} ul.list li.context {background-color: ' + x + '}');
    });

    function closeModal() {
        $('#new-user').val('');
        $('.overlay').removeClass('add');
        $('.floater').removeClass('active');
        $('#contact-modal').fadeOut();

        $('#contact-modal').off('click', '.btn.save');

    }

    function setModal(mode, $ctx) {
        var $mod = $('#contact-modal');
        switch (mode) {
            case 'edit':
                $mod.find('h3').text('Edit Name');
				//console.log('a',$ctx.text(),'a');
                $mod.find('#new-user').val($ctx.text()).addClass('used');
                break;
        }

        $mod.fadeIn();
        $('.overlay').addClass('add');
        $mod.find('#new-user').focus();
    }
    // Set Routes - set floater
    function setRoute(route) {
        GLOBALSTATE.route = route;
        $(route).addClass('shown');
        if (route === '.list-chat') {
            $('#content').addClass('chat');
        } else {
            $('#content').removeClass('chat');
        }
		if(route==='.list-text')
			$("#head").find('h1').html('訊息');
		if(route==='.list-account')
			$("#head").find('h1').html('好友');
		if(route==='.list-setting')
			$("#head").find('h1').html('設定');

	}


	//click into my post
	$('#my_post').on('click',function(){
		$('.shown').removeClass('shown');
		$.post('../php/get_item_post.php',{
			datatype:'json'
		},function(data){
			var obj = JSON.parse(data);
			//console.log(obj.list.length);
			for(var i=0;i<obj.result.length;i++){
				var url = 'https://www.charlie27.me/~test123/index.php?q=drop&s='+obj.result[i].id;
				var post_name = obj.result[i].location+' '+obj.result[i].class+' '+obj.result[i].time;
			    if(obj.result[i].type=='drop')
				  $('.list-post > ul').append('<li><input type="button" value="撿到" class="post_drop"><p onclick="parent.location.href='+"'"+url+"'"+'">'+post_name+'</p><div class="delete_icon"><img src="../image/garbage.png"></div><input type="hidden" name="drop" value="'+obj.result[i].id+'"></li>')
			    if(obj.result[i].type=='pick')
				  $('.list-post > ul').append('<li><input type="button" value="遺失" class="post_pick"><p>'+post_name+'</p><div class="delete_icon"><img src="../image/garbage.png"></div><input type="hidden" name="pick" value="'+obj.result[i].id+'"></li>')
			}
		});
		setRoute('.list-post');
	});
	//post delete
    $('.list-post > .list').on('click', 'li', function(e) {
		var target = $(e.target);
		if(target.parent().attr('class')=='delete_icon')
			target = target.parent();
		if(target.attr('class')=='delete_icon'){
			var type = $(this).parent().find('input[type="hidden"]').attr('name');
			var item_id = $(this).parent().find('input[type="hidden"]').attr('value');
			$.post('../php/delete_post.php',{
				way:type,
				item_id:item_id
			},function(data){
				if(data=='OK')
					target.parent().remove();
			});
		}
	});
	//char send
    $('.mdi-send').on('click', function() {
        var msg = $('.list-chat > div > input').val();
		//console.log('friendid',friendid);
		if(msg!=''){
			$('.list-chat > div > input').val('');
			chat_send(friend_id,msg);
		}
    });
	//enter button = send
    $('.chat-input').on('keyup', function(event) {
        event.preventDefault();
        if (event.which === 13) {
            $('.mdi-send').trigger('click');
        }
    });
	//click into chat
    $('.list-text > ul > li').on('click', function() {
		//console.log($(this).text());
		//change title name
		$("#head").find('h1').html($(this).find('div > span').html());
		//prepare chat record
		$('ul.chat').html('');
		friend_id = $(this).children('input').val();
		// timeout just for eyecandy...
        setTimeout(function() {
            $('.shown').removeClass('shown');

            $('.list-chat').addClass('shown');
			prepare_chat_record(friend_id);
        	$(this).find('.unseen').text('');
            setRoute('.list-chat');
            $('.chat-input').focus();
        }, 300);

    });

    // List context
    // Delegating for dom manipulated list elements
    $('.list-account > .list').on('click', 'li', function() {
        $(this).parent().children().removeClass('active');
        $(this).parent().find('.context').remove();
        $(this).addClass('active');
        var $TARGET = $(this);

		//console.log($sib.html());
        if (!$(this).next().hasClass('context')) {
            var $ctx = $('<li class="context"><i class="mdi mdi-pencil"></i><i class="mdi mdi-delete"></i></li>');
			//edit friend
            $ctx.on('click', '.mdi-pencil', function() {
				var $sib = $('#chat'+$(this).parent().parent().find('li:first').attr('id').slice(3));
                setModal('edit', $TARGET.children('span'));
                $('#contact-modal').one('click', '.btn.save', function() {
					var new_edit = $('#new-user').val();
					var target_id = $TARGET.children('input').val();
					//console.log(new_edit,target_id);
					$.post('../php/chat_friend.php',{
						way:'save_friend_name',
						friend_id:target_id,
						value:new_edit,
						dataType:'json',
						},function(data){
							var obj = JSON.parse(data);
							//console.log(data);
						}
					);
                    $TARGET.find('.name').text(new_edit);
					$sib.find('.name').text(new_edit);
                    closeModal();
                });
            });
			//delete friend
            $ctx.on('click', '.mdi-delete', function() {
				var $sib = $('#chat'+$(this).parent().parent().find('li:first').attr('id').slice(3));
                if(confirm('Are you sure delete this Friend ?')){
					var target_id = $TARGET.children('input').val();
					//console.log(new_edit,target_id);
					$.post('../php/chat_friend.php',{
						way:'delete_friend',
						friend_id:target_id,
						dataType:'json',
						},function(data){
							var obj = JSON.parse(data);
							console.log(data);
						}
					);
					$TARGET.remove();
					$sib.remove();
				}
            });


            $(this).after($ctx);
        }
    });

    // Navigation
    $('.nav li').on('click', function() {

        $(this).parent().children().removeClass('active');
        $(this).addClass('active');
        $('.shown').removeClass('shown');
        var route = $(this).data('route');
        $(route).addClass('shown');
        setRoute(route);
    });
    // Filter
    $('.search-filter').on('keyup', function() {
        var filter = $(this).val();
        $(GLOBALSTATE.route + ' .list > li').filter(function() {
            var regex = new RegExp(filter, 'ig');

            if (regex.test($(this).text())) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // killit
    $('#contact-modal').on('click', '.btn.cancel', function() {
        closeModal();
    });

}
