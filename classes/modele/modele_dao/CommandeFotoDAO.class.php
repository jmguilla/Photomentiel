<?php
$dir_commandefotodao_class_php = dirname(__FILE__);
include_once $dir_commandefotodao_class_php . "/daophp5/DAO.class.php";
include_once $dir_commandefotodao_class_php . "/../Commande.class.php";
include_once $dir_commandefotodao_class_php . "/../../Config.php";

class CommandeFotoDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function getCommandeFotoDepuisCommandeFoto($commandeFoto){
		$sql = "select * from CommandeFoto where commandeFoto = " .
		mysql_real_escape_string($commandeFoto);
		$tmp = $this->retrieve($sql);
		return $this->extractObjectQuery($tmp, $this, "buildCommandeFotoFromRow");	
	}

	public function getCommandeFotoDepuisID_Commande($idc){
		$sql = "select * from CommandeFoto where id_commande = " .
		mysql_real_escape_string($idc);
		$tmp = $this->retrieve($sql);
		return $this->extractArrayQuery($tmp, $this, "buildCommandeFotoFromRow");
	}

	public function create($cf){
		$sql = "insert into CommandeFoto(id_commande, commandeFoto) values (" .
		mysql_real_escape_string($cf->getID_Commande()) . ", '" .
		mysql_real_escape_string($cf->getCommandeFoto()) . "')";
		$this->startTransaction();
		$tmp = $this->update($sql);
		if($tmp){
			$cf->setCommandeFotoID($this->lastInsertedID());
			$this->commit();
			return $cf;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function expediee($cf){
		$sql = "update CommandeFoto set expediee = true where commandeFotoID = " . 
		mysql_real_escape_string($cf->getCommandeFotoID());
		$this->startTransaction();
		$tmp = $this->update($sql);
		if($tmp && $this->getAffectedRows() == 1){
			$cf->setExpediee(true);
			$this->commit();
			return $cf;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function buildCommandeFotoFromRow($row){
		$dir_commandefotodao_class_php = dirname(__FILE__);
		include_once $dir_commandefotodao_class_php . "/../CommandeFoto.class.php";
		$result = new CommandeFoto();
		$id = $row->offsetGet("commandeFotoID");
		$id_commande = $row->offsetGet("id_commande");
		$commandefoto = $row->offsetGet("commandeFoto");
		$expediee = $row->offsetGet("expediee");
		$result->setID_Commande($id_commande);
		$result->setCommandeFotoID($id);
		$result->setCommandeFoto($commandefoto);
		$result->setExpediee($expediee);
		return $result;
	}
}
?>