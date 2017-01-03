$(function(){
	$('#drop').on('click',function(){
		 location.href='index.php?q=drop';
	});
	$('#pick').on('click',function(){
		 location.href='index.php?q=pick';
	});
	$('#member').on('click',function(){
		 location.href='index.php?q=member';
	});
	
	if(getQueryVariable('q')=='drop'){
		if(getQueryVariable('s')!='')
			frames[0].location.href='drop/item.php?e=e&id='+getQueryVariable('s');
		else
			frames[0].location.href='drop/drop.html';
		$('#drop').addClass("select_header");
		$("#member > img").attr("src","image/ic_account_box_white_48dp_1x.png");
	}
	else if(getQueryVariable('q')=='pick'){
		 frames[0].location.href='pick/pick.php';
		$('#pick').addClass("select_header");
		$("#member > img").attr("src","image/ic_account_box_white_48dp_1x.png");
	}
	else if(getQueryVariable('q')=='member'){
		 frames[0].location.href='member/member.php';
 		$('#member').addClass("select_header");
		$("#member > img").attr("src","image/ic_account_box_black_48dp_1x.png");
	}
});
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        if (decodeURIComponent(pair[0]) == variable) {
            return decodeURIComponent(pair[1]);
        }
    }
    return "";
}