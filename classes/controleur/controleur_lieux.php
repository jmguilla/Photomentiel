<?php
$dir_controleur_lieux_php = dirname(__FILE__);
include_once $dir_controleur_lieux_php . "/../modele/Region.class.php";
include_once $dir_controleur_lieux_php . "/../modele/Departement.class.php";
include_once $dir_controleur_lieux_php . "/../modele/Ville.class.php";
include_once $dir_controleur_lieux_php . "/externalization.php";
include_once $dir_controleur_lieux_php . "/ControleurUtils.class.php";

switch($_GET['action']){
	case l_region:
		$regions = Region::getRegions();
		echo json_encode($regions);
	break;
	case l_dpt_reg://OK
		$regionID = $_GET['regionID'];
		$departements = Departement::getDepartementDepuisID_Region($regionID);
		$result = array();
		if($departements){
			foreach($departements as $departement){
				$result[] = array("id" =>$departement->getID_Departement(), "dpt" => $departement->getNom());
			}
		}
		echo json_encode($result);
	break;
	case g_ville_from_cp:
		$cp = $_GET['cp'];
		$ville = Ville::getVilleDepuisCP($cp);
		$filtre = array("Ville" => array(
				"ID_Ville" => true,
				"ID_Departement" => true,
				"Nom" => true,
				"CodePostal" => true,
				"Lattitude" => true,
				"Longitude" => true
		));
		ControleurUtils::serialize_object_array_json($ville, true, NULL, $filtre);
	break;
	case g_ville_from_nom:
		$nom = $_GET['nom'];
		$ville = Ville::getVilleDepuisNom($nom);
		$filtre = array("Ville" => array(
				"ID_Ville" => true,
				"ID_Departement" => true,
				"Nom" => true,
				"CodePostal" => true,
				"Lattitude" => true,
				"Longitude" => true
		));
		ControleurUtils::serialize_object_array_json($ville, true, NULL, $filtre);
	break;
	case l_ville_dpt:
		$dptID = $_GET['departementID'];
		$villes = Ville::getVilleDepuisID_Departement($dptID);
		$result = array();
		if($villes){
			foreach($villes as $ville){
				$result[] = array("id" =>$ville->getID_Ville(), "dpt" => $ville->getNom());
			}
		}
		echo json_encode($result);
	break;
}
?>