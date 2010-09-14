<?php
$dir_test_visu_php = dirname(__FILE__);
include_once $dir_test_visu_php . "/../modele/StringID.class.php";
include_once $dir_test_visu_php . "/../modele/Image.class.php";
include_once $dir_test_visu_php . "/../modele/Album.class.php";
include_once $dir_test_visu_php . "/../modele/Evenement.class.php";
include_once $dir_test_visu_php . "/../modele/Utilisateur.class.php";
include_once $dir_test_visu_php . "/../modele/PrixTaillePapierAlbum.class.php";

$stringID = StringID::getStringIDDepuisID("a5f8t1v5");
$id_album = $stringID->getID_Album();
$album = Album::getAlbumDepuisID($id_album);
$id_photographe = $album->getID_Photographe();
$photographe = Utilisateur::getUtilisateurDepuisID($id_photographe);
$id_evenement = $album->getID_Evenement();
$evenement = Evenement::getEvenementDepuisID($id_evenement);
$prixs = PrixTaillePapierAlbum::getPrixTaillePapiersDepuisID_Album($id_album);
foreach($prixs as $prix){
	echo $prix->getPrix() . '<br/>';
}

$albumsPlus = Album::getNDerniersAlbumsEtImageEtStringID(3);
if($albumsPlus){
	foreach($albumsPlus as $tmp){
		echo '[';
		echo $tmp["StringID"]->getStringID();
		echo '<br/>';
		echo $tmp["Album"]->getAlbumID();
		echo '<br/>';
		echo $tmp["Thumb"];
		echo ']<br/><br/><br/>';
	}
}
?>