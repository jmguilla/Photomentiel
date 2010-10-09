<?php
include_once "../modele/Album.class.php";
include_once "../modele/Photographe.class.php";
$assocs = Album::getAlbumEtImageEtStringIDDepuisID_Photographe(1, true);
foreach($assocs as $assoc){
	$album = $assoc["Album"];
	$sid = $assoc["StringID"];
	echo $album->getAlbumID() . " - " . $album->getTransfert() . "<br/>";
}
foreach($assocs as $assoc){
	$album = $assoc["Album"];
	$album->commencerTransfert();
	$sid = $assoc["StringID"];
	echo $album->getAlbumID() . " - " . $album->getTransfert() . "<br/>";
}
foreach($assocs as $assoc){
	$album = $assoc["Album"];
	$album->finirTransfert();
	$sid = $assoc["StringID"];
	echo $album->getAlbumID() . " - " . (false == $album->getTransfert()) . "<br/>";
}
?>