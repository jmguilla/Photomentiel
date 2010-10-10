<?php
$dir_errordao_class_php = dirname(__FILE__);
include_once $dir_errordao_class_php . "/daophp5/DAO.class.php";
include_once $dir_errordao_class_php . "/../../Config.php";

class ErrorDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function getErrors(){
		$query = "select * from Error";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildErrorFromRow");
	}

	public function getErrorDepuisErrorID($id){
		$query = "select * from Error where errorID = " .
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildErrorFromRow");
	}

	public function create($e){
		$query = "insert into Error(message) values ('" .
		mysql_real_escape_string($e->getMessage()) . "')";
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$e->setErrorID($this->lastInsertedID());
			$this->commit();
			return $e;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function delete($e){
		$query = "delete from Error where errorID = ".
		mysql_real_escape_string($e->getErrorID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function buildErrorFromRow($row, $prefix = ''){
		$id = $row->offsetGet($prefix . "errorID");
		$message = htmlspecialchars($row->offsetGet($prefix . "message"));
		$result = new Error();
		$result->setErrorID($id);
		$result->setMessage($message);
		return $result;
		
	}
}
?>