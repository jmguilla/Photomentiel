Array.prototype.contains = function (element) {
	for (var i = 0; i < this.length; i++) {
		if (this[i] == element) {
			return true;
		}
	}
	return false;
}

basketCookieVal = new Array();
function initBasket() {
	var val = readCookie(albumCookieName);
	if (val != ''){
		var values = val.split("-");
		if (values.length <= 1){
			clearCookie(albumCookieName);
		} else {
			for (var i=0;i<values.length-1;i++){
				addToBasketInit(values[i]);
				basketCookieVal[i] = values[i];
			}
			updateBasketInfo();
		}
	}
}
function addToBasketInit(picName){
	appendToBasketDiv(picName);
}
function addToBasket(picName){
	if (!basketCookieVal.contains(picName)) {
		appendToBasketDiv(picName);
		basketCookieVal.push(picName);
		writeCookie(albumCookieName,readCookie(albumCookieName)+picName+'-');
	} else {
		alert("Cette photo est déjà dans votre panier.\nLe nombre d'exemplaires est à préciser en validant le panier.");
	}
	updateBasketInfo();
}
function removeFromBasket(picName){
	var tmp = basketCookieVal;
	var tmpCookie = "";
	basketCookieVal = new Array();
	$("#basket").html("");
	for (var i = 0; i < tmp.length; i++) {
		if (tmp[i] != picName) {
			basketCookieVal.push(tmp[i]);
			tmpCookie += tmp[i]+"-";
			appendToBasketDiv(tmp[i]);
		}
	}
	writeCookie(albumCookieName,tmpCookie);
	updateBasketInfo();
}
function clearBasket(){
	$("#basket").html("");
	basketCookieVal = new Array();
	clearCookie(albumCookieName);
	updateBasketInfo();
}
function updateBasketInfo(){
	var tmp = basketCookieVal.length;
	tmp += ' photo' + (basketCookieVal.length>1?'s':'');
	var tmp_plus='';
	if (basketCookieVal.length>0){
		tmp += ' - <a href="javascript:submitViewBag();">Valider mon panier</a>';
		tmp_plus = '<a title="Vider mon panier" href="javascript:if(confirm(\'Souhaitez vous vraiment vider votre panier ?\')){clearBasket();}">Vider mon panier</a>';
	}
	$("#basket_info_plus").html(tmp_plus);
	$("#basket_info").html(tmp);
}
function appendToBasketDiv(picName){
	$("#basket").append('<div value="'+picName+'" class="basket_content"><a onClick="$(this).displayHidden();return false;" class="thickbox" rel="bag_content" title="Cliquer pour voir ou supprimer du panier" href="'+picsFullDir+picName+'"><img src="'+thumbsFullDir+picName+'"/></a></div>');
}

function submitViewBag(){
	var pics = readCookie(albumCookieName);
	document.getElementById("form_input").value=pics.substr(0,pics.length-1);
	$("#form_viewbag").submit();
}
function mailingCheckMail(){
	if (!re_mail.test($("#mailing").val())){
		alert("Veuillez saisir une adresse Email valide.");
		return false;
	}
	return true;
}
function showPhotographeDetails(){
	$('#photograph_details').css('visibility','visible');
	//$('#photograph_details').fadeIn("slow");
}
function exitPhotographeDetails(){
	//$('#photograph_details').fadeOut("fast");
	$('#photograph_details').css('visibility','hidden');
}

function sendEmailToPhotograph(idPhotographe){
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
	dataToSend.idphotographe=idPhotographe;
	dataToSend.email=$('#p_email').val();
	dataToSend.captcha=$('#p_captcha').val()
	dataToSend.msg=$('#p_content').val();
	dataToSend.action='send_email_photographe';
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
				$('#p_error').html("Une erreur est survenue, vérifier le captcha et recommencer.");
				$('#p_send').removeAttr('disabled');
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 18');
		}
	});
	return false;
}

