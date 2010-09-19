<?php
/*
 * factures.php is used to print client's facture
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 19 Sept. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
 
include("functions.php");
include_once("classes/modele/Commande.class.php");
include_once("classes/modele/CommandePhoto.class.php");
include_once("classes/modele/Utilisateur.class.php");

$command = Commande::getCommandeDepuisID(34);
$lines = CommandePhoto::getCommandePhotosDepuisID_Commande($command->getCommandeID());
$command->setCommandesPhoto($lines);
$user = Utilisateur::getUtilisateurDepuisID($command->getID_Utilisateur());

makePDF($command, $user);
?>