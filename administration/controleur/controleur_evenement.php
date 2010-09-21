<?php
$dir_administration_controleur_evenement_php = dirname(__FILE__);
include_once $dir_administration_controleur_evenement_php . "/../../classes/modele/Evenement.class.php";

switch($action){
	case supprimer_evenement:
		if(isset($_POST['id'])){
			$id = $_POST['id'];
		}else{
			$_SESSION['message'] .= "ID evenement non trouvé, suppression impossible<br/>";
		}
		$event = Evenement::getEvenementDepuisID($id);
		if(!$event){
			$_SESSION['message'] .= "Aucun évènement ne correspond à l'id #" . $id . "<br/>";
		}else{
			$result = $event->delete();
			if($result){
				$_SESSION['message'] .= "Evènement #" . $id . " supprimé avec succès<br/>";
			}else{
				$_SESSION['message'] .= "Impossible de supprimer l'évènement #" . $id ."<br/>";
			}
		}
	break;
	default:
		$_SESSION['message'] .= "action inconnue dans controleur_evenement " . $action . "<br/>";
	break;
}
header('Location: evenement.php');
exit();
?>