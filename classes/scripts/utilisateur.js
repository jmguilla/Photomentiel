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
				if(data.klass == "false"){
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
});