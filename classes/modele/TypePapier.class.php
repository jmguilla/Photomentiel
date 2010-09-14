<?php
$dir_typepapier_class_php = dirname(__FILE__);
include_once $dir_typepapier_class_php . "/modele_dao/TypePapierDAO.class.php";

class TypePapier{
	private $typePapierID;
	private $description;
	private $prix;

	public function __construct($tp = -1, $desc = NULL, $prix = NULL){
		$this->typePapierID = $tp;
		$this->description = $desc;
		$this->prix = $prix;
	}
	/**
	 * Renvoie tous les type papiers enregistres en BD
	 */
	public static function getTypePapiers(){
		$dao = new TypePapierDAO();
		return $dao->getTypePapiers();
	}
	/**
	 * Renvoie le type papier identifi� par $id
	 */
	public static function getTypePapierDepuis($id){
		if(!isset($id)){
			throw new InvalidArgumentException("ID needed to retrieve a given 'TypePapier'");
		}
		$dao = new TypePapierDAO();
		return $dao->getTypePapierDepuis($id);
	}
	/*######################################
	 * Getters
	 ######################################*/
	public function getTypePapierID(){
		return $this->typePapierID;
	}

	public function setTypePapierID($id){
		$this->typePapierID = $id;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($desc){
		$this->description = $desc;
	}

	public function getPrix(){
		return $this->prix;
	}

	public function setPrix($prix){
		$this->prix = $prix;
	}
}
?>