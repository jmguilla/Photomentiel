<?php
$dir_adressecommandedao_class_php = dirname(__FILE__);
include_once $dir_adressecommandedao_class_php . "/daophp5/DAO.class.php";
include_once $dir_adressecommandedao_class_php . "/../../Config.php";

class AdresseCommandeDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	/**
	 * cree l'adresse pass�e en parametre en bd.
	 * la transaction n'est pas commite...
	 * @param unknown_type $adresse
	 */
	public function create($adresse){
		$query = "insert into AdresseCommande(nomRue, complement, ville, codePostal, nom, prenom, id_commande) values ('" .
		mysql_real_escape_string($adresse->getNomRue()) ."', '" .
		mysql_real_escape_string($adresse->getComplement()) . "', '" . 
		mysql_real_escape_string($adresse->getVille()) ."', '" .
		mysql_real_escape_string($adresse->getCodePostal()) . "', '" .
		mysql_real_escape_string($adresse->getNom()) . "', '" .
		mysql_real_escape_string($adresse->getPrenom()) . "', " .
		mysql_real_escape_string($adresse->getID_Commande()) . ")";
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$adresse->setAdresseCommandeID($this->lastInsertedID());
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
		$query = "update AdresseCommande set nomRue = '" .
		mysql_real_escape_string($adresse->getNomRue()) . "' , complement = '" .
		mysql_real_escape_string($adresse->getComplement()) . "' , ville = '" .
		mysql_real_escape_string($adresse->getVille()) . "' , codePostal = '" .
		mysql_real_escape_string($adresse->getCodePostal()) . "' , nom = '" .
		mysql_real_escape_string($adresse->getNom()) . "' , prenom = '" .
		mysql_real_escape_string($adresse->getPrenom()) . "', id_commande = " . 
		mysql_real_escape_string($adresse->getID_Commande()) . " where adresseCommandeID = " .
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
	public function getAdresseCommandeFromID($id){
		if(!isset($id)){
			throw new InvalidArgumentException("adresseCommandeID required to get an AdresseCommande.");
		}
		$query = "select * from AdresseCommande where adresseCommandeID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildAdresseCommandeFromRow");
	}
	/*###################################
	 * Helpers
	 ###################################*/
	/**
	 * Construit une adresse a partir d'une ligne resultat de
	 * requete mysql
	 * @param unknown_type $row
	 */
	public function buildAdresseCommandeFromRow($row){
		$id = $row->offsetGet("adresseCommandeID");
		$nom = $row->offsetGet("nom");
		$prenom = $row->offsetGet("prenom");
		$nomRue = $row->offsetGet("nomRue");
		$cmp = $row->offsetGet("complement");
		$ville = $row->offsetGet("ville");
		$cp = $row->offsetGet("codePostal");
		$idu = $row->offsetGet("id_commande");
		$result = new AdresseCommande();
		$result->setAdresseCommandeID($id);
		$result->setNom($nom);
		$result->setPrenom($prenom);
		$result->setNomRue($nomRue);
		$result->setComplement($cmp);
		$result->setVille($ville);
		$result->setCodePostal($cp);
		$result->setID_Commande($idu);
		return $result;
	}
}
?>
