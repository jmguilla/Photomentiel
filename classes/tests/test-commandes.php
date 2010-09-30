<?php
$dir_test_commandes_php = dirname(__FILE__);
	$dir_test_commandes_php = dirname(__FILE__);
	include_once($dir_test_commandes_php."/../../functions.php");
	include_once($dir_test_commandes_php."/../../classes/modele/Commande.class.php");
	include_once($dir_test_commandes_php."/../../classes/modele/CommandePhoto.class.php");
	include_once($dir_test_commandes_php."/../../classes/modele/PrixTaillePapierAlbum.class.php");
	include_once($dir_test_commandes_php."/../../classes/modele/TaillePapier.class.php");
	include_once($dir_test_commandes_php."/../../classes/modele/Album.class.php");
	include_once($dir_test_commandes_php."/../../classes/modele/Photographe.class.php");
	include_once($dir_test_commandes_php."/../../classes/controleur/ControleurUtils.class.php");

$commandObj = Commande::getCommandeEtPhotosDepuisID(68);
if ($commandObj){
	$lignes = $commandObj->getCommandesPhoto();
	$amount = $commandObj->getFDP() * 100;
	$coutReel = $commandObj->getFDP();
	$prixTaillePhotos = PrixTaillePapierAlbum::getPrixTaillePapiersDepuisID_Album($commandObj->getID_Album());
	$tailles = TaillePapier::getTaillePapiers();
	foreach($lignes as $ligne){
		$taille = $tailles[$ligne->getID_TaillePapier()];
		$coutReel += $ligne->getNombre() * $taille->getPrixFournisseur();
		$amount += $ligne->getPrix() * 100;
	}
	//give this command the next state : archive is done when state goes from 0 to 1
	//add x percent of this amout to this album
	$album = $commandObj->getID_Album();
	$album = Album::getAlbumDepuisID($album);
	echo "amounts: " . $album->getBalance() . " - " . $album->getGainTotal() . " <br/>";
	echo "cout reel: " . $coutReel . " - amount: " . $amount . "<br/>";
	if ($album){
		$album->updateAmounts(toFloatAmount($amount - ($coutReel*100)));
	}
	echo "amounts: " . $album->getBalance() . " - " . $album->getGainTotal() . " <br/>";
	//send mail with facture
	ControleurUtils::sendFacture($commandObj);
}
?>