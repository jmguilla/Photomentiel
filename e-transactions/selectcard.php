<?php
//File to be imported when selecting credit card
function displayCards($p_module, $p_amount, $p_transactionID, $p_userID, $p_albumID){
	//TODO change SIREN value when available
	$SIREN_A  = '000000000';
	$SIREN_JM = '521000018';
	if ($p_module == $SIREN_A){
		//Arnaud
		$parm="merchant_id=013044876511111";
	} else {
		//JM
		$parm="merchant_id=013044876511111";
	}
	
	//needed params
	$parm="$parm merchant_country=fr";
	$parm="$parm amount=$p_amount";
	$parm="$parm currency_code=978";

	// Initialisation du chemin du fichier pathfile
	//   ex :
        //    -> Windows : $parm="$parm pathfile=c:\\repertoire\\pathfile";
        //    -> Unix    : $parm="$parm pathfile=/home/repertoire/pathfile";
    if ($p_module == '521000018'){
		//JM
		$parm="$parm pathfile=/homez.368/photomen/www/e-transactions/payment/param/pathfile";
	} else {
		//Arnaud
		$parm="$parm pathfile=/homez.368/photomen/www/e-transactions/payment/param/pathfile";
	}

	//		Si aucun transaction_id n'est affecté, request en génère
	//		un automatiquement à partir de heure/minutes/secondes
	//		Référez vous au Guide du Programmeur pour
	//		les réserves émises sur cette fonctionnalité
	if (isset($p_transactionID)){
		$parm="$parm transaction_id=$p_transactionID";
	}
	$parm="$parm language=fr";
	$parm="$parm payment_means=CB,2,VISA,2,MASTERCARD,2";
	$parm="$parm header_flag=no";
	//$parm="$parm capture_day=";
	//$parm="$parm capture_mode=";
	//$parm="$parm bgcolor=";
	//$parm="$parm block_align=left";
	//$parm="$parm block_order=";
	//$parm="$parm textcolor=";
	//$parm="$parm receipt_complement=";
	$parm="$parm caddie=$p_albumID";
	$parm="$parm customer_id=$p_userID";
	//$parm="$parm customer_email=";
	$parm="$parm customer_ip_address=".$_SERVER['REMOTE_ADDR'];
	//$parm="$parm data=";
	//$parm="$parm return_context=";
	//$parm="$parm target=";
	//$parm="$parm order_id=";

	//		Les valeurs suivantes ne sont utilisables qu'en pré-production
	//		Elles nécessitent l'installation de vos fichiers sur le serveur de paiement
	//
	//$parm="$parm normal_return_logo=";
	//$parm="$parm cancel_return_logo=";
	//$parm="$parm submit_logo=";
	//$parm="$parm logo_id=";
	//$parm="$parm logo_id2=";
	//$parm="$parm advert=";
	//$parm="$parm background_id=";
	//$parm="$parm templatefile=";

	// Initialisation du chemin de l'executable request
	// ex :
	// -> Windows : $path_bin = "c:\\repertoire\\bin\\request";
	// -> Unix    : $path_bin = "/homez.368/photomen/www/e-transaction/bin/request";
	$path_bin = "/homez.368/photomen/cgi-bin/request";

	//	Appel du binaire request
	$result=exec("$path_bin $parm");

	//	sortie de la fonction : $result=!code!error!buffer!
	//	    - code=0	: la fonction génère une page html contenue dans la variable buffer
	//	    - code=-1 	: La fonction retourne un message d'erreur dans la variable error
	//On separe les differents champs et on les met dans une variable tableau
	$tableau = explode ("!", "$result");
	$code = $tableau[1];
	$error = $tableau[2];
	$message = $tableau[3];

	//  analyse du code retour
  	if (( $code == "" ) && ( $error == "" )) {
  		//erreur d'appel
  		print ("<BR/><CENTER>ERROR</CENTER><BR/>");
  	} else if ($code != 0){
		//Erreur, affiche le message d'erreur
		print ("<br/><center><b><h2>Erreur API de paiement.</h2></center></b>");
		print ("<br/><br/>");
		print ("  message erreur : $error <br>");
	} else {
		//OK, affiche le formulaire HTML
		print ($message);
	}
}
?>
