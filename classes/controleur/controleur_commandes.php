<?php
$dir_controleur_commande_php = dirname(__FILE__);
include_once $dir_controleur_commande_php . "/ControleurUtils.class.php";
include_once $dir_controleur_commande_php . "/../modele/Commande.class.php";
include_once $dir_controleur_commande_php . "/../modele/CommandePhoto.class.php";
switch($action){
	case s_commande:
		if(isset($_GET['id'])){
			$id = $_GET['id'];
		}else{
			$id = $_POST['id'];
		}
		if(!isset($id)){
			ControleurUtils::serialize_object_json(false, false,"L'id de la commande est nécessaire pour pouvoir la supprimer");
			return;
		}
		$commande = Commande::getCommandeDepuisID($id);
		if(!$commande){
			ControleurUtils::serialize_object_json(false, false,"Aucune commande ne correspond à l'id #" . $id);
			return;			
		}
		if(!isset($_SESSION['userID'])){
			ControleurUtils::serialize_object_json(false, false,"Utilisateur non connecté, impossible de supprimer la commande #" . $id);
			return;
		}
		$user_id = $_SESSION['userID'];
		if($user_id != $commande->getID_Utilisateur()){
			ControleurUtils::serialize_object_json(false, false,"La commande #" . $id . " n'appartient pas à l'utilisateur #" . $user_id);
			return;	
		}
		$result = $commande->delete();
		ControleurUtils::serialize_object_json($result, true, NULL);
		return;
	break;
	default:
		throw new InvalidArgumentException("Action inconnue dans controlleur commande: " . $action);
	break;
}

?>