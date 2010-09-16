<?php
	$dir_bar_php = dirname(__FILE__);
	include_once($dir_bar_php."/../functions.php");
	include_once($dir_bar_php."/../classes/modele/Commande.class.php");
	include_once($dir_bar_php."/../classes/modele/CommandePhoto.class.php");
	include_once($dir_bar_php."/../classes/modele/Album.class.php");
	include($dir_bar_php."/buildresponse.php");

	//put some logs
	$log = fopen("logs/".date("Ymd").".log", 'a');
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
		//$numCmd contient le numéro de la commande
		if ($bank_response_code=='00' && $response_code=='00'){
			$commandObj = Commande::getCommandeDepuisID($numCmd);
			if ($commandObj->getEtat() == 0){
				//give this command the next state : archive is done when state goes form 0 to 1
				$commandObj->etatSuivant();
				//TODO add x percent of this amout to this album
				$lines = CommandePhoto::getCommandePhotosDepuisID_Commande($commandObj->getCommandeID());
				if($lines){
					$album = $lines[0]->getID_Album();
					$album = Album::getAlbumDepuisID($album);
					if ($album){//TODO fix percent with photograph's
						$album->setBalance($album->getBalance()+toFloatAmount($amount*0.7));
						$album->setGainTotal($album->getGainTotal()+toFloatAmount($amount*0.7));
						$album->save();
					}
				}
				//TODO send mail with facture
				//ControleurUtils::sendFacture($commandObj);
			}
		}
	}
?>
