<?php
$dir_taillepapierdao_class_php = dirname(__FILE__);
include_once $dir_taillepapierdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_taillepapierdao_class_php . "/../../Config.php";

class TaillePapierDAO extends DAO{

	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	/**
	 * Renvoie le type papier avec typePapierID == $id
	 * @param int unsigned $id
	 */
	public function getTaillePapierDepuisID($id){
		$query = "select * from TaillePapier where taillePapierID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this,"buildTaillePapierFromRow");
	}
	/**
	 * Renvoie la totalit� des type papier enregistr� en BD
	 */
	public function getTaillePapiers(){
		$query = "select * from TaillePapier";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this,"buildTaillePapierFromRow");
	}

	/*###################################
	 * Helpers
	 ###################################*/
	public function buildTaillePapierFromRow($row){
		$tp = $row->offsetGet("taillePapierID");
		$desc = $row->offsetGet("description");
		$dimensions = $row->offsetGet("dimensions");
		$prix = $row->offsetGet("prixConseille");
		$pf = $row->offsetGet("prixFournisseur");
		$pm = $row->offsetGet("prixMinimum");
		$result = new TaillePapier();
		$result->setTaillePapierID($tp);
		$result->setDimensions($dimensions);
		$result->setDescription($desc);
		$result->setPrixConseille($prix);
		$result->setPrixFournisseur($pf);
		return $result;
	}
}
?>