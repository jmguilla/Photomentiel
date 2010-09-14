<?php
$dir_region_class_php = dirname(__FILE__);
include_once $dir_region_class_php . "/modele_dao/RegionDAO.class.php";

class Region{
	private $id;
	private $nom;
	private static $regions;

	function __construct($i = -1, $n = ''){
		$this->id = $i;
		$this->nom = $n;
	}

	public static function getRegions(){
		if(!isset(self::$regions)){
			$regionDAO = new RegionDAO();
			self::$regions = $regionDAO->getRegions();
		}
		return self::$regions;		
	}
	/**
	 * Renvoie la region identifi�e par $id
	 * @param unknown_type $id
	 */
	public static function getRegionDepuisID($id){
		$dao = new RegionDAO();
		return $dao->getRegionDepuisID($id);
	}

	/*###########################################
	 * Getters & Setters
	 ###########################################*/
	public function setID_Region($id){
		$this->id = $id;
	}
	public function getID_Region(){
		return $this->id;
	}
	public function setNom($nom){
		$this->nom = $nom;
	}
	public function getNom(){
		return $this->nom;
	}
}
?>