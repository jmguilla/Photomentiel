<?php
$dir_typepapierdao_class_php = dirname(__FILE__);
include_once $dir_typepapierdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_typepapierdao_class_php . "/../../Config.php";
include_once $dir_typepapierdao_class_php . "/../TypePapier.class.php";

class TypePapierDAO extends DAO{

	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	/**
	 * Renvoie le type papier avec typePapierID == $id
	 * @param int unsigned $id
	 */
	public function getTypePapierDepuisID($id){
		$query = "select * from TypePapier where typePapierID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		$this->extractObjectQuery($tmp, $this, "buildTypePapierFromRow");
	}
	/**
	 * Renvoie la totalit� des type papier enregistr� en BD
	 */
	public function getTypePapiers(){
		$query = "select * from TypePapier";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildTypePapierFromRow");
	}

	/*###################################
	 * Helpers
	 ###################################*/
	public function buildTypePapierFromRow($row){
		$tp = $row->offsetGet("typePapierID");
		$desc = $row->offsetGet("description");
		$prix = $row->offsetGet("prix");
		$result = new TypePapier();
		$result->setTypePapierID($tp);
		$result->setDescription($desc);
		$result->setPrix($prix);
		return $result;
	}
}
?>