<?php
$dir_utilisateur_class_php = dirname(__FILE__);
include_once $dir_utilisateur_class_php . "/modele_dao/UtilisateurDAO.class.php";

class Utilisateur {

	protected $id;
	protected $email;
	protected $mdp;
	protected $actif;
	protected $dateInscription;
	protected $adresse = NULL;

	public function __construct($id = -1, $email = NULL, $mdp = NULL, $actif = false, $dateInscription = NULL){
		$this->id = $id;
		$this->email = $email;
		$this->dateInscription = $dateInscription;
		$this->actif = $actif;
		$this->mdp = $mdp;
	}

	/**
	 * Renvoi l'id utilisateur si trouvé, -1 sinon
	 * @param $email
	 * @param $MD5mdp
	 */
	public static function logon($email, $mdp){
		if(!isset($email) || !isset($mdp)){
			throw new InvalidArgumentException("email & pwd required to logon.");
		}		
		$daoUtilisateur = new UtilisateurDAO();
		$result = $daoUtilisateur->getUtilisateurFromEmailAndPassword($email, $mdp);
		return $result; 
	}
	/**
	 * Renvoie la liste des utilisateur avec une entrée dans la table Activate
	 * sous la forme d'une association $result[i]['Activate'] == $id; $result[i]['Utilisateur'] == $user
	 */
	public static function getNonActif(){
		$dao = new UtilisateurDAO();
		return $dao->getNonActif();
	}
	/**
	 * controle que l'email ne soit pas déjà utilisé.
	 * retourne false si l'email n'est pas valide ou qu'il est déjà utilisé
	 * retourne true si l'email n'est pas déjà atribué.
	 * @param unknown_type $email
	 */
	public static function controleEmail($email){
		if(!isset($email)){
			return false;
		}
		$dao = new UtilisateurDAO();
		return $dao->controleEmail($email);
	}
	/**
	 * Renvoi le nouvel utilisateur créé. Ne pas oublier de reafecter
	 * @param String $nom
	 * @param String $prenom
	 * @param String $email
	 * @param String $adresse
	 */
	public function create($activateID){
		$daoUtilisateur = new UtilisateurDAO();
		return $daoUtilisateur->create($this, $activateID);
	}
	/**
	 * met a jour l'utilisateur en BD.
	 * La valeur de retour est l'utilisateur updaté.
	 * retourne false si un problème survient.
	 */
	public function save(){
		$dao = new UtilisateurDAO();
		return $dao->save($this);
	}
	/**
	 * Met a jour le mdp en BD (l'utilisateur est identifie grace a son email,
	 * comme ca, il est possible de reutiliser la methode pour generer des pwd en cas de perte).
	 * Retourne l'utilisateur mis a jour en cas de succes, false sinon
	 * @param Utilisateur $utilisateur
	 * @param string $newMDP
	 */
	public function saveMDPEtEnvoyerEmail($newMDP){
		$dao = new UtilisateurDAO();
		return $dao->saveMDPEtEnvoyerEmail($this, $newMDP);
	}

	public static function getUtilisateurs(){
		$daoUtilisateur = new UtilisateurDAO();
		return $daoUtilisateur->getUtilisateurs();
	}

	public static function getUtilisateurDepuisID($id){
		if(!isset($id)){
			throw new InvalidArgumentException("getUtilisateurDepuisID requires an ID as parameter");
		}
		$daoUtilisateur = new UtilisateurDAO();
		return $daoUtilisateur->getUtilisateurDepuisID($id);		
	}

	public static function activerUtilisateur($id){
		if(!isset($id)){
			throw new InvalidArgumentException("activerUtilisateur requires an ID as parameter");
		}
		$daoUtilisateur = new UtilisateurDAO();
		return $daoUtilisateur->activer($id);
	}

	public function delete(){
		$daoUtilisateur = new UtilisateurDAO();
		return $daoUtilisateur->delete($this);
	}
	/*###########################################
	 * Getters & Setters
	 ###########################################*/
	public function isActif(){
		return $this->actif;
	}

	public function setActif($actif){
		$this->actif = $actif;
	}

	public function getUtilisateurID(){
		return $this->id;
	}

	public function setUtilisateurID($id){
		$this->id = $id;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;		
	}

	public function getDateInscription(){
		return $this->dateInscription;
	}

	public function setDateInscription($dateInscription){
		$this->dateInscription = $dateInscription;
	}

	public function setMDP($mdp){
		$this->mdp = $mdp;
	}

	public function getMDP(){
		return $this->mdp;
	}

	public function setAdresse($ad){
		$this->adresse = $ad;
	}

	public function getAdresse(){
		return $this->adresse;
	}
}
?>