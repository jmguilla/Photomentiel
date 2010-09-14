<?php
$dir_image_class_php = dirname(__FILE__);
include_once $dir_image_class_php . "/ModeleUtils.class.php";
include_once $dir_image_class_php . "/StringID.class.php";

class Image{
	private $album;
	private $photographe;
	private $path;

	public function __construct($path, $p, $a){
		$this->album = $a;
		$this->photographe = $p;
		$this->path = $path;
	}

	/**
	 * Renvoie seulement le path de n images aléatoires, pour
	 * éviter de multiples appels à la BD avec le stringID de l'album associé.
	 * Le resultat est en fait un tableau de tableau associatif. Chaque entrée du tableau
	 * est une association avec 2 entrées: "StringID", "Thumb"
	 * "StringID" est un objet et "Thumb" est le path d'une miniature de l'album.
	 * le parametre $paysage permet de specifier si la fonction ne doit retourner que des photos paysages...
	 */
	public static function getRandomImageThumbPathEtStringID($isPublique = true, $n = 1, $paysage = true, $etatAlbum = NULL){
		$stringIDs = StringID::getStringIDAleatoire($isPublique, $n, $etatAlbum);
		if($stringIDs){
			return self::getRandomImageThumbPathDepuisStringID($stringIDs, $n, $paysage);
		}else{
			return array();
		}
	}

	/**
	 * Renvoie seulement le path de n images prises dans les albums identifiés par $stringIDs, pour
	 * éviter de multiples appels à la BD avec le stringID de l'album associé.
	 * Le resultat est en fait un table de tableau associatif. Chaque entrée du tableau
	 * est une association avec 2 entrées: "StringID", "Thumb"
	 * "StringID" est un objet et "Thumb" est le path d'une miniature de l'album 
	 */
	public static function getRandomImageThumbPathDepuisStringID($stringIDs, $n = 1, $paysage = true){
		$circuitBroker = 5;
		$result = array();
		while(count($result) < $n && $circuitBroker > 0){
			foreach($stringIDs as $stringID){
				if(count($result) >= $n){
					break;
				}
				if(isset($stringID)){
					$dir_nom = PHOTOGRAPHE_ROOT_DIRECTORY . $stringID->getHomePhotographe() . "/" . $stringID->getStringID() . "/" . THUMB_DIRECTORY;
					$http_path = APPLICATION_ROOT_DIRECTORY . PICTURE_ROOT_DIRECTORY . $stringID->getHomePhotographe() . "/" . $stringID->getStringID() . "/" . THUMB_DIRECTORY;
					$files = ModeleUtils::getFileFromDirectory($dir_nom);
					if($files){
							$assoc = array();
							$assoc["StringID"] = $stringID;
							$thumb = NULL;
						if(!$paysage){
							$int = rand(0, (count($files) - 1));
							$thumb = $http_path . $files[$int];
						}else{
							$int = rand(0, 20);//on va recuperer un paysage aléatoire parmis les 5 premiers
							foreach($files as $file){
								if(!(substr_compare($file, "index.html", -strlen("index.html"), strlen("index.html")) === 0)){
									$infos = getimagesize($dir_nom . $file);
									if($infos && $infos[0] > $infos[1]){
										$int--;
										if($int <= 0){
											$thumb = $http_path . $file;
											break;
										}
									}
								}
							}
							if(!isset($thumb)){//on a pas reussi a recuperer un paysage... on prend au hasard.
								$thumb = $http_path . $files[rand(0, (count($files) - 1))];
							}
						}
						$assoc["Thumb"] = $thumb;
						$result[] = $assoc;
					}
				}
			}
			$circuitBroker--;
		}
		return $result;
	}
	/*#####################################
	 * Getters & Setters
	 #####################################*/
	public function getPath(){
		return $this->path;
	}

	public function getAlbum(){
		return $this->album;
	}

	public function getPhotographe(){
		return $this->photographe;
	}
}
?>