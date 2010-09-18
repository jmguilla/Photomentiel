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
	case down_xml:
		$id = $_POST['id'];
		$commande = Commande::getCommandeEtPhotosDepuisID($id);
		if(!$commande){
			echo "Aucune commande ne correspond à l'identifiant #" . $id;
			exit();
		}
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/StringID.class.php";
		include_once $dir_administration_controleur_commande_php . "/../../classes/modele/TaillePapier.class.php";
		$taillesPapier = TaillePapier::getTaillePapiers();
		$adresse = $commande->getAdresse();
	    header("Content-Type: text/xml");
		echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		echo "<commande id=\"" . $commande->getCommandeID() . "\">\n";
		echo "	<adresse>\n";
		echo "		<prenom>" . $adresse->getPrenom() . "</prenom>\n";
		echo "		<nom>" . $adresse->getNom() . "</nom>\n";
		echo "		<nomRue>" . $adresse->getNomRue() . "</nomRue>\n";
		echo "		<complement>" . $adresse->getComplement() . "</complement>\n";
		echo "		<codePostal>" . $adresse->getCodePostal() . "</codePostal>\n";
		echo "		<ville>" . $adresse->getVille() . "</ville>\n";
		echo "	</adresse>";
		echo "	<photos>\n";
		$photos = $commande->getCommandesPhoto();
		foreach($photos as $photo){
			echo "		<photo nombre=\"" . $photo->getNombre() . "\" dimensions=\"" . $taillesPapier[$photo->getID_TaillePapier()]->getDimensions() . "\">" . $photo->getPhoto() . "</photo>\n";
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