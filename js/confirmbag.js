function radioChange(){
	if (this.value == 1){
			$('#adress_right').css('border','2px white solid');
			$('#adress_left').css('border','2px blue solid');
			$('div[class=checkform]').each(function() {
		    	$('#'+ this.id).css('background-image','url(null)');
		    });
		} else {
			$('#adress_left').css('border','2px white solid');
			$('#adress_right').css('border','2px blue solid');
		}
}
function queryCPFromVille(ville){
	if ($('#code_postal').val() == ''){
		$('#rville').css('background-image','url(design/misc/form_loading.gif)');
		$.ajax({
			type: "GET",
			url: "/dispatcher.php",
			data:"action=get_ville_from_nom&nom="+ville,
			dataType:"json",
			success:function(data){
				$('#rville').css('background-image','url(null)');
				if(data.result == true && $('#code_postal').val() == ''){
					$('#code_postal').val(data.value[0].fields.CodePostal);
					checkMaxChar('code_postal');
				}
			}
		});
	}
}
function queryVilleFromCP(cp){
	if($('#ville').val() == ''){
		$('#rcode_postal').css('background-image','url(design/misc/form_loading.gif)');
		$.ajax({
			type: "GET",
			url: "/dispatcher.php",
			data:"action=get_ville_from_cp&cp="+cp,
			dataType:"json",
			success:function(data){
				$('#rcode_postal').css('background-image','url(null)');
				if(data.result == true && $('#ville').val() == ''){
					$('#ville').val(data.value[0].fields.Nom);
					checkRequired('ville');
				}
			}
		});
	}
}
$(function() {
	$("input[name=adresses]").change(radioChange);
	//control form
	/* nom */
	$('#nom').blur(function(){
		$('#main_adr2').attr("checked", "true");
		radioChange(); 
		checkRequired('nom');
	});
	/* prenom */
	$('#prenom').blur(function(){
		$('#main_adr2').attr("checked", "true");
		radioChange(); 
		checkRequired('prenom');
	});
	/* adresse 1*/
	$('#adresse1').blur(function(){
		$('#main_adr2').attr("checked", "true");
		radioChange(); 
		checkRequired('adresse1');
	});
	/* adresse 2*/
	$('#adresse2').blur(function(){
		$('#main_adr2').attr("checked", "true");
		radioChange(); 
	});
	/* code postal */
	$('#code_postal').blur(function(){
		$('#main_adr2').attr("checked", "true");
		radioChange();
		if (!checkMaxChar('code_postal').error && !checkRegexp('code_postal').error){
			queryVilleFromCP($('#code_postal').val());
		}
	});
	/* ville */
	$('#ville').blur(function(){
		$('#main_adr2').attr("checked", "true");
		radioChange(); 
		if (!checkRequired('ville').error){
			queryCPFromVille($('#ville').val());
		}
	});
	
	/* submit action */
	$('#adress_selection').submit(function() {
		var isValid = {"error":false,"mess":""};;
		if($('#main_adr').is(':checked')){
			isValid = {"error":false,"mess":""};
		} else {
			isValid = checkForm();
		}
		if(!isValid.error) {
			return true;
		}else{
			alert(isValid.mess);
			return false;
		}
	});
});

function checkForm () {
    var error = false;
    var mess = '';
    //pour tous les champs requis
    var tmp = checkRequired();
    error = error || tmp.error;
    mess += tmp.mess;
    //pour les champs avec un nombre de char à respecter
    tmp = checkMaxChar();
    error = error || tmp.error;
    mess += tmp.mess;
    //pour les champs avec une regexp
    tmp = checkRegexp();
    error = error || tmp.error;
    mess += tmp.mess;
	//result
    return { "error" : error, "mess" : mess };
}
