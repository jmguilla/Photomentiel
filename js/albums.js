function albumString(albumObj, index) {
	if (index%2==0){
		idi = '';
	} else {
		idi = 'id="impair"'; 
	}
	albumObj = albumObj.value[index];
	var album = albumObj.Album.fields;
	var stringID = albumObj.StringID.fields;
	var thumb = albumObj.Thumb;
	var photograph = albumObj.Photographe.fields;
	var adress = photograph.Adresse.fields;
	var event = albumObj.Evenement.fields;
	var tmp = '<div '+idi+' class="album">';
	tmp += '<div class="album_pic"><a href="viewalbum.php?al='+stringID.StringID+'"><img height="88px" src="'+thumb+'"/></a></div>';
	tmp += '<div class="album_link">';
	tmp += '<span class="date"><b>Date</b> : '+convertUSDate(album.Date)+'</span><br/><span class="content">';
	tmp += '<span class="intitule"><a class="intitule" href="viewalbum.php?al='+stringID.StringID+'"><b>Intitulé</b> : '+toNchar(album.Nom,92)+'</a></span><br/>';
	tmp += '<b>Photographe</b> : '+adress.Prenom+' '+adress.Nom+'<br/>';
	tmp += '<b>Contact</b> : <a href="mailto:'+photograph.Email+'">'+photograph.Email+'</a><br/>';
	if (album.ID_Evenement != null && album.ID_Evenement != '') {
		tmp += '<b>Evénement</b> : <a href="events.php?ev='+album.ID_Evenement+'">'+event.Description+'</a><br/>';
	}
	tmp += '</span></div></div>';
	return tmp;
}
//*********************************************************************
//get all albums smart research
function getAlbums(){
	var d1 = $('#dc_from').val();
	var d2 = $('#dc_to').val();
	var kw = $('#keywords').val();
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
	param.action = 'smart_search_album';
	if (d1!='') {param.d1=d1;}
	if (d2!='') {param.d2=d2;}
	if (kw!='') {param.query=kw;}
	$.ajax({
		type:"POST",
		url: "classes/dispatcher.php",
		data:param,
		dataType:"json",
		success:function(data){
			if (data.result == false){
				alert('Impossible de récupérer des albums !');
				return;
			}
			if(data.value == false){
				alert("Aucun album trouvé.");
				return;
			}
			tmp = "";
			for(var i = 0; i < data.value.length; i++){
				tmp += albumString(data,i);
			}
			$('#rcontent').html(tmp);
			$('#rtitle').html("Résultat de votre recherche :");
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 9');
		}
	});
}

