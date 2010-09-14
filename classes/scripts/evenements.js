$(document).ready(function() {
	$("#submitEvenements1").click(function(){
		var d1 = $("#d1Evenements1").val();
		var d2 = $("#d2Evenements1").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=get_evenements_entre_dates&d1=" + d1 + "&d2=" + d2,
			dataType:"json",
			success:function(data){
				if(data.klass == "false"){
					alert("pas d'evenements trouves");
					return;
				}
				var tmp = "";
				for(var i = 0; i < data.length; i++){
					tmp = tmp + "Evenement:\n";
					if(data[i].klass == "Evenement"){
						var evt = data[i];
						$.each(data[i].fields, function(key, val){
							tmp = tmp + "\t" + key + ": " + val + "\n";
						});
						tmp = tmp + "\n";
					}else{
						alert("failed!!");
					}
				}
				alert(tmp);
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		return false;
	});
	$("#submitEvenements2").click(function(){
		var n = $("#nEvenements2").val();
		var d1 = $("#d1Evenements2").val();
		var d2 = $("#d2Evenements2").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=get_n_premiers_evenements_entre_dates&n=" + n + "&d1=" + d1 + "&d2=" + d2,
			dataType:"json",
			success:function(data){
				if(data.result == false){
					alert("pas d'evenements trouves");
					return;
				}
				var tmp = "";
				for(var i = 0; i < data.length; i++){
					tmp = tmp + "Evenement:\n";
					if(data[i].klass == "Evenement"){
						var evt = data[i];
						$.each(data[i].fields, function(key, val){
							tmp = tmp + "\t" + key + ": " + val + "\n";
						});
						tmp = tmp + "\n";
					}else{
						alert("failed!!");
					}
				}
				alert(tmp);
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		return false;
	});
	$("#submitEvenements3").click(function(){
		var nom = $("#nomEvenements3").val();
		var idp = $("#idpEvenements3").val();
		var ide = $("#ideEvenements3").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=create_album&nom=" + nom + "&idp=" + idp + "&ide=" + ide,
			dataType:"json",
			success:function(data){
				if(data.result == "false"){
					alert("creation d'album impossible");
					return;
				}
				if(data.klass == "Album" && data.result == true){
					var tmp = "Album créé:\n";
					$.each(data.fields, function(key, val){
						tmp = tmp + "\t" + key + ": " + val + "\n";
					});
					tmp = tmp + "\n";
				}else{
					alert("failed!!");
				}
				alert(tmp);
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		return false;
	});
	$("#submitEvenements4").click(function(){
		var id = $("#idEvenements4").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=delete_album&id=" + id,
			dataType:"json",
			success:function(data){
				if(data.result == false){
					alert("impossible de détruire l'album");
					return;
				}
				if(data.result == true){
					alert("album détruit");
				}else{
					alert("failed!!");
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		return false;
	});
});