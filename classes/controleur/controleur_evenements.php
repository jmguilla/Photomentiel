<?php
$dir_controleur_evenement_php = dirname(__FILE__);
include_once $dir_controleur_evenement_php . "/ControleurUtils.class.php";
include_once $dir_controleur_evenement_php . "/../modele/Evenement.class.php";
include_once $dir_controleur_evenement_php . "/../modele/StringID.class.php";
include_once $dir_controleur_evenement_php . "/../modele/Album.class.php";
include_once $dir_controleur_evenement_php . "/../vue/JSONVue.class.php";
include_once $dir_controleur_evenement_php . "/externalization.php";

switch($action){
	case gnp_evt_entre_dates://OKevenement_id, date, ville, departement, description
		$n = 0;$d1 = NULL;$d2 = NULL;
		if(isset($_GET['n'])){
			$n = $_GET['n'];
		}
		if(isset($_GET['d1'])){
			$d1 = $_GET['d1'];
		}
		if(isset($_GET['d2'])){
			$d2 = $_GET['d2'];
		}
		$evenements = Evenement::getNProchainsEvenementsEntreDates($n, $d1, $d2);
		$filtre = array("Evenement" => array(
								"EvenementID" => true,
								"Date" => true,
								"Ville" => true,
								"Departement" => true,
								"Description" => true
								),
						"Ville" => array(
								"ID_Ville" => true,
								"ID_Departement" => true,
								"Nom" => true,
								"CodePostal" => true,
								"Latitude" => true,
								"Longitude" => true
								),
						"Departement" => array(
								"ID_Departement" => true,
								"ID_Region" => true,
								"Code" => true,
								"Nom" => true
								));
		ControleurUtils::serialize_assoc_array_json($evenements, true, NULL, $filtre);
	break;
	case ss_album:
		$query = NULL; $d1 = NULL; $d2 = NULL;
		if(isset($_POST['query'])){
			$query = $_POST['query'];
		}
		if(isset($_POST['d1'])){
			$d1 = $_POST['d1'];
		}
		if(isset($_POST['d2'])){
			$d2 = $_POST['d2'];
		}if(isset($_POST['n'])){
			$n = $_POST['n'];
		}else{
			$n = 10;
		}
		$filtre = array("Album" => array(
								"Date" => true,
								"Nom" => true,
								"ID_Evenement" => true
								),
						"StringID" => array(
								"StringID" =>true
								),
						"Photographe" => array(
								"Telephone" => true,
								"Adresse"  => true
								),
						"Adresse" => array(
								"Prenom" => true,
								"Nom" =>true
								),
						"Evenement" => array(
								"Description" => true
								));
		$albums = Album::smartRechercheAlbumEtImageStringIDEtPhotographeEtEvenement($query, $d1, $d2, true, 2, $n);
		ControleurUtils::serialize_assoc_array_json($albums, true, NULL, $filtre);
	break;
	case ss_evt: 
		$query = NULL; $d1 = NULL; $d2 = NULL; $idr = NULL; $type = NULL;
		if(isset($_POST['query'])){
			$query = $_POST['query'];
		}
		if(isset($_POST['d1'])){
			$d1 = $_POST['d1'];
		}
		if(isset($_POST['d2'])){
			$d2 = $_POST['d2'];
		}
		if(isset($_POST['idr'])){
			$idr = $_POST['idr']; 	
		}
		if(isset($_POST['type'])){
			$type = $_POST['type'];
		}
		if(isset($POST['n'])){
			$n = $_POST['n'];
		}else{
			$n = 10;
		}
		$assocs = Evenement::smartRechercheEvenementEtUtilisateur($query, $d1, $d2, $idr, $type, $n);
		$filtre = array("Utilisateur" => array(
						"Email" => true
						),
						"Photographe" => array(
						"Email" => true
						),
						"Evenement" => array(
						"EvenementID" => true,
						"Date" => true,
						"Web" => true,
						"Type" => true,
						"Description" => true
						));
		if($assocs){
			foreach($assocs as $assoc){
				$assoc['Evenement']->setType($EVENTS_TYPES[$assoc['Evenement']->getType()]);
			}
		}
		ControleurUtils::serialize_assoc_array_json($assocs, true, NULL, $filtre);
	break;
	case a_m_evt:
		if(isset($_GET['email'])){
			$email = $_GET['email'];
		}else{
			$email = NULL;
		}
		if(isset($_GET['id'])){
			$id_evenement = $_GET['id'];
		}else{
			$id_evenement = NULL;
		}
		$evt = Evenement::getEvenementDepuisID($id_evenement);
		$result = $evt->addMailAMailing($email);
		ControleurUtils::serialize_object_json($result, true, NULL);
	break;
	case a_m_album:
		if(isset($_GET['email'])){
			$email = $_GET['email'];
		}else{
			$email = NULL;
		}
		if(isset($_GET['id'])){
			$id_album = $_GET['id'];
		}else{
			$id_album = NULL;
		}
		$album = Album::getAlbumDepuisID($id_album);
		$result = $album->addMailAMailing($email);
		ControleurUtils::serialize_object_json($result, true, NULL);
	break;
	default:
		throw new InvalidArgumentException("Action inconnue dans controlleur evenements: " . $action);
	break;
}
?>
