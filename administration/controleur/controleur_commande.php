<?php
$dir_administration_controleur_commande_php = dirname(__FILE__);
include_once $dir_administration_controleur_commande_php . "/../../classes/modele/Commande.class.php";

switch($action){
	case traiter_commande:
		$id = $_POST['id'];
		$result = Commande::setEnCoursDePreparation($id, $_SERVER['REMOTE_USER']);
		if($result){
			$_SESSION['message'] .= "Commande effectuée avec succes.<br/>";
		}else{
			$_SESSION['message'] .= "Impossible de changer l'état de la commande #" . $id . ".<br/>";
		}
	break;
	default:
		$_SESSION['message'] .= "action inconnue dans controleur_album " . $action . "<br/>";
	break;
}
header('Location: commande.php');
exit();
?>