function specialConvertUSDate(USdateStr){
	var d = USdateStr.split(" ")[0];
	var tmp = d.split("-");
	var y = tmp[0];
	var m = tmp[1];
	var d = tmp[2];
	return d+"/"+m+"/"+y.substring(2);
}
/* check that at least one price is set and each price is lesser than or equals to the min value */
function checkMinPrice(){
	var errorMin = false;
	var mess = '';
	var oneSet = false;
	var numberExist = 0;
	$('input[min]').each(function() {
		numberExist++;
		if ($('#'+ this.id).val() != ''){
			oneSet = true;
			var minPrice = parseFloat($('#'+ this.id).attr('min'));
			if (parseFloat($('#'+ this.id).val()) < minPrice){
				$('#r'+ this.id).css('background-image','url(design/misc/unchecked.gif)');
				$('#r'+ this.id).html('Le prix doit être supérieur ou égal à '+minPrice);
				if(!errorMin) {
				    	mess = mess + "\nCertains prix sont en dessous de la limite fixée.";
				    	errorMin = true;
				}
			}
		}
    	});
	if (!errorMin && numberExist>0 && !oneSet){
		mess = mess + "\nVous devez spécifier le prix d'au moins un format de photos.";
		errorMin = true;
	}
    	return { "error" : errorMin, "mess" : mess };
}
/* check that their is no consecutive values set for pictures formats */
function checkNotCompliant(){
	previous = false;
	current = false;
	result = false;
	$('input[min]').each(function() {
		previous = current;
		current = ($('#'+ this.id).val() != '');
		if (previous && current){
			result = true;
		}
    	});
	return result;
}

function filterEvent(){
	var d = $('#filter_date').val();
	var kw = $('#filter_tf').val();
	if (d != '' && d != 'jj/mm/aaaa' && !re_date.test(d)){
		alert("La date est incorrecte. Veuillez vérifier son format (jj/mm/aaaa) et sa validité !");
		return;
	} else if (d != '' && d != 'jj/mm/aaaa') {
		d = convertDate(d);
	}
	var param = new Object();
	param.action = 'smart_search_evenement';
	if (d!='' && d!='jj/mm/aaaa') {param.d1=d;param.d2=d;}
	if (kw!='' && kw!='mots-clés') {param.query=kw;}
	$.ajax({
		type:"POST",
		url:"/dispatcher.php",
		data:param,
		dataType:"json",
		success:function(data){
			if (data.result == false){
				alert('Impossible de filtrer les évenements !');
				return;
			}
			if(data.value == false){
				alert("Aucun événement trouvé.");
				return;
			}
			tmp = '<option value="0" selected="true">Aucun événement associé</option>';
			for(var i = 0; i < data.value.length; i++){
				var event = data.value[i].Evenement.fields;
				tmp += '<option value="'+event.EvenementID+'">'+specialConvertUSDate(event.Date)+' - '+event.Description+'</option>';
			}
			$('#event').html(tmp);
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 11');
		}
	});
}

function checkRadio(){
	var errorRadio = false;
	var mess = '';
	$('#rpublic').css('background-image','url(null)');
	$('#rpublic').html('');
	if (!$('#public1').attr('checked') && !$('#public2').attr('checked')){
		errorRadio = true;
		mess = "\nChoisissez si votre album est privé ou public";
		$('#rpublic').css('background-image','url(design/misc/unchecked.gif)');
		$('#rpublic').html('Aucun élément sélectionné');
	}
	return { "error" : errorRadio, "mess" : mess };
}

function validForm(update){
	$('input[regexp]').each(function() {
		$('#r'+ this.id).css('background-image','url(null)');
		$('#r'+ this.id).html('');
	});
	$('#rmails').css('background-image','url(null)');
	var error = false;
	var mess = '';
	//pour tous les champs requis
	var tmp = checkRequired();
	error = error || tmp.error;
	mess += tmp.mess;
	if (!update){
		//pour les champs radio
		tmp = checkRadio();
		error = error || tmp.error;
		mess += tmp.mess;
	}
	//pour les champs avec une regexp
	tmp = checkRegexp();
	error = error || tmp.error;
	mess += tmp.mess;
	//pour les champs avec un minimum et au moins 1 set
	if (!tmp.error){
		tmp = checkMinPrice();
	    	error = error || tmp.error;
	    	mess += tmp.mess;
		
	}
	//check mailing list
	tmp = checkRegexp('mails');
	error = error || tmp.error;
	mess += tmp.mess;
	//check format fields
	if (!update && checkNotCompliant() && !confirm("Vous avez sélectionné des formats dont les ratios ne sont pas compatibles.\nCeci peut avoir des répercussions négatives sur l'impression des photos de vos clients.\nContinuer quand même ?")) {
		return;
	}
	//result
	if (error){
		alert(mess);
		return;
	} else {
		$('input[min]').each(function() {
			$('#'+ this.id).val($('#'+ this.id).val().replace(",","."));
	    });
		if (update){
			$('#update_form').submit();
		} else {
			$("#create_submit").attr("disabled","true");
			$('#create_form').submit();
		}
	}
}

function changeAlbumState(){
	if (!$('#cb_gonext').attr('checked')){
		alert("Vous devez cocher la case \"J'ai terminé de télécharger mes photos\" pour valider.");
		return false;
	} else {
		$("#b_gonext").attr("disabled","true");
		return true;
	}
}

function confirmDelAlbum(albsid){
	if (confirm("Etes vous vraiment sûr de vouloir cloturer l'album ?")){
		document.location.href='createalbum.php?action=del&al='+albsid;
	}
}

