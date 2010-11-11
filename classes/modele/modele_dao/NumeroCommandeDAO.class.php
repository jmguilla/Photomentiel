<?php
$dir_numerocommandedao_php = dirname(__FILE__);
include_once $dir_numerocommandedao_php . "/daophp5/DAO.class.php";
include_once $dir_numerocommandedao_php . "/../../Config.php";
include_once $dir_numerocommandedao_php . "/../../controleur/ControleurUtils.class.php";

class NumeroCommandeDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function lockTablesGetAndIncrement(){
		$sql = "lock tables NumeroCommande write";
		$tmp = $this->retrieve($sql);
		if($tmp){
			return true;
		}else{
			ControleurUtils::addError("Impossible de locker table numero commande", true);
			return false;
		}
	}

	public function unlockTables(){
		$sql = "unlock tables";
		$tmp = $this->retrieve($sql);
		if($tmp){
			return true;
		}else{
			ControleurUtils::addError("Impossible d'unlocker table numero commande", true);
			return false;
		}
	}

	public function getProchain(){
		$sql = "select * from NumeroCommande";
		$tmp = $this->retrieve($sql);
		return $this->extractObjectQuery($tmp, $this, "buildNumeroCommandeFromRow");
	}

	public function setProchain($prochain = NULL){
		if(!isset($prochain)){
			return false;
		}
		$sql = "update NumeroCommande set prochain = '" .
		mysql_real_escape_string($prochain) . "'";
		$tmp = $this->update($sql);
		if($tmp){
			return true;
		}else{
			ControleurUtils::addError("Impossible de setter prochain numero de commande", true);
			return false;
		}
	}

	protected function buildNumeroCommandeFromRow($row, $prefix = ''){
		$id = $row->offsetGet($prefix . "numeroCommandeID");
		$prochain = $row->offsetGet($prefix . "prochain");
		$result = new NumeroCommande();
		$result->setNumeroCommandeID($id);
		$result->setProchain($prochain);
		return $result;
	}
}
?>