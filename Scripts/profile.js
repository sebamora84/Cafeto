$(function(){		
	validateFields();
	$( '#savePasswordButton' ).click(function( event ) {
		if ( $('#letter').attr('class') !== "valid" ||
			$('#number').attr('class') !== "valid" ||
			$('#length').attr('class') !== "valid") {
			$('#newPassword').focus();
			return;
		}
		if ( $('#match').attr('class') !== "valid") {
			$('#repeatPassword').focus();
			return;
		}
		currentPassword = $('#currentPassword').val();
		newPassword = $('#newPassword').val();
		$.post("Api/User/ChangePassword.php",
				{currentPassword:currentPassword, newPassword:newPassword },
				function(data, status){
					$('input').val("");
					if(data=="INCORRECT"){
						$('#currentPassword').focus();
						$('#pswd_incorrect').show();
					}
					else{
						redirect = GetURLParameter("uri");
						if(redirect!= null){
							$(location).attr('href',"./"+redirect);	
							return;
						}
						else{
							$(location).attr('href',"./login.html");	
							return;
						}
					}
			});
	});
});

function validateFields(){
		$('#currentPassword').focus(function() {
			$('#pswd_incorrect').hide();
		}).blur(function() {
			$('#pswd_incorrect').hide();
		});
		$('#newPassword').keyup(function() {
			validatePassword();
		}).focus(function() {
			$('#pswd_info').show();
		}).blur(function() {
			$('#pswd_info').hide();
		});
		$('#repeatPassword').keyup(function() {
			validatePassword();
		}).focus(function() {
			$('#pswd_match').show();
		}).blur(function() {
			$('#pswd_match').hide();
		});
		$('#repeatPassword').keyup(function() {
			validatePassword();
		}).focus(function() {
			$('#pswd_match').show();
		}).blur(function() {
			$('#pswd_match').hide();
		});
}

function validatePassword (){
	var pswd = $('#newPassword').val();
	//validate the length
	if ( pswd.length < 8 ) {
		$('#length').removeClass('valid').addClass('invalid');
	} else {
		$('#length').removeClass('invalid').addClass('valid');
	}
	
	//validate letter
	if ( pswd.match(/[A-z]/) ) {
		$('#letter').removeClass('invalid').addClass('valid');
	} else {
		$('#letter').removeClass('valid').addClass('invalid');
	}

	//validate number
	if ( pswd.match(/\d/) ) {
		$('#number').removeClass('invalid').addClass('valid');
	} else {
		$('#number').removeClass('valid').addClass('invalid');
	}
	
	//validate confirm
	var repeatPassword = $('#repeatPassword').val();
	if(pswd===repeatPassword)	{
		$('#match').removeClass('invalid').addClass('valid');
	} else {
		$('#match').removeClass('valid').addClass('invalid');
	}
}


function GetURLParameter(urlParameter){
	var sPageURL = window.location.search.substring(1);
	var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == urlParameter)
        {
            return sParameterName[1];
        }
    }
}
