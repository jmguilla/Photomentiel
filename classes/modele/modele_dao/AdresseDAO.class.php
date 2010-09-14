<?php
$dir_adressedao_class_php = dirname(__FILE__);
include_once $dir_adressedao_class_php . "/daophp5/DAO.class.php";
include_once $dir_adressedao_class_php . "/../../Config.php";

class AdresseDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	/**
	 * cree l'adresse pass�e en parametre en bd.
	 * @param unknown_type $adresse
	 */
	public function create($adresse){
		$query = "insert into Adresse(nomRue, complement, ville, codePostal, nom, prenom, id_utilisateur) values ('" .
		mysql_real_escape_string($adresse->getNomRue()) ."', '" .
		mysql_real_escape_string($adresse->getComplement()) . "', '" . 
		mysql_real_escape_string($adresse->getVille()) ."', '" .
		mysql_real_escape_string($adresse->getCodePostal()) . "', '" .
		mysql_real_escape_string($adresse->getNom()) . "', '" .
		mysql_real_escape_string($adresse->getPrenom()) . "', " .
		mysql_real_escape_string($adresse->getID_Utilisateur()) . ")";
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$adresse->setAdresseID($this->lastInsertedID());
			return $adresse;
		}else{
			return false;
		}
	}
	/**
	 * sauve l'objet en parametre en BD et le retourne en cas de succes.
	 * en cas d'erreur, retourne false;
	 */
	public function save($adresse){
		$query = "update Adresse set nomRue = '" .
		mysql_real_escape_string($adresse->getNomRue()) . "' , complement = '" .
		mysql_real_escape_string($adresse->getComplement()) . "' , ville = '" .
		mysql_real_escape_string($adresse->getVille()) . "' , codePostal = '" .
		mysql_real_escape_string($adresse->getCodePostal()) . "' , nom = '" .
		mysql_real_escape_string($adresse->getNom()) . "' , prenom = '" .
		mysql_real_escape_string($adresse->getPrenom()) . "', id_utilisateur = " . 
		mysql_real_escape_string($adresse->getID_Utilisateur()) . " where adresseID = " .
		mysql_real_escape_string($adresse->getAdresseID());
		$tmp = $this->retrieve($query);
		if($tmp && $this->getAffectedRows() >= 0){
			return $adresse;
		}else{
			return false;
		}
	}
	/**
	 * retourne l'adresse associ�e � cet ID.
	 * @param unknown_type $id
	 */
	public function getAdresseFromID($id){
		if(!isset($id)){
			throw new InvalidArgumentException("adresseID required to get a string ID.");
		}
		$query = "select * from Adresse where adresseID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildAdresseFromRow");
	}
	/*###################################
	 * Helpers
	 ###################################*/
	/**
	 * Construit une adresse a partir d'une ligne resultat de
	 * requete mysql
	 * @param unknown_type $row
	 */
	public function buildAdresseFromRow($row, $prefix = NULL){
		if(!isset($prefix)){
			$prefix = '';
		}
		$id = $row->offsetGet($prefix . "adresseID");
		$nom = $row->offsetGet($prefix . "nom");
		$prenom = $row->offsetGet($prefix . "prenom");
		$nomRue = $row->offsetGet($prefix . "nomRue");
		$cmp = $row->offsetGet($prefix . "complement");
		$ville = $row->offsetGet($prefix . "ville");
		$cp = $row->offsetGet($prefix . "codePostal");
		$idu = $row->offsetGet($prefix . "id_utilisateur");
		$result = new Adresse();
		$result->setAdresseID($id);
		$result->setNom($nom);
		$result->setPrenom($prenom);
		$result->setNomRue($nomRue);
		$result->setComplement($cmp);
		$result->setVille($ville);
		$result->setCodePostal($cp);
		$result->setID_Utilisateur($idu);
		return $result;
	}
}
?>
