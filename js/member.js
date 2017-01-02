//jquery input ui
$(function(){
	// trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
	if (!String.prototype.trim) {
		(function() {
			// Make sure we trim BOM and NBSP
			var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
			String.prototype.trim = function() {
				return this.replace(rtrim, '');
			};
		})();
	}

	[].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
		// in case the input is already filled..
		if( inputEl.value.trim() !== '' ) {
			classie.add( inputEl.parentNode, 'input--filled' );
		}

		// events:
		inputEl.addEventListener( 'focus', onInputFocus );
		inputEl.addEventListener( 'blur', onInputBlur );
	} );

	function onInputFocus( ev ) {
		classie.add( ev.target.parentNode, 'input--filled' );
	}

	function onInputBlur( ev ) {
		if( ev.target.value.trim() === '' ) {
			classie.remove( ev.target.parentNode, 'input--filled' );
		}
	}
});
//main function click
$(function(){
	$('#sign_up').on('click',function(){
		location.href='../register.html';
	});
	$('#sign_in').on('click',function(){
		$.post('../php/login.php',{
			way:'login',
			email: document.getElementById('email').value,
			password: document.getElementById('password').value,
			dataType: 'json',
			},function(data){
				var obj = JSON.parse(data);
				alert(obj.message);
				if(obj.error==0)
					location.reload();
				else{
					$('#email').val('');
					$('#password').val('');
				}
			}
		);
	});	 
	
	$('#send').on('click',function(){
		if(validate()){
			$.post('../php/register.php',{
				email: document.getElementById('email').value,
				password: document.getElementById('password').value,
				phone: document.getElementById('phone').value,
				dataType:'text',
				},function(data){
					alert(data);
					location.href = 'member.php';
				}
			);
		}
	});
	$('#back').on('click',function(){
		location.href='member.php';
	});
});
//press Enter to next
$(function(){
	$("#email,#password,#phone").keydown(function(event){
	    if(event.which==13){
			//enter click submit
			if($(this).parent().next().attr('type')=='button'){
				$(this).parent().next().trigger('click');
			}
			//enter to next
			else
	  			$(this).parent().next().find('input').focus();
	  	}
	});
});
//validate
function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}
function repeatEmail(email){
	$.post('php/login.php',{
		way:'check_email',
		email: document.getElementById('email').value,
		dataType:'json',
		},function(data){
			var obj = JSON.parse(data);
			return obj.error;
		}
	);
}
function validatePassword(password){
	var re = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
	return re.test(password);
}
function validatePhone(phone){
	var phoneno = /^[0-9]*$/;
	return phoneno.test(phone);
}
function validate() {
	var email = $("#email").val();
	var password = $('#password').val();
	var phone = $('#phone').val();
	if(repeatEmail(email)){
		alert('Repeated Email :(');
		return false;
	}
	else if(!validateEmail(email)){
		alert('Email: ' + email + " is not valid :(");
		return false;
	}
	else if(!validatePhone(phone)){
		alert('Phone: ' + phone + " is not valid :(");
		return false;
	}
	else if(!validatePassword(password)){
		alert('Password require minimum 8 characters at least 1 Alphabet and 1 Number');
		return false;
	}
	else
		return true;
}