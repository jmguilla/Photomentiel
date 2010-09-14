<?php
$dir_adressecommande_class_php = dirname(__FILE__);
include_once $dir_adressecommande_class_php . "/modele_dao/AdresseCommandeDAO.class.php";

class AdresseCommande{
	private $adresseID;
	private $nom;
	private $prenom;
	private $nomRue;
	private $ville;
	private $cp;
	private $complement;
	private $id_commande = -1;

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
	 * @param unknown_type $id
	 */
	public static function getAdresseCommandeDepuisID($id){
		$daoAdresse = new AdresseCommandeDAO();
		return $daoAdresse->getAdresseCommandeFromID($id);
	}
	/**
	 * cree cette adresse en base de donnée et retourne
	 * une reférence à jour sur la nouvelle adresse.
	 */
	public function create(){
		$dao = new AdresseCommandeDAO();
		return $dao->create($this);
	}
	/**
	 * sauvegarde les modifications apportées en BD.
	 * retourne l'objet update en cas de succes, false sinon.
	 */
	public function save(){
		$dao = new AdresseCommandeDAO();
		return $dao->save($this);
	}
	public function delete(){}

	/*###########################################
	 * Getters & Setters
	 ###########################################*/
	public function getAdresseCommandeID(){
		return $this->adresseID;
	}

	public function setAdresseCommandeID($id){
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

	public function getID_Commande(){
		return $this->id_commande;
	}

	public function setID_Commande($id){
		$this->id_commande = $id;
	}
}
?>