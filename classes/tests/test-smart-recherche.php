<?php
include_once "../modele/Evenement.class.php";
include_once "../modele/Album.class.php";
$assocs = Evenement::chercheEvenement("mariage", true);
if($assocs){
foreach($assocs as $assoc){
	$evt = $assoc["Evenement"];
	$user = $assoc["Utilisateur"];
	echo "evt: " . $evt->getEvenementID() . " - user: " . $user->getUtilisateurID() . "<br/>";
}
}else{
	echo "rien trouve<br/>";
}

$assocs = Album::smartRechercheAlbumEtImageStringIDEtPhotographeEtEvenement(NULL, "2010-06-28", "2010-08-01");
if($assocs){
foreach($assocs as $assoc){
	$evt = $assoc["Evenement"];
	$album = $assoc["Album"];
	$th = $assoc["Thumb"];
	$ph = $assoc["Photographe"];
	echo "evt: " . $evt->getEvenementID() . " - album: " . $album->getAlbumID() . " - thumb: " . $th . " - photographe: " . $ph->getPhotographeID() . "<br/>";
}
}else{
	echo "rien trouve<br/>";
}

$assocs = Album::getAlbumEtImageEtStringIDEtPhotographeEtEvenementDepuisID_Evenement(3);
foreach($assocs as $assoc){
	foreach($assoc as $id => $entry){
		echo $id . " = " . get_class($entry) . "<br/>";
	}
}
?>