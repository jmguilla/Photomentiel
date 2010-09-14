<?php
$dir_villedao_class_php = dirname(__FILE__);
include_once $dir_villedao_class_php . "/daophp5/DAO.class.php";
include_once $dir_villedao_class_php . "/../../Config.php";
include_once $dir_villedao_class_php . "/../Ville.class.php";

class VilleDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBMAPS;
		parent::__construct($dsn);
	}
	/**
	 * Retourne toutes les villes.
	 * si $deptID est renseigne, retourne toutes les villes du dpt
	 * avec pour ID $deptID.
	 */
	public function getVilleDepuisID_Departement($deptID){
		$query = "select * from ville ";
		if(isset($deptID)){
			$query .= " where id_departement = " .
			mysql_real_escape_string($deptID) . " ";
		}
		$query .= "order by nom";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildVilleFromRow");
	}
	/**
	 * Renvoie la ville identifiee par $id
	 */
	public function getVille($id){
		$query = "select * from ville where id_ville = " .
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildVilleFromRow");
	}
	/**
	 * Renvoie la ville associe a ce code postal ou false.
	 * @param string $cp
	 */
	public function getVilleDepuisCP($cp){
		$query = "select * from ville where cp = '" .
		mysql_real_escape_string($cp) . "'";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildVilleFromRow");
	}
	/**
	 * Renvoie la ville avec le nom donne ou false
	 * @param string $nom
	 */
	public function getVilleDepuisNom($nom){
		$query = "select * from ville where nom like '" .
		mysql_real_escape_string($nom) . "'";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildVilleFromRow");
	}

	/*####################################
	 * Helpers
	 ####################################*/
	/**
	 * Renvoie une instance de Ville construite a partir
	 * de la ligne resultat de requete sql $row.
	 * $prefix est le prefix ajoute en tete de champs pour
	 * r�cup�rer les champs. (ie. si un champs est nomm� id_departement
	 * et que prefix vaut 'd', le champs r�cup�rer sera did_departement...)
	 * @param int $row
	 * @param string $pregix
	 */
	public function buildVilleFromRow($row, $prefix = NULL){
		$isPrefixSet = isset($prefix);
		if($isPrefixSet){
			$id = $row->offsetGet($prefix . "id_ville");
		}else{
			$id = $row->offsetGet("id_ville");
		}
		if($isPrefixSet){
			$idDepartement = $row->offsetGet($prefix . "id_departement");
		}else{
			$idDepartement = $row->offsetGet("id_departement");
		}
		if($isPrefixSet){
			$nom = $row->offsetGet($prefix . "nom");
		}else{
			$nom = $row->offsetGet("nom");
		}
		if($isPrefixSet){
			$cp = $row->offsetGet($prefix . "cp");
		}else{
			$cp = $row->offsetGet("cp");
		}
		if($isPrefixSet){
			$lat =  $row->offsetGet($prefix . "lat");
		}else{
			$lat =  $row->offsetGet("lat");
		}
		if($isPrefixSet){
			$lon =  $row->offsetGet($prefix . "lon");
		}else{
			$lon =  $row->offsetGet("lon");
		}
		$result = new Ville();
		$result->setID_Ville($id);
		$result->setID_Departement($idDepartement);
		$result->setNom($nom);
		$result->setCodePostal($cp);
		$result->setLattitude($lat);
		$result->setLongitude($lon);
		return $result;
	}
}
?>