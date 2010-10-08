var re_mail = new RegExp('^.+@.+[.].+$');
var previousMail;
photographMode = false;
createMode = false;
nextPage = false;
function checkPasswords(){
	var errorPWD = false;
	var mess = '';
	var pwd1 = trim($("#pwd").val());
    var pwd2 = trim($("#pwd2").val());
    if((!createMode && pwd1.length+pwd2.length > 0) || pwd1 != ''){
    	if (pwd1 != pwd2){
	        $('#rpwd2').css('background-image','url(design/misc/unchecked.gif)');
	        $('#rpwd2').html('Mot de passe différent');
	        if(!errorPWD) {
	        	mess = mess + "\nVos mots de passe ne sont pas identiques.";
	        	errorPWD = true;
	        }
        } else {
	    	$('#rpwd2').css('background-image','url(design/misc/checked.gif)');
	        $('#rpwd2').html('');
	    }
    }
    return { "error" : errorPWD, "mess" : mess };
}
function checkMail(){
    var errorEmail = false;
    var mess = '';
    var email = $('#email').val();
	if (!re_mail.test(email)){
    	if(!errorEmail){
            $("#remail").css('background-image','url(design/misc/unchecked.gif)');
            $("#remail").html('E-mail invalide');
            mess = mess + "\nVotre E-mail n'est pas valide.";
    		errorEmail = true;
    	}
    }else{
    	if (previousMail != email){
    		$('#remail').css('background-image','url(design/misc/form_loading.gif)');
    		$("#remail").html('');
			$.ajax({
				type: "GET",
				url: "/dispatcher.php",
				data:"action=check_email&email=" + email,
				dataType:"json",
				async:false, 
				success:function(data){
					if(data.value == false){
						$('#remail').css('background-image','url(design/misc/unchecked.gif)');
						$("#remail").html('<b>E-mail déjà utilisé</b>');
						mess = mess + "\nCet E-mail est déjà utilisé.";
						errorEmail = true;
					} else {
						$('#remail').css('background-image','url(design/misc/checked.gif)');
						previousMail = email;
						errorEmail = false;
					}
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					$('#remail').css('background-image','url(null)');
					alert('Error with code 2');
				}
			});
		}
    }
    return { "error" : errorEmail, "mess" : mess };
}
function queryCPFromVille(ville){
	$.ajax({
		type: "GET",
		url: "/dispatcher.php",
		data:"action=get_ville_from_nom&nom="+ville,
		dataType:"json",
		success:function(data){
			if(data.result == true && $('#code_postal').val() == ''){
				$('#code_postal').val(data.value[0].fields.CodePostal);
				checkMaxChar('code_postal');
			}
		}
	});
}
function queryVilleFromCP(cp){
	$.ajax({
		type: "GET",
		url: "/dispatcher.php",
		data:"action=get_ville_from_cp&cp="+cp,
		dataType:"json",
		success:function(data){
			if(data.result == true && $('#ville').val() == ''){
				$('#ville').val(data.value[0].fields.Nom);
				checkRequired('ville');
			}
		}
	});
}
function checkSIREN(){
	var errorSiren = false;
	var mess = '';
	if ($('#siren').val() != ''){
		var siren = parseInt($('#siren').val());
		var sum = 0;
		for (var i=0;i<9;i++){
			sum += siren%10*(i%2==0?1:2);
			siren = parseInt(siren/10);
		}
		if (sum%10!=0){
			$('#rsiren').css('background-image','url(design/misc/unchecked.gif)');
			$('#rsiren').html('SIREN invalide');
			mess = "\nLe numéro Siren n'est pas valide";
			errorSiren = true;
		}
	}
	return { "error" : errorSiren, "mess" : mess };
}
function checkRIB(){
	var errorRIB = false;
	var mess = '';
	var b = $('#banque').val();
	var g = $('#guichet').val();
	var c = $('#numero_compte').val()+"";
	var r = $('#cle_rib').val();
	if (b != '' && g != '' && c != '' && r != ''){
		b = parseInt(b,10);
		g = parseInt(g,10);
		var tab= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        var tab1="123456789123456789234567890123456789".split("");
        while (c.match(/\D/) != null){
            c=c.replace(/\D/, tab1[tab.indexOf(c.match(/\D/))]);
        }
		d = parseInt(c.substring(0,6),10);
		c = parseInt(c.substring(6),10);
		r = parseInt(r,10);
		var cle = 97 - ((89 * b + 15 * g + 76 * d + 3 * c) % 97);
		if (cle!=r){
			$('#rcle_rib').css('background-image','url(design/misc/unchecked.gif)');
			$('#rcle_rib').html('RIB invalide');
			mess = "\nLe RIB n'est pas valide";
			errorRIB = true;
		}
	}
	return { "error" : errorRIB, "mess" : mess };
}
function checkCGU() {
	var errorCGU = false;
	var mess = '';
	if (!$('#cgu').attr('checked')){
		errorCGU = true;
		mess = "\nVous devez accepter les conditions générales de ventes et d'utilisation";
		$('#rcgu').css('background-image','url(design/misc/unchecked.gif)');
	} else {
		$('#rcgu').css('background-image','url(design/misc/checked.gif)');
	}
	return { "error" : errorCGU, "mess" : mess };
}

