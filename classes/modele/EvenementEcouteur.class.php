<?php
$dir_evenementecouteur_class_php = dirname(__FILE__);
include_once $dir_evenementecouteur_class_php . "/modele_dao/EvenementEcouteurDAO.class.php";

class EvenementEcouteur {
	private $id = -1;
	private $id_utilisateur = -1;
	private $id_evenement = -1;

	public function __construct($idu = -1, $ide = -1){
		$this->id_utilisateur = $idu;
		$this->id_evenement = $ide;
	}

	/**
	 * Retourne un tableau d'evenements à venir auquel s'est enregistré
	 * l'utilisateur passe en parametre
	 * @param Utilisateur $user
	 */
	public static function getEvenementsAVenirDepuisID_Utilisateur($id_user){
		if(!isset($id_user)){
			throw new InvalidArgumentException("Un utilisateur avec un ID correct doit etre passe en parametre");
		}
		$dao = new EvenementEcouteurDAO();
		return $dao->getEvenementsAVenirDepuisID_Utilisateur($id_user);
	}
	/**
	 * Retourne true si un evenementEcouteur avec cet id_utilisateur et cet id_evenement existe, false sinon
	 * @param int $id_utilisateur
	 * @param int $id_evenement
	 */
	public static function exists($id_evenement, $id_utilisateur){
		$dao = new EvenementEcouteurDAO();
		$ee = new EvenementEcouteur();
		$ee->setID_Utilisateur($id_utilisateur);
		$ee->setID_Evenement($id_evenement);
		return (false != $dao->getEvenementEcouteurDepuisID_UtilisateurEtID_Evenement($ee));
	}

	public function create(){
		$dao = new EvenementEcouteurDAO();
		return $dao->create($this);
	}

	public function save(){
		$dao = new EvenementEcouteurDAO();
		return $dao->save($this);
	}

	public function delete(){
		$dao = new EvenementEcouteurDAO();
		return $dao->delete($this);
	}
	/*#####################################
	 * Getters & Setters
	 #####################################*/

	public function getEvenementEcouteurID(){
		return $this->id;
	}
	public function setEvenementEcouteurID($id){
		$this->id = $id;
	}
	public function setID_Utilisateur($id){
		$this->id_utilisateur = $id;
	}
	public function getID_Utilisateur(){
		return $this->id_utilisateur;
	}
	public function setID_Evenement($id){
		$this->id_evenement = $id;
	}
	public function getID_Evenement(){
		return $this->id_evenement;
	}
}
