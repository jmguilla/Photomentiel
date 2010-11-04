<?php
$dir_cron_cleanalbum_php = dirname(__FILE__);
include_once $dir_cron_cleanalbum_php . '/../../classes/modele/Album.class.php';
include_once $dir_cron_cleanalbum_php . '/../../classes/modele/Commande.class.php';
include_once $dir_cron_cleanalbum_php . '/../../classes/Config.php';

$albums = Album::getAlbumDepuisEtat(count($ALBUM_STATES) - 1);
if($albums){
	foreach($albums as $album){
	echo "Traitement de l'album #" . $album->getAlbumID() . "<br/>";
		$commandes = Commande::getCommandeDepuisID_Album($album->getAlbumID());
		$canDelete = true;
		if($commandes){
			foreach($commandes as $commande){
				$canDelete = $canDelete && ($commande->getEtat()!=1 && $commande->getEtat()!=2 && $commande->getEtat()!=3);
			}
		}
		if($canDelete){
			if($album->getBalance()>0){
				echo "Balance non nulle pour l'album #" . $album->getAlbumID() . ", suppression interrompue<br/>";
				continue;
			}
			if($album->delete()){
				echo "Album #" . $album->getAlbumID() . " supprime avec succes<br/>";
			}else{
				echo "Impossible de supprimer l'album #" . $album->getAlbumID() . "<br/>";
			}
		}else{
			echo "Commandes en attente pour l'album cloture #" . $album->getAlbumID() . "<br/>";
		}
	}
} else {
	echo "Aucun album n'est en etat de suppression";
}
?>
