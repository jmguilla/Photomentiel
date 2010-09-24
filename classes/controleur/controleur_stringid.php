<?php
$dir_controleur_stringid_php = dirname(__FILE__);
include_once $dir_controleur_stringid_php . "/ControleurUtils.class.php";
include_once $dir_controleur_stringid_php . "/../modele/StringID.class.php";
include_once $dir_controleur_stringid_php . "/../modele/Album.class.php";
include_once $dir_controleur_stringid_php . "/externalization.php";

$action = $_GET['action']; 
switch($action){
	case g_sid://OK true/false
		$sidstring = $_GET['sid'];
		$sid = StringID::getStringIDDepuisID($sidstring);
		if($sid){
			$album = Album::getAlbumDepuisID($sid->getID_Album());
			if($album){
				ControleurUtils::serialize_object_json(array(true,  $album->isPublique()));
			}else{
				ControleurUtils::serialize_object_json(array(false, false));
			}
		}else{
			ControleurUtils::serialize_object_json(array(false, false));
		}
	break;
	case g_sid_p_ida:
		$ida = $_GET['ida'];
		$sid = StringID::getStringIDDepuisID_Album($ida);
		ControleurUtils::serialize_object_json($sid);
	break;
	default:
		echo "unknown action: " . $action; 
	break;
}
?>