<?php
$dir_transactioniddao_class_php = dirname(__FILE__);
include_once $dir_transactioniddao_class_php . "/../../Config.php";
include_once $dir_transactioniddao_class_php . "/daophp5/DAO.class.php";
include_once $dir_transactioniddao_class_php . "/../../controleur/ControleurUtils.class.php";

class TransactionIDDAO extends DAO {
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	public function lockTableGet(){
		$query = "lock tables transactionID write";
		$tmp = $this->update($query);
		if($tmp){
			return true;
		}else{
			return false;
		}
	}
	public function unlockTable(){
		$query = "unlock tables";
		$tmp = $this->update($query);
		if($tmp){
			return true;
		}else{
			return false;
		}
	}
	public function get(){
		if(!$this->lockTableGet()){
			ControleurUtils::addError("Impossible de locker la table transactionID pdt get", true);
		}
		try{
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
				if(!$this->unlockTable()){
					ControleurUtils::addError("Impossible de unlocker la table transactionID pdt get sur succes", true);
				}
				return $transactionID;
			}else{
				$this->rollback();
				if(!$this->unlockTable()){
					ControleurUtils::addError("Impossible de unlocker la table transactionID pdt get sur echec", true);
				}
				return false;
			}
		}catch(Exception $e){
			ControleurUtils::addError("Un exception est survenue pendant TransactionID::GET, unlock tables force\n" . $e->getMessage(), true);
			$this->unlockTable();
		}
	}	
}
?>