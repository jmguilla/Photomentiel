<?php
$dir_albumdao_class_php = dirname(__FILE__);
include_once $dir_albumdao_class_php . "/../../Config.php";
include_once $dir_albumdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_albumdao_class_php . "/../CreateException.class.php";
include_once $dir_albumdao_class_php . "/StringIDDAO.class.php";
include_once $dir_albumdao_class_php . "/PhotographeDAO.class.php";

class AlbumDAO extends DAO {
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	public function cloturer($album){
		if($album->getEtat() != 2){
			return false;
		}
		$query = "update Album set etat = 3 where etat = 2 and albumID = " .
		mysql_real_escape_string($album->getAlbumID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$this->commit();
			return true;
		}
		$this->rollback();
		return false;
	}

	public function activerListeAlbum($listeAlbum){
		if(isset($listeAlbum) && !empty($listeAlbum)){
			$query = "update Album set etat = 2 where etat = 3 and ( ";
			$length = count($listeAlbum);
			$current = 0;
			foreach($listeAlbum as $album){
				$current++;
				$query .= "albumID = " . mysql_real_escape_string($album->getAlbumID());
				if($current < $length){
					$query .= " or ";
				}
			}
			$query .= " )";
			$this->startTransaction();
			$tmp = $this->update($query);
			if($tmp && $this->getAffectedRows() >= 0){
				$this->commit();
				return true;
			}else{
				$this->rollback();
				return false;
			}
		}
		return false;
	}
	public function validerListeAlbum($listeAlbum){
		if(isset($listeAlbum) && !empty($listeAlbum)){
			$query = "update Album set etat = 2 where etat = 1 and ( ";
			$length = count($listeAlbum);
			$current = 0;
			foreach($listeAlbum as $album){
				$current++;
				$query .= " albumID = " . mysql_real_escape_string($album->getAlbumID());
				if($current < $length){
					$query .= " or ";
				}
			}
			$query .= " )";
			$this->startTransaction();
			$tmp = $this->update($query);
			if($tmp && $this->getAffectedRows() == count($listeAlbum)){
				//on envoie les mails...
				if($this->listeAlbumValidee($listeAlbum)){
					$this->commit();
					return true;
				}
			}
			$this->rollback();
			return false;
		}
		return false;
	}
	/**
	 * Renvoie les n derniers albums publique ou pas, fonction du parametre isPublique
	 * si n n'est pas fournies, renvoies tous les derniers albums
	 * @param int $n
	 */
	public function getNDerniersAlbums($n, $isPublique = true, $etat = NULL){
		$query = "select * from Album where date < now() ";
		if($isPublique == true){
			$query .= "and isPublique = true ";
		}
		if(isset($etat)){
			$query .= " and etat = " .
			mysql_real_escape_string($etat);
		}
		$query .= " order by date desc";
		if(isset($n) && $n > 0){
			$query .= " limit " . 
			mysql_real_escape_string($n);
		}
		$result = array();
		$tmp = $this->retrieve($query);
		if($tmp->getNumRows() > 0) {
			foreach($tmp as $row){
				$result[] = $this->buildAlbumFromRow($row);
			}
			return $result;
		}else{
			return false;
		}
	}
	/**
	 * Renvoie les n derniers Albums avec en plus le string ID associé et un photo
	 * choisie aléatoirement. Si $n n'est pas précisé, renvoie tous les derniers albums.
	 * @param int $n
	 */
	public function getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates($n = 1, $d1 = NULL, $d2 = NULL, $isPublique = true, $etat = NULL){
		$query = "select a.filigramme as alfiligramme, a.gainTotal as algainTotal, a.mailing as almailing, a.balance as albalance, a.albumID as alalbumID, a.nom as alnom, a.isPublique as alisPublique, a.id_photographe as alid_photographe, a.id_evenement as alid_evenement, a.etat as aletat, a.module as almodule, a.date as aldate, " .
		//table stringID
		"s.stringID as sstringID, s.homePhotographe as shomePhotographe, s.id_album as sid_album, " .
		//table utilisateur
		"u.utilisateurID as ut_utilisateurID, u.email as ut_email, u.mdp as ut_mdp, u.actif as ut_actif, u.dateInscription as ut_dateInscription, " .
		//table photographe
		"p.pourcentage as ut_pourcentage, p.photographeID as ut_photographeID, p.nomEntreprise as ut_nomEntreprise, p.siren as ut_siren, p.telephone as ut_telephone, p.siteWeb as ut_siteWeb, p.home as ut_home, p.rib_b as ut_rib_b, p.rib_g as ut_rib_g, p.rib_c as ut_rib_c, p.rib_k as ut_rib_k, p.bic as ut_bic, p.iban as ut_iban, p.id_utilisateur as ut_id_utilisateur, " .
		//talbe adresse
		"ad.adresseID as ut_adresseID, ad.nom as ut_nom, ad.prenom as ut_prenom, ad.nomRue as ut_nomRue, ad.complement as ut_complement, ad.ville as ut_ville, ad.codePostal as ut_codePostal, ad.id_utilisateur as ut_id_utilisateur, " .
		//table evenement
		"e.adresse as e_adresse, e.mailing as e_mailing, e.evenementID as e_evenementID, e.description as e_description, e.id_region as e_id_region, e.id_departement as e_id_departement, e.id_ville as e_id_ville, e.web as e_web, e.type as e_type, e.date as e_date, e.id_utilisateur as e_id_utilisateur, " .
		//table ville
		"v.id_ville as vid_ville, v.id_departement as vid_departement, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon, " .
		//table
		"r.id_region as rid_region, r.nom as rnom, " .
		"d.id_region as did_region, d.nom as dnom, d.code as dcode, d.id_departement as did_departement " .
		"from Album as a " . 
		"left join Evenement as e on a.id_evenement = e.evenementID " . 
		"left join region as r on e.id_region = r.id_region " .
		"left join departement as d on e.id_departement = d.id_departement and r.id_region = d.id_region " .
		"left join ville as v on e.id_ville = v.id_ville and d.id_departement = v.id_departement, " .
		"StringID as s, Photographe as p, Utilisateur as u, Adresse as ad " . 
		"where a.albumID = s.id_album and " .
		"a.id_photographe = p.photographeID and " .
		"u.utilisateurID = p.id_utilisateur and " .
		"ad.id_utilisateur = u.utilisateurID ";
		if(isset($d1)){
			$query .= "and a.date >= '" . mysql_real_escape_string($d1) . " 00:00:00' ";
		}
		if(isset($d2)){
			$query .= "and a.date <= ";
			$query .= "'" . mysql_real_escape_string($d2) . " 23:59:59' ";
		}
		if($isPublique){
			$query = $query . "and a.isPublique = true ";
		}
		if(isset($etat)){
			$query .= " and etat = " .
			mysql_real_escape_string($etat);
		}
		$query = $query . " order by a.date desc";
		if(isset($n) && ($n > 0)){
			$query = $query . " limit " . 
			mysql_real_escape_string($n);
		}
		$tmp = $this->retrieve($query);
		if(!$tmp || $tmp->getNumRows() <= 0){
			return false;
		}
		$result = array();
		foreach($tmp as $row){
			$assoc = array();
			$dir_albumdao_class_php = dirname(__FILE__);
			include_once $dir_albumdao_class_php . "/EvenementDAO.class.php";
			include_once $dir_albumdao_class_php . "/VilleDAO.class.php";
			include_once $dir_albumdao_class_php . "/DepartementDAO.class.php";
			include_once $dir_albumdao_class_php . "/RegionDAO.class.php";
			$assoc["Album"] = $this->buildAlbumFromRow($row, "al");
			$stringIDDao = new StringIDDAO();
			$assoc["StringID"] = $stringIDDao->buildStringIDFromRow($row, "s");
			$photoDao = new PhotographeDAO();
			$assoc["Photographe"] = $photoDao->buildUtilisateurFromRow($row, "ut_", "ut_");
			$evtDAO = new EvenementDAO();
			$assoc["Evenement"] = $evtDAO->buildEvenementEtLieuxFromRow($row, "e_", "r", "d", "v");
			$result[] = $assoc;
		}
		return $result;
	}
	/**
	 * Retourne un tableau d'association $result[i]["Album"] et $result[i]["Utilisateur"]
	 * @param $search
	 * @param $d1
	 * @param $d2
	 * @param $isPublique
	 */
	public function smartRechercheAlbumEtStringIDEtPhotographeEtEvenement($search = NULL, $d1 = NULL, $d2 = NULL, $isPublique = true, $etat = NULL, $n = NULL){
		if(str_word_count($search) > 1){
			$words = explode(' ', $search);
		}else{
			if(isset($search)){
				$words = array($search);
			}else{
				$words = array();
			}
		}
		//table album
		$query = "select a.filigramme as alfiligramme, a.gainTotal as algainTotal, a.mailing as almailing, a.balance as albalance, a.albumID as alalbumID, a.nom as alnom, a.isPublique as alisPublique, a.id_photographe as alid_photographe, a.id_evenement as alid_evenement, a.etat as aletat, a.module as almodule, a.date as aldate, " .
		//table stringID
		"s.stringID as sstringID, s.homePhotographe as shomePhotographe, s.id_album as sid_album, " .
		//table utilisateur
		"u.utilisateurID as ut_utilisateurID, u.email as ut_email, u.mdp as ut_mdp, u.actif as ut_actif, u.dateInscription as ut_dateInscription, " .
		//table photographe
		"p.pourcentage as ut_pourcentage, p.photographeID as ut_photographeID, p.nomEntreprise as ut_nomEntreprise, p.siren as ut_siren, p.telephone as ut_telephone, p.siteWeb as ut_siteWeb, p.home as ut_home, p.rib_b as ut_rib_b, p.rib_g as ut_rib_g, p.rib_c as ut_rib_c, p.rib_k as ut_rib_k, p.bic as ut_bic, p.iban as ut_iban, p.id_utilisateur as ut_id_utilisateur, " .
		//talbe adresse
		"ad.adresseID as ut_adresseID, ad.nom as ut_nom, ad.prenom as ut_prenom, ad.nomRue as ut_nomRue, ad.complement as ut_complement, ad.ville as ut_ville, ad.codePostal as ut_codePostal, ad.id_utilisateur as ut_id_utilisateur, " .
		//table evenement
		"e.adresse as e_adresse, e.mailing as e_mailing, e.evenementID as e_evenementID, e.description as e_description, e.id_region as e_id_region, e.id_departement as e_id_departement, e.id_ville as e_id_ville, e.web as e_web, e.type as e_type, e.date as e_date, e.id_utilisateur as e_id_utilisateur, " .
		//table ville
		"v.id_ville as vid_ville, v.id_departement as vid_departement, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon, " .
		//table
		"r.id_region as rid_region, r.nom as rnom, " .
		"d.id_region as did_region, d.nom as dnom, d.code as dcode, d.id_departement as did_departement " .
		"from Album as a " . 
		"left join Evenement as e on a.id_evenement = e.evenementID " . 
		"left join region as r on e.id_region = r.id_region " .
		"left join departement as d on e.id_departement = d.id_departement and r.id_region = d.id_region " .
		"left join ville as v on e.id_ville = v.id_ville and d.id_departement = v.id_departement, " .
		"StringID as s, Photographe as p, Utilisateur as u, Adresse as ad " . 
		"where a.albumID = s.id_album and " .
		"a.id_photographe = p.photographeID and " .
		"u.utilisateurID = p.id_utilisateur and " .
		"ad.id_utilisateur = u.utilisateurID ";
		foreach($words as $word){
			$query .= " and a.nom like '%" . mysql_real_escape_string($word) . "%'";
		}
		if($isPublique){
			$query .= " and a.isPublique = true ";
		}
		if(isset($d1)){
			$query .= " and a.date >= '" . 
			mysql_real_escape_string($d1) ." 00:00:00' ";
		}
		if(isset($d2)){
			$query .= " and a.date <= '" . 
			mysql_real_escape_string($d2) . " 23:59:59' ";
		}
		if(isset($etat)){
			$query .= " and etat = " . mysql_real_escape_string($etat);
		}
		$query .= " order by a.date desc ";
		if(isset($n) && $n > 0){
			$query .= " limit " .
			mysql_real_escape_string($n);
		}
		$tmp = $this->retrieve($query);
		if(!$tmp || $tmp->getNumRows() <= 0){
			return false;
		}
		$result = array();
		foreach($tmp as $row){
			$assoc = array();
			$dir_albumdao_class_php = dirname(__FILE__);
			include_once $dir_albumdao_class_php . "/EvenementDAO.class.php";
			include_once $dir_albumdao_class_php . "/VilleDAO.class.php";
			include_once $dir_albumdao_class_php . "/DepartementDAO.class.php";
			include_once $dir_albumdao_class_php . "/RegionDAO.class.php";
			$assoc["Album"] = $this->buildAlbumFromRow($row, "al");
			$stringIDDao = new StringIDDAO();
			$assoc["StringID"] = $stringIDDao->buildStringIDFromRow($row, "s");
			$photoDao = new PhotographeDAO();
			$assoc["Photographe"] = $photoDao->buildUtilisateurFromRow($row, "ut_", "ut_");
			$evtDAO = new EvenementDAO();
			$assoc["Evenement"] = $evtDAO->buildEvenementEtLieuxFromRow($row, "e_", "r", "d", "v");
			$result[] = $assoc;
		}
		return $result;
	}
	/**
	 * Retourne un tableau d'association d'album, stringid, photographe, evt pour un id_evenement donné.
	 * @param int $id
	 * @param boolean $isPublique
	 */
	public function getAlbumEtStringIDEtPhotographeEtEvenementDepuisID_Evenement($id, $isPublique = false, $etat = NULL){
		//table album
				$query = "select a.filigramme as alfiligramme, a.gainTotal as algainTotal, a.mailing as almailing, a.balance as albalance, a.albumID as alalbumID, a.nom as alnom, a.isPublique as alisPublique, a.id_photographe as alid_photographe, a.id_evenement as alid_evenement, a.etat as aletat, a.module as almodule, a.date as aldate, " .
		//table stringID
		"s.stringID as sstringID, s.homePhotographe as shomePhotographe, s.id_album as sid_album, " .
		//table utilisateur
		"u.utilisateurID as ut_utilisateurID, u.email as ut_email, u.mdp as ut_mdp, u.actif as ut_actif, u.dateInscription as ut_dateInscription, " .
		//table photographe
		"p.pourcentage as ut_pourcentage, p.photographeID as ut_photographeID, p.nomEntreprise as ut_nomEntreprise, p.siren as ut_siren, p.telephone as ut_telephone, p.siteWeb as ut_siteWeb, p.home as ut_home, p.rib_b as ut_rib_b, p.rib_g as ut_rib_g, p.rib_c as ut_rib_c, p.rib_k as ut_rib_k, p.bic as ut_bic, p.iban as ut_iban, p.id_utilisateur as ut_id_utilisateur, " .
		//talbe adresse
		"ad.adresseID as ut_adresseID, ad.nom as ut_nom, ad.prenom as ut_prenom, ad.nomRue as ut_nomRue, ad.complement as ut_complement, ad.ville as ut_ville, ad.codePostal as ut_codePostal, ad.id_utilisateur as ut_id_utilisateur, " .
		//table evenement
		"e.adresse as e_adresse, e.mailing as e_mailing, e.evenementID as e_evenementID, e.description as e_description, e.id_region as e_id_region, e.id_departement as e_id_departement, e.id_ville as e_id_ville, e.web as e_web, e.type as e_type, e.date as e_date, e.id_utilisateur as e_id_utilisateur, " .
		//table ville
		"v.id_ville as vid_ville, v.id_departement as vid_departement, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon, " .
		//table
		"r.id_region as rid_region, r.nom as rnom, " .
		"d.id_region as did_region, d.nom as dnom, d.code as dcode, d.id_departement as did_departement " .
		"from Album as a " . 
		"left join Evenement as e on a.id_evenement = e.evenementID " . 
		"left join region as r on e.id_region = r.id_region " .
		"left join departement as d on e.id_departement = d.id_departement and r.id_region = d.id_region " .
		"left join ville as v on e.id_ville = v.id_ville and d.id_departement = v.id_departement, " .
		"StringID as s, Photographe as p, Utilisateur as u, Adresse as ad " . 
		"where a.albumID = s.id_album and " .
		"a.id_photographe = p.photographeID and " .
		"u.utilisateurID = p.id_utilisateur and " .
		"ad.id_utilisateur = u.utilisateurID ";
		if($isPublique){
			$query .= " and a.isPublique = true ";
		}
		$query .= " and a.id_evenement = " .
		mysql_real_escape_string($id);
		if(isset($etat)){
			$query .= " and etat = " .
			mysql_real_escape_string($etat);
		}
		$tmp = $this->retrieve($query);
		if(!$tmp || $tmp->getNumRows() <= 0){
			return false;
		}
		$result = array();
		foreach($tmp as $row){
			$assoc = array();
			$dir_albumdao_class_php = dirname(__FILE__);
			include_once $dir_albumdao_class_php . "/EvenementDAO.class.php";
			include_once $dir_albumdao_class_php . "/VilleDAO.class.php";
			include_once $dir_albumdao_class_php . "/DepartementDAO.class.php";
			include_once $dir_albumdao_class_php . "/RegionDAO.class.php";
			$assoc["Album"] = $this->buildAlbumFromRow($row, "al");
			$stringIDDao = new StringIDDAO();
			$assoc["StringID"] = $stringIDDao->buildStringIDFromRow($row, "s");
			$photoDao = new PhotographeDAO();
			$assoc["Photographe"] = $photoDao->buildUtilisateurFromRow($row, "ut_", "ut_");
			$evtDAO = new EvenementDAO();
			$assoc["Evenement"] = $evtDAO->buildEvenementEtLieuxFromRow($row, "e_", "r", "d", "v");
			$result[] = $assoc;
		}
		return $result;
	}
	/**
	 * 
	 * @param unknown_type $query
	 * @param unknown_type $isPublique
	 */
	public function chercheAlbumEtStringIDEtPhotographeEtEvenement($search, $isPublique = true, $etat = NULL){
		if(str_word_count($search) > 1){
			$words = explode(' ', $search);
		}else{
			if(isset($search)){
				$words = array($search);
			}else{
				$words = array();
			}
		}
		//table album
				$query = "select a.filigramme as alfiligramme, a.gainTotal as algainTotal, a.mailing as almailing, a.balance as albalance, a.albumID as alalbumID, a.nom as alnom, a.isPublique as alisPublique, a.id_photographe as alid_photographe, a.id_evenement as alid_evenement, a.etat as aletat, a.module as almodule, a.date as aldate, " .
		//table stringID
		"s.stringID as sstringID, s.homePhotographe as shomePhotographe, s.id_album as sid_album, " .
		//table utilisateur
		"u.utilisateurID as ut_utilisateurID, u.email as ut_email, u.mdp as ut_mdp, u.actif as ut_actif, u.dateInscription as ut_dateInscription, " .
		//table photographe
		"p.pourcentage as ut_pourcentage, p.photographeID as ut_photographeID, p.nomEntreprise as ut_nomEntreprise, p.siren as ut_siren, p.telephone as ut_telephone, p.siteWeb as ut_siteWeb, p.home as ut_home, p.rib_b as ut_rib_b, p.rib_g as ut_rib_g, p.rib_c as ut_rib_c, p.rib_k as ut_rib_k, p.bic as ut_bic, p.iban as ut_iban, p.id_utilisateur as ut_id_utilisateur, " .
		//talbe adresse
		"ad.adresseID as ut_adresseID, ad.nom as ut_nom, ad.prenom as ut_prenom, ad.nomRue as ut_nomRue, ad.complement as ut_complement, ad.ville as ut_ville, ad.codePostal as ut_codePostal, ad.id_utilisateur as ut_id_utilisateur, " .
		//table evenement
		"e.adresse as e_adresse, e.mailing as e_mailing, e.evenementID as e_evenementID, e.description as e_description, e.id_region as e_id_region, e.id_departement as e_id_departement, e.id_ville as e_id_ville, e.web as e_web, e.type as e_type, e.date as e_date, e.id_utilisateur as e_id_utilisateur, " .
		//table ville
		"v.id_ville as vid_ville, v.id_departement as vid_departement, v.nom as vnom, v.cp as vcp, v.lat as vlat, v.lon as vlon, " .
		//table
		"r.id_region as rid_region, r.nom as rnom, " .
		"d.id_region as did_region, d.nom as dnom, d.code as dcode, d.id_departement as did_departement " .
		"from Album as a " . 
		"left join Evenement as e on a.id_evenement = e.evenementID " . 
		"left join region as r on e.id_region = r.id_region " .
		"left join departement as d on e.id_departement = d.id_departement and r.id_region = d.id_region " .
		"left join ville as v on e.id_ville = v.id_ville and d.id_departement = v.id_departement, " .
		"StringID as s, Photographe as p, Utilisateur as u, Adresse as ad " . 
		"where a.albumID = s.id_album and " .
		"a.id_photographe = p.photographeID and " .
		"u.utilisateurID = p.id_utilisateur and " .
		"ad.id_utilisateur = u.utilisateurID ";
		foreach($words as $word){
			$query .= " and a.nom like '%" . mysql_real_escape_string($word) . "%' ";
		}
		if($isPublique){
			$query .= " and a.isPublique = true";
		}
		if(isset($etat)){
			$query .= " and etat = " .
			mysql_real_escape_string($etat);
		}
		$tmp = $this->retrieve($query);
		if(!$tmp || $tmp->getNumRows() <= 0){
			return false;
		}
		$result = array();
		foreach($tmp as $row){
			$assoc = array();
			$dir_albumdao_class_php = dirname(__FILE__);
			include_once $dir_albumdao_class_php . "/EvenementDAO.class.php";
			include_once $dir_albumdao_class_php . "/VilleDAO.class.php";
			include_once $dir_albumdao_class_php . "/DepartementDAO.class.php";
			include_once $dir_albumdao_class_php . "/RegionDAO.class.php";
			$assoc["Album"] = $this->buildAlbumFromRow($row, "al");
			$stringIDDao = new StringIDDAO();
			$assoc["StringID"] = $stringIDDao->buildStringIDFromRow($row, "s");
			$photoDao = new PhotographeDAO();
			$assoc["Photographe"] = $photoDao->buildUtilisateurFromRow($row, "ut_", "ut_");
			$evtDAO = new EvenementDAO();
			$assoc["Evenement"] = $evtDAO->buildEvenementEtLieuxFromRow($row, "e_", "r", "d", "v");
			$result[] = $assoc;
		}
		return $result;
	}

