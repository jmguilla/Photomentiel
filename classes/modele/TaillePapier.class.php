<?php
$dir_taillepapier_class_php = dirname(__FILE__);
include_once $dir_taillepapier_class_php . "/modele_dao/TaillePapierDAO.class.php";

class TaillePapier{
	private $taillePapierID;
	private $description;
	private $dimensions;
	private $prixConseille = -1;
	private $prixMinimum = 1;
	private $prixFournisseur = 0;

	public function __construct($tp = -1, $desc = NULL, $dim = NULL){
		$this->taillePapierID = $tp;
		$this->description = $desc;
		$this->dimensions = $dim;
	}
	/**
	 * Renvoie toutes les taille papiers enregistres en BD
	 */
	public static function getTaillePapiers(){
		$dao = new TaillePapierDAO();
		$tmp = $dao->getTaillePapiers();
		if($tmp){
			$result = array();
			foreach($tmp as $entry){
				$result[$entry->getTaillePapierID()] = $entry;
			}
			return $result;
		}
		return $tmp;
	}
	/**
	 * Renvoie la taille papier identifi� par $id
	 */
	public static function getTaillePapierDepuisID($id){
		if(!isset($id)){
			throw new InvalidArgumentException("ID needed to retrieve a given 'TaillePapier'");
		}
		$dao = new TaillePapierDAO();
		return $dao->getTaillePapierDepuisID($id);
	}
	/*######################################
	 * Getters
	 ######################################*/
	public function getTaillePapierID(){
		return $this->taillePapierID;
	}

	public function setTaillePapierID($id){
		$this->taillePapierID = $id;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($desc){
		$this->description = $desc;
	}

	public function getDimensions(){
		return $this->dimensions;
	}

	public function setDimensions($dim){
		$this->dimensions = $dim;
	}

	public function getPrixConseille(){
		return $this->prixConseille;
	}

	public function setPrixConseille($p){
		$this->prixConseille = $p;
	}

	public function getPrixFournisseur(){
		return $this->prixFournisseur;
	}

	public function setPrixFournisseur($p){
		$this->prixFournisseur = $p;
	}

	public function getPrixMinimum(){
		return $this->prixMinimum;
	}

	public function setPrixMinimum($pm){
		$this->prixMinimum = $pm;
	}
}
?>