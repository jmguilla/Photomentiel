<?php
$dir_album_class_php = dirname(__FILE__);
include_once $dir_album_class_php . "/modele_dao/AlbumDAO.class.php";
include_once $dir_album_class_php . "/../Config.php";

class Album {
	private $albumID;
	private $filigramme = 'www.photomentiel.fr';
	private $isPublique;
	private $nom;
	private $id_photographe;
	private $id_evenement;
	private $date;
	private $etat;
	private $module;
	private $balance = 0;
	private $mailing = '';
	private $prixTaillePapier = NULL;
	private $gainTotal = 0;
	private $transfert = true;

	public function __construct($aid = -1, $h = NULL, $idp = -1, $ide = NULL, $d = NULL, $isPublique = false, $etat = NULL, $module = NULL){
		$this->albumID = $aid;
		$this->nom = $h;
		$this->id_photographe = $idp;
		$this->id_evenement = $ide;
		$this->date = $d;
		$this->isPublique = $isPublique;
		if(!isset($etat)){
			$this->etat = 0;
		}else{
			$this->etat = $etat;
		}
		$this->module = $module;
	}

	/**
	 * Renvoie les n derniers albums
	 * si n n'est pas fournies, renvoies tous les derniers albums
	 * @param int $n
	 */
	public static function getNDerniersAlbums($n, $isPublique = true, $etat = NULL){
		$daoAlbum = new AlbumDAO();
		return $daoAlbum->getNDerniersAlbums($n, $isPublique, $etat);
	}
	/**
	 * Retourne un tableau d'associations avec pour chaque entrées:
	 * $result[$i]["Album"], $result[$i]["StringID"], $result[$i]["Thumb"], $result[$i]["Photographe"] et $result[$i]["Evenement"]
	 * @param unknown_type $query
	 * @param unknown_type $isPublique
	 */
	public static function chercheAlbumEtImageEtStringIDEtPhotographeEtEvenement($query, $isPublique = true, $etat = NULL){
		$dir_album_class_php = dirname(__FILE__);
		include_once $dir_album_class_php . "/Image.class.php";
		$daoAlbum = new AlbumDAO();
		$tmp =  $daoAlbum->chercheAlbumEtStringIDEtPhotographeEtEvenement($query, $isPublique, $etat);
		if($tmp){
			foreach($tmp as &$assoc){
				$thumb = Image::getRandomImageThumbPathDepuisStringID(array($assoc["StringID"]));
				$assoc["Thumb"] = @$thumb[0]["Thumb"];
			}
		}
		return $tmp;
	}
	/**
	 * Retourne le tableaux des albums pour l'evenement donné
	 */
	public static function getAlbumEtImageEtStringIDEtPhotographeEtEvenementDepuisID_Evenement($id, $isPublique = true, $etat = 2){
		if(!isset($id)){
			throw new InvalidArgumentException("Fournir l'id de l'évenement");
		}
		$dir_album_class_php = dirname(__FILE__);
		include_once $dir_album_class_php . "/Image.class.php";
		$daoAlbum = new AlbumDAO();
		$tmp =  $daoAlbum->getAlbumEtStringIDEtPhotographeEtEvenementDepuisID_Evenement($id, $isPublique, $etat);
		if($tmp){
			foreach($tmp as &$assoc){
				$thumb = Image::getRandomImageThumbPathDepuisStringID(array($assoc["StringID"]));
				$assoc["Thumb"] = @$thumb[0]["Thumb"];
			}
		}
		return $tmp;
	}
	/**
	 * Retourne un tableau d'associations avec pour chaque entrées:
	 * $result[$i]["Album"], $result[$i]["StringID"], $result[$i]["Thumb"], $result[$i]["Photographe"] et $result[$i]["Evenement"]
	 * @param unknown_type $query
	 * @param unknown_type $d1
	 * @param unknown_type $d2
	 * @param unknown_type $isPublique
	 */
	public static function smartRechercheAlbumEtImageStringIDEtPhotographeEtEvenement($search = NULL, $d1 = NULL, $d2 = NULL, $isPublique = true, $etat = NULL, $n =NULL){
		$dir_album_class_php = dirname(__FILE__);
		include_once $dir_album_class_php . "/Image.class.php";
		$daoAlbum = new AlbumDAO();
		$tmp =  $daoAlbum->smartRechercheAlbumEtStringIDEtPhotographeEtEvenement($search, $d1, $d2, $isPublique, $etat, $n);
		if($tmp){
			foreach($tmp as &$assoc){
				$thumb = Image::getRandomImageThumbPathDepuisStringID(array($assoc["StringID"]));
				$assoc["Thumb"] = @$thumb[0]["Thumb"];
			}
		}
		return $tmp;
	}
	/**
	 * Renvoie un tableau d'associations. Chaque entrée du tableau est un table associatif
	 * contenant les entrées suivantes: StringID, Album & Thumb.
	 * StringID et Album sont des objets, Thumb est le path d'une vignette de l'album au hasard
	 * @param $n
	 */
	public static function getNDerniersAlbumsEtImageEtStringID($n, $isPublique = true, $etat = NULL){
		$dir_album_class_php = dirname(__FILE__);
		include_once $dir_album_class_php . "/Image.class.php";
		$daoAlbum = new AlbumDAO();
		$tmp = $daoAlbum->getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates($n, NULL, NULL, $isPublique, $etat);
		//$tmp[i]["Album"] == Album & $tmp[i]["StringID"] == StringID
		if($tmp){
			foreach($tmp as &$assoc){
				$thumb = Image::getRandomImageThumbPathDepuisStringID(array($assoc["StringID"]));
				$assoc["Thumb"] = @$thumb[0]["Thumb"];
			}
		}
		return $tmp;
	}
	/**
	 * Renvoie un tableau de $n association ["Thumb"], ["StringID"] et ["Album"]
	 * où les albums sont compris entre les dates d1 & d2 et publique ou pas
	 * @param int $n
	 * @param STRING $d1
	 * @param STRING $d2
	 * @param bool $isPublique
	 */
	public static function getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates($n, $d1, $d2, $isPublique = true, $etat = NULL){
		$dir_album_class_php = dirname(__FILE__);
		include_once $dir_album_class_php . "/Image.class.php";
		$daoAlbum = new AlbumDAO();
		$tmp = $daoAlbum->getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates($n, $d1, $d2, $isPublique, $etat);
		if($tmp){
			foreach($tmp as &$assoc){
				$thumb = Image::getRandomImageThumbPathDepuisStringID(array($assoc["StringID"]));
				if($thumb){
					$assoc["Thumb"] = @$thumb[0]["Thumb"];
				}else{
					$assoc["Thumb"] = '';
				}
			}
		}
		return $tmp;
	}
	/**
	 * Renvoie un tableau d'association d'album (publique) et de stringID dont le photographe
	 * passé en parametre est l'auteur
	 * result[i]["Album"] = Album
	 * result[i]["StringID"] = StringID
	 * @param INT $photographe
	 */
	public static function getAlbumEtImageEtStringIDDepuisID_Photographe($id_photographe, $isPublique = true){
		$dir_album_class_php = dirname(__FILE__);
		include_once $dir_album_class_php . "/Image.class.php";
		$dao = new AlbumDAO();
		$tmp = $dao->getAlbumEtImageEtStringIDDepuisID_Photographe($id_photographe, $isPublique);
		if($tmp){
			foreach($tmp as &$assoc){
				$thumb = Image::getRandomImageThumbPathDepuisStringID(array($assoc["StringID"]));
				$assoc["Thumb"] = $thumb[0]["Thumb"];
			}
		}
		return $tmp;
	}
	/**
	 * Renvoie tous les albums
	 * $isPublique pour les albums publique seulement
	 */
	public static function getAlbums($isPublique = true){
		$daoAlbum = new AlbumDAO();
		return $daoAlbum->getAlbums($isPublique);
	}
	/**
	 * cree cet album en bd.
	 * gere en plus la creation du string id associ� et la creation
	 * du repertoire associ�.
	 */
	public function create(){
		$daoAlbum = new AlbumDAO();
		//crée également le repertoire dans le DAO pour economiser
		//des appels à la BD
		return $daoAlbum->create($this);
	}
	/**
	 * Renvoie l'album associé à l'id donné
	 * @param int $id
	 * @throws InvalidArgumentException
	 */
	public static function getAlbumDepuisID($id){
		if(!isset($id)){
			throw new InvalidArgumentException("you must supply an id to retrieve an album from an id.");
		}
		$daoAlbum = new AlbumDAO();
		return $daoAlbum->getAlbumDepuisID($id);
	}
	/**
	 * Renvoie le tableau des album du photographe identifié par son id
	 */
	public static function getAlbumDepuisID_Photographe($id, $isPublique = true){
		if(!isset($id)){
			throw new InvalidArgumentException("L'id du photographe est requis");
		}
		$daoAlbum = new AlbumDAO();
		return $daoAlbum->getAlbumDepuisID_Photographe($id, $isPublique);
	}
	/**
	 * retire cet album de la bd, ainsi que le string id associ�
	 * (car contrainte on delete cascade) et le repertoire
	 */
	public function delete(){
		$daoAlbum = new AlbumDAO();
		return $daoAlbum->delete($this);
	}
	/**
	 * sauve les modifications apport�es � cet album
	 * en BD et le retourne.
	 * Retourne false si un probl�me survient.
	 */
	public function save(){
		$dao = new AlbumDAO();
		return $dao->save($this);
	}
	/**
	 * Change l'etat d'une liste d'album à 2 et sauve le changement en BD.
	 * @param unknown_type $listeAlbum
	 */
	public static function validerListeAlbum($listeAlbum){
		$dao = new AlbumDAO();
		return $dao->validerListeAlbum($listeAlbum);
	}
	/**
	 * Change l'état d'une liste d'album à 2 et sauve le changement en BD.
	 * @param unknown_type $listeAlbum
	 */
	public static function activerListeAlbum($listeAlbum){
		$dao = new AlbumDAO();
		return $dao->activerListeAlbum($listeAlbum);
	}

