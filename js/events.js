function eventString(eventObj, index) {
	eventObj = eventObj.value[index];
	if (index%2==0){
		idi = '';
	} else {
		idi = 'id="impair"';
	}
	var event = eventObj.Evenement.fields;
	var user = eventObj.Utilisateur.fields;
	var tmp = '<div '+idi+' class="event">';
	tmp += '<span class="date"><b>Date</b> : '+convertUSDate(event.Date)+'&nbsp;&nbsp;&nbsp;&nbsp;<b>Type</b> : '+event.Type+'</span><br/><span class="content">';
	tmp += '<span class="intitule"><a class="intitule" href="events.php?ev='+event.EvenementID+'"><b>Intitulé</b> : '+toNchar(event.Description,100)+'</a></span><br/>';
	if (event.Web != ''){
		tmp += '<a target="_blank" href="'+event.Web+'">Plus de détails sur le lien officiel...</a><br/>';
	} else {
		tmp += 'Site web non communiqué<br/>';
	}
	tmp += '</span></div>';
	return tmp;
}
//*********************************************************************
//get all events between 2 dates and region, keywords and type params
function getEvents(){
	$("#rcontent").css('height','420px');
	var d1 = $('#dc_from').val();
	var d2 = $('#dc_to').val();
	var kw = $('#keywords').val();
	var rg = $('#region').val();
	var tp = $('#type').val();
	if (d1 != '' && !re_date.test(d1)){
		alert("La première date est incorrecte. Veuillez vérifier son format (jj/mm/aaaa) et sa validité !");
		return;
	} else if (d1 != '') {
		d1 = convertDate(d1);
	}
	if (d2 != '' && !re_date.test(d2)){
		alert("La deuxième date est incorrecte. Veuillez vérifier son format (jj/mm/aaaa) et sa validité !");
		return;
	} else if (d2 != '') {
		d2 = convertDate(d2);
	}
	var param = new Object();
	param.action = 'smart_search_evenement';
	param.n=20;
	if (d1!='') {param.d1=d1;}
	if (d2!='') {param.d2=d2;}
	if (kw!='') {param.query=kw;}
	if (rg!=0) {param.idr=rg;}
	if (tp!=-1) {param.type=tp;}
	$('#search').attr('disabled', 'true');
	$.ajax({
		type:"POST",
		url:"/dispatcher.php",
		data:param,
		dataType:"json",
		success:function(data){
			$('#search').removeAttr('disabled');
			if (data.result == false){
				alert('Impossible de récupérer des évenements !');
				return;
			}
			if(data.value == false){
				alert("Aucun événement trouvé.");
				return;
			}
			tmp = "";
			for(var i = 0; i < data.value.length; i++){
				tmp += eventString(data,i);
			}
			$('#rcontent').html(tmp);
			$('#rtitle').html("Résultat de votre recherche :");
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 11');
		}
	});
}
//*********************************  ADD MAILING ********************************
function addMailing(eventID){
	var email = $('#mailing').val();
	if (!re_mail.test(email)){
		alert("L'adresse E-mail spécifiée n'est pas valide.");
		return;
	}
	$.ajax({
		type:"GET",
		url:"/dispatcher.php",
		data:"action=add_mail_to_evenement&email="+email+"&id="+eventID,
		dataType:"json",
		success:function(data){
			if (data.result == false){
				alert('Impossible d\'ajouter cet E-mail.');
				return;
			}
			if (data.value == true){
				$('#span_mailing').html('<u>Vous êtes maintenant inscrit à cet événement</u>');
			} else {
				$('#span_mailing').html('<u>Vous êtes déjà inscrit à cet événement</u>');
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 12');
		}
	});
}
//********************************* FORM FILLING ********************************
function checkForm () {
    var error = false;
    var mess = '';
    //pour tous les champs requis
    var tmp = checkRequired();
    error = error || tmp.error;
    mess += tmp.mess;
    //pour les champs avec une regexp
    tmp = checkRegexp();
    error = error || tmp.error;
    mess += tmp.mess;
	//result
    return { "error" : error, "mess" : mess };
}

$(document).ready(function() {
	$('#fdate').blur(function(){
		if (!checkRequired('fdate').error && !checkRegexp('fdate').error){}
	});
	$('#fhour').blur(function(){
		if (!checkRequired('fhour').error && !checkRegexp('fhour').error){}
	});
	$('#fdesc').blur(function(){
		checkRequired('fdesc');
	});
	$("#fregion").change(function(){
		$.ajax({
			type: "GET",
			url: "/dispatcher.php",
			data:"action=list_departement_par_region&regionID=" + $("#fregion").val(),
			dataType:"json",
			success:function(data){
				var tmp = '';
				for(var x = 0; x < data.length; x++){
					tmp += '<option value=' + data[x].id +'>' + data[x].dpt + '</option>';
				};
				$("#fdepartement").html(tmp);
				changeVille();
			}
		});
	});
	$("#fdepartement").change(function(){
		changeVille();
	});
	$('#event_add_form').submit(function() {
		var isValid = checkForm();
		if(!isValid.error) {
			if ($('#fweb').val() == 'http://') {
				$('#fweb').val('');
			}
			return true;
		}else{
			alert(isValid.mess);
			return false;
		}
	});
});
function changeVille(){
	$.ajax({
		type: "GET",
		url: "/dispatcher.php",
		data:"action=list_ville_par_departement&departementID=" + $("#fdepartement").val(),
		dataType:"json",
		success:function(data){
			var tmp = '';
			for(var x = 0; x < data.length; x++){
				tmp += '<option value=' + data[x].id +'>' + data[x].dpt + '</option>';
			};
			$("#fville").html(tmp);
		}
	});
}

function sendEmailToUser(idUser){
	if ($('#p_email').val() == ''){
		alert("Veuillez saisir une adresse E-mail.");
		return false;
	}
	if (!re_mail.test($('#p_email').val())){
		alert("Veuillez saisir une adresse E-mail valide.");
		return false;
	}
	if ($('#p_content').val().length < 20){
		alert("Votre message semble un peu court.");
		return false;
	}
	if ($('#p_captcha').val().length < 5){
		alert("Veuillez saisir les 5 caractères de vérification.");
		return false;
	}
	var dataToSend = new Object();
	dataToSend.iduser=idUser;
	dataToSend.email=$('#p_email').val();
	dataToSend.captcha=$('#p_captcha').val();
	dataToSend.msg=$('#p_content').val();
	dataToSend.action='send_email_user';
	$('#p_send').attr('disabled', 'true');
	$('#p_error').html("");
	$('#p_success').html("");
	$.ajax({
		type: "POST",
		url: "/dispatcher.php",
		data:dataToSend,
		dataType:"json",
		success:function(data){
			if(data.result == true && data.value == true){
				$('#p_success').html("Votre message a bien été envoyé. Merci.");
			} else {
				$('#p_error').html(data.cause);
				$('#p_send').removeAttr('disabled');
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 18');
		}
	});
	return false;
}
