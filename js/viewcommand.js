function deleteCommand(idCmd){
	if(confirm("Souhaitez vous réellement supprimer cette commande ?")){
		var param = new Object();
		param.action = 'supprimer_commande';
		param.id=idCmd;
		$.ajax({
			type:"POST",
			url: "/dispatcher.php",
			data:param,
			dataType:"json",
			success:function(data){
				if (data.result == false){
					return;
				}
				if(data.value == false){
					alert("Une erreur est survenue lors de la suppression de cette commande.");
					return;
				} else {
					alert('Votre commande a été supprimée avec succés !');
					document.location.href = 'myaccount.php';
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert('Error with code 14');
			}
		});
	}
}
