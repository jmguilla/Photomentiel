<?php
$dir_administration_controleur_commande_php = dirname(__FILE__);
include_once $dir_administration_controleur_commande_php . "/../../classes/modele/Commande.class.php";

switch($action){
	case traiter_commande:
		$id = $_POST['id'];
		$result = Commande::setEnCoursDePreparation($id, $_SERVER['PHP_AUTH_USER']);
		if($result){
			$_SESSION['message'] .= "Commande effectuée avec succes.\n";
		}else{
			$_SESSION['message'] .= "Impossible de changer l'état de la commande #" . $id . ".\n";
		}
	break;
	default:
		echo "action inconnue dans controleur_album " . $action;
	break;
}
header('Location: commande.php');
exit();
?>