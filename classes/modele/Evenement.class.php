<?php
$dir_evenement_class_php = dirname(__FILE__);
include_once $dir_evenement_class_php . "/modele_dao/EvenementDAO.class.php";

class Evenement {
	private $evenementID;
	private $type;
	private $date;
	private $description;
	private $adresse = '';
	private $region;
	private $departement;
	private $ville;
	private $id_utilisateur;
	private $mailing = '';
	private $web = '';

	public function __construct($id = -1, $type = 3, $date = NULL, $id_utilisateur = -1, $desc = '', $reg = NULL, $dep = NULL, $ville = NULL){
		$this->evenementID = $id;
		$this->type = $type;
		$this->date = $date;
		$this->id_utilisateur = $id_utilisateur;
		$this->description = $desc;
		$this->region = $reg;
		$this->departement = $dep;
		$this->ville = $ville;
	}
	/**
	 * Retourne l'evenement associé à l'id passé en parametre
	 * @param int $id
	 * @throws InvalidArgumentException
	 */
	public static function getEvenementDepuisID($id){
		if(!isset($id)){
			return false;
		}
		$daoEvenement = new EvenementDAO();
		return $daoEvenement->getEvenementDepuisID($id);
	}
	/**
	 * Renvoie un tableau d'evenement dont la description contient les mots contenus dans la string
	 * $query
	 * @param $query
	 */
	public static function chercheEvenement($query){
		$dao = new EvenementDAO();
		return $dao->chercheEvenement($query);
	}

	/**
	 * Renvoie le resultat de la recherche intelligente des evenements en fonction des parametre passé.
	 * Retourne en plus l'utilisateur ayant créé l'evenement: $result[i]["Evenement"] = $evt; $result[i]["Utilisateur"] = $user
	 * @param string $query
	 * @param date $d1
	 * @param date $d2
	 * @param int $id_region
	 * @param string $type
	 */
	public static function smartRechercheEvenementEtUtilisateur($query = NULL, $d1 = NULL, $d2 = NULL, $id_region = NULL, $type = NULL, $n = NULL){
		$dao = new EvenementDAO();
		return $dao->smartRechercheEvenementEtUtilisateur($query, $d1, $d2, $id_region, $type, $n);
	}
	/**
	 * Renvoie les n prochains evenements
	 * Si n est non précisé, renvoie tous les evenements à venir.
	 * @param int $n
	 */
	public static function getNProchainsEvenements($n, $isPublique = true){
		$daoEvenement = new EvenementDAO();
		return $daoEvenement->getNProchainsEvenements($n, $isPublique);
	}
	/**
	 * Renvoie les n prochains (premiers) evenements entre 2 dates.
	 * @param int $n
	 * @param date $d1
	 * @param date $d2
	 */
	public static function getNProchainsEvenementsEntreDates($n, $d1, $d2){
		$daoEvenement = new EvenementDAO();
		$result = $daoEvenement->getNProchainsEvenementsEntreDates($n, $d1, $d2); 
		return $result;
	}
	/**
	 * Renvoie tous les evenements entre 2 dates.
	 * Si une des 2 dates n'est pas précisée, retourne tous les prochains
	 * @param int $n
	 * @param date $d1
	 * @param date $d2
	 */
	public static function getEvenementsEntreDates($d1, $d2){
		if(!isset($d1) || !isset($d2)){
			return self::getNProchainsEvenements(NULL);
		}
		$daoEvenement = new EvenementDAO();
		$result = $daoEvenement->getEvenementsEntreDates($d1, $d2); 
		return $result;
	}
	/**
	 * Renvoie les n prochains evenements apres une date donnee
	 * Si la date n'est pas renseignee, retourne les n prochains evenements
	 * @param int $n
	 * @param date $d
	 */
	public static function getNProchainsEvenementsApresDate($n, $d){
		if(!isset($d)){
			return self::getNProchainsEvenements($n);
		}
		$daoEvenement = new EvenementDAO();
		$result = $daoEvenement->getNProchainsEvenementsApresDate($n, $d);
		return $result;
	}
	/**
	 * Renvoie tous les evenements d'une date donnee
	 * Si aucune date n'est fournie, renvoie les evenements du jour
	 * @param date $d
	 */
	public static function getEvenementsADate($d){
		if(!isset($d)){
			$d = date('Y-m-d');
		}
		$daoEvenement = new EvenementDAO();
		$result = $daoEvenement->getEvenementsADate($d);
		return $result;
	}
	/**
	 * Renvoie les n derniers evenements, si n n'est pas fournie,
	 * renvoie tous les evenements passés
	 * @param int $n
	 */
	public static function getNDerniersEvenements($n){
		$daoEvenement = new EvenementDAO();
		$result = $daoEvenement->getNDerniersEvenements($n);
		return $result;
	}

