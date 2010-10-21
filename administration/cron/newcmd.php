<?php
$_SERVER['SERVER_ADDR'] = "213.186.33.16";
//controle s'il y a des commandes en attente de traitement et envoie un mail les cas où...
$dir_administration_cron_newcmd_php = dirname(__FILE__);
include_once $dir_administration_cron_newcmd_php . '/../../classes/modele/Commande.class.php';
include_once $dir_administration_cron_newcmd_php . '/../AdministrationUtils.class.php';

$commande = Commande::getCommandeEtPhotosDepuisEtat(1);
//au moins une commande à traiter
if($commande && count($commande)>0){
	AdministrationUtils::sendMailCommandeATraiter();
}

?>
