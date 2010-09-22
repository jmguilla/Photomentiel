<?php
/*
 * factures_pdf.php is used to print client's facture
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 19 Sept. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
@session_start();
include_once("functions.php");
include_once("classes/PMError.class.php");
include_once("classes/modele/Commande.class.php");
include_once("classes/modele/CommandePhoto.class.php");
include_once("classes/modele/Utilisateur.class.php");
include_once("classes/modele/TaillePapier.class.php");

if (!isset($_GET['cmd'])){
	photomentiel_die(new PMError("Commande non spécifiée !","Aucune commande spécifiée !"));
}
if(!isset($_SESSION['userID'])){
	photomentiel_die(new PMError("Aucun utilisateur connecté !","Cette page requiert la connexion d'un utilisateur !"));
}

$utilisateurObj = Utilisateur::getUtilisateurDepuisID($_SESSION['userID']);
$command = Commande::getCommandeDepuisID($_GET['cmd']);
if ($command->getID_Utilisateur() != $utilisateurObj->getUtilisateurID()){
	photomentiel_die(new PMError("Commande inapropriée !","Cette commande ne vous appartient pas, que faites vous là ?"));
}

$lines = CommandePhoto::getCommandePhotosDepuisID_Commande($command->getCommandeID());
$command->setCommandesPhoto($lines);

$tmp = TaillePapier::getTaillePapiers();
$photoFormatsDim = array();
foreach($tmp as $tp){
	$photoFormatsDim[$tp->getTaillePapierID()] = $tp->getDimensions();
}

makePDF($command, $utilisateurObj, $photoFormatsDim);

?>
