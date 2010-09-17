<?php
$dir_controleur_commande_php = dirname(__FILE__);
include_once $dir_controleur_commande_php . "/ControleurUtils.class.php";
include_once $dir_controleur_commande_php . "/../modele/Commande.class.php";
include_once $dir_controleur_commande_php . "/../modele/CommandePhoto.class.php";
switch($action){
	case s_commande:
		if(isset($_GET['id'])){
			$id = $_GET['id'];
		}
		if(!isset($id)){
			ControleurUtils::serialize_object_json(false, false,"L'id de la commande est nécessaire pour pouvoir la supprimer");
			return;
		}
		$commande = Commande::getCommandeDepuisID($id);
		if($commande){
			$result = $commande->delete();
			ControleurUtils::serialize_object_json($result, true, NULL);
			return;
		}else{
			ControleurUtils::serialize_object_json(false, false,"Aucune commande avec l'identifiant " . $id);
			return;
		}
	break;
	default:
		throw new InvalidArgumentException("Action inconnue dans controlleur commande: " . $action);
	break;
}

?>