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
 
include_once("functions.php");
include_once("classes/PMError.class.php");
include_once("classes/modele/StringID.class.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Adresse.class.php");

$sidObj = StringID::getStringIDDepuisID($_GET['al']);
if (!$sidObj){
	photomentiel_die(new PMError("Album inexistant !","Ce code album n'existe pas !"));
}
$album = Album::getAlbumDepuisID($sidObj->getID_Album());
$photograph = Photographe::getPhotographeDepuisID($album->getID_Photographe());


makeCard($sidObj->getStringID(), $album, $photograph);
?>