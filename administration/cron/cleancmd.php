<?php
$_SERVER['SERVER_ADDR'] = "213.186.33.16";
//controle s'il y a des commandes en attente de traitement et envoie un mail les cas où...
$dir_administration_cron_newcmd_php = dirname(__FILE__);
include_once $dir_administration_cron_newcmd_php . '/../../classes/modele/Commande.class.php';
include_once $dir_administration_cron_newcmd_php . '/../AdministrationUtils.class.php';

Commande::setTermineePourVielleCommandes();
$commande1 = Commande::getCommandeEtPhotosDepuisEtat(0);
$commande2 = Commande::getCommandeEtPhotosDepuisEtat(4);

list($usec, $sec) = explode(" ", microtime());
$unMois = 60 * 60 * 24 * 31;
$origin = date("Y-m-d", $sec - $unMois);
if($commande1 && !empty($commande1)){
	foreach($commande1 as $commande){
		if($commande->getDate() < $origin){
			echo 'suppression de la commande #' . $commande->getCommandeID() . " etat 0 -> " . (($commande->delete())?"ok":"ko")."<br/>";
		}
	}
}
if($commande2 && !empty($commande2)){
	foreach($commande2 as $commande){
		$datePaiement = $commande->getDatePaiement(); 
		if($datePaiement){
			if($commande->getDatePaiement() < $origin){
				echo 'suppression de la commande #' . $commande->getCommandeID() . " etat 4 -> " . (($commande->delete())?"ok":"ko")."<br/>";
			}
		}
	}
}
?>
