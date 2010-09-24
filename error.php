<?php
/*
 * error.php is the file that will be in charge to display errors
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 22 sept. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */

include_once("classes/PMError.class.php");

$title = "Erreur inconnue";
$message = "Photomentiel a rencontré une erreur de type inconnue.<br/>Ce problème sera corrigé dans les plus brefs délais.";
if (isset($_GET['err'])){
	$title = "Erreur ".$_GET['err'];
	if ($_GET['err'] == '400'){
		$message = "La syntaxe de la requête est erronée";
	} else if ($_GET['err'] == '401'){
		$message = "Une authentification est nécessaire pour accéder à cette ressource";
	} else if ($_GET['err'] == '403'){
		$message = "L'authentification a été refusée";
	} else if ($_GET['err'] == '404'){
		$message = "La ressource demandée n'existe pas :<br/>".$_SERVER['REQUEST_URI'];
	} else if ($_GET['err'] == '500'){
		$message = "Une erreur interne du serveur est survenue";
	} else {
		$message = "Photomentiel a rencontré une erreur de type ".$_GET['err'].".<br/>Ce problème sera corrigé dans les plus brefs délais.";
	}
}
photomentiel_die(new PMError($title,$message));
?>
