<?php
$dir_virementdao_class_php = dirname(__FILE__);
include_once $dir_virementdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_virementdao_class_php . "/../../Config.php";

class VirementDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function create($v){
		$sql = "insert into Virement(id_photographe, montant) values (" .
		mysql_real_escape_string($v->getID_Photographe()) . ", " .
		mysql_real_escape_string($v->getMontant()) . ")";
		$this->startTransaction();
		$result = $this->update($sql);
		if($result){
			$v->setVirementID($this->lastInsertedID());
			$this->commit();
			return $v;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function getVirementDepuisID_Photographe($idp){
		$sql = "select * from Virement where id_photographe = " .
		mysql_real_escape_string($idp);
		$tmp = $this->retrieve($sql);
		return $this->extractArrayQuery($tmp, $this, "buildVirementFromRow");
	}

	public function buildVirementFromRow($row, $prefix = ''){
		$id = $row->offsetGet($prefix . "virementID");
		$ifp = $row->offsetGet($prefix . "id_photographe");
		$montant = $row->offsetGet($prefix . "montant");
		$date = $row->offsetGet($prefix . "date");
		$result = new Virement();
		$result->setVirementID($id);
		$result->setID_Photographe($ifp);
		$result->setMontant($montant);
		$result->setDate($date);
		return $result;
	}
}
?>