<?php
$dir_administration_controleur_utilisateur_php = dirname(__FILE__);
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/modele/Utilisateur.class.php";
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/modele/Photographe.class.php";
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/controleur/ControleurUtils.class.php";

switch($action){
	case renvoyer_email_confirmation:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Impossible d'envoyer un email pour activer l'utilisateur car aucun id_utilisateur n'a été fourni...<br/>";
			break;
		}
		if(!isset($_POST['aid'])){
			$_SESSION['message'] .= "Impossible d'envoyer un email pour activer l'utilisateur car aucun activate_id n'a été fourni...<br/>";
			break;
		}
		$user = Utilisateur::getUtilisateurDepuisID($id);
		$aid = $_POST['aid'];
		if(ControleurUtils::sendValidationEmail($user, $aid)){
			$_SESSION['message'] .= "Email envoyé avec succès<br/>";
		}else{
			$_SESSION['message'] .= "Impossible d'envoyer un email à l'utilisateur<br/>";
		}
	break;
	case activer_utilisateur:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Impossible d'activer l'utilisateur car aucun id n'a été fourni...<br/>";
			break;
		}
		$id = $_POST['id'];
		if(Utilisateur::activerUtilisateur($id)){
			$_SESSION['message'] .= "Utilisateur activé avec succès<br/>";
		}else{			
			$_SESSION['message'] .= "Impossible d'activer l'utilisateur<br/>";
		}
	break;
	default:
		echo "action inconnue dans controleur_photographe " . $action;
	break;
}
header('Location: utilisateur.php');
exit();
?>