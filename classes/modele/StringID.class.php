<?php
$dir_stringid_class_php = dirname(__FILE__);
include_once $dir_stringid_class_php . "/modele_dao/StringIDDAO.class.php";

class StringID{
	private $stringID;
	private $homePhotographe;
	private $id_album;

	public function __construct($id = -1, $homeP = NULL, $idA = NULL){
		$this->stringID = $id;
		$this->homePhotographe = $homeP;
		$this->id_album = $idA;
	}
	/**
	 * Renvoie un StringID pour un "stringID" donné
	 * @param string $stringID
	 */
	public static function getStringIDDepuisID($stringID){
		if(!isset($stringID)){
			throw new InvalidArgumentException("ID required to get a string ID.");
		}
		$daoStringID = new StringIDDAO();
		return $daoStringID->getStringID($stringID);
	}
	/**
	 * Renvoie un stringID pour un id_album donnée
	 * @param unknown_type $ida
	 */
	public static function getStringIDDepuisID_Album($ida){
		if(!isset($ida)){
			throw new InvalidArgumentException("id_album to required get a string ID.");
		}
		$daoStringID = new StringIDDAO();
		return $daoStringID->getStringIDDepuisID_Album($ida);
	}
	/**
	 * Renvoie des string id au hasard en fonction de ispublique et n
	 * @param boolean $isPublique
	 * @param int $n
	 */
	public static function getStringIDAleatoire($isPublique = true, $n = 1, $etatAlbum = NULL){
		$daoStringID = new StringIDDAO();
		return $daoStringID->getStringIDAleatoire($isPublique, $n, $etatAlbum);
	}
	/**
	 * cree ce string id en BD
	 */
	public function create(){
		$dao = new StringIDDAO();
		return $dao->create($this);
	}
	/*#####################################
	 * Getters & Setters
	 #####################################*/
	public function getStringID(){
		return $this->stringID;
	}

	public function setStringID($sid){
		$this->stringID = $sid;
	}

	public function getHomePhotographe(){
		return $this->homePhotographe;
	}

	public function setHomePhotographe($hp){
		$this->homePhotographe = $hp;
	}

	public function getID_Album(){
		return $this->id_album;
	}

	public function setID_Album($ida){
		$this->id_album = $ida;
	}

	public function internalGetUploaderPath(){
		return "http://www.photomentiel.fr/pictures/" . $this->homePhotographe . "/" . $this->stringID . "/client.jnlp";
	}
}
?>