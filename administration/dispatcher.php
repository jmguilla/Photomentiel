<?php
@session_start();
$dir_administration_dispatcher_php = dirname(__FILE__);
include_once $dir_administration_dispatcher_php . "/controleur/externalization.php";

if(isset($_POST['action'])){
	$action = $_POST['action'];
}else{
	$action = $_GET['action'];
}
switch($action){
	case valider_album:
	case activer_album:
	case montrer_album:
		include_once $dir_administration_dispatcher_php . "/controleur/controleur_album.php";
	break;
	case traiter_commande:
		include_once $dir_administration_dispatcher_php . "/controleur/controleur_commande.php";
	break;
	case supprimer_evenement:
		include_once $dir_administration_dispatcher_php . "/controleur/controleur_evenement.php";
	break;
	default:
		echo "action inconnue dans dispatcher " . $action;
	break;
}
?>