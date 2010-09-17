<?php
$dir_administration_controleur_album_php = dirname(__FILE__);
include_once $dir_administration_controleur_album_php . "/../../classes/modele/Album.class.php";

switch($action){
	case valider_album:
		$albums = Album::getNDerniersAlbums(0, false, 1);
		$listeAlbum = array();
		foreach($albums as $album){
			if(isset($_POST['albumID' . $album->getAlbumID()])){
				$listeAlbum[] = $album;
			}
		}
		$result = Album::validerListeAlbum($listeAlbum);
		if($result){
			$_SESSION['message'] .= "Liste d'album validée\n";
		}else{
			$_SESSION['message'] .= "Un problème est survenue pendant la validation de la liste d'album\n";
		}
	break;
	default:
		echo "action inconnue dans controleur_album " . $action;
	break;
}
header('Location: album.php');
exit();
?>