<?php
$dir_photographedao_class_php = dirname(__FILE__);
include_once $dir_photographedao_class_php . "/../../Config.php";
include_once ($dir_photographedao_class_php . "/UtilisateurDAO.class.php");
include_once ($dir_photographedao_class_php . "/ModeleDAOUtils.class.php");

class PhotographeDAO extends UtilisateurDAO{
	public function __construct() {
		parent::__construct();
	}
	/**
	 * Renvoie un photographe au hasard
	 */
	public function getPhotographeAleatoire(){
		$query = "select * from Photographe as p, Utilisateur as u, Adresse as a where p.id_utilisateur = u.utilisateurID and a.id_utilisateur = u.utilisateurID order by rand() limit 1";
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildUtilisateurFromRow");
	}
	/**
	 * Renvoie le photographe avec l'id fourni
	 */
	public function getPhotographeDepuisID($id){
		$query = "select * from Photographe as p, Utilisateur as u, Adresse as a where p.id_utilisateur = u.utilisateurID and a.id_utilisateur = u.utilisateurID and p.photographeID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildUtilisateurFromRow");		
	}

	public function getPhotographes(){
		$query = "select * from Photographe as p, Utilisateur as u, Adresse as a where p.id_utilisateur = u.utilisateurID and a.id_utilisateur = u.utilisateurID";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildUtilisateurFromRow");
	}
	/**
	 * cree le photographe passe en parametre en BD et le
	 * retourne avec ses champs mis a jour.
	 * @param unknown_type $photographe
	 */
	public function create($photographe, $activateID){
		$dir_photographedao_class_php = dirname(__FILE__);
		include_once $dir_photographedao_class_php . "/AdresseDAO.class.php";
		include_once $dir_photographedao_class_php . "/../Adresse.class.php";
		include_once $dir_photographedao_class_php . "/../CreateException.class.php";
		$email = $photographe->getEmail();
		//controle de l'email.
		if(!$this->controleEmail($email)){
			throw new CreateUtilisateurException("Email already in use");
		}

		$this->startTransaction();
		//creation de l'utilisateur
		$utilisateur = $this->createUtilisateur($photographe, $activateID);

		if(!$utilisateur){
			$this->rollback();
			throw new CreateUtilisateurException("Cannot get the newly created user.");
		}
		$photographe->setUtilisateurID($utilisateur->getUtilisateurID());
		//creation du photographe
		$photographe = $this->createPhotographe($photographe);
		if(!$photographe){
			$this->rollback();
			throw new CreateUtilisateurException("Cannot create the photographe.");
		}

		$adresse = $photographe->getAdresse();
		if(isset($adresse)){
			$adresse->setID_Utilisateur($photographe->getUtilisateurID());
			if(0 < $adresse->getAdresseID()){
				$adao = new AdresseDAO();
				$adresse = $adao->save($adresse);
				if(!$adresse){
					$this->rollback();
					throw new CreateUtilisateurException("Impossible de sauver la nouvelle adresse.");
				}
			}else{
				$adao = new AdresseDAO();
				$adresse = $adao->create($adresse);
				if(!$adresse){
					$this->rollback();
					throw new CreateUtilisateurException("Impossible de creer la nouvelle adresse.");
				}
			}
		}
		$this->commit();
		return $photographe;
	}

	/**
	 * sauve le parametre en BD.
	 * Retourne true en cas de succes, false sinon.
	 * @param Utilisateur $utilisateur
	 */
	public function save($photographe){
		$photographe->setIBAN(ModeleDAOUtils::Rib2Iban($photographe->getRIB_b(), $photographe->getRIB_g(), $photographe->getRIB_c(), $photographe->getRIB_k()));
		$adresse = $photographe->getAdresse();
		$query = "update Utilisateur, Photographe, Adresse set nom = '" .
		mysql_real_escape_string($adresse->getNom()) . "', prenom = '" .
		mysql_real_escape_string($adresse->getPrenom()) . "', nomRue = '" .
		mysql_real_escape_string($adresse->getNomRue()) . "', complement = '" .
		mysql_real_escape_string($adresse->getComplement()) . "', ville = '" .
		mysql_real_escape_string($adresse->getVille()) . "', codePostal = '" .
		mysql_real_escape_string($adresse->getCodePostal()) . "', nomEntreprise = '" .
		mysql_real_escape_string($photographe->getNomEntreprise()) . "', siren = '" . 
		mysql_real_escape_string($photographe->getSiren()) . "', telephone = '" .
		mysql_real_escape_string($photographe->getTelephone()) . "', siteWeb = '" .
		mysql_real_escape_string($photographe->getSiteWeb()) . "', rib_b = '" .
		mysql_real_escape_string($photographe->getRIB_b()) . "', rib_g = '" .
		mysql_real_escape_string($photographe->getRIB_g()) . "', rib_c = '" . 
		mysql_real_escape_string($photographe->getRIB_c()) . "', rib_k = '" .
		mysql_real_escape_string($photographe->getRIB_k()) . "', bic = '" . 
		mysql_real_escape_string($photographe->getBIC()) . "', iban = '" .
		mysql_real_escape_string($photographe->getIBAN()) . "', pourcentage = " .
		mysql_real_escape_string($photographe->getPourcentage()) . " where Utilisateur.utilisateurID = " . 
		"Photographe.id_utilisateur and Adresse.id_utilisateur = Utilisateur.utilisateurID and Utilisateur.utilisateurID = " .
		mysql_real_escape_string($photographe->getUtilisateurID()) . " and Photographe.photographeID = " .
		mysql_real_escape_string($photographe->getPhotographeID());
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return $photographe;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**###########################################
	 * Helpers
	 ############################################*/
	/**
	 * Pour effectivement creer le photographe en BD
	 * @param string $ne nom entreprise
	 * @param string $siren #siren
	 * @param string $tel telephone
	 * @param string $web site web
	 * @param string $utilisateur idutilisateur
	 * @param string $rib #rib
	 */
	protected function createPhotographe($photographe){
		$photographe->setIBAN(ModeleDAOUtils::Rib2Iban($photographe->getRIB_b(), $photographe->getRIB_g(), $photographe->getRIB_c(), $photographe->getRIB_k()));
		$ne = $photographe->getNomEntreprise();
		$siren = $photographe->getSiren();
		$tel = $photographe->getTelephone();
		$web = $photographe->getSiteWeb();
		$rib_b = $photographe->getRIB_b();
		$rib_g = $photographe->getRIB_g();
		$rib_c = $photographe->getRIB_c();
		$rib_k = $photographe->getRIB_k();
		$uid = $photographe->getUtilisateurID();
		$bic = $photographe->getBIC();
		$iban = $photographe->getIBAN();
		$pourcentage = $photographe->getPourcentage();
		$hometmp = date('Ymd');
		$query = "select count(*) as num from Photographe where home like '" . $hometmp . "%'";
		$tmp = $this->retrieve($query);
		if(!$tmp){
			return false;
		}
		$homeDelta = 0;
		foreach($tmp as $count){
			$homeDelta = $count['num'];
			break;
		}
		$home = $hometmp . sprintf("%02d", $homeDelta);
		$query = "insert into Photographe(nomEntreprise, siren, telephone, siteWeb, home, pourcentage, id_utilisateur, rib_b, rib_g, rib_c, rib_k, bic, iban) values ('" .
		mysql_real_escape_string($ne) . "', '" . 
		mysql_real_escape_string($siren) . "', '" . 
		mysql_real_escape_string($tel) . "', '" . 
		mysql_real_escape_string($web) . "', '" . 
		mysql_real_escape_string($home) . "', " .
		mysql_real_escape_string($pourcentage) . ", " .
		mysql_real_escape_string($uid) . ", '" .
		mysql_real_escape_string($rib_b) . "', '" .
		mysql_real_escape_string($rib_g) . "', '" .
		mysql_real_escape_string($rib_c) . "', '" .
		mysql_real_escape_string($rib_k) . "', '" .
		mysql_real_escape_string($bic) . "', '" .
		mysql_real_escape_string($iban) . "')";
		$tmp = $this->retrieve($query);
		if(!$tmp){
			return false;
		}
		$photographe->setPhotographeID($this->lastInsertedID());
		return $photographe;	
	}
}
?>