	/*#########################################
	 * Getters & Setters
	 #########################################*/
	public function getAlbumID(){
		return $this->albumID;
	}

	public function setAlbumID($id){
		$this->albumID = $id;
	}

	public function getNom(){
		return $this->nom;
	}

	public function setNom($nom){
		$this->nom = $nom;
	}

	public function getID_Photographe(){
		return $this->id_photographe;
	}

	public function setID_Photographe($idp){
		$this->id_photographe = $idp;
	}

	public function getID_Evenement(){
		return $this->id_evenement;
	}

	public function setID_Evenement($ide){
		$this->id_evenement = $ide;
	}

	public function getDate(){
		return $this->date;
	}

	public function setDate($d){
		$this->date = $d;
	}

	public function isPublique(){
		return $this->isPublique;
	}

	public function setIsPublique($p){
		$this->isPublique = $p;
	}

	public function setEtat($etat){
		$this->etat = $etat;
	}

	public function getEtat(){
		return $this->etat;
	}

	public function etatSuivant(){
		global $ALBUM_STATES;
		if(!isset($this->etat)){
			$this->etat = 0;
		}else{
			$this->etat++;
			if($this->etat >= count($ALBUM_STATES)){
				$this->etat = count($ALBUM_STATES) - 1;
			}
		}
		$dao = new AlbumDAO();
		return $dao->saveEtat($this);
	}

