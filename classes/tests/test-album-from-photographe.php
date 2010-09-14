<?php
include_once "../modele/Album.class.php";
include_once "../modele/Photographe.class.php";
$photo = new Photographe();
$photo->setPhotographeID(1);
$assocs = Album::getAlbumFromPhotographe($photo, true);
foreach($assocs as $assoc){
	$album = $assoc["Album"];
	$sid = $assoc["StringID"];
	echo $album->getAlbumID() . " - " . $sid->getStringID() . "<br/>";
}
?>