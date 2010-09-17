<?php
$dir_administration_controleur_photographe_php = dirname(__FILE__);
include_once $dir_administration_controleur_photographe_php . "/../../classes/modele/Utilisateur.class.php";
include_once $dir_administration_controleur_photographe_php . "/../../classes/modele/Photographe.class.php";

switch($action){
	default:
		echo "action inconnue dans controleur_photographe " . $action;
	break;
}
header('Location: photographe.php');
exit();
?>