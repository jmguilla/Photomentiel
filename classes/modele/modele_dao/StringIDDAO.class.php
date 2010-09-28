<?php
$dir_stringiddao_class_php = dirname(__FILE__);
include_once $dir_stringiddao_class_php . "/../../Config.php";
include_once $dir_stringiddao_class_php . "/daophp5/DAO.class.php";
include_once $dir_stringiddao_class_php . "/../StringID.class.php";

class StringIDDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function getStringID($stringID){
		$query = "select * from StringID where stringID = '" . 
		mysql_real_escape_string($stringID) ."'";
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildStringIDFromRow");	
	}

	public function getStringIDDepuisID_Album($ida){
		$query = "select * from StringID where id_album = " . 
		mysql_real_escape_string($ida);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildStringIDFromRow");	
	}
	/**
	 * Renvoie des string id au hasard dans un tableau en fonction de ispublique et n.
	 * @param boolean $isPublique
	 * @param int $n
	 */
	public function getStringIDAleatoire($isPublique = true, $n = 1, $etatAlbum = NULL){
		if($isPublique){
			$query = "select * from StringID as id, Album as a where id.id_album = a.albumID and a.isPublique = true ";
		}else{
			$query = "select * from StringID ";
		}
		if(isset($etatAlbum)){
			$query .= " and a.etat like '%" .
			mysql_real_escape_string($etatAlbum) . "%' ";
		}
		$query .= " order by rand() limit " .
		mysql_real_escape_string($n);
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildStringIDFromRow");	
	}
	/**
	 * cree le string id passe en parametre en BD et le retourne.
	 * false si erreur
	 * @param unknown_type $sid
	 */
	public function create($sid){
		$stringID = $sid->getStringID();
		$homeP = $sid->getHomePhotographe();
		$idA = $sid->getID_Album();
		$query = "insert into StringID(stringID, homePhotographe, id_album) values('" . 
		mysql_real_escape_string($stringID) ."', '" .
		mysql_real_escape_string($homeP) . "', " . 
		mysql_real_escape_string($idA) . ")";
		$tmp = $this->retrieve($query);
		if($tmp) {
			return new StringID($stringID, $homeP, $idA);
		} else {
			false;
		}
	}

	/*######################################
	 * Helpers
	 #####################################*/
	public function buildStringIDFromRow($row, $prefix = ''){
		$id = $row->offsetGet($prefix . "stringID");
		$homeP = $row->offsetGet($prefix . "homePhotographe");
		$idA = $row->offsetGet($prefix . "id_album");
		$result = new StringID();
		$result->setStringID($id);
		$result->setHomePhotographe($homeP);
		$result->setID_Album($idA);
		return $result;
	}
}
?>