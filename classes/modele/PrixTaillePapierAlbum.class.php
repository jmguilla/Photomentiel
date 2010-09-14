<?php
$dir_prixtaillepapieralbum_class_php = dirname(__FILE__);
include_once $dir_prixtaillepapieralbum_class_php . "/modele_dao/PrixTaillePapierAlbumDAO.class.php";

class PrixTaillePapierAlbum{
	private $prixTaillePapierAlbumID;
	private $prix;
	private $id_taillePapier;
	private $id_album;

	public function __construct($id = -1, $p = 0, $idt = NULL, $ida = NULL){
		$this->prixTaillePapierAlbumID = $id;
		$this->prix = $p;
		$this->id_taillePapier = $idt;
		$this->id_album = $ida;
	}
	/**
	 * Renvoie la liste des prix pour un id_album donn�e
	 * ou le prix si $idt est fournie.
	 * Dans tous les cas, le resultat est un tableau.
	 * @param $ida
	 */
	public static function getPrixTaillePapiersDepuisID_Album($ida, $idt = NULL){
		$dao = new PrixTaillePapierAlbumDAO();
		return $dao->getPrixTaillePapiersDepuisID_Album($ida, $idt);
	}
	/**
	 * Cree l'objet en BD
	 */
	public function create(){
		$dao = new PrixTaillePapierAlbumDAO();
		return $dao->create($this);
	}
	/**
	 * Supprime l'objet de la BD
	 */
	public function delete(){
		$dao = new PrixTaillePapierAlbumDAO();
		return $dao->delete($this);
	}

	/*####################################
	 * Getters & Setters
	 ######################################*/
	public function getPrixTaillePapierAlbumID(){
		return $this->prixTaillePapierAlbumID;
	}

	public function setPrixTaillePapierAlbumID($id){
		$this->prixTaillePapierAlbumID = $id;
	}

	public function getPrix(){
		return $this->prix;
	}

	public function setPrix($p){
		$this->prix = $p;
	}

	public function getID_TaillePapier(){
		return $this->id_taillePapier;
	}

	public function setID_TaillePapier($idp){
		$this->id_taillePapier = $idp;
	}

	public function getID_Album(){
		return $this->id_album;
	}

	public function setID_Album($ida){
		$this->id_album = $ida;
	}
}
?>