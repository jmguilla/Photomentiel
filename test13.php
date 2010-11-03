<?php
/*
 * test.php...
 * 
 * Author : SCHIOUFF (All rights reserved)
 * 
 * Created on 2 nov. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
 include_once("classes/modele/Photographe.class.php");
 include_once("classes/modele/Album.class.php");
 include_once("classes/modele/Adresse.class.php");
 include_once("functions.php");
 
 $photograph = Photographe::getPhotographeDepuisID(15);
 $albums = Album::getAlbumDepuisID_Photographe($photograph->getPhotographeID(), false);
 $siren = "123456789";//celui qui fait le virement
 $pm_numFacture = $photograph->getHome()."-".date("Ymd");

 //create facture path
 /*$pm_file = "/homez.368/photomen/cgi-bin/factures/photographes/".date("Ym");
 if (!file_exists($pm_file)){
	 mkdir($pm_file, 0755);
 }
 $pm_file = $pm_file."/".$pm_numFacture.".pdf";*/
 
 makePDFVirement($TVA, $albums, $photograph, $siren, $pm_numFacture/*, $pm_file */);
 
?>
