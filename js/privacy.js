$(document).ready(function() {
	$('#privacy_form').submit(function() {
		var error = false;
		var mess = '';
		//for all required fields
		var tmp = checkRequired();
		error = error || tmp.error;
		mess += tmp.mess;
		//check others
		if (!re_mail.test($('#email').val())){
			$('#remail').css('background-image','url(design/misc/unchecked.gif)');
	        $('#remail').html('E-mail invalide');
	        error = true;
			mess += "\nVeuillez saisir une adresse E-mail valide";
		} else {
			$('#remail').css('background-image','url(design/misc/checked.gif)');
	        $('#remail').html('');
		}
		if ($('#album').val().length < $('#album').attr('maxlength')){
			$('#ralbum').css('background-image','url(design/misc/unchecked.gif)');
	        $('#ralbum').html('Code album trop court');
	        error = true;
			mess += "\nLe code album doit comporter "+$('#album').attr('maxlength')+" caractères";
		} else {
			$('#ralbum').css('background-image','url(design/misc/checked.gif)');
	        $('#ralbum').html('');
		}
		//check file name
		var re_fileName = new RegExp('^.+[.](PDF|JPG)$');
		if (!re_fileName.test($('#id_file').val().toUpperCase())){
			$('#rid_file').css('background-image','url(design/misc/unchecked.gif)');
	        $('#rid_file').html('Extension de fichier incorrecte');
	        error = true;
			mess += "\nL'extension du fichier doit être pdf ou jpg.";
		} else {
			$('#rid_file').css('background-image','url(design/misc/checked.gif)');
	        $('#rid_file').html('');
		}
		//check others
		if ($('#raison').val().length < 20){
			error = true;
			mess += "\nVotre message semble un peu court";
		}
		if ($('#captcha').val().length < 5){
			error = true;
			mess += "\nVeuillez saisir les 5 caractères de vérification";
		}
		if (error){
			alert(mess);
			return false;
		}
		return true;
	});
});
