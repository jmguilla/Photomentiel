$(document).ready(function() {
	$("#submitUtilisateurs").click(function(){
		var email = $("#emailUtilisateurs").val();
		var pwd = $("#pwdUtilisateurs").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=logon&email=" + email + "&pwd=" + pwd,
			dataType:"json",
			success:function(data){
				if(data.result == false){
					alert("utilisateur non trouve");
					return;
				}
				if(data.klass == "Utilisateur" || data.klass == "Photographe"){
					var tmp = "Utilisateur:\n";
					$.each(data.fields, function(key, val){
						tmp = tmp + "\t" + key + ": " + val + "\n";
					});
					alert(tmp);
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
	$("#submitUtilisateurs1").click(function(){
		var nom = $("#nomUtilisateurs1").val();
		var prenom = $("#prenomUtilisateurs1").val();
		var email = $("#emailUtilisateurs1").val();
		var pwd = $("#pwdUtilisateurs1").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=create_utilisateur&nom=" + nom + "&prenom=" + prenom + "&email=" + email + "&pwd="
			+ pwd,
			dataType:"json",
			success:function(data){
				if(data.result == "false"){
					alert("utilisateur non cree");
					return;
				}
				if(data.klass == "Utilisateur" || data.klass == "Photographe"){
					var tmp = "Utilisateur:\n";
					$.each(data.fields, function(key, val){
						tmp = tmp + "\t" + key + ": " + val + "\n";
					});
					alert(tmp);
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
	$("#submitUtilisateurs2").click(function(){
		var id = $("#idUtilisateurs2").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=delete_utilisateur&id=" + id,
			dataType:"json",
			success:function(data){
				if(data.result == false){
					alert("utilisateur non detruit");
					return;
				}else{
					alert("utilisateur detruit");
					return;
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		return false;
	});
	$("#submitUtilisateurs3").click(function(){
		var nom = $("#nomUtilisateurs1").val();
		var prenom = $("#prenomUtilisateurs1").val();
		var email = $("#emailUtilisateurs1").val();
		var pwd = $("#pwdUtilisateurs1").val();
		var rue = $("#rueUtilisateurs1").val();
		var cmp = $("#cmpUtilisateurs1").val();
		var cp = $("#cpUtilisateurs1").val();
		var ville = $("#villeUtilisateurs1").val();
		var ne = $("#neUtilisateurs3").val();
		var siren = $("#sirenUtilisateurs3").val();
		var tel = $("#telUtilisateurs3").val();
		var web = $("#webUtilisateurs3").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=create_photographe&nom=" + nom + "&prenom=" + prenom + "&email=" + email + "&pwd="
			+ pwd + "&rue=" + rue + "&cmp=" + cmp + "&cp=" + cp + "&ville=" +ville + "&ne=" + ne + 
			"&siren=" + siren + "&tel=" + tel + "&web=" + web,
			dataType:"json",
			success:function(data){
				if(data.result == false){
					alert("photographe non cree");
					return;
				}
				if(data.klass == "Utilisateur" || data.klass == "Photographe"){
					var tmp = data.klass + ":\n";
					$.each(data.fields, function(key, val){
						tmp = tmp + "\t" + key + ": " + val + "\n";
					});
					alert(tmp);
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