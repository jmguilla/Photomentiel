<?php
$dir_commande_class_php = dirname(__FILE__);
include_once $dir_commande_class_php . "/modele_dao/CommandeDAO.class.php";
include_once $dir_commande_class_php . "/../Config.php";

class Commande{
	private $id;
	private $date;
	private $idutilisateur;
	private $commandesPhoto;
	private $etat;
	private $adresse = NULL;
	private $fdp = 0;
	private $numero = '';
	private $id_album = NULL;

	public function __construct($i = -1, $idu = -1, $date = NULL, $etat = NULL){
		$this->id = $i;
		$this->idutilisateur = $idu;
		$this->date = $date;
		global $COMMAND_STATES;
		$this->etat = 0;
		$commandesPhoto = array();
	}
	/**
	 * Renvoie la commande identifée par l'id passé en parametre
	 * sans les photos associées
	 * @param int $id
	 */
	public static function getCommandeDepuisID($id){
		$dao = new CommandeDAO();
		return $dao->getCommandeDepuisID($id);
	}
	/**
	 * Retourne les commandes de l'utilisateur avec l'identifiant $id 
	 * @param int $id
	 */
	public static function getCommandeDepuisID_Utilisateur($id){
		$dao = new CommandeDAO();
		return $dao->getCommandeDepuisID_Utilisateur($id);
		
	}
	/**
	 * renvoie la commande avec les ligne à jour.
	 * false si la commande echoue.
	 * @param int $id
	 */
	public static function getCommandeEtPhotosDepuisID($id){
		$dir_commande_class_php = dirname(__FILE__);
		include_once $dir_commande_class_php . "/CommandePhoto.class.php";
		$dao = new CommandeDAO();
		return $dao->getCommandeEtPhotosDepuisID($id);
	}
	/**
	 * Renvoie un tableau de commandes avec les lignes mises � jour.
	 * ou false si erreur
	 * @param int $id
	 */
	public static function getCommandesEtPhotosDepuisID_Utilisateur($id){
		$dir_commande_class_php = dirname(__FILE__);
		include_once $dir_commande_class_php . "/CommandePhoto.class.php";
		$dao = new CommandeDAO();
		return $dao->getCommandesEtPhotosDepuisID_Utilisateur($id);
	}
	/**
	 * Sauve cette commande en base de donnée
	 */
	public function create(){
		$dao = new CommandeDAO();
		return $dao->create($this);
	}
	/**
	 * pour deleter une commande
	 */
	public function delete(){
		$dao = new CommandeDAO();
		return $dao->delete($this);
	}

	/*#####################################
	 * Getters & Setters
	 ######################################"*/
	public function addCommandePhoto($commandePhoto){
		$this->commandesPhoto[] = $commandePhoto;
	}
	public function setCommandesPhoto($array){
		$this->commandesPhoto = $array;
	}
	public function getCommandesPhoto(){
		$dir_commande_class_php = dirname(__FILE__);
		include_once $dir_commande_class_php . "/CommandePhoto.class.php";
		return $this->commandesPhoto;
	}
	public function getCommandeID(){
		return $this->id;
	}
	public function setCommandeID($id){
		$this->id = $id;
	}
	public function getID_Utilisateur(){
		return $this->idutilisateur;
	}
	public function setID_Utilisateur($id){
		$this->idutilisateur = $id;
	}
	public function getDate(){
		return $this->date;
	}
	public function setDate($d){
		$this->date = $d;
	}
	public function getEtat(){
		return $this->etat;
	}
	public function setEtat($e){
		$this->etat = $e;
	}
	public function etatSuivant(){
		global $COMMAND_STATES;
		if(!isset($this->etat)){
			$this->etat = 0;
		}else{
			$this->etat++;
			if($this->etat >= count($COMMAND_STATES)){
				$this->etat = count($COMMAND_STATES) - 1;
			}
		}
		$dao = new CommandeDAO();
		return $dao->saveEtat($this);
	}
	public function getAdresse(){
		return $this->adresse;
	}
	public function setAdresse($a){
		$this->adresse = $a;
	}
	public function getFDP(){
		return $this->fdp;
	}
	public function setFDP($fdp){
		$this->fdp = $fdp;
	}
	public function getNumero(){
		return $this->numero;
	}
	public function setNumero($n){
		$this->numero = $n;
	}
	public function getID_Album(){
		return $this->id_album;
	}
	public function setID_Album($ida){
		$this->id_album = $ida;
	}
}
?>
