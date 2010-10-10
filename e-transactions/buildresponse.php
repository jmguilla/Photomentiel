<?php
	// Récupération de la variable cryptée DATA
	$message="message=$HTTP_POST_VARS[DATA]";
	
	// Initialisation du chemin du fichier pathfile
        //   ex :
        //    -> Windows : $pathfile="pathfile=c:\\repertoire\\pathfile";
        //    -> Unix    : $pathfile="pathfile=/home/repertoire/pathfile";
    $pathfile="pathfile=/homez.368/photomen/www/e-transactions/payment/param/pathfile";

	// Initialisation du chemin de l'executable response
	// ex :
	// -> Windows : $path_bin = "c:\\repertoire\\bin\\response";
	// -> Unix    : $path_bin = "/home/repertoire/bin/response";
	//
	$path_bin = "/homez.368/photomen/cgi-bin/response";

	// Appel du binaire response
	$result=exec("$path_bin $pathfile $message");

	//	Sortie de la fonction : !code!error!v1!v2!v3!...!v29
	//		- code=0	: la fonction retourne les données de la transaction dans les variables v1, v2, ...
	//				: Ces variables sont décrites dans le GUIDE DU PROGRAMMEUR
	//		- code=-1 	: La fonction retourne un message d'erreur dans la variable error
	//	on separe les differents champs et on les met dans une variable tableau
	$tableau = explode ("!", $result);
	//	Récupération des données de la réponse
	$code = $tableau[1];
	$error = $tableau[2];
	$merchant_id = $tableau[3];
	$merchant_country = $tableau[4];
	$amount = $tableau[5];
	$transaction_id = $tableau[6];
	$payment_means = $tableau[7];
	$transmission_date= $tableau[8];
	$payment_time = $tableau[9];
	$payment_date = $tableau[10];
	$response_code = $tableau[11];
	$payment_certificate = $tableau[12];
	$authorisation_id = $tableau[13];
	$currency_code = $tableau[14];
	$card_number = $tableau[15];
	$cvv_flag = $tableau[16];
	$cvv_response_code = $tableau[17];
	$bank_response_code = $tableau[18];
	$complementary_code = $tableau[19];
	$complementary_info = $tableau[20];
	$return_context = $tableau[21];
	$caddie = $tableau[22];
	$receipt_complement = $tableau[23];
	$merchant_language = $tableau[24];
	$language = $tableau[25];
	$customer_id = $tableau[26];
	$order_id = $tableau[27];
	$customer_email = $tableau[28];
	$customer_ip_address = $tableau[29];
	$capture_day = $tableau[30];
	$capture_mode = $tableau[31];
	$data = $tableau[32];
	
	
	$CB_RETURN_EXIT_CODE = -1;
	//  analyse du code retour
  	if (( $code == "" ) && ( $error == "" )){
	  	$CB_RETURN_EXIT_CODE = -2;
 	} else if ( $code != 0 ){
		//	Erreur, affiche le message d'erreur
		$CB_RETURN_EXIT_CODE = -3;
	} else {
		// OK, affichage des champs de la réponse
		$CB_RETURN_EXIT_CODE = 0;
		$idCmd = $caddie;
	}
	
?>
