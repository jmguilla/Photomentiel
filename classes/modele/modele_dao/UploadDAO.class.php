<?php
$dir_uploaddao_class_php = dirname(__FILE__);
include_once $dir_uploaddao_class_php . "/daophp5/DAO.class.php";
include_once $dir_uploaddao_class_php . "/../../Config.php";

class UploadDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function getUploadDepuisStringID($sid){
		$query = "select * from Upload where stringID = '" .
		mysql_real_escape_string($sid) . "'";
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildUploadFromRow");
	}

	public function create($up){
		$query = "insert into Upload(stringID, nombre) values ('".
		mysql_real_escape_string($up->getStringID()) . "', " .
		mysql_real_escape_string($up->getNombre()) . ")";
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows()==1){
			$up->setUploadID($this->lastInsertedID());
			$this->commit();
			return $up;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function delete($up){
		$query = "delete from Upload where uploadID = ".
		mysql_real_escape_string($up->getUploadID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows()==1){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function buildUploadFromRow($row, $prefix = ''){
		$id = $row->offsetGet($prefix . "uploadID");
		$sid = $row->offsetGet($prefix . "stringID");
		$nombre = $row->offsetGet($prefix . "nombre");
		$result = new Upload();
		$result->setUploadID($id);
		$result->setStringID($sid);
		$result->setNombre($nombre);
		return $result;
	}
}
?>