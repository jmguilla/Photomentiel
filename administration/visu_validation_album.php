<?php
@session_start();
$dir_visu_validation_album_php = dirname(__FILE__);
include_once $dir_visu_validation_album_php . '/../classes/Config.php';
include_once $dir_visu_validation_album_php . '/../classes/modele/StringID.class.php';
include_once $dir_visu_validation_album_php . '/../classes/modele/ModeleUtils.class.php';

$sid = $_GET['sid'];
$stringID = StringID::getStringIDDepuisID($sid);
$root =  PHOTOGRAPHE_ROOT_DIRECTORY . $stringID->getHomePhotographe() . "/" . $stringID->getStringID() . "/" . THUMB_DIRECTORY;
$rootHTML = PICTURE_ROOT_DIRECTORY . $stringID->getHomePhotographe() . "/" . $stringID->getStringID() . "/" . THUMB_DIRECTORY;
$files = ModeleUtils::getFileFromDirectory($root);
if(isset($_SESSION['message'])){
	echo $_SESSION['message'];
	unset($_SESSION['message']);
}
$_SESSION['message'] = '';
$increment = 0;
foreach($files as $file){
	echo '<a name="id'.$increment.'"><img src="http://www.photomentiel.fr/' . $rootHTML . $file . '"/></a><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="supprimer_photo"/><input type="hidden" name="id" value="' . $sid . '"/><input type="hidden" name="photo_id" value="' . $file . '"/><input type="hidden" name="anchor" value="id'.$increment.'"/><input type="submit" value="supprimer"/></form><br/>';
	$increment++;
}
?>