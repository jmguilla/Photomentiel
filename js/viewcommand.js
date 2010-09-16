function deleteCommand(idCmd){
	//TODO
	//AJAX delete commande pour un id command donné
	//method POST
	//condition pour la suppresion (protection) : 
	//   $_SESSION['userID'] existe
	//   la commande appartient a $_SESSION['userID'] ( le ID_user de la commande == $_SESSION['userID'])
	//   l'état de la commande est 0 ou 4
	if(confirm("Souhaitez vous réellement supprimer cette commande ?")){
		alert('Votre commande a été supprimée avec succés !');
		document.location.href = 'myaccount.php';
	}
}