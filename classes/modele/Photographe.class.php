<?php
$dir_photographe_class_php = dirname(__FILE__);
include_once $dir_photographe_class_php . "/Utilisateur.class.php";
include_once $dir_photographe_class_php . "/modele_dao/PhotographeDAO.class.php";

class Photographe extends Utilisateur{
	
	protected $photographeID;
	protected $siren;
	protected $telephone;
	protected $siteweb;
	protected $nomEntreprise;
	protected $home;
	protected $pourcentage = PHOTOGRAPH_INITIAL_PERCENT;
	protected $note = 6;
	protected $nombreVotant = 1;
	protected $rib_b;
	protected $rib_g;
	protected $rib_c;
	protected $rib_k;
	protected $iban;
	protected $bic;
	
	public function __construct($id = -1, $nom = NULL, $prenom = NULL, $email = NULL, $dateInscription = NULL, $adresse_id = NULL, $photographeID = NULL, $siren = '', $telephone = '', $siteweb = '', $nomEntreprise = '', $home = '', $rib_b = '', $rib_g = '', $rib_c = '', $rib_k = '', $iban = '', $bic = ''){
		parent::__construct($id, $nom, $prenom, $email, $dateInscription, $adresse_id);
		$this->photographeID = $photographeID;
		$this->siren = $siren;
		$this->telephone = $telephone;
		$this->siteweb = $siteweb;
		$this->nomEntreprise = $nomEntreprise;
		$this->home = $home;
		$this->rib_b = $rib_b;
		$this->rib_g = $rib_g;
		$this->rib_c = $rib_c;
		$this->rib_k = $rib_k;
		$this->iban = $iban;
		$this->bic = $bic;
	}
	/** Renvoie un photographe de la BD aleatoirement */
	public static function getPhotographeAleatoire(){
		$daoPhotographe = new PhotographeDAO();
		return $daoPhotographe->getPhotographeAleatoire();
	}
	/** Renvoie tous les photographes */
	public static function getPhotographes(){
		$daoPhotographe = new PhotographeDAO();
		return $daoPhotographe->getPhotographes();
	}

	/**  Renvoie le photographe avec l'ID correspondant */
	public static function getPhotographeDepuisID($id) {
		if(!isset($id)){
			throw new InvalidArgumentException("you must supply an id to retrieve a photograph from an id.");
		}
		$daoPhotographe = new PhotographeDAO();
		return $daoPhotographe->getPhotographeDepuisID($id);
	}
	/**
	 * cree ce photographe en BD.
	 * En cas de succes retourne le photographe mis � jour,
	 * sinon retourne false ou jete une excpetion.
	 */
	public function create($activateID){
		$daoPhotographe = new PhotographeDAO();
		//creation des repertoires dans le DAO pour eventuelle rollback.
		return $daoPhotographe->create($this, $activateID);
	}

	/**
	 * met a jour l'utilisateur en BD
	 * et retourne une copy � jour
	 */
	public function save(){
		$dao = new PhotographeDAO();
		return $dao->save($this);
	}
	/*###########################################
	 * Getters & Setters
	 *###########################################*/
	public function getPhotographeID(){
		return $this->photographeID;
	}

	public function setPhotographeID($id){
		$this->photographeID = $id;
	}

	public function getSiren(){
		return $this->siren;
	}

	public function setSiren($siren){
		$this->siren = $siren;
	}

	public function getTelephone(){
		return $this->telephone;
	}

	public function setTelephone($tel){
		$this->telephone = $tel;
	}

	public function getSiteWeb(){
		return $this->siteweb;
	}

	public function setSiteWeb($site){
		$this->siteweb = $site;
	}

	public function getNomEntreprise(){
		return $this->nomEntreprise;
	}

	public function setNomEntreprise($nom){
		$this->nomEntreprise = $nom;
	}

	public function getHome(){
		return $this->home;
	}

	public function setHome($home){
		$this->home = $home;
	}

	public function getRIB_b(){
		return $this->rib_b;
	}

	public function setRIB_b($rib_b){
		$this->rib_b = $rib_b;
	}

	public function getRIB_g(){
		return $this->rib_g;
	}

	public function setRIB_g($rib_g){
		$this->rib_g = $rib_g;
	}

	public function getRIB_c(){
		return $this->rib_c;
	}

	public function setRIB_c($rib_c){
		$this->rib_c = $rib_c;
	}

	public function getRIB_k(){
		return $this->rib_k;
	}

	public function setRIB_k($rib_k){
		$this->rib_k = $rib_k;
	}

	public function setIBAN($iban){
		$this->iban = $iban;
	}

	public function getIBAN(){
		return $this->iban;
	}

	public function setBIC($bic){
		$this->bic = $bic;
	}

	public function getBIC(){
		return $this->bic;
	}

	public function getPourcentage(){
		return $this->pourcentage;
	}

	public function setPourcentage($p){
		$this->pourcentage = $p;
	}

	public function getNote(){
		return $this->note;
	}

	public function setNote($note){
		$this->note = $note;
	}

	public function getNombreVotant(){
		return $this->nombreVotant;
	}

	public function setNombreVotant($nv){
		$this->nombreVotant = $nv;
	}

	public function voter($note){
		if($note < 0 || $note > 10){
			return false;
		}
		$dao = new PhotographeDAO();
		return $dao->voter($this, $note);
	}
}
?>
