<?php
$dir_commandephoto_class_php = dirname(__FILE__);
include_once $dir_commandephoto_class_php . "/modele_dao/CommandePhotoDAO.class.php";

class CommandePhoto{
	private $id;
	private $photo;
	private $nb;
	private $id_commande;
	private $id_type;
	private $id_taille;
	private $id_couleur;
	private $id_album;
	private $prix;

	public function __construct($id = -1, $photo = NULL, $nb = -1, $id_commande = -1, $id_type = -1, $id_taille = -1, $id_couleur = -1, $id_album = -1, $prix = 0){
		$this->id = $id;
		$this->photo = $photo;
		$this->nb = $nb;
		$this->id_commande = $id_commande;
		$this->id_type = $id_type;
		$this->id_taille = $id_taille;
		$this->id_couleur = $id_couleur;
		$this->id_album = $id_album;
		$this->prix = $prix;
	}

	public static function getCommandePhotoDepuisID($id){
		$dao = new CommandePhotoDAO();
		return $dao->getCommandePhotoDepuisID($id);
	}

	/**
	 * Renvoie toutes les commandes photos associees a un album donne
	 * @param $id
	 */
	public static function getCommandePhotoDepuisID_Album($id){
		$dao = new CommandePhotoDAO();
		return $dao->getCommandePhotoDepuisID_Album($id);
	}

	/**
	 * Pour creer une nouvelle entree dans la BD. Le resultat n'est pas commite
	 * pour pouvoir etre coherent a la creation de commandes...
	 * @param varchar $photo
	 * @param int $nb
	 * @param int $id_type
	 * @param int $id_taille
	 * @param int $id_couleur
	 * @param int $id_album
	 */
	public function create(){
		$dao = new CommandePhotoDAO();
		return $dao->create($this);
	}

	/**
	 * Renvoie la liste des CommandePhotos associees a une commande.
	 * @param $id
	 */
	public static function getCommandePhotosDepuisID_Commande($id){
		if(!isset($id)){
			throw new InvalidArgumentException("commande id needed to get associated commande photos");
		}
		$dao = new CommandePhotoDAO();
		return $dao->getCommandePhotosDepuisID_Commande($id);
	}

	/*######################################
	 * Getters & Setters
	 ######################################*/
	public function getCommandePhotoID(){
		return $this->id;
	}
	public function setCommandePhotoID($id){
		$this->id = $id;
	}
	public function getID_Commande(){
		return $this->id_commande;
	}
	public function setID_Commande($id){
		$this->id_commande = $id;
	}
	public function getPhoto(){
		return $this->photo;
	}
	public function setPhoto($p){
		$this->photo = $p;
	}
	public function getNombre(){
		return $this->nb;
	}
	public function setNombre($n){
		$this->nb = $n;
	}
	public function getID_TypePapier(){
		return $this->id_type;
	}
	public function setID_TypePapier($i){
		$this->id_type = $i;
	}
	public function getID_TaillePapier(){
		return $this->id_taille;
	}
	public function setID_TaillePapier($i){
		$this->id_taille = $i;
	}
	public function getID_Couleur(){
		return $this->id_couleur;
	}
	public function setID_Couleur($i){
		$this->id_couleur = $i;
	}
	public function getID_Album(){
		return $this->id_album;
	}
	public function setID_Album($i){
		$this->id_album = $i;
	}
	public function setPrix($p){
		$this->prix = $p;
	}
	public function getPrix(){
		return $this->prix;
	}
}
?>