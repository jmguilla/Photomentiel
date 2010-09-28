<?php
$dir_commandephotodao_class_php = dirname(__FILE__);
include_once $dir_commandephotodao_class_php . "/daophp5/DAO.class.php";
include_once $dir_commandephotodao_class_php . "/../../Config.php";

class CommandePhotoDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function getCommandePhotoDepuisID_Album($id){
		$query = "select * from CommandePhoto where id_album = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildCommandePhotoFromRow");
	}

	public function getCommandePhotoDepuisID($id){
		$query = "select * from CommandePhoto where commandePhotoID = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildCommandePhotoFromRow");
	}

	public function getCommandePhotosDepuisID_Commande($id){
		$query = "select * from CommandePhoto where id_commande = " . 
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildCommandePhotoFromRow");
	}

	/**
	 * Pour creer une nouvelle entree dans la BD.
	 * @param CommandePhoto $cp
	 */
	public function create($cp){
		$query = "insert into CommandePhoto(photo, nombre, id_commande, id_typePapier, id_taillePapier, id_couleur, id_album, prix) values ('".
		mysql_real_escape_string($cp->getPhoto()) . "', " . 
		mysql_real_escape_string($cp->getNombre()) . ", " . 
		mysql_real_escape_string($cp->getID_Commande()) . ", " .
		mysql_real_escape_string($cp->getID_TypePapier()) . ", " . 
		mysql_real_escape_string($cp->getID_TaillePapier()) . ", " . 
		mysql_real_escape_string($cp->getID_Couleur()) . ", " . 
		mysql_real_escape_string($cp->getID_Album()) . ", " . 
		mysql_real_escape_string($cp->getPrix()) . ")";
		$tmp = $this->update($query);
		if($tmp){
			$cp->setCommandePhotoID($this->lastInsertedID());
			return $cp;
		}
		return false;
	}

	public function delete($cp){
		$query = "delete from CommandePhoto where commandePhotoID = " . 
		mysql_real_escape_string($cp->getCommandePhotoID());
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

	/*##################################
	 * Helpers
	 ###################################*/
	public function buildCommandePhotoFromRow($row){
		$id = $row->offsetGet("commandePhotoID");
		$photo = htmlspecialchars($row->offsetGet("photo"));
		$nb = htmlspecialchars($row->offsetGet("nombre"));
		$id_taille = $row->offsetGet("id_taillePapier");
		$id_type = $row->offsetGet("id_typePapier");
		$id_couleur = $row->offsetGet("id_couleur");
		$id_album = $row->offsetGet("id_album");
		$id_commande = $row->offsetGet("id_commande");
		$prix = $row->offsetGet("prix");
		$result = new CommandePhoto();
		$result->setID_Commande($id_commande);
		$result->setID_Album($id_album);
		$result->setID_Couleur($id_couleur);
		$result->setID_TypePapier($id_type);
		$result->setID_TaillePapier($id_taille);
		$result->setNombre($nb);
		$result->setPhoto($photo);
		$result->setCommandePhotoID($id);
		$result->setPrix($prix);
		return $result;
	}
}