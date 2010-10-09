<?php
$dir_retraitphotodao_class_php = dirname(__FILE__);
include_once $dir_retraitphotodao_class_php . "/daophp5/DAO.class.php";
include_once $dir_retraitphotodao_class_php . "/../../Config.php";

class RetraitPhotoDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	public function getRetraitsPhoto(){
		$query = "select * from RetraitPhoto";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildRetraitPhotoFromRow");
	}
	public function getRetraitPhoto($id){
		$query = "select * from RetraitPhoto where retraitPhotoID = " .
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildRetraitPhotoFromRow");
	}
	public function create($rp){
		$query = "insert into RetraitPhoto( nom, prenom, mail, stringID, ref, justificatif, raison) values ('" .
		mysql_real_escape_string($rp->getNom()) . "', '" .
		mysql_real_escape_string($rp->getPrenom()) . "', '" .
		mysql_real_escape_string($rp->getMail()) . "', '" .
		mysql_real_escape_string($rp->getStringID()) . "', '" .
		mysql_real_escape_string($rp->getRef()) . "', '" .
		mysql_real_escape_string($rp->getJustificatif()) . "', '" .
		mysql_real_escape_string($rp->getRaison()) . "')";
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$rp->setRetraitPhotoID($this->lastInsertedID());
			$this->commit();
			return $rp;
		}else{
			$this->rollback();
			return false;
		}
	}
	public function delete($rp){
		$query = "delete from RetraitPhoto where retraitPhotoID = " .
		mysql_real_escape_string($rp->getRetraitPhotoID());
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	public function saveRef($rp){
		$query = "update RetraitPhoto set ref = '" .
		mysql_real_escape_string($rp->getRef()) . "' where retraitPhotoID = " .
		mysql_real_escape_string($rp->getRetraitPhotoID());
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
	public function buildRetraitPhotoFromRow($row, $prefix = ''){
		$id = $row->offsetGet($prefix . "retraitPhotoID");
		$nom = $row->offsetGet($prefix . "nom");
		$prenom = $row->offsetGet($prefix . "prenom");
		$mail = $row->offsetGet($prefix . "mail");
		$stringid = $row->offsetGet($prefix . "stringID");
		$ref = $row->offsetGet($prefix . "ref");
		$justificatif = $row->offsetGet($prefix . "justificatif");
		$raison = $row->offsetGet($prefix . "raison");
		$result = new RetraitPhoto();
		$result->setRetraitPhotoID($id);
		$result->setNom($nom);
		$result->setPrenom($prenom);
		$result->setMail($mail);
		$result->setStringID($stringid);
		$result->setRef($ref);
		$result->setJustificatif($justificatif);
		$result->setRaison($raison);
		return $result;
	}
}
?>