	public function getModule(){
		return $this->module;
	}

	public function cloturer(){
		$dao = new AlbumDAO();
		return $dao->cloturer($this);
	}

	public function validerUpload(){
		$dao = new AlbumDAO();
		return $dao->validerUpload($this);
	}

	public function setModule($mod){
		$this->module = $mod;
	}

	public function getBalance(){
		return $this->balance;
	}

	public function setBalance($b){
		$this->balance = $b;
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
		$dao = new AlbumDAO();
		return $dao->saveMailing($this);
	}

	public function getGainTotal(){
		return $this->gainTotal;
	}

	public function setGainTotal($gt){
		$this->gainTotal = $gt;
	}

	public function getFiligramme(){
		return $this->filigramme;
	}

	public function setFiligramme($fili){
		$this->filigramme = $fili;
	}
	/**
	 * Remet la balance à 0 et sauve en BD
	 */
	public function resetBalance(){
		$dao = new AlbumDAO();
		if($dao->resetBalance($this)){
			$this->balance = 0;
			return true;
		}
		return false;
	}
	/**
	 * Update balance et gaintotal avec le parametre et sauve en BD
	 */
	public function updateAmounts($amount){
		$dir_album_class_php = dirname(__FILE__);
		include_once $dir_album_class_php . "/Utilisateur.class.php";
		include_once $dir_album_class_php . "/Photographe.class.php";
		$percentApplied = Photographe::getPhotographeDepuisID($this->getID_Photographe())->getPourcentage();
		$amount = $amount*$percentApplied/100;
		$this->balance += $amount;
		$this->gainTotal += $amount;
		$dao = new AlbumDAO();
		return $dao->saveAmounts($this);
	}
	/**
	 * Cette méthode est uniquement utilisée en pour une éventuelle
	 * création
	 * @param PrixTaillePapierAlbum $p
	 */
	public function addPrixTaillePapier($p){
		if(!isset($this->prixTaillePapier) || !is_array($this->prixTaillePapier)){
			$this->prixTaillePapier = array();
		}
		$this->prixTaillePapier[] = $p;
	}
	/**
	 * Seulement appelé depuis le DAO
	 */
	function internalGetPrixTaillePapier(){
		return $this->prixTaillePapier;
	}

	public function envoyerMailing(){
		$dir_album_class_php = dirname(__FILE__);
		include_once $dir_album_class_php . "/ModeleUtils.class.php";
		include_once $dir_album_class_php . "/StringID.class.php";
		$mailing = $this->getMailing();
		$mailing = str_replace("\n", "", $mailing);
		$sid = StringID::getStringIDDepuisID_Album($this->albumID);
		if($sid){
			if(ModeleUtils::sendAlbumDisponible($this, $sid, $mailing)){
				return true;
			}
		}
		return false;
	}

	public function getTransfert(){
		return $this->transfert;
	}

	public function setTransfert($t){
		$this->transfert = $t;
	}

	public function commencerTransfert(){
		$dao = new AlbumDAO();
		return $dao->commencerTransfert($this);		
	}

	public function finirTransfert(){
		$dao = new AlbumDAO();
		return $dao->finirTransfert($this);
	}
}
?>