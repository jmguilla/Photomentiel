<?php
$dir_ville_class_php = dirname(__FILE__);
include_once $dir_ville_class_php . "/modele_dao/VilleDAO.class.php";

class Ville{
	private $id;
	private $departement;
	private $nom;
	private $codePostal;
	private $lattitude;
	private $longitude;

	function __construct($i = -1, $d = -1, $n = '', $c = -1, $lat = -1, $long = -1){
		$this->id = $i;
		$this->departement = $d;
		$this->nom = $n;
		$this->codePostal = $c;
		$this->lattitude = $lat;
		$this->longitude = $long;
	}

	public static function getVilleDepuisID_Departement($departementID = NULL){
		$villeDAO = new VilleDAO();
		return $villeDAO->getVilleDepuisID_Departement($departementID);		
	}
	/**
	 * Renvoie la ville associ�e � $id
	 * @param int $id
	 */
	public static function getVilleDepuisID($id){
		if(!isset($id)){
			throw new InvalidArgumentException("L'id de la ville est necessaire pour retrouver la ville.");
		}
		$dao = new VilleDAO();
		return $dao->getVille($id);
	}
	/**
	 * Renvoie la ville associe a ce code postal ou false.
	 * @param string $cp
	 */
	public static function getVilleDepuisCP($cp){
		if(!isset($cp)){
			throw new InvalidArgumentException("Un code postal est obligatoire pour retrouver une ville.");
		}
		$dao = new VilleDAO();
		return $dao->getVilleDepuisCP($cp);
	}
	/**
	 * Renvoie la ville avec le nom donne ou false
	 * @param string $nom
	 */
	public static function getVilleDepuisNom($nom){
		if(!isset($nom)){
			throw new InvalidArgumentException("Un nom est requis pour retrouver une ville depuis son nom.");
		}
		$dao = new VilleDAO();
		return $dao->getVilleDepuisNom($nom);
	}

	/*###########################################
	 * Getters & Setters
	 ###########################################*/
	public function getNom(){
		return $this->nom;
	}

	public function setNom($n){
		$this->nom = $n;
	}

	public function getCodePostal(){
		return $this->codePostal;
	}

	public function setCodePostal($cp){
		$this->codePostal = $cp;
	}

	public function getLattitude(){
		return $this->lattitude;
	}

	public function setLattitude($lat){
		$this->lattitude = $lat;
	}

	public function getLongitude(){
		return $this->longitude;
	}

	public function setLongitude($long){
		$this->longitude = $long;
	}

	public function getID_Ville(){
		return $this->id;
	}

	public function setID_Ville($id){
		$this->id = $id;
	}

	public function getID_Departement(){
		return $this->departement;
	}

	public function setID_Departement($dep){
		$this->departement = $dep;
	}
}
?>