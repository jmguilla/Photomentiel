<?php
$start = microtime(true);

// Affiche la liste des regions
include_once("../modele/modele_dao/RegionDAO.class.php");
include_once("../modele/modele_dao/DepartementDAO.class.php");
include_once("../modele/modele_dao/VilleDAO.class.php");
include_once("../modele/Region.class.php");
include_once("../modele/Departement.class.php");
include_once("../modele/Ville.class.php");

$regionDAO = new RegionDAO();
$departementDAO = new DepartementDAO(); 
$villeDAO = new VilleDAO();


if(php_sapi_name() != "cli") {
	header("Content-Type: text/plain");
}

$regionList = $regionDAO->getRegions();
foreach($regionList as $region) {
	echo "ID:\t".$region->getID()."\n";
	echo "NOM:\t".$region->getNom()."\n";
	$departementList = $departementDAO->getDepartements($region->getID());	
	foreach($departementList as $departement){
		echo"\tNOM:\t".$departement->getNom()."\n";
		echo"\tCODE:\t".$departement->getCode()."\n";		
		$villeList = $villeDAO->getVilleDepuisID_Departement($departement->getID());
		foreach($villeList as $ville){
			echo"\t\tNOM:\t".$ville->getNom()."\n";
			echo"\t\tCP:\t".$ville->getCodePostal()."\n";
			echo"\t\tLAT:\t".$ville->getLattitude()."\n";
			echo"\t\tLON:\t".$ville->getLongitude()."\n";
		}
	
	}
}
$regionDAO->close();
$departementDAO->close();
$villeDAO->close();
echo "\n\n\n";
echo "Time: ".(microtime(true) - $start)."\n\n";
?>