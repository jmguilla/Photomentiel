<?php
$dir_transactioniddao_class_php = dirname(__FILE__);
include_once $dir_transactioniddao_class_php . "/../../Config.php";
include_once $dir_transactioniddao_class_php . "/daophp5/DAO.class.php";

class TransactionIDDAO extends DAO {
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	public function get(){
		$query = "select * from transactionID";
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		$transactionID = 1;
		$query = "insert into transactionID (transactionID) values(1)";
		if($tmp && $tmp->getNumRows() == 1){
			foreach($tmp as $row){
				$transactionID = $row->offsetGet('transactionID');
				$transactionID++;
				if($transactionID >= 1000000){
					$transactionID = 1;
				}
				$query = "update transactionID set transactionID = " .
				mysql_real_escape_string($transactionID);
				break;
			}
		}
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$this->commit();
			return $transactionID;
		}else{
			$this->rollback();
			return false;
		}
	}	
}
?>