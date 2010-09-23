//preload album banner
$(document).ready(function(){
	banniere_alb_left = new Image();
	banniere_alb_left.src = "design/backgrounds/banniere_alb_left.png";
});
//*********************************************************************
//Public albums slide show
function changeAlbumsContent(){
	$.ajax({
		type: "GET",
		url: "classes/dispatcher.php",
		data:"action=get_random_image_thumb_path&n=1",
		dataType:"json",
		success:function(data){
		   if(data.result == false){
		       return;
		   }else{
				imgTabs[0] = imgTabs[1];
				imgTabs[1] = imgTabs[2];
				imgTabs[2] = imgTabs[3];
				imgTabs[3] = imgTabs[4];
				imgTabs[4] = imgTabs[5];
				$.each(data.value,function(i, assoc){
					var o = new Object();
					o.album_id=assoc.StringID;
					o.thumb=assoc.Thumb;
					imgTabs[5] = o;
				});
				var content="";
				for (var i=0; i < imgTabs.length; i++){
					content += '<a href="viewalbum.php?al='+imgTabs[i].album_id+'&pic='+imgTabs[i].thumb.substring(imgTabs[i].thumb.lastIndexOf("/")+1)+'"><img class="slide_show_image" src="'+imgTabs[i].thumb+'"/></a>';
				}
				$(".slide_show").html(content);
			  }
        	},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			clearInterval(intervalCAC);
			alert('Error with code 5');
		}
	});
}
intervalCAC = setInterval("changeAlbumsContent()",10000);

//*********************************************************************
//check if album existe and go if OK
function displayAlbum(albumId){
	if (albumId.length != stringIdLength){
		$('#tl_error').html("Le code album doit comporter "+stringIdLength+" caractères");
		$('#tl_error').css('padding-top','3px');
		$('#tl_error').css('height','32px');
		$('#tl_error').show();
		return false;
	}
	$.ajax({
		type: "GET",
		url: "classes/dispatcher.php",
		data:"action=get_stringid&sid="+albumId,
		dataType:"json",
		success:function(data){
			$('#tl_error').hide();
			if(data.value != false){
				if (true/*public*/){
					$('#card_form').attr('method','GET');
				}
				$('#card_form').submit();
			} else {
				$('#tl_error').html("Code album incorrect");
				$('#tl_error').css('padding-top','10px');
				$('#tl_error').css('height','25px');
				$('#tl_error').show();
				return;
			}
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 6');
		}
	});
	return false;
}

//*********************************************************************
//get events between 2 dates
function getEvents(){
	var d1 = $('#dc_from').val();
	var d2 = $('#dc_to').val();
	if (d1.length+d2.length==0){
		alert("Veuillez renseigner au moins une date");
		return;
	}
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
	var param = "action=get_n_premiers_evenements_entre_dates&n=3";
	if (d1!=''){param+="&d1="+d1;}
	if (d2!=''){param+="&d2="+d2;}
	$.ajax({
		type:"GET",
		url:"classes/dispatcher.php",
		data:param,
		dataType:"json",
		success:function(data){
			if (data.result == false){
				alert('Impossible de récupérer des événements !');
				return;
			}
			if(data.value == false){
				alert("Aucun événement trouvé.");
				return;
			}
			tmp = "";
			for(var i = 0; i < data.value.length; i++){
				if (i%2==0){
					idi = '';
				} else {
					idi = 'id="impair"'; 
				}
				var event = data.value[i].Evenement.fields;
				tmp += '<a '+idi+' class="last_event" href="events.php?ev='+event.EvenementID+'"><div class="event">';
				tmp += '<span id="event">Date : '+convertUSDate(event.Date)+'</span>';
				tmp += '<span id="event">Lieu : '+event.Ville.fields.Nom+'('+event.Departement.fields.Nom+')</span><br/>';
				tmp += toNchar(event.Description,65)+'</div></a>';
				if (i==2){break;}
			}
			$('#events_height').html(tmp);
		},
		error:function(XMLHttpRequest, textStatus, errorThrown){
			alert('Error with code 7');
		}
	});
}

