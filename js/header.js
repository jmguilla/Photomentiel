function trim (myString) {
	return myString.replace(/^\s+/g,'').replace(/\s+$/g,'')
}
//create mail regexp
var re_mail = new RegExp('^.+@.+[.].+$');
//create data regexp
var re_date = new RegExp('^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[0-9]{4}$');
//check username//pwd is correct and connect if so
function checkUserOrConnect(scriptname){
	if (scriptname != null && scriptname == 'validaccount'){
		$('#form_connect').attr('action','index.php?');
	}
	var email = $("#user_email").val();
	var pwd = $("#user_pwd").val();
	if (email == "" || pwd == ""){
		alert("Vous devez spécifier votre adresse e-mail et votre mot de passe");
		return false;
	}
	if (!re_mail.test(email)){
		alert("Vous devez spécifier une adresse e-mail valide !");
		return false;
	}
	$.ajax({
		type: "POST",
		url: "classes/dispatcher.php",
		data:"action=logon&email=" + email + "&pwd=" + pwd,
		dataType:"json",
		success:function(data){
			$("#form_connect_error").hide();
			if(data.result == true && "-1" != data.value){
				$('#form_connect').submit();
			} else {
				$("#form_connect_error").show();
				$("#user_pwd").val("");
				return;
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 4');
		}
	});
	return false;
}

//check mail for user connection
function checkEmail(){
	var email = $("#user_email").val();
	if (email == ""){
		alert("Spécifiez d'abord votre adresse dans le champ E-mail à gauche.");
		return false;
	}
	if (!re_mail.test(email)){
		alert("Vous devez spécifier une adresse e-mail valide");
		return false;
	}
	$.ajax({
		type: "GET",
		url: "classes/dispatcher.php",
		data:"action=lostpwd&email=" + email,
		dataType:"json",
		success:function(data){
			if (data.result == true){
				alert("Un E-mail vient de vous être envoyé à l'adresse suivante : "+email);
			} else {
				alert(data.cause);
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 1');
		}
	});
	return false;
}
//*********************************************************************
//Convert date : from jj/mm/aaaa -> aaaa-mm-jj
function convertDate(dateStr){
	dateTab = dateStr.split('/');
	return dateTab[2]+"-"+dateTab[1]+"-"+dateTab[0];
}
//convert date from aaaa-mm-jj HH:mm:ss -> jj/mm/aaaa à HHhmm
function convertUSDate(USdateStr){
	var d = USdateStr.split(" ")[0];
	var t = USdateStr.split(" ")[1];
	var tmp = d.split("-");
	var y = tmp[0];
	var m = tmp[1];
	var d = tmp[2];
	tmp = t.split(":");
	var h = tmp[0];
	var mn = tmp[1];
	return d+"/"+m+"/"+y+" à "+h+"h"+mn;
}
//returns the same string but shortened to the given length, '...' are appended when cut
function toNchar(str, len){
	if (str.length <= len){
		return str;
	} else {
		return str.substring(0,len-3)+"...";
	}
}

//*********************************************************************
//function to check fields
function checkRequired(inputID){
	var errorRequired = false;
	var mess = '';
	if (inputID){
		if ($('#'+ inputID).val() == ''){
			$('#r'+ inputID).css('background-image','url(design/misc/unchecked.gif)');
	        $('#r'+ inputID).html('Champ obligatoire');
	        mess = "Le champ ne peut pas être vide";
	        errorRequired = true;
		} else {
			$('#r'+ inputID).css('background-image','url(design/misc/checked.gif)');
			$('#r'+ inputID).html('');
		}
	} else {
		$('input[required=required]').each(function() {
	    	//raz
	    	$('#r'+ this.id).css('background-image','url(design/misc/checked.gif)');
	    	$('#r'+ this.id).html('');
	        if(trim(this.value) == '') {
	            $('#r'+ this.id).css('background-image','url(design/misc/unchecked.gif)');
	            $('#r'+ this.id).html('Champ obligatoire');
	            if(!errorRequired) {
	            	mess = "Veuillez renseigner tous les champs marqués d'une croix.";
	            	errorRequired = true;
	            }
	        }
	    });
	}
	return { "error" : errorRequired, "mess" : mess };
}
function checkMaxChar(inputID){
	var errorNbChar = false;
	var mess = '';
	if (inputID){
		if (trim($('#'+ inputID).val()).length != $('#'+inputID).attr('exactlength')){
			$('#r'+ inputID).css('background-image','url(design/misc/unchecked.gif)');
			$('#r'+ inputID).html('Nombre de caractères incorrect');
			mess = "Le champ ne contient pas le nombre de caractères souhaité.";
			errorNbChar = true;
		} else {
			$('#r'+ inputID).css('background-image','url(design/misc/checked.gif)');
			$('#r'+ inputID).html('');
		}
	} else {
		$('input[maxlength][required=required]').each(function() {
	    	//raz
	    	$('#r'+ this.id).css('background-image','url(design/misc/checked.gif)');
	    	$('#r'+ this.id).html('');
	        if(trim(this.value).length != $('#'+this.id).attr('exactlength')) {
	            $('#r'+ this.id).css('background-image','url(design/misc/unchecked.gif)');
	            $('#r'+ this.id).html('Nombre de caractères incorrect');
	            if(!errorNbChar) {
	            	mess = mess + "\nCertains champs n'ont pas le nombre de caractères souhaité.";
	            	errorNbChar = true;
	            }
	        }
	    });
	}
    return { "error" : errorNbChar, "mess" : mess };
}
function checkRegexp(inputID){
	var errorRegexp = false;
	var mess = '';
	var re_tmp;
	if (inputID){
		if ($('#'+ inputID).val() != ''){
			re_tmp = new RegExp($('#'+ inputID).attr('regexp'));
			if (!re_tmp.test($('#'+ inputID).val())){
				$('#r'+ inputID).css('background-image','url(design/misc/unchecked.gif)');
				$('#r'+ inputID).html('Champ mal formé');
				mess = "Le champ contient des caractères non autorisés";
				errorRegexp = true;
			} else {
				$('#r'+ inputID).css('background-image','url(design/misc/checked.gif)');
				$('#r'+ inputID).html('');
			}
		}
	} else {
		$('input[regexp]').each(function() {
			if ($('#'+ this.id).val() != ''){
				re_tmp = new RegExp($('#'+ this.id).attr('regexp'));
				if (!re_tmp.test($('#'+ this.id).val())){
					$('#r'+ this.id).css('background-image','url(design/misc/unchecked.gif)');
					$('#r'+ this.id).html('Champ mal formé');
					if(!errorRegexp) {
					    	mess = mess + "\nCertain champ contiennent des caractères non autorisés ou sont mal formés.";
					    	errorRegexp = true;
					}
				}
			}
	    	});
	}
    return { "error" : errorRegexp, "mess" : mess };
}
