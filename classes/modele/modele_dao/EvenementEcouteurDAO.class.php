<?php
$dir_evenementecouteurdao_class_php = dirname(__FILE__);
include_once $dir_evenementecouteurdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_evenementecouteurdao_class_php . "/../../Config.php";

class EvenementEcouteurDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function create($ee){
		$query = "insert into EvenementEcouteur (id_evenement, id_utilisateur) values (".
		mysql_real_escape_string($ee->getID_Evenement()) . ", " .
		mysql_real_escape_string($ee->getID_Utilisateur()) . ")";
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp){
			$ee->setEvenementEcouteurID($this->lastInsertedID());
			$this->commit();
			return $ee;
		}
		$this->rollback();
		return false;
	}

	public function delete($ee){
		$query = "delete from EvenementEcouteur where evenementEcouteurID = " .
		mysql_real_escape_string($ee->getEvenementEcouteurID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return true;
		}
		$this->rollback();
		return false;
	}
	/**
	 * Retourne l'evenementEcouteur si un tel evenementecouteur existe en BD ( ne tient pas compte de l'evenementEcouteurID )
	 * false sinon
	 * @param EvenementEcouteur $ee
	 */
	public function getEvenementEcouteurDepuisID_UtilisateurEtID_Evenement($ee){
		$query = "select * from EvenementEcouteur where id_evenement = " .
		mysql_real_escape_string($ee->getID_Evenement()) . " and id_utilisateur = " .
		mysql_real_escape_string($ee->getID_Utilisateur());
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp,$this, "buildEvenementEcouteurFromRow");
	}
	/**
	 * Renvoie un tableau contenant la totalite des evenement
	 * a venir pour l'utilisateur passe en parametre
	 * @param unknown_type $user
	 */
	public function getEvenementsAVenirDepuisID_Utilisateur($id_user){
		$dir_evenementecouteurdao_class_php = dirname(__FILE__);
		include_once $dir_evenementecouteurdao_class_php . "/EvenementDAO.class.php";
		include_once $dir_evenementecouteurdao_class_php . "/../Evenement.class.php";
		$query = "select e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon " .	
		"from Evenement as e " . 
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join region as r on e.id_region = r.id_region " .
		"left join ville as v on e.id_ville = v.id_ville " .
		"left join EvenementEcouteur as ee on ee.id_evenement = e.evenementID where " .
		"ee.id_utilisateur = " .
		mysql_real_escape_string($id_user) . " and e.date > now() ";
		$query .= "order by date asc";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, new EvenementDAO(), "buildEvenementEtLieuxFromRow");
	}

	public function save($ee){
		$query = "update EvenementEcouteur set id_evenement = " .
		mysql_real_escape_string($ee->getID_Evenement()) . ", id_utilisateur = " .
		mysql_real_escape_string($ee->getID_Utilisateur()) . " where evenementEcouteurID = " .
		mysql_real_escape_string($ee->getEvenementEcouteurID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return $ee;
		}else{
			$this->rollback();
			return false;
		}
	}

	/*##################################
	 * Helpers
	 ##################################*/

	public function buildEvenementEcouteurFromRow($row){
		$id = $row->offsetGet("evenementEcouteurID");
		$idu = $row->offsetGet("id_utilisateur");
		$ide = $row->offsetGet("id_evenement");
		$result = new EvenementEcouteur();
		$result->setEvenementEcouteurID($id);
		$result->setID_Utilisateur($idu);
		$result->setID_Evenement($ide);
		return $result;
	}
}