	public static function getEvenements(){
		$daoEvenement = new EvenementDAO();
		$result = $daoEvenement->getEvenements();
		return $result;
	}
	/**
	 * crée cet object en base de donnée. Si l'objet est effectivement
	 * créé, une nouvelle référence à jour est retournée. En cas d'echec, retourne false;
	 */
	public function create(){
		$dao = new EvenementDAO();
		return $dao->create($this);
	}
	/**
	 * Sauvegarde cet objet en bd;
	 */
	public function save(){
		$dao = new EvenementDAO();
		return $dao->save($this);
	}
	/**
	 * supprime cet objet de la BD
	 */
	public function delete(){
		$dao = new EvenementDAO();
		return $dao->delete($this);
	}

	/*#####################################
	 * Getters & Setters
	 #####################################*/
	public function getEvenementID(){
		return $this->evenementID;
	}

	public function setEvenementID($id){
		$this->evenementID = $id;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($d){
		$this->date = $d;
	}

	public function getID_Utilisateur(){
		return $this->id_utilisateur;
	}

	public function setID_Utilisateur($id){
		$this->id_utilisateur = $id;
	}

	public function getDescription(){
		return $this->description;
	}

	public function setDescription($des){
		$this->description = $des;
	}

	public function setRegion($reg){
		$this->region = $reg;
	}

	public function getRegion(){
		return $this->region;
	}

	public function setDepartement($dept){
		$this->departement = $dept;
	}

	public function getDepartement(){
		return $this->departement;
	}

	public function setVille($ville){
		$this->ville = $ville;
	}

	public function getVille(){
		return $this->ville;
	}
	public function getMailing(){
		return $this->mailing;
	}
	public function setMailing($m){
		$this->mailing = $m;
	}
	public function addMailAMailing($m){
		$mails = explode(";", $this->mailing);
		foreach($mails as $mail){
			if($mail == $m){
				return false;
			}
		}
		if(!isset($this->mailing) || $this->mailing == ''){
			$this->mailing = $m;
		}else{			
			$this->mailing .= ";" . $m;
		}
		$dao = new EvenementDAO();
		return $dao->saveMailing($this);
	}
	public function getWeb(){
		return $this->web;
	}
	public function setWeb($w){
		$this->web = $w;
	}
	public function getAdresse(){
		return $this->adresse;
	}
	public function setAdresse($adresse){
		$this->adresse = $adresse;
	}
	public function envoyerMailing(){
		$dir_evenement_class_php = dirname(__FILE__);
		include_once $dir_evenement_class_php . "/ModeleUtils.class.php";
		include_once $dir_evenement_class_php . "/EvenementEcouteur.class.php";
		include_once $dir_evenement_class_php . "/Utilisateur.class.php";
		$mailing = $this->getMailing();
		$mailing = str_replace("\n", "", $mailing);
		if(!strpos($mailing,"@")){
			return true;
		}
		if(ModeleUtils::sendEvenementAlbumDisponible($this, $mailing)){
			$ees = EvenementEcouteur::getEvenementEcouteurDepuisID_Evenement($this->getEvenementID());
			$count = count($ees);
			if($ees && $count > 0){
				$mailing = '';
				foreach($ees as $ee){
					$count--;
					$mailing .= Utilisateur::getUtilisateurDepuisID($ee->getID_Utilisateur())->getEmail();
					if($count > 0){
						$mailing .= ";";
					}
				}
				return ModeleUtils::sendEvenementAlbumDisponible($this, $mailing);
			}
			return true;
		}
		return false;
	}
}
?>