<?php
$dir_departement_class_php = dirname(__FILE__);
include_once $dir_departement_class_php . "/modele_dao/DepartementDAO.class.php";

class Departement{
	private $id;
	private $region;
	private $code;
	private $nom;
	
	function __construct($i = -1, $r = -1, $c = -1, $n = ''){
		$this->id = $i;
		$this->region = $r;
		$this->code = $c;
		$this->nom = $n;
	}
	/**
	 * Renvoie la liste de tous les departemens
	 */
	public static function getDepartements(){
		$departementDAO = new DepartementDAO();
		return $departementDAO->getDepartements();		
	}
	/**
	 * Renvoie la liste de departement appartenant à la region identifiée par regionID
	 * Enter description here ...
	 * @param int $regionID
	 */
	public static function getDepartementDepuisID_Region($regionID){
		$departementDAO = new DepartementDAO();
		return $departementDAO->getDepartementDepuisID_Region($regionID);		
	}
	/*###########################################
	 * Getters & Setters
	 ###########################################*/
	
	public function getID_Departement(){
		return $this->id;
	}

	public function setID_Departement($id){
		$this->id = $id;
	}

	public function getID_Region(){
		return $this->region;
	}

	public function setID_Region($id){
		$this->region = $id;
	}

	public function getCode(){
		return $this->code;
	}

	public function setCode($code){
		$this->code = $code;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}
}
?>