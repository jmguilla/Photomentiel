<?php
$dir_utilisateurdao_class_php = dirname(__FILE__);
include_once $dir_utilisateurdao_class_php . "/daophp5/DAO.class.php";
include_once $dir_utilisateurdao_class_php . "/../../Config.php";

class UtilisateurDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function getUtilisateurFromEmailAndPassword($email, $MD5pwd){
		$query = "select * from Utilisateur as u left join Photographe as p on u.utilisateurID = p.id_utilisateur left join Adresse as a on u.utilisateurID = a.id_utilisateur where email = '" . 
		mysql_real_escape_string($email) . "' and mdp = md5('" . 
		mysql_real_escape_string($MD5pwd) . "') and actif = true";
		$tmp = $this->retrieve($query);
		$result = $this->extractObjectQuery($tmp, $this, "buildUtilisateurFromRow");
		if($result == false){
			return -1;
		}else{
			return $result->getUtilisateurID();
		}
	}

	public function getNonActif(){
		$query = "select * from Adresse as ad, Activate as a, Utilisateur as u left join Photographe as p on u.utilisateurID = p.id_utilisateur where a.id_utilisateur = u.utilisateurID and ad.id_utilisateur = u.utilisateurID";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildUtilisateurEtActivateIDFromRow");
	}

	public function create($utilisateur, $activateID){
		$dir_utilisateurdao_class_php = dirname(__FILE__);
		include_once $dir_utilisateurdao_class_php . "/AdresseDAO.class.php";
		include_once $dir_utilisateurdao_class_php . "/../Adresse.class.php";
		include_once $dir_utilisateurdao_class_php . "/../CreateException.class.php";
		//controle de l'email.
		$email = $utilisateur->getEmail();
		if(!$this->controleEmail($email)){
			throw new CreateUtilisateurException("Email already in use");
		}
		$this->startTransaction();
		//creation de l'utilisateur
		$utilisateur = $this->createUtilisateur($utilisateur, $activateID);

		if(!$utilisateur){
			$this->rollback();
			throw new CreateUtilisateurException("Cannot get the newly created user.");
		}

		$adresse = $utilisateur->getAdresse();
		if(isset($adresse)){
		$adresse->setID_Utilisateur($utilisateur->getUtilisateurID());
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
		return $utilisateur;
	}

	public function getUtilisateurs(){
		$query = "select * from Utilisateur as u left join Photographe as p on u.utilisateurID = p.id_utilisateur left join Adresse as a on u.utilisateurID = a.id_utilisateur";
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildUtilisateurFromRow");
	}

	public function getUtilisateurDepuisID($id){
		$query = "select * from Utilisateur as u left join Photographe as p on u.utilisateurID = p.id_utilisateur left join Adresse as a on a.id_utilisateur = u.utilisateurID where utilisateurID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildUtilisateurFromRow");
	}

	public function delete($usr){
		$this->startTransaction();
		$query = "delete from Utilisateur where utilisateurID = " . 
		mysql_real_escape_string($usr->getUtilisateurID());
		$tmp = $this->retrieve($query);
		if($tmp){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * sauve le parametre en BD.
	 * Retourne true en cas de succes, false sinon.
	 * @param Utilisateur $utilisateur
	 */
	public function save($utilisateur){
		$adresse = $utilisateur->getAdresse();
		$query = "update Utilisateur, Adresse set nom = '".
		mysql_real_escape_string($adresse->getNom()) . "', prenom = '" .
		mysql_real_escape_string($adresse->getPrenom()) . "', nomRue = '" .
		mysql_real_escape_string($adresse->getNomRue()) . "', complement = '" .
		mysql_real_escape_string($adresse->getComplement()) . "', ville = '" .
		mysql_real_escape_string($adresse->getVille()) . "', codePostal = '" .
		mysql_real_escape_string($adresse->getCodePostal()) .
		"' where utilisateurID = " . 
		mysql_real_escape_string($utilisateur->getUtilisateurID()) .
		" and Utilisateur.utilisateurID = Adresse.id_utilisateur";
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$this->commit();
			return $this->getUtilisateurDepuisID($utilisateur->getUtilisateurID());
		}else{
			$this->rollback();
			return false;
		}
	}
	/**
	 * Met a jour le mdp en BD. L'utilisateur est identifie grace a son email.
	 * Il est ainsi possible d'utiliser cette methode en cas de perte de mdp.
	 * Retourne true en cas de succes, false sinon
	 * @param Utilisateur $utilisateur
	 * @param string $newMDP
	 */
	public function saveMDPEtEnvoyerEmail($utilisateur, $newMDP, $genere = false){
		$query = "update Utilisateur set mdp = md5('" . 
		mysql_real_escape_string($newMDP) . "') where email = '" . 
		mysql_real_escape_string($utilisateur->getEmail()) . "'";
		$this->startTransaction();
		$tmp = $this->retrieve($query);
		if($tmp && $this->getAffectedRows() >= 0){
			$dir_utilisateurdao_class_php = dirname(__FILE__);
			include_once $dir_utilisateurdao_class_php . "/../../controleur/ControleurUtils.class.php";
			$res = ControleurUtils::sendNouveauMDPEmail($utilisateur, $newMDP, $genere);
			if($res){
				$this->commit();
				return md5($newMDP);
			}
		}
		$this->rollback();
		return false;
	}
	/**
	 * Marque cet utilisateur comme actif. Cela implique que son
	 * adresse email a pu etre validee. Le parametre est un unique ID
	 * (PHPSESSID?) setté à la création de l'utilisateur.
	 */
	public function activer($id){
		$query = "update Utilisateur, Activate set actif = true where activateID = '" . 
		mysql_real_escape_string($id) . "' and utilisateurID = id_utilisateur";
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			//maintenant on remove l'activateID
			$query = "delete from Activate where activateID = '" .
			mysql_real_escape_string($id) . "'";
			$this->startTransaction();
			$tmp = $this->update($query);
			$this->commit();
			return true;
		}else{
			return false;
		}
	}

	/**##############################################
	 * Helpers
	 ###############################################*/

	/**
	 * Determine si un email peut etre utilise ou s'il est deja en BD.
	 * retourne false si l'email est deja present de BD, true sinon.
	 * @param string $email
	 */
	public function controleEmail($email){
		$query = "select * from Utilisateur where email = '" . 
		mysql_real_escape_string($email) . "'";
		$tmp = $this->retrieve($query);
		if($tmp->getNumRows() > 0){
			return false;
		}
		return true;
	}
	/**
	 * Renvoie un objet du type Utilisateur ou Photographe fonction de la ligne resultat
	 * de select mysql en y ajoutant le prefix $prefix si setté.
	 * attention, l'adresse est également retrouvé en ajoutant ce prefix.
	 * Enter description here ...
	 * @param ligne resultat mysql $row
	 * @param string $prefix
	 */
	public function buildUtilisateurFromRow($row, $prefix = '', $pa = ''){
		$dir_utilisateurdao_class_php = dirname(__FILE__);
		include_once $dir_utilisateurdao_class_php . "/AdresseDAO.class.php";
		include_once $dir_utilisateurdao_class_php . "/../Adresse.class.php";
		$adao = new AdresseDAO();
		$adresse = $adao->buildAdresseFromRow($row, $pa);
		$id = $row->offsetGet($prefix . "utilisateurID");
		$email = htmlspecialchars($row->offsetGet($prefix . "email"));
		$dateInscription = htmlspecialchars($row->offsetGet($prefix . "dateInscription"));
		$mdp = htmlspecialchars($row->offsetGet($prefix . "mdp"));
		$actif = htmlspecialchars($row->offsetGet($prefix . "actif"));
		//est-ce un photographe?
		$pid = $row->offsetGet($prefix . "photographeID");
		$result = NULL;
		if(isset($pid)){
			$dirname = dirname(__FILE__);
			include_once $dirname . "/../Photographe.class.php";
			$result = new Photographe();
			$isTelPub = htmlspecialchars($row->offsetGet($prefix . "isTelephonePublique"));
			$isReady = htmlspecialchars($row->offsetGet($prefix . "isReady"));
			$tva = htmlspecialchars($row->offsetGet($prefix . "TVA"));
			$siren = htmlspecialchars($row->offsetGet($prefix . "siren"));
			$sw = htmlspecialchars($row->offsetGet($prefix . "siteWeb"));
			$tel = htmlspecialchars($row->offsetGet($prefix . "telephone"));
			$ne = htmlspecialchars($row->offsetGet($prefix . "nomEntreprise"));
			$home = htmlspecialchars($row->offsetGet($prefix . "home"));
			$rib_b = htmlspecialchars($row->offsetGet($prefix . "rib_b"));
			$rib_g = htmlspecialchars($row->offsetGet($prefix . "rib_g"));
			$rib_c = htmlspecialchars($row->offsetGet($prefix . "rib_c"));
			$rib_k = htmlspecialchars($row->offsetGet($prefix . "rib_k"));
			$bic = htmlspecialchars($row->offsetGet($prefix . "bic"));
			$iban = htmlspecialchars($row->offsetGet($prefix . "iban"));
			$pourcentage = $row->offsetGet($prefix . "pourcentage");
			$note = $row->offsetGet($prefix . "note");
			$nv = $row->offsetGet($prefix . "nombreVotant");
			$ftp = $row->offsetGet($prefix . "openftp");
			$result->setTVA($tva);
			$result->setIsTelephonePublique($isTelPub);
			$result->setIsReadey($isReady);
			$result->setOpenFTP($ftp);
			$result->setNombreVotant($nv);
			$result->setNote($note);
			$result->setHome($home);
			$result->setPhotographeID($pid);
			$result->setNomEntreprise($ne);
			$result->setTelephone($tel);
			$result->setSiteWeb($sw);
			$result->setSiren($siren);
			$result->setRIB_b($rib_b);
			$result->setRIB_g($rib_g);
			$result->setRIB_c($rib_c);
			$result->setRIB_k($rib_k);
			$result->setBIC($bic);
			$result->setIBAN($iban);
			$result->setPourcentage($pourcentage);
		}else{
			$result = new Utilisateur();
		}
		$result->setActif($actif);
		$result->setMDP($mdp);
		$result->setDateInscription($dateInscription);
		$result->setEmail($email);
		$result->setUtilisateurID($id);
		$result->setAdresse($adresse);
		return $result;
	}

	protected function buildActivateIDFromRow($row, $prefix = ''){
		return $row->offsetGet($prefix . "activateID");
	}

	protected function buildUtilisateurEtActivateIDFromRow($row, $prefix = '', $pa = '', $pactivate = ''){
		$result = array();
		$result['Utilisateur'] = $this->buildUtilisateurFromRow($row, $prefix, $pa);
		$result['ActivateID'] = $this->buildActivateIDFromRow($row, $pactivate);
		return $result;
	}

	/**
	 * Cette fonction execute la requete de creation de l'utilisateur.
	 * Elle est utilise dans les methodes de creation de Photographe & utilisateur.
	 * @param unknown_type $nom
	 * @param unknown_type $prenom
	 * @param unknown_type $pwd
	 * @param unknown_type $email
	 * @param unknown_type $id_adresse
	 */
	protected function createUtilisateur($utilisateur, $id){
		$pwd = $utilisateur->getMDP();
		$email = $utilisateur->getEmail();
		$query = "insert into Utilisateur(mdp, email, dateInscription) values ( md5('" . 
		mysql_real_escape_string($pwd) . "'), '" . 
		mysql_real_escape_string($email) . "', now())";
		$tmp = $this->retrieve($query);
		if(!$tmp){
			return false;
		}
		$utilisateur->setUtilisateurID($this->lastInsertedID());
		$query = "insert into Activate (activateID, id_utilisateur) values('" . 
		mysql_real_escape_string($id) . mysql_real_escape_string($utilisateur->getUtilisateurID()) . "', " .
		mysql_real_escape_string($utilisateur->getUtilisateurID()) . ")";
		$tmp = $this->retrieve($query);
		if(!$tmp){
			return false;
		}
		return $utilisateur;
	}
}
?>