function checkForm () {
    var error = false;
    var mess = '';
    //pour tous les champs requis
    var tmp = checkRequired();
    error = error || tmp.error;
    mess += tmp.mess;
    //pour les pwds
    tmp = checkPasswords();
    error = error || tmp.error;
    mess += tmp.mess;
    //pour l'email
    if(createMode){
    	tmp = checkMail();
	    error = error || tmp.error;
	    mess += tmp.mess;
    }
    //pour les champs avec un nombre de char à respecter
    tmp = checkMaxChar();
    error = error || tmp.error;
    mess += tmp.mess;
    //pour les champs avec une regexp
    tmp = checkRegexp();
    error = error || tmp.error;
    mess += tmp.mess;
    if (photographMode){
	    //SIREN
	    tmp = checkSIREN();
	    error = error || tmp.error;
	    mess += tmp.mess;
	    //RIB
	    tmp = checkRIB();
	    error = error || tmp.error;
	    mess += tmp.mess;
	}
	if(createMode){
		tmp = checkCGU();
		error = error || tmp.error;
	    mess += tmp.mess;
	}
	//result
    return { "error" : error, "mess" : mess };
}

function createUserDataAction(){
	var o = new Object();
	o.action = "create_"+(photographMode?"photographe":"utilisateur");
	$('input[class=textfield]').each(function() {
		if (this.name != "pwd2"){
			eval('o.'+this.name+'="'+this.value+'"');
		}
	});
	return o;
}
function updateUserDataAction(){
	var o = new Object();
	o.action = "update_"+(photographMode?"photographe":"utilisateur");
	$('input[class=textfield]').each(function() {
		if (this.name != "pwd2"){
			eval('o.'+this.name+'="'+this.value+'"');
		}
	});
	return o;
}
$(document).ready(function() {
	/***********************************************
	 * controle du formulaire
	 ***********************************************/
	/* on controle que l'email ne soit pas déjà utilisée */
	$('#email').blur(function(){
		checkMail();
	});
	/* on controle les passwords */
	if (createMode){
		$('#pwd').blur(function(){
			checkRequired('pwd');
		});
	}
	$('#pwd2').blur(function(){
		$('#rpwd2').css('background-image','url(null)');
		$('#rpwd2').html('');
		checkPasswords();
	});
	/* on s'assure que le nom n'est pas vide */
	$('#nom').blur(function(){
		checkRequired('nom');
	});
	/* on s'assure que le prenom n'est pas vide */
	$('#prenom').blur(function(){
		checkRequired('prenom');
	});
	/* on s'assure que l'adresse 1 n'est pas vide */
	$('#adresse1').blur(function(){
		checkRequired('adresse1');
	});
	/* code postal */
	$('#code_postal').blur(function(){
		if (!checkMaxChar('code_postal').error && !checkRegexp('code_postal').error){
			queryVilleFromCP($('#code_postal').val());
		}
	});
	/* ville */
	$('#ville').blur(function(){
		if (!checkRequired('ville').error){
			queryCPFromVille($('#ville').val());
		}
	});
	if (photographMode){
		/* telephone */
		$('#telephone').blur(function(){
			checkRegexp('telephone');
		});
		/* siren */
		$('#siren').blur(function(){
			if (!checkMaxChar('siren').error && !checkRegexp('siren').error && !checkSIREN().error){}
		});
		/* banque */
		$('#banque').blur(function(){
			if (!checkMaxChar('banque').error && !checkRegexp('banque').error){}
		});
		/* guichet */
		$('#guichet').blur(function(){
			if (!checkMaxChar('guichet').error && !checkRegexp('guichet').error){}
		});
		/* numero compte */
		$('#numero_compte').blur(function(){
			$('#numero_compte').val($('#numero_compte').val().toUpperCase());
			if (!checkMaxChar('numero_compte').error && !checkRegexp('numero_compte').error){}
		});
		/* cle RIB */
		$('#cle_rib').blur(function(){
			if (!checkMaxChar('cle_rib').error && !checkRegexp('cle_rib').error && !checkRIB().error){}
		});
	}

	/**************************************
	 * Soumission du formulaire
	 **************************************/
	$('#createUser').submit(function() {
		var isValid = checkForm();
		if(!isValid.error) {
			$('#userSubmit').attr("disabled","true");
			$.ajax({
				type: "POST",
				url: "/dispatcher.php",
				data: createUserDataAction(),
				dataType:"json",
				success:function(data){
					if(data.result == false){
						alert(data.cause);
						return;
					}
					if(data.value.klass == "Utilisateur" || data.value.klass == "Photographe"){
						//alert("Votre compte vient d'être créé.\nVous allez recevoir un email de confirmation pour activer votre compte.");
						previousMail='';
						if (nextPage != 'false'){
							document.location.href = "adduser.php?action=ac&np="+nextPage;
						} else {
							document.location.href = "adduser.php?action=ac";
						}
					}else{
						alert(data.cause);
					}
					$('#userSubmit').removeAttr("disabled");
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					alert('Error with code 3');
				}
			});
		}else{
			alert(isValid.mess);
		}
		return false;
	});
	$('#updateUser').submit(function() {
		var isValid = checkForm();
		if(!isValid.error) {
			$('#userSubmit').attr("disabled","true");
			$.ajax({
				type: "POST",
				url: "/dispatcher.php",
				data: updateUserDataAction(),
				dataType:"json",
				success:function(data){
					if(data.result == false){
						alert(data.cause);
						return;
					}
					if(data.value.klass == "Utilisateur" || data.value.klass == "Photographe"){
						alert("Votre compte a été modifié avec succés.");
						previousMail='';
						if (nextPage != 'false'){
							document.location.href = nextPage;
						}/* else {
							history.back();
						}*/
					}else{
						alert(data.cause);
					}
					$('#userSubmit').removeAttr("disabled");
				},
				error:function(XMLHttpRequest, textStatus, errorThrown){
					alert('Error with code 8');
				}
			});
		}else{
			alert(isValid.mess);
		}
		return false;
	});
});
