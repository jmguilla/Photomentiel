<?php
$dir_retraitphoto_class_php = dirname(__FILE__);
include_once $dir_retraitphoto_class_php . "/modele_dao/RetraitPhotoDAO.class.php";

class RetraitPhoto{
	private $id = 0;
	private $nom = '';
	private $prenom = '';
	private $mail = '';
	private $stringid = '';
	private $ref = '';
	private $justificatif = '';
	private $raison = '';

	public function __construct(){}

	public static function getRetraitsPhoto(){
		$dao = new RetraitPhotoDAO();
		return $dao->getRetraitsPhoto();
	}

	public function create(){
		$dao = new RetraitPhotoDAO();
		return $dao->create($this);
	}

	public function delete(){
		$dao = new RetraitPhotoDAO();
		return $dao->delete($this);
	}
	/**
	 * Getters and setters
	 */
	public function getRetraitPhotoID(){
		return $this->id;
	}
	public function setRetraitPhotoID($id){
		$this->id = $id;
	}
	public function getNom(){
		return $this->nom;
	}
	public function setNom($n){
		$this->nom = $n;
	}
	public function getPrenom(){
		return $this->prenom;
	}
	public function setPrenom($p){
		$this->prenom = $p;
	}
	public function getMail(){
		return $this->mail;
	}
	public function setMail($m){
		$this->mail = $m;
	}
	public function getStringID(){
		return $this->stringid;
	}
	public function setStringID($s){
		$this->stringid = $s;
	}
	public function getRef(){
		return $this->ref;
	}
	public function setRef($r){
		$this->ref = $r;
	}
	public function getJustificatif(){
		return $this->justificatif;
	}
	public function setJustificatif($j){
		$this->justificatif = $j;
	}
	public function getRaison(){
		return $this->raison;
	}
	public function setRaison($r){
		$this->raison = $r;
	}
}
?>