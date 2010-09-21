<?php
$dir_administration_controleur_utilisateur_php = dirname(__FILE__);
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/modele/Utilisateur.class.php";
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/modele/Photographe.class.php";
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/controleur/ControleurUtils.class.php";

switch($action){
	case reinitialiser_mdp:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Impossible de réinitialiser le mdp de l'utilisateur car aucun id_utilisateur n'a été fourni...<br/>";
			break;
		}
		$id = $_POST['id'];
		$user = Utilisateur::getUtilisateurDepuisID($id);
		if(!$user){
			$_SESSION['message'] .= "Aucun utilisateur ne correspond à cet identifiant.<br/>";
			break;
		}
		$array = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
		'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4',
		'5', '6', '7', '8', '9');
		$mdp = '';
		for($i = 0; $i < 6; $i++){
			$mdp = $mdp . $array[rand(0, (count($array) - 1))];
		}
		$res = $user->saveMDPEtEnvoyerEmail($mdp);
		if($res){//on envoie un email avec le nouveau mdp
			$_SESSION['message'] .= "Mot de passe changé avec succès.<br/>";
		}else{
			$_SESSION['message'] .= "Impossible de changer le mot de passe.<br/>";
		}
	break;
	case renvoyer_email_confirmation:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Impossible d'envoyer un email pour activer l'utilisateur car aucun id_utilisateur n'a été fourni...<br/>";
			break;
		}
		if(!isset($_POST['aid'])){
			$_SESSION['message'] .= "Impossible d'envoyer un email pour activer l'utilisateur car aucun activate_id n'a été fourni...<br/>";
			break;
		}
		$user = Utilisateur::getUtilisateurDepuisID($_POST['id']);
		if(!$user){
			$_SESSION['message'] .= "Aucun utilisateur ne correspond à cet identifiant.<br/>";
			break;
		}
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