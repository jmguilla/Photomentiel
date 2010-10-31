<?php
$dir_administration_controleur_commande_php = dirname(__FILE__);
include_once $dir_administration_controleur_commande_php . "/../../classes/modele/Commande.class.php";

switch($action){
	case set_commande_foto:
		$dir_administration_controleur_commande_php = dirname(__FILE__);
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/CommandeFoto.class.php";
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id commande fournie<br/>";
			break;
		}
		if(!isset($_POST['number']) || ''===trim($_POST['number'])){
			$_SESSION['message'] .= "numero de commande foto.com invalide<br/>";
			break;
		}
		$commande = Commande::getCommandeDepuisID($_POST['id']);
		if(!$commande){
			$_SESSION['message'] .= "Aucune commande ne correspond a l'id #".$_POST['id']."#<br/>";
			break;
		}
		if(strstr($_POST['number'], ";")){
			$numbers = explode(";", trim($_POST['number']));
		}else{
			$numbers = array($_POST['number']);
		}
		foreach($numbers as $number){
			$number = trim($number);
			if(!$number || ''===$number){
				continue;
			}
			$cf = new CommandeFoto();
			$cf->setCommandeFoto($number);
			$cf->setID_Commande($_POST['id']);
			if($cf->create()){
				$_SESSION['message'] .= "CommandeFoto #".$number." creee avec succes<br/>";
			}else{
				$_SESSION['message'] .= "Impossible de creer la commandefoto #".$number."<br/>";
			}
		}
	break;
	case offrir_commande:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id, impossible d'offrir la commande<br/>";
			break;
		}
		$id = $_POST['id'];
		$commande = Commande::getCommandeDepuisID($id);
		if($commande->etatSuivant()){
			$_SESSION['message'] .= "Commande offerte<br/>";
		}else{
			$_SESSION['message'] .= "Impossible d'offrir la commande<br/>";
		}
	break;
	case detail_commande:
		$dir_administration_controleur_commande_php = dirname(__FILE__);
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/Utilisateur.class.php";
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/Adresse.class.php";
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/AdresseCommande.class.php";
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/Album.class.php";
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/TaillePapier.class.php";
		include $dir_administration_controleur_commande_php . "/../header.php";
		if(isset($_POST['id'])){
			$id = $_POST['id'];
			$commande = Commande::getCommandeEtPhotosDepuisID($id);
			list($usec, $sec) = explode(" ", microtime());
			echo "<span>Commande #" . $commande->getCommandeID() . " passée le " . $commande->getDate() . " soit  <b>" . round((($sec - strtotime($commande->getDate())) / (60 * 60 * 24)), 0) . " jours</b></span>\n";
			$user = Utilisateur::getUtilisateurDepuisID($commande->getID_Utilisateur());
			$userAdresse = $user->getAdresse();
			$userAdresseCmp = $userAdresse->getComplement();
			$commandeAdresse = $commande->getAdresse();
			$commandeAdresseCmp = $commandeAdresse->getComplement();
			$total = $commande->getFDP();
			$album = NULL;
			foreach($commande->getCommandesPhoto() as $commandePhoto){
				if($album == NULL){
					$album = Album::getAlbumDepuisID($commandePhoto->getID_Album());
				}
				$total += $commandePhoto->getPrix();
			}
			$stringid = StringID::getStringIDDepuisID_Album($album->getAlbumID());
			echo "<table><tr><td>\n";
			echo "<br/>Par:<br/><b>" . $userAdresse->getPrenom() . " " . $userAdresse->getNom() . "</b><br/>\n" . $userAdresse->getNomRue() . "<br/>\n" . ((isset($userAdresseCmp) && $userAdresseCmp!='')?($userAdresseCmp . "<br/>\n"):'') . $userAdresse->getCodePostal() . "<br/>\n" . $userAdresse->getVille() . "<br/>\n";
			echo "</td><td width=\"40px\"></td><td>\n";
			echo "<br/>Destinataire:<br/><b>" . $commandeAdresse->getPrenom() . " " . $commandeAdresse->getNom() . "</b><br/>\n" . $commandeAdresse->getNomRue() . "<br/>\n" . ((isset($commandeAdresseCmp) && $commandeAdresseCmp!='')?($commandeAdresseCmp . "<br/>\n"):'') . $commandeAdresse->getCodePostal() . "<br/>\n" . $commandeAdresse->getVille() . "<br/>\n";
			echo "</td></tr></table><br/>Concernant l'album <b>#" . $album->getAlbumID() . "</b> -  <a target=\"_blank\" href=\"http://www.photomentiel.fr/viewalbum.php?al=" . $stringid->getStringID() . '">' . $album->getNom() . "</a><br/>\n";
			echo "Pour un montant total de <b>" . $total . "&#x20AC;<b><br/><br/>\n";
			echo "Détails:<br/>\n<table>";
			foreach($commande->getCommandesPhoto() as $commandePhoto){
				echo "<tr><td>#" . $commandePhoto->getCommandePhotoID() . "</td><td> - dim: " . TaillePapier::getTaillePapierDepuisID($commandePhoto->getID_TaillePapier())->getDimensions() . "</td><td> - qtt: " . $commandePhoto->getNombre() . "</td><td> - prix: " . $commandePhoto->getPrix() . "&#x20AC;</td><td> - <a href=\"http://www.photomentiel.fr/" . PICTURE_ROOT_DIRECTORY . $stringid->getHomePhotographe() . "/" . $stringid->getStringID() . "/" . PICTURE_DIRECTORY . $commandePhoto->getPhoto() . "\" target=\"_blank\">" . $commandePhoto->getPhoto() . "</a></td></tr>\n";
			}
				echo "<tr><td>fdp: " . $commande->getFDP() . "</td><td></td><td></td><td></td><td></td></tr>\n";
			echo "</table>";
		}else{
			echo "Aucun id commande n'est fournie";
		}
		include $dir_administration_controleur_commande_php . "/../footer.php";
		exit();
	break;
	case supprimer_commande:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "ID commande introuvable, impossible de la supprimer.<br/>\n";
			break;
		}
		$commande = Commande::getCommandeDepuisID($_POST['id']);
		if(!$commande){
			$_SESSION['message'] .= "Aucune commande ne correspond a l'id #".$_POST['id'].".<br/>\n";
			break;
		}
		if($commande->delete()){
			$_SESSION['message'] .= "Commande supprimée avec succès.<br/>\n";
		}else{
			$_SESSION['message'] .= "Impossible de supprimer la commande #" . $_POST['id'] . "<br/>\n";
		}
	break;
	case commande_expediee:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "ID commande introuvable, impossible de changer l'état.<br/>";
			break;
		}
		$commande = Commande::getCommandeDepuisID($_POST['id']);
		if(!$commande){
			$_SESSION['message'] .= "Aucune commande ne correspond à cet identifiant.<br/>";
			break;
		}
		if($commande->getEtat() != 2){
			$_SESSION['message'] .= "Impossible de changer l'état, état courant différent de 2.<br/>";
			break;
		}
		if($commande->etatSuivant()){
			$_SESSION['message'] .= "Etat changé avec succès.<br/>";
		}else{
			$_SESSION['message'] .= "Impossible de changer l'état.<br/>";
		}
	break;
	case traiter_commande:
		$id = $_POST['id'];
		$result = Commande::setEnCoursDePreparation($id, $_SERVER['REMOTE_USER']);
		if($result){
			$_SESSION['message'] .= "Commande effectuée avec succes.<br/>";
		}else{
			$_SESSION['message'] .= "Impossible de changer l'état de la commande #" . $id . ".<br/>";
		}
	break;
	case down_xml:
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/StringID.class.php";
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/TaillePapier.class.php";
		$id = $_POST['id'];
		$commande = Commande::getCommandeEtPhotosDepuisID($id);
		$sid = StringID::getStringIDDepuisID_Album($commande->getID_Album());
		if(!$commande || !$sid){
			echo "Aucune commande ne correspond à l'identifiant #" . $id;
			exit();
		}
		$taillesPapier = TaillePapier::getTaillePapiers();
		$adresse = $commande->getAdresse();
	    header("Content-Type: text/xml");
		echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		echo '<commande id="'.$commande->getCommandeID().'" numero="'.$commande->getNumero().'" id_album="' . $sid->getID_Album() . '" homePhotographe="' . $sid->getHomePhotographe() . '" stringID="' . $sid->getStringID() . '">'."\n";
		echo "	<adresse>\n";
		echo "		<nom>" . $adresse->getNom() . "</nom>\n";
		echo "		<prenom>" . $adresse->getPrenom() . "</prenom>\n";
		echo "		<adresse1>" . $adresse->getNomRue() . "</adresse1>\n";
		echo "		<adresse2>" . $adresse->getComplement() . "</adresse2>\n";
		echo "		<codePostal>" . $adresse->getCodePostal() . "</codePostal>\n";
		echo "		<ville>" . $adresse->getVille() . "</ville>\n";
		echo "	</adresse>\n";
		echo "	<photos>\n";
		$photos = $commande->getCommandesPhoto();
		foreach($photos as $photo){
			echo '		<photo quantite="'.$photo->getNombre().'" dimensions="'.$taillesPapier[$photo->getID_TaillePapier()]->getDimensions().'">'.$photo->getPhoto()."</photo>\n";
		}
		echo "	</photos>\n";
		echo "</commande>\n";
		exit();
	break;
	default:
		$_SESSION['message'] .= "action inconnue dans controleur_album " . $action . "<br/>";
	break;
}
header('Location: commande.php');
exit();
?>

