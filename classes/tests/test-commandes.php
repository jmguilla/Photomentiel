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

echo Commande::setTermineePourVielleCommandes();
?>