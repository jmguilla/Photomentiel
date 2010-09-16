$(document).ready(function() {
	$('#form_contact').submit(function() {
		if ($('#email').val() == ''){
			alert("Veuillez saisir une adresse E-mail.");
			return false;
		}
		if (!re_mail.test($('#email').val())){
			alert("Veuillez saisir une adresse E-mail valide.");
			return false;
		}
		if ($('#content').val().length < 20){
			alert("Votre message semble un peu court.");
			return false;
		}
		return true;
	});
	$('#content').keyup(function(){
		$('#char_left').html(500-$('#content').val().length);
	});
});

