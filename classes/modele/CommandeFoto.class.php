<?php
$dir_commandefoto_class_php = dirname(__FILE__);
include_once $dir_commandefoto_class_php . "/modele_dao/CommandeFotoDAO.class.php";
include_once $dir_commandefoto_class_php . "/../Config.php";

class CommandeFoto{
	private $id = -1;
	private $id_commande = -1;
	private $commandeFoto = '';
	private $expediee = false;

	public function __construct(){}

	public static function getCommandeFotoDepuisCommandeFoto($commandeFoto){
		$dao = new CommandeFotoDAO();
		return $dao->getCommandeFotoDepuisCommandeFoto($commandeFoto);
	}

	public static function getCommandeFotoDepuisID_Commande($idc){
		$dao = new CommandeFotoDAO();
		return $dao->getCommandeFotoDepuisID_Commande($idc);
	}

	public function create(){
		$dao = new CommandeFotoDAO();
		return $dao->create($this);
	}

	public function expediee(){
		$dao = new CommandeFotoDAO();
		return $dao->expediee($this);
	}

	public function setCommandeFotoID($id){
		$this->id = $id;
	}

	public function getCommandeFotoID(){
		return $this->id;
	}

	public function setID_Commande($idc){
		$this->id_commande = $idc;
	}

	public function getID_Commande(){
		return $this->id_commande;
	}

	public function setCommandeFoto($cf){
		$this->commandeFoto = $cf;
	}

	public function getCommandeFoto(){
		return $this->commandeFoto;
	}

	public function setExpediee($ex){
		$this->expediee = $ex;
	}

	public function isExpediee(){
		return $this->expediee;
	}
}
?>