<?php
$dir_departementdao_class_php = dirname(__FILE__);
include_once $dir_departementdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_departementdao_class_php . "/../Departement.class.php";
include_once $dir_departementdao_class_php . "/../../Config.php";

class DepartementDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBMAPS;
		parent::__construct($dsn);
	}

	public function getDepartementDepuisID_Region($regionID){
		$query = "select * from departement where id_region = " . 
		mysql_real_escape_string($regionID) . " order by nom";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildDepartementFromRow");
	}

	public function getDepartements(){
		$query = "select * from departement order by nom";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildDepartementFromRow");
	}
	/**
	 * Renvoie le departement associ� � $id
	 * @param unknown_type $id
	 */
	public function getDepartementDepuisID($id){
		$query = "select * from departement where id_departement = " .
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildDepartementFromRow");
	}
	/*##########################################
	 * Helpers
	 ###########################################*/
	/**
	 * Renvoie une instance de Departement construite � partir
	 * de la ligne resultat de requete sql $row.
	 * $prefix est le prefix ajout� en t�te de champs pour
	 * r�cup�rer les champs. (ie. si un champs est nomm� id_departement
	 * et que prefix vaut 'd', le champs r�cup�rer sera d.id_departement...)
	 * @param int $row
	 * @param string $pregix
	 */
	public function buildDepartementFromRow($row, $prefix = NULL){
		$isPrefixSet = isset($prefix);
		if($isPrefixSet){
			$id = $row->offsetGet($prefix . "id_departement");
		}else{
			$id = $row->offsetGet("id_departement");
		}
		if($isPrefixSet){
			$idRegion = $row->offsetGet($prefix . "id_region");
		}else{
			$idRegion = $row->offsetGet("id_region");
		}
		if($isPrefixSet){
			$code = $row->offsetGet($prefix . "code");
		}else{
			$code = $row->offsetGet("code");
		}
		if($isPrefixSet){
			$nom = $row->offsetGet($prefix . "nom");
		}else{
			$nom = $row->offsetGet("nom");
		}
		$result = new Departement();
		$result->setID_Departement($id);
		$result->setID_Region($idRegion);
		$result->setCode($code);
		$result->setNom($nom);
		return $result;
	}
}
?>