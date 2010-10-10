<?php
$dir_administration_controleur_album_php = dirname(__FILE__);
include_once $dir_administration_controleur_album_php . "/../../classes/modele/Error.class.php";

switch($action){
	case supprimer_error:
		$id = @$_POST['id'];
		if(!isset($id)){
			$_SESSION['message'] .= "Aucun id fournie, impossible de supprimer<br/>";
			break;
		}
		$error = Error::getErrorDepuisErrorID($id);
		if($error && $error->delete()){
			$_SESSION['message'] .= "Error #" . $error->getErrorID() . " supprimee avec succes.<br/>";
		}else{
			$_SESSION['message'] .= "Error #" . $error->getErrorID() . " n'a pas ete supprimee.<br/>";
		}
	break;
	default:
		$_SESSION['message'] .= "action inconnue dans controleur_error " . $action . "<br/>";
	break;
}
header('Location: error.php');
exit();
?>