<?php
$dir_commandedao_class_php = dirname(__FILE__);
include_once $dir_commandedao_class_php . "/daophp5/DAO.class.php";
include_once $dir_commandedao_class_php . "/CommandePhotoDAO.class.php";
include_once $dir_commandedao_class_php . "/../Commande.class.php";
include_once $dir_commandedao_class_php . "/../../Config.php";

class CommandeDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
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
	 * @param Commande $commande
	 */
	public function saveEtat($commande){
		$dir_commandedao_class_php = dirname(__FILE__);
		include_once $dir_commandedao_class_php . "/../Album.class.php";
		include_once $dir_commandedao_class_php . "/../Photographe.class.php";
		$query = "update Commande set etat = " .
		mysql_real_escape_string($commande->getEtat()) .
		" where commandeID = " .
		mysql_real_escape_string($commande->getCommandeID());
		$this->startTransaction();
		$tmp = $this->update($query);
		if($tmp && $this->getAffectedRows() >= 0){
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
					return true;
				}
			}
		}
		$this->rollback();
		return false;
	}
	/**
	 * cree la commande en base de donn�e et retourne
	 * la nouvelle commande créée.
	 * @param Commande $commande
	 */
	public function create($commande){
		//d'abord on calcul le numéro de la commande
		$numTmp = date('ymdH');
		$query = "select count(*) as num from Commande where numero like '" . $numTmp . "%'";
		$tmp = $this->retrieve($query);
		foreach($tmp as $count){
			$numDelta = $count['num'];
			break;
		}
		$numero = $numTmp . sprintf("%04d", $numDelta);
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
		$this->startTransaction();
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
							return false;
						}
					}else{
						$adresse = $adao->create($adresse);
						if(!$adresse){
							$this->rollback();
							return false;
						}
					}
					$commande->setAdresse($adresse);
				}

				$this->commit();
				return $commande;
			}catch(Exception $e){
				echo $e->getMessage();
				$this->rollback();
				return false;
			}	
		}
		$this->rollback();
		return false;
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
		$date = $row->offsetGet("date");
		$idUtilisateur = $row->offsetGet("id_utilisateur");
		$s = $row->offsetGet("etat");
		$fdp = $row->offsetGet("fdp");
		$numero = $row->offsetGet("numero");
		$id_album = $row->offsetGet("id_album");
		$result = new Commande();
		$result->setAdresse($adresse);
		$result->setFDP($fdp);
		$result->setCommandeID($id);
		$result->setDate($date);
		$result->setNumero($numero);
		$result->setID_Utilisateur($idUtilisateur);
		$result->setEtat($s);
		$result->setID_Album($id_album);
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