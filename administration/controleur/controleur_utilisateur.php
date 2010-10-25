<?php
$dir_administration_controleur_utilisateur_php = dirname(__FILE__);
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/modele/Utilisateur.class.php";
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/modele/Photographe.class.php";
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/modele/Album.class.php";
include_once $dir_administration_controleur_utilisateur_php . "/../../classes/controleur/ControleurUtils.class.php";

switch($action){
	case envoyer_confirmation_paiement:
		if(!isset($_POST['id'])){
			echo "<h1>Aucun id photographe fournie</h1>";
			exit();
		}
		$photographe = Photographe::getPhotographeDepuisID($_POST['id']);
		if(ControleurUtils::sendPaiementPhotographeEmail($photographe)){
			echo "<h1>Email de confirmation envoye!</h1>";
		}else{
			echo "<h1>Impossible d'envoyer l'email de confirmation</h1>";
		}
		exit();
	break;
	case modifier_photographe:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id fourni, annulation.<br1>";
			break;
		}
		if(!isset($_POST['pourcentage'])){
			$_SESSION['message'] .= "Aucun pourcentage fourni, annulation.<br/>";
			break;
		}
		$id = $_POST['id'];
		$pourcentage = $_POST['pourcentage'];
		$photographe = Photographe::getPhotographeDepuisID($id);
		if(!$photographe){
			$_SESSION['message'] .= "Aucun photographe ne correspond à cette id, annulation.<br/>";
			break;
		}
		$photographe->setPourcentage($pourcentage);
		if($photographe->save()){
			$_SESSION['message'] .= "Changement de pourcentage effectué avec succès<br/>";
		}else{
			$_SESSION['message'] .= "Impossible de sauver le changement.<br/>";
		}
		header('Location: photographe.php');
		exit();
	case payer:
		if(!isset($_POST['id'])){
			echo "<h1>Aucun id fourni, annulation.</h1>";
			exit();
		}
		$photographe = Photographe::getPhotographeDepuisID($_POST['id']);
		if(!$photographe){
			echo "<h1>Aucun photographe ne correspond à cet id.</h1>";
			exit();
		}
		$albums = Album::getAlbumDepuisID_Photographe($photographe->getPhotographeID(), false);
		$totalAVerser = 0;
		foreach($albums as $album){
			$previousBalance = $album->resetBalance();
			if($previousBalance >= 0){
				$totalAVerser += $previousBalance;
				echo "<h3>Balance reinitialisee pour l'album #" . $album->getAlbumID() . ".</h3>";
			}else{
				echo "<font color=\"red\">Impossible de reinitialiser la balance de l'album #" . $album->getAlbumID() . ".</font><br/>";
			}
		}
		echo "<h1>Versement de " . $totalAVerser . " a effectuer sur le compte:</h1>";
		echo "<h2>rib: " . $photographe->getRIB_b() . $photographe->getRIB_g() . $photographe->getRIB_c() . $photographe->getRIB_k() . "</h2>";
		echo "<h2>iban: " . $photographe->getIBAN() . "</h2>";
		echo "<h2>pour le photographe:</h2>";
		echo "<h2>" . $photographe->getAdresse()->getPrenom() . " " . $photographe->getAdresse()->getNom() . "</h2>";
		echo '<form method="post" action="dispatcher.php" target="_blank"><input type="hidden" name="action" value="envoyer_confirmation_paiement"/><input type="hidden" name="id" value="'.$photographe->getPhotographeID().'"/><input type="submit" value="envoyer confirmation"/></form>';
		exit();
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