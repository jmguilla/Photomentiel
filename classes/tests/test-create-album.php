<?php
include_once '../modele/Album.class.php';
include_once '../modele/PrixTaillePapierAlbum.class.php';

$album = new Album();
for($i = 1; $i < 5; $i++){
//	if($i == 3){
//		$album->addPrixTaillePapier(new PrixTaillePapierAlbum($id = -1, $p = 10.99, $idt = 10));
//	}else{
		$album->addPrixTaillePapier(new PrixTaillePapierAlbum($id = -1, $p = 10.99, $idt = $i));
//	}
}
$album->setNom("un nom pas au hazard");
$album->setID_Photographe(rand(1, 4));
$album->setIsPublique(true);
$album->setFiligramme('testfiligramme');
$album = $album->create();
if(!$album){
	echo "l'album il a pas pu etre cree!!";
}else{
	echo "l'album il a bien ete cree!!";
}
//$album->setAlbumID(16);
//$album->setID_Photographe(2);
//$result = $album->delete();
//if($result == true){
//	echo "l'album il a bien ete delete";
//}else{
//	echo "l'album il a pas ete delete";
//}
//$album->setAlbumID(17);
//$album->setID_Photographe(4);
//$result = $album->delete();
//if($result == true){
//	echo "l'album il a bien ete delete";
//}else{
//	echo "l'album il a pas ete delete";
//}
//$album->setAlbumID(18);
//$album->setID_Photographe(4);
//$result = $album->delete();
//if($result == true){
//	echo "l'album il a bien ete delete";
//}else{
//	echo "l'album il a pas ete delete";
//}
?>