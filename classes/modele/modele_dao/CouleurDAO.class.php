<?php
$dir_couleurdao_class_php = dirname(__FILE__);
include_once $dir_couleurdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_couleurdao_class_php . "/../Couleur.class.php";
include_once $dir_couleurdao_class_php . "/../../Config.php";

class CouleurDAO extends DAO{

	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	/**
	 * Renvoie toutes les couleurs enregistrees en BD
	 */
	public function getCouleurs(){
		$query = "select * from Couleur";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildCouleurFromRow");
	}
	/**
	 * Renvoie toutes les couleurs enregistrees en BD
	 * identifiees par un $id
	 */
	public function getCouleurDepuisID($id){
		$query = "select * from Couleur where couleurID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildCouleurFromRow");
	}

	/*######################################
	 * Helpers
	 #####################################*/
	public function buildCouleurFromRow($row){
		$tp = htmlspecialchars($row->offsetGet("couleurID"));
		$desc = htmlspecialchars($row->offsetGet("description"));
		return new Couleur($tp, $desc);
	}
}