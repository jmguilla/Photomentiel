<?php
	$dir_bar_php = dirname(__FILE__);
	include_once($dir_bar_php."/../functions.php");
	include_once($dir_bar_php."/../classes/modele/Commande.class.php");
	include_once($dir_bar_php."/../classes/modele/CommandePhoto.class.php");
	include_once($dir_bar_php."/../classes/modele/PrixTaillePapierAlbum.class.php");
	include_once($dir_bar_php."/../classes/modele/TaillePapier.class.php");
	include_once($dir_bar_php."/../classes/modele/Album.class.php");
	include_once($dir_bar_php."/../classes/modele/Photographe.class.php");
	include_once($dir_bar_php."/../classes/controleur/ControleurUtils.class.php");
	include($dir_bar_php."/buildresponse.php");

	//put some logs
	$log = fopen("logs/".date("Ym").".log", 'a');
	fwrite($log, "--------------------------------------------------\n");
	fwrite($log, "transmission_date = $transmission_date\n");
	fwrite($log, "merchant_id = $merchant_id\n");
	fwrite($log, "transaction_id = $transaction_id\n");
	fwrite($log, "amount = $amount\n");
	fwrite($log, "payment_time = $payment_time\n");
	fwrite($log, "payment_date = $payment_date\n");
	fwrite($log, "authorisation_id = $authorisation_id\n");
	fwrite($log, "card_number = $card_number\n");
	fwrite($log, "cvv_flag = $cvv_flag\n");
	fwrite($log, "cvv_response_code = $cvv_response_code\n");
	fwrite($log, "response_code = $response_code\n");
	fwrite($log, "bank_response_code = $bank_response_code\n");
	fwrite($log, "command_id = $caddie\n");
	fwrite($log, "customer_id = $customer_id\n");
	fwrite($log, "customer_ip_address = $customer_ip_address\n");
	fclose($log);

	if ($CB_RETURN_EXIT_CODE == 0){
		//$idCmd contient l'ID de la commande
		if ($bank_response_code=='00' && $response_code=='00'){
			$commandObj = Commande::getCommandeEtPhotosDepuisID($idCmd);
			if ($commandObj->getEtat() == 0){
				$lignes = $commandObj->getCommandesPhoto();
				$coutReel = $commandObj->getFDP();
				$prixTaillePhotos = PrixTaillePapierAlbum::getPrixTaillePapiersDepuisID_Album($commandObj->getID_Album());
				$tailles = TaillePapier::getTaillePapiers();
				foreach($lignes as $ligne){
					$coutReel += $ligne->getNombre() * $tailles[$ligne->getID_TaillePapier()]->getPrixFournisseur();
				}
				//give this command the next state : archive is done when state goes from 0 to 1
				$commandObj->etatSuivant();
				//add x percent of this amout to this album
				$album = $commandObj->getID_Album();
				$album = Album::getAlbumDepuisID($album);
				if ($album){
					$album->updateAmounts(toFloatAmount($amount - $coutReel));
				}
				//send mail with facture
				ControleurUtils::sendFacture($commandObj);
			}
		}
	}

?>
