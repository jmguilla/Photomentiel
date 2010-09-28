<?php
$dir_evenementdao_class_php = dirname(__FILE__);
include_once $dir_evenementdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_evenementdao_class_php . "/../../Config.php";

class EvenementDAO extends DAO {
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function getEvenementDepuisID($id){
		$query = "select e.adresse, e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon " .	
		"from Evenement as e " . 
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join region as r on e.id_region = r.id_region " .
		"left join ville as v on e.id_ville = v.id_ville " .
		"where evenementID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildEvenementEtLieuxFromRow");
	}

	public function chercheEvenement($search){
		if(str_word_count($search) > 1){
			$words = explode(' ', $search);
		}else{
			if(isset($search)){
				$words = array($search);
			}else{
				$words = array();
			}
		}
		$query = "select e.adresse, e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon, " .	
		"u.utilisateurID as u_utilisateurID, u.email as u_email, u.mdp as u_mdp, u.actif as u_actif, u.dateInscription as u_dateInscription, ".
		"p.pourcentage as u_pourcentage, p.photographeID as u_photographeID, p.nomEntreprise as u_nomEntreprise, p.siren as u_siren, p.telephone as u_telephone, p.siteWeb as u_siteWeb, p.home as u_home, p.rib_b as u_rib_b, p.rib_g as u_rib_g, p.rib_c as u_rib_c, p.rib_k as u_rib_k, p.bic as u_bic, p.iban as u_iban, p.id_utilisateur as u_id_utilisateur, " .
		"a.adresseID as ad_adresseID, a.nom as ad_nom, a.prenom as ad_prenom, a.nomRue as ad_nomRue, a.complement as ad_complement, a.codePostal as ad_codePostal, a.ville as ad_ville, a.id_utilisateur as ad_id_utilisateur " .	
		"from Utilisateur as u left join Photographe as p on u.utilisateurID = p.id_utilisateur left join Adresse as a on u.utilisateurID = a.id_utilisateur, Evenement as e " . 
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join region as r on e.id_region = r.id_region " .
		"left join ville as v on e.id_ville = v.id_ville " .
		"where e.id_utilisateur = u.utilisateurID ";
		foreach($words as $word){
			$query .= " and description like '%" . mysql_real_escape_string($word) . "%'";
		}
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildEvenementEtLieuxEtUtilisateurFromRow");
	}

	public function smartRechercheEvenementEtUtilisateur($search = NULL, $d1 = NULL, $d2 = NULL, $id_region = NULL, $type = NULL, $n = NULL){
		if(str_word_count($search) > 1){
			$words = explode(' ', $search);
		}else{
			if(isset($search)){
				$words = array($search);
			}else{
				$words = array();
			}
		}
		$query = "select e.adresse, e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon, " .
		"u.utilisateurID as u_utilisateurID, u.email as u_email, u.mdp as u_mdp, u.actif as u_actif, u.dateInscription as u_dateInscription, ".
		"p.pourcentage as u_pourcentage, p.photographeID as u_photographeID, p.nomEntreprise as u_nomEntreprise, p.siren as u_siren, p.telephone as u_telephone, p.siteWeb as u_siteWeb, p.home as u_home, p.rib_b as u_rib_b, p.rib_g as u_rib_g, p.rib_c as u_rib_c, p.rib_k as u_rib_k, p.bic as u_bic, p.iban as u_iban, p.id_utilisateur as u_id_utilisateur, " .
		"a.adresseID as ad_adresseID, a.nom as ad_nom, a.prenom as ad_prenom, a.nomRue as ad_nomRue, a.complement as ad_complement, a.codePostal as ad_codePostal, a.ville as ad_ville, a.id_utilisateur as ad_id_utilisateur " .	
		"from Utilisateur as u left join Photographe as p on u.utilisateurID = p.id_utilisateur left join Adresse as a on u.utilisateurID = a.id_utilisateur, Evenement as e " . 
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join region as r on e.id_region = r.id_region " .
		"left join ville as v on e.id_ville = v.id_ville " .
		"where e.id_utilisateur = u.utilisateurID and a.id_utilisateur = u.utilisateurID ";
		foreach($words as $word){
			$query .= " and e.description like '%" . mysql_real_escape_string($word) . "%' ";
		}
		if(isset($id_region)){
			$query .= " and e.id_region = " .
			mysql_real_escape_string($id_region);
		}
		if(isset($type)){
			$query .= " and e.type = " .
			mysql_real_escape_string($type) . " ";
		}
		if(isset($d1)){
			$query .= " and e.date >= '" . 
			mysql_real_escape_string($d1) ." 00:00:00' ";
		}
		if(isset($d2)){
			$query .= " and e.date <= '" . 
			mysql_real_escape_string($d2) . " 23:59:59' ";
		}
		$query .= " order by e.date asc ";
		if(isset($n) && $n > 0){
			$query .= " limit " .
			mysql_real_escape_string($n);
		}
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildEvenementEtLieuxEtUtilisateurFromRow");
	}

	public function getEvenements(){
		$query = "select e.adresse, e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon " .
		"from Evenement as e " .
		"left join region as r on e.id_region = r.id_region " .
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join ville as v on e.id_ville = v.id_ville";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildEvenementEtLieuxFromRow");
	}

	public function getNProchainsEvenements($n){
		$query = "select e.adresse, e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon " .	
		"from Evenement as e " . 
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join region as r on e.id_region = r.id_region " .
		"left join ville as v on e.id_ville = v.id_ville " .
		"where date > now() ";
		$query .= "order by e.date ";
		if(isset($n)){
			$query = $query . "limit " . 
			mysql_real_escape_string($n);
		}
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildEvenementEtLieuxFromRow");
	}
	/**
	 * Pour seulement sauver l'etat du mailing en BD
	 * @param Evenement $event
	 */
	public function saveMailing($event){
		$query = "update Evenement set mailing = '" .
		mysql_real_escape_string($event->getMailing()) . "' where evenementID = " .
		mysql_real_escape_string($event->getEvenementID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() > 0){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function getEvenementsEntreDates($d1, $d2){
		return $this->getNProchainsEvenementsEntreDates(NULL, $d1, $d2);
	}
	/**
	 * Renvoie les n premiers evenements entre 2 dates.
	 * Si n est non precisé, renvoie tous les evenements.
	 * @param unknown_type $n
	 * @param unknown_type $d1
	 * @param unknown_type $d2
	 */
	public function getNProchainsEvenementsEntreDates($n, $d1, $d2){
		$query = "select e.adresse, e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon, " .
		"u.utilisateurID as u_utilisateurID, u.email as u_email, u.mdp as u_mdp, u.actif as u_actif, u.dateInscription as u_dateInscription, ".
		"p.pourcentage as u_pourcentage, p.photographeID as u_photographeID, p.nomEntreprise as u_nomEntreprise, p.siren as u_siren, p.telephone as u_telephone, p.siteWeb as u_siteWeb, p.home as u_home, p.rib_b as u_rib_b, p.rib_g as u_rib_g, p.rib_c as u_rib_c, p.rib_k as u_rib_k, p.bic as u_bic, p.iban as u_iban, p.id_utilisateur as u_id_utilisateur, " .
		"a.adresseID as ad_adresseID, a.nom as ad_nom, a.prenom as ad_prenom, a.nomRue as ad_nomRue, a.complement as ad_complement, a.codePostal as ad_codePostal, a.ville as ad_ville, a.id_utilisateur as ad_id_utilisateur " .	
		"from Utilisateur as u left join Photographe as p on u.utilisateurID = p.id_utilisateur left join Adresse as a on u.utilisateurID = a.id_utilisateur, Evenement as e " .
		"left join region as r on e.id_region = r.id_region " .
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join ville as v on e.id_ville = v.id_ville " .
		"where e.id_utilisateur = u.utilisateurID and a.id_utilisateur = u.utilisateurID ";
		if(isset($d1)){
			$query .= " and e.date >= '" . 
			mysql_real_escape_string($d1) ." 00:00:00' ";
		}
		if(isset($d2)){
			$query .= "and e.date <= '" . 
			mysql_real_escape_string($d2) . " 23:59:59' ";
		}
		$query .= "order by date ";
		if(isset($n) && $n > 0){
			$query = $query . "limit " . 
			mysql_real_escape_string($n);
		}
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildEvenementEtLieuxEtUtilisateurFromRow");
	}

	public function getNProchainsEvenementsApresDate($n, $d){
		return $this->getNProchainsEvenementsEntreDates($n, $d, NULL);
	}
	/**
	 * Retourne tous les evenements d'une certaine date
	 * @param unknown_type $n
	 * @param unknown_type $d
	 */
	public function getEvenementsADate($d){
		$query = "select e.adresse, e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon " .
		"from Evenement as e " .
		"left join region as r on e.id_region = r.id_region " .
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join ville as v on e.id_ville = v.id_ville " .
		"where date like '%" . 
		mysql_real_escape_string($d) ."%' ";
		$query .= "order by date";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildEvenementEtLieuxFromRow");
	}
	/**
	 * Renvoie la liste des $n derniers evenements, si $n n'est pas renseigné,
	 * renvoie tous les evenements passés.
	 * @param int $n
	 */
	public function getNDerniersEvenements($n){
		$query = "select e.adresse, e.mailing, e.evenementID, e.description, e.web, e.type, e.date, e.id_utilisateur, " . 
		"r.id_region as rid_region, r.nom as rnom, " . //relatifs a region
		"d.id_departement as did_departement, d.id_region as did_region, d.code as dcode, d.nom as dnom, " .
		"v.id_departement as vid_departement, v.id_ville as vid_ville, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon " .	
		"from Evenement as e " . 
		"left join departement as d on e.id_departement = d.id_departement " .
		"left join region as r on e.id_region = r.id_region " .
		"left join ville as v on e.id_ville = v.id_ville order by date asc";
		if(isset($n)){
			$query = $query . " limit " . mysql_real_escape_string($n);
		}
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildEvenementEtLieuxFromRow");
	}

	/**
	 * cree l'objet Evenement passé en parametre en bd.
	 * @param unknown_type $evt
	 */
	public function create($evt){
		$query = "insert into Evenement ( mailing, description, adresse, id_region, id_departement, id_ville, web, type, date, id_utilisateur ) " .
		"values ('" . 
		mysql_real_escape_string($evt->getMailing()) . "', '" .
		mysql_real_escape_string($evt->getDescription()) . "', '" .
		mysql_real_escape_string($evt->getAdresse()) . "', ";
		$region = $evt->getRegion();
		if(isset($region)){
			$query = $query . mysql_real_escape_string($region->getID_Region()) . ", ";
		}else{
			$query = $query . "NULL, ";
		}
		$dept = $evt->getDepartement();
		if(isset($dept)){
			$query = $query . mysql_real_escape_string($dept->getID_Departement()) . ", ";
		}else{
			$query = $query . "NULL, ";
		}
		$ville = $evt->getVille();
		if(isset($ville)){
			$query = $query . mysql_real_escape_string($ville->getID_Ville()) . ", ";
		}else{
			$query = $query . "NULL, ";
		}
		$query = $query . "'" . mysql_real_escape_string($evt->getWeb()) . "', ";
		$query = $query . mysql_real_escape_string($evt->getType()) . ", ";
		$query = $query . "'" . mysql_real_escape_string($evt->getDate()) . "', ";
		$query = $query . mysql_real_escape_string($evt->getID_Utilisateur());
		$query .= ")";
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		if($tmp){
			$evt->setEvenementID($this->lastInsertedID());
			$this->commit();
			return $evt;
		}
		$this->rollback();
		return false;
	}

	/**
	 * sauve le parametre en BD.
	 * en cas de success, retourne une reference de l'objet, sinon false
	 * @param unknown_type $evt
	 */
	public function save($evt){
		$query = "update Evenement ";
		$query .= "set description = '" . mysql_real_escape_string($evt->getDescription()) . "', adresse = '" .
		mysql_real_escape_string($evt->getAdresse()) . ", ";
		$region = $evt->getRegion();
		if(isset($region) && $region > 0){
			$query .= "id_region = " . 
			mysql_real_escape_string($region->getID_Region()) . ", ";
		}
		$dpt = $evt->getDepartement();
		if(isset($dpt) && $dpt > 0){
			$query .= "id_departement = " . 
			mysql_real_escape_string($dpt->getID_Departement()) . ", ";
		}
		$ville = $evt->getVille();
		if(isset($ville) && $ville > 0){
			$query .= "id_ville = " . 
			mysql_real_escape_string($ville->getID_Ville()) . ", ";
		}
		$query .= "web = '" . mysql_real_escape_string($evt->getWeb()) . "', ";
		$query .= "mailing = '" . mysql_real_escape_string($evt->getMailing()) . "', ";
		$query .= "type = " . mysql_real_escape_string($evt->getType()) . ", ";
		$query .= "date = '" . mysql_real_escape_string($evt->getDate()) . "', ";
		$query .= "id_utilisateur = " . mysql_real_escape_string($evt->getID_Utilisateur()) . " ";
		$query .= "where evenementID = " .
		mysql_real_escape_string($evt->getEvenementID());
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return $evt;
		}
		$this->rollback();
		return false;
	}
	/**
	 * pour supprimer un evenemnt de la BD.
	 * @param Evenement $evt
	 */
	public function delete($evt){
		$query = "delete from Evenement where evenementID = " .
		mysql_real_escape_string($evt->getEvenementID());
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		if($tmp){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}

	/*###################################################
	 * Helpers
	 ###################################################*/
	/**
	 * Construit une instance de la classe Evenement
	 * depuis une ligne resultat de requete mysql.
	 * Notez que les lieux ne sont pas setté dans cette methode.
	 * @param unknown_type $row
	 */
	public function buildEvenementFromRow($row, $prefix = ''){
		$id = htmlspecialchars($row->offsetGet($prefix . "evenementID"));
		$web = htmlspecialchars($row->offsetGet($prefix . "web"));
		$type = htmlspecialchars($row->offsetGet($prefix . "type"));
		$date = htmlspecialchars($row->offsetGet($prefix . "date"));
		$id_utilisateur = htmlspecialchars($row->offsetGet($prefix . "id_utilisateur"));
		$desc = htmlspecialchars($row->offsetGet($prefix . "description"));
		$mail = htmlspecialchars($row->offsetGet($prefix . "mailing"));
		$adresse = htmlspecialchars($row->offsetGet($prefix . "adresse"));
		$result = new Evenement();
		$result->setAdresse($adresse);
		$result->setMailing($mail);
		$result->setEvenementID($id);
		$result->setWeb($web);
		$result->setType($type);
		$result->setDate($date);
		$result->setID_Utilisateur($id_utilisateur);
		$result->setDescription($desc);
		return $result;
	}

	public function buildEvenementEtLieuxFromRow($row, $prefix = NULL, $prefixRegion = "r", $prefixDept = "d", $prefixVille = "v"){
		$dir_evenementdao_class_php = dirname(__FILE__);
		include_once $dir_evenementdao_class_php . "/RegionDAO.class.php";
		include_once $dir_evenementdao_class_php . "/DepartementDAO.class.php";
		include_once $dir_evenementdao_class_php . "/VilleDAO.class.php";
		$evt = $this->buildEvenementFromRow($row, $prefix);
		$daoRegion = new RegionDAO();
		$reg = $daoRegion->buildRegionFromRow($row,$prefixRegion);
		$evt->setRegion($reg);
		$daoDepartement = new DepartementDAO();
		$dept = $daoDepartement->buildDepartementFromRow($row,$prefixDept);
		$evt->setDepartement($dept);
		$daoVille = new VilleDAO();
		$ville = $daoVille->buildVilleFromRow($row, $prefixVille);
		$evt->setVille($ville);
		return $evt;
	}

	public function buildEvenementEtLieuxEtUtilisateurFromRow($row, $prefix = NULL, $prefixRegion = "r", $prefixDept = "d", $prefixVille = "v", $prefixU = "u_", $prefixA = "ad_"){
		$dir_evenementdao_class_php = dirname(__FILE__);
		include_once $dir_evenementdao_class_php . "/RegionDAO.class.php";
		include_once $dir_evenementdao_class_php . "/DepartementDAO.class.php";
		include_once $dir_evenementdao_class_php . "/VilleDAO.class.php";
		include_once $dir_evenementdao_class_php . "/UtilisateurDAO.class.php";
		include_once $dir_evenementdao_class_php . "/../Utilisateur.class.php";
		$evt = $this->buildEvenementFromRow($row, $prefix);
		$daoRegion = new RegionDAO();
		$reg = $daoRegion->buildRegionFromRow($row,$prefixRegion);
		$evt->setRegion($reg);
		$daoDepartement = new DepartementDAO();
		$dept = $daoDepartement->buildDepartementFromRow($row,$prefixDept);
		$evt->setDepartement($dept);
		$daoVille = new VilleDAO();
		$ville = $daoVille->buildVilleFromRow($row, $prefixVille);
		$evt->setVille($ville);
		$daoUser = new UtilisateurDAO();
		$user = $daoUser->buildUtilisateurFromRow($row, $prefixU, $prefixA);
		$result = array();
		$result["Evenement"] = $evt;
		$result["Utilisateur"] = $user;
		return $result;
	}
}
?>
