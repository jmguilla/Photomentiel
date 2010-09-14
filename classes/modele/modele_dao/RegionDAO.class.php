<?php
$dir_regiondao_class_php = dirname(__FILE__);
include_once $dir_regiondao_class_php . "/daophp5/DAO.class.php";
include_once $dir_regiondao_class_php . "/../../Config.php";
include_once $dir_regiondao_class_php . "/../Region.class.php";

class RegionDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBMAPS;
		parent::__construct($dsn);
	}

	public function getRegions(){
		$query = "select * from region";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildRegionFromRow");
	}
	/**
	 * Renvoie la region identifi�e par $id
	 * @param unknown_type $id
	 */
	public function getRegionDepuisID($id){
		$query = "select * from region where id_region = " .
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildRegionFromRow");
	}
	/*##########################################
	 * Helpers
	 ###########################################*/
	/**
	 * Renvoie une instance de Region construite � partir
	 * de la ligne resultat de requete sql $row.
	 * $prefix est le prefix ajout� en t�te de champs pour
	 * r�cup�rer les champs. (ie. si un champs est nomm� id_region
	 * et que prefix vaut 'r', le champs r�cup�rer sera r.id_region...)
	 * @param unknown_type $row
	 * @param unknown_type $pregix
	 */
	public function buildRegionFromRow($row, $prefix = NULL){
		$isPrefixSet = isset($prefix);
		if($isPrefixSet){
			$id = $row->offsetGet($prefix . "id_region");
		}else{
			$id = $row->offsetGet("id_region");
		}
		if($isPrefixSet){
			$nom = $row->offsetGet($prefix . "nom");
		}else{
			$nom = $row->offsetGet("nom");
		}
		$result = new Region();
		$result->setID_Region($id);
		$result->setNom($nom);
		return $result;
	}
}
?>