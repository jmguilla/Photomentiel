<?php
$dir_numerocommande_php = dirname(__FILE__);
include_once '/modele_dao/NumeroCommandeDAO.class.php';
class NumeroCommande{
	private $prochain = '';
	private $id = -1;
	public function __construct(){}
	public function setNumeroCommandeID($id){
		$this->id = $id;
	}
	public function getNumeroCommandeID(){
		return $this->id;
	}
	public function setProchain($p){
		$this->prochain = $p;
	}
	public function getProchain(){
		return $this->prochain;
	}
}
?>