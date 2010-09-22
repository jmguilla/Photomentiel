<?php
/*
 * card_pdf.php is used to print photographe's card in PDF file
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
include_once("classes/modele/StringID.class.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Adresse.class.php");

if (!isset($_GET['al'])){
	photomentiel_die(new PMError("Album non spécifié !","Aucun code album spécifié !"));
}
if(!isset($_SESSION['userID'])){
	photomentiel_die(new PMError("Aucun photographe connecté !","Cette page requiert une connexion photographe !"));
}
if ($_SESSION['userClass'] != 'Photographe'){
	photomentiel_die(new PMError("Accés réservé !","Cette page est strictement réservée à nos photographes, que faites vous là ?"));
}

$sidObj = StringID::getStringIDDepuisID($_GET['al']);
if (!$sidObj){
	photomentiel_die(new PMError("Album inexistant !","Ce code album n'existe pas !"));
}

$utilisateurObj = Utilisateur::getUtilisateurDepuisID($_SESSION['userID']);
$album = Album::getAlbumDepuisID($sidObj->getID_Album());
if ($album->getID_Photographe() != $utilisateurObj->getPhotographeID()){
	photomentiel_die(new PMError("Album inaproprié !","Cet album ne vous appartient pas, que faites vous là ?"));
}

makeCard($sidObj->getStringID(), $album, $utilisateurObj);

?>
