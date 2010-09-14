<?php
$dir_adresse_class_php = dirname(__FILE__);
include_once $dir_adresse_class_php . "/modele_dao/AdresseDAO.class.php";

class Adresse{
	private $adresseID;
	private $nom;
	private $prenom;
	private $nomRue;
	private $ville;
	private $cp;
	private $complement;
	private $id_utilisateur = -1;

	public function __construct($id = -1, $nom = '', $prenom = '', $nr = NULL, $c = NULL, $cp = NULL, $v = NULL){
		$this->adresseID = $id;
		$this->nom = $nom;
		$this->prenom = $prenom;
		$this->nomRue = $nr;
		$this->complement = $c;
		$this->cp = $cp;
		$this->ville = $v;
	}
	/**
	 * pour retrouver un adresse depuis un ID
	 * @param INT $id
	 */
	public static function getAdresseDepuisID($id){
		$daoAdresse = new AdresseDAO();
		return $daoAdresse->getAdresseFromID($id);
	}
	/**
	 * cree cette adresse en base de donn�e et retourne
	 * une reférence à jour sur la nouvelle adresse.
	 */
	public function create(){
		$dao = new AdresseDAO();
		return $dao->create($this);
	}
	/**
	 * sauvegarde les modifications apportées en BD.
	 * retourne l'objet update en cas de succes, false sinon.
	 */
	public function save(){
		$dao = new AdresseDAO();
		return $dao->save($this);
	}
	public function delete(){}

	/*###########################################
	 * Getters & Setters
	 ###########################################*/
	public function getAdresseID(){
		return $this->adresseID;
	}

	public function setAdresseID($id){
		$this->adresseID = $id;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function getPrenom(){
		return $this->prenom;
	}

	public function setPrenom($prenom){
		$this->prenom = $prenom;
	}

	public function getVille(){
		return $this->ville;
	}

	public function setVille($ville){
		$this->ville = $ville;
	}

	public function getCodePostal(){
		return $this->cp;
	}

	public function setCodePostal($c){
		$this->cp = $c;
	}

	public function getNomRue(){
		return $this->nomRue;		
	}

	public function setNomRue($nom){
		$this->nomRue = $nom;
	}

	public function getComplement(){
		return $this->complement;
	}

	public function setComplement($c){
		$this->complement = $c;
	}

	public function getID_Utilisateur(){
		return $this->id_utilisateur;
	}

	public function setID_Utilisateur($id){
		$this->id_utilisateur = $id;
	}
}
?>