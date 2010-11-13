<?php
$dir_commandedao_class_php = dirname(__FILE__);
include_once $dir_commandedao_class_php . "/daophp5/DAO.class.php";
include_once $dir_commandedao_class_php . "/CommandePhotoDAO.class.php";
include_once $dir_commandedao_class_php . "/../Commande.class.php";
include_once $dir_commandedao_class_php . "/../../controleur/ControleurUtils.class.php";
include_once $dir_commandedao_class_php . "/../../Config.php";

class CommandeDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

//	public function saveCommandeFoto($c){
//		$query = "update Commande set commandeFoto = '".
//		mysql_real_escape_string($c->getCommandeFoto()). "' where commandeID = " .
//		mysql_real_escape_string($c->getCommandeID());
//		$this->startTransaction();
//		$tmp = $this->update($query);
//		if($tmp && $this->getAffectedRows() == 1){
//			$this->commit();
//			return true;
//		}else{
//			$this->rollback();
//			return false;
//		}
//	}

	public function setTermineePourVielleCommandes(){
		list($usec, $sec) = explode(" ", microtime());
		$deuxSemaines = 60 * 60 * 24 * 14;
		$origin = date("Y-m-d", $sec - $deuxSemaines);
		$query = "update Commande set etat = 4 where datePaiement <= '".
		$origin . "' and etat = 3";
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >=0){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}
	public function getCommandeDepuisID_Album($ida){
		$sql = "select * from Commande left join AdresseCommande on commandeID = id_commande where id_album = " .
		mysql_real_escape_string($ida);
		$tmp = $this->retrieve($sql);
		return $this->extractArrayQuery($tmp, $this, "buildCommandeFromRow");
	}
	public function getCommandeEtPhotosDepuisID_Album($ida){
		$query = "select * from Commande as c left join CommandePhoto as cp on c.commandeID = cp.id_commande left join AdresseCommande as a on a.id_commande = c.commandeID where cp.id_album = " .
		mysql_real_escape_string($ida);
		$result = $this->retrieve($query);
		if($result->getNumRows() > 0) {
			$tmp = array();
			$daoCP = new CommandePhotoDAO();
			$currentCommande = NULL;
			foreach($result as $row){
				$commande = $this->buildCommandeFromRow($row);
				if($currentCommande == NULL || $currentCommande->getCommandeID() != $commande->getCommandeID()){
					//on enregistre la commande precedente
					if(isset($currentCommande)){//on est pas dans le premier run
						$tmp[] = $currentCommande;
					}
					//on met a jour la commande
					$currentCommande = $commande;
				}
				$photo = $daoCP->buildCommandePhotoFromRow($row);
				$currentCommande->addCommandePhoto($photo);
			}
			//reste � enregistrer la derni�re commande
			$tmp[] = $currentCommande;
			return $tmp;
		}else{
			return false;
		}
	}

	public function setEnCoursDePreparation($id, $prep){
		$query = "update Commande set etat = 2, preparateur = '" .
		mysql_real_escape_string($prep) . "' where commandeID = " .
		mysql_real_escape_string($id) . " and etat = 1";
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() == 1){
			$this->commit();
			return true;
		}else{
			$this->rollback();
			return false;
		}
	}

	public function getCommandeEtPhotosDepuisEtat($etat){
		$query = "select * from Commande as c left join CommandePhoto as cp on cp.id_commande = c.commandeID left join AdresseCommande as a on a.id_commande = c.commandeID where c.etat = " . 
		mysql_real_escape_string($etat);
		$tmp = $this->retrieve($query);
		if($tmp->getNumRows() > 0) {
			$result = array();
			$currentCommande = NULL;
			$daoCP = new CommandePhotoDAO();
			foreach($tmp as $row){
				$commande = $this->buildCommandeFromRow($row);
				$photo = $daoCP->buildCommandePhotoFromRow($row);
				if(!isset($currentCommande) || $currentCommande->getCommandeID() != $commande->getCommandeID()){
					$currentCommande = $commande;
					$result[] = $currentCommande;
				}
				$currentCommande->addCommandePhoto($photo);
			}
			return $result;
		}else{
			return false;
		}
	}

	public function getCommandeDepuisID($id){
		$query = "select * from Commande as c, AdresseCommande as a where a.id_commande = c.commandeID and commandeID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp,$this, "buildCommandeFromRow");
	}
	/**
	 * Sauve en BD seulement l'état de la commande. Si l'état est $COMMAND_STATES[1],
	 * doit en plus archiver la commande.
	 * appele seulement apres un etatSuivant... On check en plus l'etat precedent...
	 * @param Commande $commande
	 */
	public function saveEtat($commande){
		$dir_commandedao_class_php = dirname(__FILE__);
		include_once $dir_commandedao_class_php . "/../Album.class.php";
		include_once $dir_commandedao_class_php . "/../Photographe.class.php";
		$previousNumero = $commande->getNumero();
		try{
			if(!$this->lockTableChangementEtat()){
				ControleurUtils::addError("Impossible de locker talbe pour changement etat commande");
				$this->unlockTable();
				return false;
			}
			$previousState = $commande->getEtat() - 1;
			$this->startTransaction();
			$query = "update Commande set etat = " .
			mysql_real_escape_string($commande->getEtat());
			if($commande->getEtat() == 1){
				//on recupere le numero de commande et
				//on increment pour le prochain
				$numero = $this->getNumeroCommande();
				$commande->setNumero($numero);
				$query .= ", datePaiement = now(), numero = '" .
				mysql_real_escape_string($numero) . "' ";
			}
			$query .= " where commandeID = " .
			mysql_real_escape_string($commande->getCommandeID());
			global $COMMAND_STATES;
			if($previousState >= 0 && $previousState < count($COMMAND_STATES)){
				$query .= " and etat = " .
				mysql_real_escape_string($previousState);
			}
			$tmp = $this->update($query);
			if($tmp && $this->getAffectedRows() == 1){
				if($commande->getEtat() == 1){
					//on récupère les lignes
					$commandePhotos = $commande->getCommandesPhoto(); 
					if(!isset($commandePhotos) || count($commandePhotos) == 0){
						$commande = Commande::getCommandeEtPhotosDepuisID($commande->getCommandeID());
					}
					//on récupère le photographe & album
					if($commande->getCommandesPhoto() > 0){
						$commandePhotos = $commande->getCommandesPhoto(); 
						$idalbum = $commandePhotos[0]->getID_Album();
						$album = Album::getAlbumDepuisID($idalbum);
						$idphotographe = $album->getID_Photographe();
					}
					$commandePhotos = $commande->getCommandesPhoto();
					$prix = $commande->getFDP();
					foreach($commandePhotos as $commandePhoto){
						$prix += $commandePhoto->getPrix();
					}
					$query = "insert into CommandeArchive (date, id_utilisateur, numero, id_photographe, prix) values ('" .
					mysql_real_escape_string($commande->getDate()) . "', " .
					mysql_real_escape_string($commande->getID_Utilisateur()) . ", " .
					mysql_real_escape_string($commande->getNumero()) . ", " .
					mysql_real_escape_string($idphotographe) . ", " .
					mysql_real_escape_string($prix) . ")";
					$this->update($query);
					if($tmp && $this->getAffectedRows() >= 0){					
						$this->commit();		
						if(!$this->unlockTable()){
							ControleurUtils::addError("Impossible unlock table sur changement etat commande", true);
						}
						return true;
					}
				}else{
					$this->commit();
					if(!$this->unlockTable()){
						ControleurUtils::addError("Impossible unlock table sur changement etat commande", true);
					}
					return true;
				}
			}
			$this->rollback();
			if(!$this->unlockTable()){
				ControleurUtils::addError("Impossible unlock table sur changement etat commande", true);
			}
			$commande->setNumero($previousNumero);
			ControleurUtils::addError("Impossible de changer etat de commande: " . $query);
			return false;
		}catch(Exception $exception){
			$this->rollback();
			if(!$this->unlockTable()){
				ControleurUtils::addError("Impossible unlock table sur changement etat commande", true);
			}
			ControleurUtils::addError("Impossible de sauver l'etat de la facture: " . $exception->getMessage());
			$commande->setNumero($previousNumero);
			return false;
		}
	}
	/**
	 * Retourne un numero de commande valide
	 */
	private function getNumeroCommande(){
		$dir_commandedao_class_php = dirname(__FILE__);
		include_once '/NumeroCommandeDAO.class.php';
		include_once '/../NumeroCommande.class.php';
		$dao = new NumeroCommandeDAO();
		$prochain = $dao->getProchain();
		//on doit verifier le prochain sur ce pattern
		$numTmp = date('Ym');
		if(preg_match("/^".$numTmp."[0-9]+$/", $prochain->getProchain()) == 0){
			//le pattern ne match pas, on remet a 1 puisqu'on prend le 0...
			$numero = sprintf('%d%04d', $numTmp, 0);
		}else{
			$numero = $prochain->getProchain();
		}
		$prochainNumeroCommande = $numero + 1;
		$dao->setProchain($prochainNumeroCommande);
		return $numero;
	}
	/**
	 * Retourne un numero de commande avant encaissement...
	 */
	private function getFauxNumeroCommande(){
		$year = date('Y');
		$year = $year - 42;
		$month = date('m');
		$numTmp = sprintf('%4d%2d', $year, $month);
		$query = "select count(*) as num from Commande where numero like '" . $numTmp . "%'";
		$tmp = $this->retrieve($query);
		foreach($tmp as $count){
			$numDelta = $count['num'];
			break;
		}
		$numero = sprintf('%d%04d', $numTmp, $numDelta);
		return $numero;
	}
	public function unlockTable(){
		$query = "unlock tables";
		$tmp = $this->update($query);
		if($tmp){
			return true;
		}else{
			return false;
		}
	}
	public function lockTableCreateCommande(){
		$query = "lock tables Commande write, Commande as c write, CommandePhoto as cp write, AdresseCommande write";
		$tmp = $this->update($query);
		if($tmp){
			return true;
		}else{
			return false;
		}
	}
	public function lockTableChangementEtat(){
		$query = "lock tables Commande write, CommandeArchive write, NumeroCommande write";
		$tmp = $this->update($query);
		if($tmp){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * cree la commande en base de donnée et retourne
	 * la nouvelle commande créée.
	 * @param Commande $commande
	 */
	public function create($commande){
		try{
			if(!$this->lockTableCreateCommande()){
				ControleurUtils::addError("Impossible de locker table commande pour creation", true);
				$this->unlockTable();
				return false;
			}
			$this->startTransaction();
			$numero = $this->getFauxNumeroCommande();
			$id_album = $commande->getID_Album(); 
			if(isset($id_album)){
				$query = "insert into Commande (date, id_album, id_utilisateur, etat, fdp, numero) values (now(), ".
				mysql_real_escape_string($id_album) . ", ";		
			}else{
				$query = "insert into Commande (date, id_utilisateur, etat, fdp, numero) values (now(), ";
			}
			$query .= mysql_real_escape_string($commande->getID_Utilisateur()) . ", " . 
			mysql_real_escape_string($commande->getEtat()) . ", " .
			mysql_real_escape_string($commande->getFDP()) . ", '" .
			mysql_real_escape_string($numero) . "')";
			$tmp = $this->update($query);
			if($tmp && $this->getAffectedRows() >= 0){
				$commande->setCommandeID($this->lastInsertedID());
				//maintenant on cree chaque commandePhoto
				$commandesPhoto = $commande->getCommandesPhoto();
				$createdPhotos = array();
				try{
					foreach($commandesPhoto as $commandePhoto){
						$commandePhoto->setID_Commande($commande->getCommandeID());
						$isCPCreated = $commandePhoto->create();
						if(!$isCPCreated){
							$this->rollback();
							if(!$this->unlockTable()){
								ControleurUtils::addError("Impossible de unlocker commande sur erreur pour creation, !isCPCreated est true", true);
							}
							return false;
						}else{
							$createdPhotos[] = $isCPCreated;
						}
					}
					$commande->setCommandesPhoto($createdPhotos);
					
					//maintenant on cree l'adresse
					$dir_commandedao_class_php = dirname(__FILE__);
					include_once $dir_commandedao_class_php . "/AdresseCommandeDAO.class.php";
					include_once $dir_commandedao_class_php . "/../AdresseCommande.class.php";
					$adresse = $commande->getAdresse();
					$adresse->setID_Commande($commande->getCommandeID());
					if(isset($adresse)){
						$adao = new AdresseCommandeDAO();
						if(0 < $adresse->getAdresseCommandeID()){
							$adresse = $adao->save($adresse);
							if(!$adresse){
								$this->rollback();
								if(!$this->unlockTable()){
									ControleurUtils::addError("Impossible de unlocker commande sur erreur pour creation a la sauvegarde de ladresse", true);
								}
								return false;
							}
						}else{
							$adresse = $adao->create($adresse);
							if(!$adresse){
								$this->rollback();
								if(!$this->unlockTable()){
									ControleurUtils::addError("Impossible de unlocker commande sur erreur pour creation a la creation de ladresse", true);
								}
								return false;
							}
						}
						$commande->setAdresse($adresse);
					}
					$this->commit();
					if(!$this->unlockTable()){
						ControleurUtils::addError("Impossible de unlocker commande pour creation sur resultat correct", true);
					}
					return $commande;
				}catch(Exception $e){
					$this->rollback();
					if(!$this->unlockTable()){
						ControleurUtils::addError("Impossible de unlocker commande sur erreur pour creation sur catch 1", true);
					}
					return false;
				}	
			}
			$this->rollback();
			if(!$this->unlockTable()){
				ControleurUtils::addError("Impossible de unlocker commande sur erreur pour creation sur resultat faux", true);
			}
			return false;
		}catch(Exception $exception){
			$this->rollback();
			if(!$this->unlockTable()){
				ControleurUtils::addError("Impossible de unlocker commande sur erreur pour creation sur catch 2", true);
			}
			return false;
		}
	}
	/**
	 * Pour effacer la commande de la base de donnée
	 * retourne true si succes false sinon
	 * @param Commande $commande
	 */
	public function delete($commande){
		$query = "delete from Commande where commandeID = " . 
		mysql_real_escape_string($commande->getCommandeID());
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
	/**
	 * Renvoie une commande avec les photos mises à jour
	 * @param int $id
	 */
	public function getCommandeEtPhotosDepuisID($id){
		$query = "select * from Commande as c left join CommandePhoto as cp on cp.id_commande = c.commandeID left join AdresseCommande as a on a.id_commande = c.commandeID where c.commandeID = " . 
		mysql_real_escape_string($id);
		$result = $this->retrieve($query);
		if($result->getNumRows() > 0) {
			$commande = NULL;
			$isOKForCommande = false;
			$daoCP = new CommandePhotoDAO();
			foreach($result as $row){
				if(!$isOKForCommande){
					$commande = $this->buildCommandeFromRow($row);
					$isOKForCommande = true;
				}
				$photo = $daoCP->buildCommandePhotoFromRow($row);
				$commande->addCommandePhoto($photo);
			}
			return $commande;
		}else{
			return false;
		}
	}
	/**
	 * Renvoie le tableau des commandes de l'utilisateur avec id = $id.
	 * $result[$i] == L'object commande avec toutes les commandes photo � jour
	 * @param int $id
	 */
	public function getCommandesEtPhotosDepuisID_Utilisateur($id){
		$query = "select * from Commande as c left join CommandePhoto as cp on c.commandeID = cp.id_commande left join AdresseCommande as a on a.id_commande = c.commandeID where c.id_utilisateur = " . 
		mysql_real_escape_string($id) . 
		" order by date desc";
		$result = $this->retrieve($query);
		if($result->getNumRows() > 0) {
			$tmp = array();
			$daoCP = new CommandePhotoDAO();
			$currentCommande = NULL;
			foreach($result as $row){
				$commande = $this->buildCommandeFromRow($row);
				if($currentCommande == NULL || $currentCommande->getCommandeID() != $commande->getCommandeID()){
					//on enregistre la commande precedente
					if(isset($currentCommande)){//on est pas dans le premier run
						$tmp[] = $currentCommande;
					}
					//on met a jour la commande
					$currentCommande = $commande;
				}
				$photo = $daoCP->buildCommandePhotoFromRow($row);
				$currentCommande->addCommandePhoto($photo);
			}
			//reste � enregistrer la derni�re commande
			$tmp[] = $currentCommande;
			return $tmp;
		}else{
			return false;
		}
	}
	/**
	 * Retourne la commande avec l'identifiant demande si elle existe,
	 * false sinon
	 * @param int $id
	 */
	public function getCommandeSansPhotos($id){
		$query = "select * from Commande as c, AdresseCommande as a where a.id_commande = c.commandeID commandeID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildCommandeFromRow");
	}
	/**
	 * Retourne les commandes de l'utilisateur avec l'identifiant $id 
	 * @param int $id
	 */
	public function getCommandeDepuisID_Utilisateur($id){
		$query = "select * from Commande, AdresseCommande as a where a.id_commande = c.commandeID id_utilisateur = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildCommandeFromRow");
	}

	public function setExpediee($c){
		$sql = "update Commande set etat = 3 where etat = 2 and commandeID = " .
		mysql_real_escape_string($c->getCommandeID());
		$this->startTransaction();
		$tmp = $this->update($sql);
		if($tmp && $this->getAffectedRows() == 1){
			$c->setEtat(3);
			$this->commit();
			return $c;
		}else{
			$this->rollback();
			return false;
		}
	}

	/*######################################
	 * Helpers
	 ######################################*/
	public function buildCommandeFromRow($row){
		$dir_commandedao_class_php = dirname(__FILE__);
		include_once $dir_commandedao_class_php . "/AdresseCommandeDAO.class.php";
		include_once $dir_commandedao_class_php . "/../AdresseCommande.class.php";
		$adao = new AdresseCommandeDAO();
		$adresse = $adao->buildAdresseCommandeFromRow($row);
		$id = $row->offsetGet("commandeID");
		$date = htmlspecialchars($row->offsetGet("date"));
		$dp = htmlspecialchars($row->offsetGet("datePaiement"));
		$idUtilisateur = $row->offsetGet("id_utilisateur");
		$s = $row->offsetGet("etat");
		$fdp = $row->offsetGet("fdp");
		$numero = $row->offsetGet("numero");
		$id_album = $row->offsetGet("id_album");
		$prep = htmlspecialchars($row->offsetGet("preparateur"));
		$result = new Commande();
		$result->setAdresse($adresse);
		$result->setFDP($fdp);
		$result->setCommandeID($id);
		$result->setDate($date);
		$result->setDatePaiement($dp);
		$result->setNumero($numero);
		$result->setID_Utilisateur($idUtilisateur);
		$result->setEtat($s);
		$result->setID_Album($id_album);
		$result->setPreparateur($prep);
		return $result;
	}

	public function buildCommandeAndCommandePhotoFromRow($row){
		$result = array();
		$result["Commande"] = $this->buildCommandeFromRow($row);
		$daoCP = new CommandePhotoDAO();
		$result["CommandePhoto"] = $daoCP->buildCommandePhotoFromRow($row);
		return $result;
	}
}
?>
