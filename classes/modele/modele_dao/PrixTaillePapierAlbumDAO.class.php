<?php
$dir_prixtaillepapieralbum_class_php = dirname(__FILE__);
include_once $dir_prixtaillepapieralbum_class_php . "/daophp5/DAO.class.php";
include_once $dir_prixtaillepapieralbum_class_php . "/../../Config.php";
include_once $dir_prixtaillepapieralbum_class_php . "/../PrixTaillePapierAlbum.class.php";

class PrixTaillePapierAlbumDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}
	/**
	 * Retourne un tableau contenant le prix pour la taille $idt pour
	 * l'album $ida. Si $idt n'est pas fournie, renvoie la totalit� des prix.
	 * @param int $ida
	 * @param int $idt
	 */
	public function getPrixTaillePapiersDepuisID_Album($ida, $idt){
		$query = "select * from PrixTaillePapierAlbum where id_album = " . 
		mysql_real_escape_string($ida);
		if(isset($idt)){
			$query = $query . " and id_taillePapier = " . 
			mysql_real_escape_string($idt);
		}
		$tmp = $this->retrieve($query);
		return $this->extractArrayQuery($tmp, $this, "buildPrixTaillePapierAlbumFromRow");
	}
	/**
	 * Renvoie l'objet de type prixtaillepapieralbum avec l'id fournie.
	 * @param unknown_type $id
	 */
	public function getPrixTaillePapierAlbum($id){
		if(!isset($id)){
			throw new InvalidArgumentException("id must be set to retrieve an object from ID");
		}
		$query = "select * from PrixTaillePapierAlbum where prixTaillePapierAlbumID = " .
		mysql_real_escape_string($id);
		$tmp = $this->retrieve($query);
		return $this->extractObjectQuery($tmp, $this, "buildPrixTaillePapierAlbumFromRow");
	}
	/**
	 * cree l'objet passe en parametre en BD et retourne une copie a jour.
	 * ne gére pas les transactions afin de pouvoir le faire depuis AlbumDAO
	 * @param unknown_type $ptpa
	 */
	public function create($ptpa){
		$query = "insert into PrixTaillePapierAlbum (prix, id_taillePapier, id_album) values (" .
		mysql_real_escape_string($ptpa->getPrix()) . ", " .
		mysql_real_escape_string($ptpa->getID_TaillePapier()) . ", " .
		mysql_real_escape_string($ptpa->getID_Album()) . ")";
		$tmp = $this->retrieve($query);
		if($tmp && $this->getAffectedRows()>0){
			$ptpa->setPrixTaillePapierAlbumID($this->lastInsertedID());
			return $ptpa;
		}
		return false;
	}
	/**
	 * supprime l'objet passe en parametre de la BD
	 * retourne true en cas de succes, false sinon.
	 * @param unknown_type $ptpa
	 */
	public function delete($ptpa){
		$query = "delete from PrixTaillePapierAlbum where prixTaillePapierAlbumID = " .
		mysql_real_escape_string($ptpa->getPrixTaillePapierAlbumID());
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

	/*############################
	 * Helpers
	 ###########################*/
	public function buildPrixTaillePapierAlbumFromRow($row){
		$id = $row->offsetGet("prixTaillePapierAlbumID");
		$idp = $row->offsetGet("id_taillePapier");
		$ida = $row->offsetGet("id_album");
		$prix = $row->offsetGet("prix");
		$result = new PrixTaillePapierAlbum();
		$result->setPrixTaillePapierAlbumID($id);
		$result->setID_TaillePapier($idp);
		$result->setID_Album($ida);
		$result->setPrix($prix);
		return $result;
	}
}
?>