	public function getAlbums($isPublique, $etat = NULL){
		if($isPublique){
			$query = "select * from Album where isPublique = true";
		}else{
			$query = "select * from Album";
		}
		$result = array();
		$tmp = $this->retrieve($query);
		if($tmp->getNumRows() > 0) {
			foreach($tmp as $row){
				$result[] = $this->buildAlbumFromRow($row);
			}
			return $result;
		}else{
			return false;
		}
	}
	/**
	 * Renvoie l'album associé à l'id donné
	 * @param unknown_type $id
	 */
	public function getAlbumDepuisID($id){
		$query = "select * from Album where albumID = " . $id;
		$tmp = $this->retrieve($query);
		if($tmp->getNumRows() == 1) {
			foreach($tmp as $row){
				return $this->buildAlbumFromRow($row);
			}
		}else{
			return false;
		}
	}
	/**
	 * Renvoie le tableau des albums du photographe identifié par id
	 */
	public function getAlbumDepuisID_Photographe($id, $isPublique = true){
		$query = "select * from Album where id_photographe = " .
		mysql_real_escape_string($id) . " ";
		if($isPublique){
			$query .= "and isPublique = true";
		}
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildAlbumFromRow");
	}
	/**
	 * Renvoie un tableau d'association d'album (publique) et de stringID dont le photographe
	 * passé en parametre est l'auteur
	 * result[i]["Album"] = Album
	 * result[i]["StringID"] = StringID
	 * @param INT $photographe
	 */
	public function getAlbumEtImageEtStringIDDepuisID_Photographe($id_photographe, $isPublique = true, $etat = NULL){
		$query = "select * from Album as a, StringID as s where a.id_photographe = " .
		mysql_real_escape_string($id_photographe);
		if($isPublique){
		$query .= " and isPublique = true";
		}
		$query .= " and a.albumID = s.id_album";
		if(isset($etat)){
			$query .= " and etat = " .
			mysql_real_escape_string($etat);
		}
		$query .= " order by a.date desc";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildAlbumAndStringIDFromRow");
	}
	/**
	 * cree l'album passé en parametre en BD et gere
	 * en plus la création d'un stringID associ� et
	 * celle du repertoire album.
	 * si un probl�me survient, une erreur et jetee et
	 * la creation est annulee
	 * @param unknown_type $album
	 */
	public function create($album){
		$dir_albumdao_class_php = dirname(__FILE__);
		include_once $dir_albumdao_class_php . "/../Photographe.class.php";
		include_once $dir_albumdao_class_php . "/PhotographeDAO.class.php";
		$nom = $album->getNom();
		$idp = $album->getID_Photographe();
		$ide = $album->getID_Evenement();
		$isPublique = $album->isPublique();
		$date = $album->getDate();
		$etat = $album->getEtat();
		$module = $album->getModule();
		$balance = $album->getBalance();
		$mailing = $album->getMailing();
		$gt = $album->getGainTotal();
		$filigramme = $album->getFiligramme();
		//creation album
		try{
			$this->startTransaction();
			$query = "insert into Album(filigramme, mailing, balance, gainTotal, nom, id_photographe, id_evenement, isPublique, date, etat, module) values ('" . 
			mysql_real_escape_string($filigramme) . "', '" .
			mysql_real_escape_string($mailing) . "', " .
			mysql_real_escape_string($balance) . ", " .
			mysql_real_escape_string($gt) . ", '" . 
			mysql_real_escape_string($nom) . "', " .
			mysql_real_escape_string($idp) . ", ";
			//pas d'evenement associé
			if(isset($ide)){
				$query .= mysql_real_escape_string($ide) . ", ";
			}else{
				$query .= "NULL, ";
			}
			if($isPublique){
				$query .= "true";
			}else{
				$query .= "false";
			}
			$query .= ", ";
			if(isset($date)){
				$query .= "'" . mysql_real_escape_string($date) . "'";
			}else{
				$query .= "now()";
			}
			$query .= ", ";
			if(isset($etat)){
				$query .= mysql_real_escape_string($etat); 
			}else{
				$query .= 0;
			}
			$query .= ", '" .
			mysql_real_escape_string($module) . "')";
			$tmp = $this->retrieve($query);
			if(!$tmp){
				$this->rollback();
				throw new CreateAlbumException("Impossible de créer le nouvel album"); 
			}
			$lid = $this->lastInsertedID();
			if(!$lid){
				$this->rollback();
				throw new CreateAlbumException("Impossible de retrouver l'identifiant du nouvel ablum");
			}

			global $MODULES;
			$module = $MODULES[$lid%sizeof($MODULES)];

			$queryModule = "update Album set module = '" .
			mysql_real_escape_string($module) . "' where albumID = " .
			mysql_real_escape_string($lid);
			$tmpModule = $this->update($queryModule);
			if(!$tmpModule || $this->getAffectedRows() != 1){
				$this->rollback();
				throw new CreateAlbumException("Impossible d'affecter un module bancaire à l'album");
			}

			//creation des prixTaillePapierAlbum
			$prixTaillePapiers = $album->internalGetPrixTaillePapier();
			if(isset($prixTaillePapiers) && is_array($prixTaillePapiers) && count($prixTaillePapiers) > 0){
				include_once $dir_albumdao_class_php . "/PrixTaillePapierAlbumDAO.class.php";
				$prixTaillePapierDAO = new PrixTaillePapierAlbumDAO();
				foreach($prixTaillePapiers as $prixTaillePapier){
					$prixTaillePapier->setID_Album($lid);
					$prixTaillePapier = $prixTaillePapierDAO->create($prixTaillePapier);
					if(!$prixTaillePapier){
						$this->rollback();
						throw new CreateAlbumException("Impossible de creer les prix associés");
					}
				}
			}

			
			//creation stringID
			$stringID = $this->createRandomStringID();
			if(!$stringID){
				$this->rollback();
				throw new CreateAlbumException('Impossible de générer un identifiant');
			}
			$daoPhotographe = new PhotographeDAO();
			$photographe = $daoPhotographe->getPhotographeDepuisID($idp);
			if(!$photographe){
				$this->rollback();
				throw new CreateAlbumException("Impossible de retrouver le photographe avec l'identifiant " . $idp . " pour la création de l'album");
			}
			$stringIDOBJ = new StringID();
			$stringIDOBJ->setStringID($stringID);
			$photographeHome = $photographe->getHome();
			$stringIDOBJ->setHomePhotographe($photographeHome);
			$stringIDOBJ->setID_Album($lid);			
			$newStringID = $stringIDOBJ->create();
			if(!$newStringID){
				$this->rollback();
				throw new CreateAlbumException('Impossible de créer le nouvel identifiant ' . $stringID . " pour la création de l'album");
			}
			if(!$this->createAlbumDirectory($photographeHome, $stringID)){
				$this->rollback();
				throw new CreateAlbumException("Impossible de créer le répertoire d'album");
			}
		}catch(Exception $e){
			$this->rollback();
			throw new CreateAlbumException("Impossible de créer l'album à cause d'une exception: " . $e->getMessage());
		}
		$this->commit();		
		$resultingAlbum = $this->getAlbumDepuisID($lid);
		if($resultingAlbum){
			return $resultingAlbum;
		}else{
			throw new CreateAlbumException('Impossible de retrouver le nouvel album');
		}
	}
	/**
	 * sauve les modifications apportées à cet album en BD.
	 */
	public function save($album){
		$query = "update Album set nom = '" .
		mysql_real_escape_string($album->getNom()) . "', id_photographe = " .
		mysql_real_escape_string($album->getID_Photographe());
		$ide = $album->getID_Evenement();
		if(isset($ide) && $ide > 0){
			$query .= ", id_evenement = " . mysql_real_escape_string($ide);
		}
		$query .= ", date = '" .
		mysql_real_escape_string($album->getDate()) . "', etat = " .
		mysql_real_escape_string($album->getEtat()) . ", module = '" .
		mysql_real_escape_string($album->getModule()) ."', balance = " .
		mysql_real_escape_string($album->getBalance()) . ", mailing = '" .
		mysql_real_escape_string($album->getMailing()) . "', gainTotal = ".
		mysql_real_escape_string($album->getGainTotal()) . ", filigramme = '".
		mysql_real_escape_string($album->getFiligramme()) . "', ";
		if($album->isPublique()){
			$query .= "isPublique = true ";
		}else{
			$query .= "isPublique = false ";
		}
		$query .= "where albumID = " .
		mysql_real_escape_string($album->getAlbumID());
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		if($tmp){
			$this->commit();
			return $this->getAlbumDepuisID($album->getAlbumID());
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * Sauve en BD seulement l'état de l'album.
	 * @param Album $album
	 */
	public function saveEtat($album){
		$query = "update Album set etat = " .
		mysql_real_escape_string($album->getEtat()) .
		" where albumID = " .
		mysql_real_escape_string($album->getAlbumID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * retire cet album de la BD.
	 * @param unknown_type $album
	 */
	public function delete($album){
		$dir_albumdao_class_php = dirname(__FILE__);
		include_once $dir_albumdao_class_php . "/../Photographe.class.php";
		include_once $dir_albumdao_class_php . "/PhotographeDAO.class.php";
		$daoStringID = new StringIDDAO();
		$stringID = $daoStringID->getStringIDDepuisID_Album($album->getAlbumID());
		if(!$stringID){
			return false;
		}
		$this->startTransaction();
		$query = "delete from Album where albumID = " . 
		mysql_real_escape_string($album->getAlbumID());
		$tmp = $this->retrieve($query);
		if($tmp){
			$daoPhotographe = new PhotographeDAO();
			$photographe = $daoPhotographe->getPhotographeDepuisID($album->getID_Photographe());
			if($photographe){
				$albumRootDirectory = PHOTOGRAPHE_ROOT_DIRECTORY . $photographe->getHome() .
				DIRECTORY_SEPARATOR . $stringID->getStringID();
				if(!$this->deleteAlbumDirectory($albumRootDirectory)){
					$this->rollback();
					return false;
				}else{
					$this->commit();
					return true;
				}
			}else{
				$this->rollback();
				return false;
			}
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * Method destinée a sauver seulement la liste de mails de l'album
	 * @param Album $album
	 */
	public function saveMailing($album){
		$query = "update Album set mailing = '" .
		mysql_real_escape_string($album->getMailing()) . "' where albumID = " .
		mysql_real_escape_string($album->getAlbumID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * Sauve seulement gaintotal & balance en BD.
	 * nécessite l'albumID
	 */
	public function saveAmounts($album){
		$query = "update Album set balance = " .
		mysql_real_escape_string($album->getBalance()) . ", gainTotal = " .
		mysql_real_escape_string($album->getGainTotal()) . " where albumID = " .
		mysql_real_escape_string($album->getAlbumID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * Remet la balance à 0 en BD
	 */
	public function resetBalance($album){
		$query = "update Album set balance = 0 where albumID = " .
		mysql_real_escape_string($album->getAlbumID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
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

	public function buildAlbumFromRow($row, $prefix = ''){
		$id = htmlspecialchars($row->offsetGet($prefix . "albumID"));
		$filigramme = htmlspecialchars($row->offsetGet($prefix . "filigramme"));
		$nom = htmlspecialchars($row->offsetGet($prefix . "nom"));
		$idp = htmlspecialchars($row->offsetGet($prefix . "id_photographe"));
		$ide = htmlspecialchars($row->offsetGet($prefix . "id_evenement"));
		$date = htmlspecialchars($row->offsetGet($prefix . "date"));
		$isp = htmlspecialchars($row->offsetGet($prefix . "isPublique"));
		$etat = htmlspecialchars($row->offsetGet($prefix . "etat"));
		$mod = htmlspecialchars($row->offsetGet($prefix . "module"));
		$balance = htmlspecialchars($row->offsetGet($prefix . "balance"));
		$gt = htmlspecialchars($row->offsetGet($prefix . "gainTotal"));
		$mailing = htmlspecialchars($row->offsetGet($prefix . "mailing"));
		$result = new Album();
		$result->setFiligramme($filigramme);
		$result->setAlbumID($id);
		$result->setNom($nom);
		$result->setID_Photographe($idp);
		$result->setID_Evenement($ide);
		$result->setDate($date);
		$result->setIsPublique($isp);
		$result->setEtat($etat);
		$result->setMailing($mailing);
		$result->setBalance($balance);
		$result->setGainTotal($gt);
		$result->setModule($mod);
		return $result;
	}
	/**
	 * Renvoie un tableau
	 * ["Album"] = Album
	 * ["StringID"] = StringID
	 * construit a partir de la ligne row
	 * @param unknown_type $row
	 */
	public function buildAlbumAndStringIDFromRow($row){
		$result = array();
		$result["Album"] = $this->buildAlbumFromRow($row);
		$stringIDDao = new StringIDDAO();
		$result["StringID"] = $stringIDDao->buildStringIDFromRow($row);
		return $result;
	}

	public function buildAlbumEtStringIDEtPhotographeEtEvenementFromRow($row){
		$dir_albumdao_class_php = dirname(__FILE__);
		include_once $dir_albumdao_class_php . "/EvenementDAO.class.php";
		$result = array();
		$result["Album"] = $this->buildAlbumFromRow($row);
		$stringIDDao = new StringIDDAO();
		$result["StringID"] = $stringIDDao->buildStringIDFromRow($row);
		$photoDao = new PhotographeDAO();
		$result["Photographe"] = $photoDao->buildUtilisateurFromRow($row);
		$evtDAO = new EvenementDAO();
		$result["Evenement"] = $evtDAO->buildEvenementFromRow($row);
		return $result;
	}

	private function createRandomStringID(){
		$array = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
		'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4',
		'5', '6', '7', '8', '9');
		$daoStringID = new StringIDDAO();
		$circuitBroker = 1000;
		$stringID = '';
		while(true){
			for($i = 0; $i < STRINGID_LENGTH; $i++){
				$stringID = $stringID . $array[rand(0, (count($array) - 1))];
			}
			if(!$daoStringID->getStringID($stringID)){
				return $stringID;
			}else{
				$stringID = '';
			}
			$circuitBroker--;
			if($circuitBroker <= 0){
				return false;
			}
		}
	}

	private function createAlbumDirectory($phome, $stringID){
		//dabord on cree le repertoire du photographe
		$photographeDirectory = PHOTOGRAPHE_ROOT_DIRECTORY . $phome;
		$error = false;
		if(!is_dir($photographeDirectory)){
			if(!mkdir($photographeDirectory, 0744)){
				$error = true;
			}
		}
		//on copie le index.html qui est a la racines pictures/
		$photographeIndex = $photographeDirectory . "/index.html";
		if(!is_file($photographeIndex)){
			if(!copy(PHOTOGRAPHE_ROOT_DIRECTORY . "/index.html", $photographeIndex)){
				$error = true;
			}
		}
		//puis celui de l'album
		$albumDirectory = $photographeDirectory . DIRECTORY_SEPARATOR . $stringID;
		if(!is_dir($albumDirectory)){
			if(!mkdir($albumDirectory, 0744)){
				$error = true;
			}
		}

		//on crée le jnlp
		$albumJnlp = $albumDirectory . DIRECTORY_SEPARATOR . "client.jnlp";
		if(!is_file($albumJnlp)){
			$fh = fopen($albumJnlp, 'w');
			if(!fwrite($fh,'<?xml version="1.0" encoding="utf-8"?>
			<jnlp spec="1.0+" codebase="http://www.photomentiel.fr/client"> 
  <information> 
    <title>Photomentiel - Photo Uploader</title> 
    <vendor>www.photomentiel.fr</vendor> 
    <!-- <homepage href="docs/help.html"/> --> 
    <description>Application d upload de photos dediee au site www.photomentiel.fr</description> 
    <description kind="short">Application d upload de photos dediee au site www.photomentiel.fr</description>
    <!-- <icon href="images/swingset2.jpg"/> --> 
    <!-- <icon kind="splash" href="images/splash.gif"/> -->
  </information> 
  <security> 
      <all-permissions/> 
  </security> 
  <resources> 
    <j2se version="1.6"/> 
    <jar href="http://www.photomentiel.fr/client/client.jar"/>
  </resources> 
  <application-desc main-class="photomentiel.controler.Controler"> 
    <argument>' . $phome . '</argument> 
    <argument>' . $stringID . '</argument>
  </application-desc> 
</jnlp>')){
				$error = true;
			}
			fclose($fh);		
		}

		$albumIndex = $albumDirectory . "/index.html";
		if(!is_file($albumIndex)){
			if(!copy(PHOTOGRAPHE_ROOT_DIRECTORY . "/index.html", $albumIndex)){
				$error = true;
			}
		}
		//et enfin pics & thumbs
		$thumbs = $albumDirectory . DIRECTORY_SEPARATOR . THUMB_DIRECTORY;
		if(!is_dir($thumbs)){
			if(!mkdir($thumbs, 0744)){
				$error = true;
			}
		}
		$thumbIndex = $thumbs . "/index.html";
		if(!is_file($thumbIndex)){
			if(!copy(PHOTOGRAPHE_ROOT_DIRECTORY . "/index.html", $thumbIndex)){
				$error = true;
			}
		}
		$pictures = $albumDirectory . DIRECTORY_SEPARATOR . PICTURE_DIRECTORY;
		if(!is_dir($pictures)){
			if(!mkdir($pictures, 0744)){
				$error = true;
			}
		}
		$picturesIndex = $pictures . "/index.html";
		if(!is_file($picturesIndex)){
			if(!copy(PHOTOGRAPHE_ROOT_DIRECTORY . "/index.html", $picturesIndex)){
				$error = true;
			}
		}
		if($error){
			$this->deleteAlbumDirectory(PHOTOGRAPHE_ROOT_DIRECTORY . $phome . DIRECTORY_SEPARATOR . $stringID);
			return false;
		}else{
			return true;
		}
	}

	private function deleteAlbumDirectory($dir_nom){
		return $this->rrmdir($dir_nom);
	}

	private function rrmdir($dir) { 
		if (is_dir($dir)){
			$objects = scandir($dir);
			foreach ($objects as $object){
				if ($object != "." && $object != ".."){
					if (filetype($dir.DIRECTORY_SEPARATOR.$object) == "dir"){
						$this->rrmdir($dir.DIRECTORY_SEPARATOR.$object);
					}else{
						unlink($dir.DIRECTORY_SEPARATOR.$object);
					}
				}
			}
		}
		reset($objects);
		return rmdir($dir);
	}

	private function listeAlbumValidee($liste){
		$dir_albumdao_class_php = dirname(__FILE__);
		include_once $dir_albumdao_class_php . "/../../controleur/ControleurUtils.class.php";
		include_once $dir_albumdao_class_php . "/../Evenement.class.php";
		$result = true;
		foreach($liste as $album){
			$id_evt = $album->getID_Evenement();
			if(isset($id_evt)){
				$evt = Evenement::getEvenementDepuisID($id_evt);
				$result &= $evt->envoyerMailing();
			}
			$result &= $album->envoyerMailing();
		}
		return $result;
	}
}

?>
