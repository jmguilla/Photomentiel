function changePrice(rp, rf, p){
	var target = "#"+rp+rf+"nb";
	var previous = parseInt($(target).val());
	if (isNaN(previous)) {previous=0;}
	if (previous > 0 || p>0){
		//change number
		var n = (p<0)?-1:1;
		if (previous+n>0){
			$(target).val(previous+n);
		} else {
			$(target).val(0);
		}
		//change format tot
		target = "#"+rp+rf+"stot";
		previous = parseFloat($(target).html());
		if (isNaN(previous)) {previous=0;}
		if (previous+p>=0){
			$(target).html(new Number(previous+p).toFixed(2));
		}
		//change pic tot
		target = "#"+rp+"tot";
		previous = parseFloat($(target).html());
		if (isNaN(previous)) {previous=0;}
		if (previous+p>0){
			$(target).html(new Number(previous+p).toFixed(2));
		} else {
			$(target).html("<img src='design/misc/trash.png'></img><br/><font size='1'>Non commandée</font>");
		}
		
		//change total pic
		target = "#total_pic";
		previous = parseFloat($(target).html());
		if (isNaN(previous)) {previous=0;}
		if (previous+p>=0){
			$(target).html(new Number(previous+p).toFixed(2));
		}
		//change shipping rate
		target = "#shipping_rate";
		var totalPic = parseFloat($("#total_pic").html());
		if (totalPic<shippingRateUntil && totalPic!=0) {
			$(target).html(new Number(shippingRate).toFixed(2));
		} else {
			$(target).html(new Number(0).toFixed(2));
		}
		//change total
		target = "#total_total";
		var a = parseFloat($("#total_pic").html());
		var b = parseFloat($("#shipping_rate").html());
		$(target).html(new Number(a+b).toFixed(2));
	}
}

function goPrevious(){
	if (confirm('En retournant sur l\'album, vous perdrez les quantités sélectionnées, continuer quand même ?')){
		document.location.href='viewalbum.php?al='+album_ID;
	}
}
function confirmAndGoNext(){
	//check total > 0
	if (parseInt($('#total_total').html()) == 0){
		alert("Aucune photo n'a été commandée. Veuillez sélectionner vos format et quantités.");
		return false;
	}
	//check pictures to be deleted
	var checkOnce = false;
	var continueAnyway = true;
	$.each($('span[class=stot]'), function() {
		var nb = parseFloat($(this).html());
		if (isNaN(nb) && !checkOnce){
			checkOnce = true;
			continueAnyway = confirm("Au moins une photo n'a pas été commandée. Continuer quand même ?");
		}
	});
	if (!continueAnyway){
		return false;
	}
	//prepare form
	var form = $('#form_confirmbag');
	var index = 0;
	$.each($('input[class=faq_q]'), function() {
		var nb = parseInt($(this).val());
		if (!isNaN(nb) && nb > 0){
			form.append('<input type="hidden" name="pictur_'+index+'" value="'+$(this).attr('picture')+'"></input>');
			form.append('<input type="hidden" name="format_'+index+'" value="'+$(this).attr('formatId')+'"></input>');
			form.append('<input type="hidden" name="number_'+index+'" value="'+$(this).val()+'"></input>');
			index++;
		}
	});
	//clear cookie
	//clearCookie(albumCookieName);
	//send to php session
	form.submit();
}
