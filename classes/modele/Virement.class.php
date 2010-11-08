<?php
$dir_virement_php = dirname(__FILE__);
include_once $dir_virement_php . '/modele_dao/VirementDAO.class.php';

class Virement{
	private $id = -1;
	private $id_photographe = -1;
	private $montant = -1;
	private $date = '';

	public function __construct(){}

	public function create(){
		$dao = new VirementDAO();
		return $dao->create($this);
	}

	public static function getVirementDepuisID_Photographe($idp){
		$dao = new VirementDAO();
		return $dao->getVirementDepuisID_Photographe($idp);
	}
	public function getVirementID(){
		return $this->id;
	}

	public function setVirementID($id){
		$this->id = $id;
	}

	public function getID_Photographe(){
		return $this->id_photographe;
	}

	public function setID_Photographe($id){
		$this->id_photographe = $id;
	}

	public function getMontant(){
		return $this->montant;
	}

	public function setMontant($m){
		$this->montant = $m;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($d){
		$this->date = $d;
	}
}

?>
