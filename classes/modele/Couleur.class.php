<?php
$dir_couleur_class_php = dirname(__FILE__);
include_once $dir_couleur_class_php . "/modele_dao/CouleurDAO.class.php";

class Couleur{
	private $couleurID;
	private $description;
	public function __construct($id, $desc){
		$this->couleurID = $id;
		$this->description = $desc;
	}
	/**
	 * Renvoie toutes les couleurs
	 */
	public static function getCouleurs(){
		$dao = new CouleurDAO();
		return $dao->getCouleurs();
	}
	/**
	 * Renvoie la couleur identifiee par $id
	 */
	public static function getCouleurDepuisID($id){
		$dao = new CouleurDAO();
		return $dao->getCouleurDepuisID($id);
	}
	/*##############################
	 * Getters 
	 #############################*/
	public function getCouleurID(){
		return $this->couleurID;
	}

	public function getDescription(){
		return $this->description;
	}
}