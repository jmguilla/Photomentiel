<?php
$dir_controleur_images_php = dirname(__FILE__);
include_once $dir_controleur_images_php . "/ControleurUtils.class.php";
include_once $dir_controleur_images_php . "/../modele/Image.class.php";
include_once $dir_controleur_images_php . "/externalization.php";

$action = $_GET['action'];
switch($action){
	case gr_image_thumb_path:
	$n = $_GET['n'];
	if(!isset($n)){
		$n = 1;
	}
	$tmp = Image::getRandomImageThumbPathEtStringID(true,$n,true,2);
	if($tmp){
		$array = array();
		foreach($tmp as $assoc){
			$stringID = $assoc["StringID"];
			$array[] = array("Thumb" => $assoc["Thumb"], "StringID" => $stringID->getStringID());
		}
		ControleurUtils::serialize_assoc_array_json($array);
	}else{
		ControleurUtils::serialize_object_json($tmp);
	}
	break;
	default:
		echo 'unknown action in controleur image: ' . $action;
	break;
}

?>