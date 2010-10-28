function checkContrat(){
	if ($('#pcontrat').attr('checked') == 0){
		alert("Vous devez accepter les termes du présent contrat");
		return false;
	} else {
		return true;
	}
}

