<?php
/*
 * userconnection.php manage the session of a user.
 * After including this file, 3 variables can be available :
 * - $_SESSION['userID'] : if set, the user is connected and the content is its ID (database ID)
 * - $_SESSION['userClass'] : if set, the user is connected and the content is its type in ('Photographe','Utilisateur')
 * - $utilisateurObj : if set, contains the user/photograph informations as a php object in ('Photographe','Utilisateur')
 *
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 20 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
if (isset($_GET['action'])){
	if ($_GET['action']=='disc'){
		unset($_SESSION['userID']);
		unset($_SESSION['userClass']);
	}
}
$utilisateurObj = false;
if (isset($_POST['user_email']) && isset($_POST['user_pwd'])){
	$utilisateurObj = Utilisateur::logon($_POST['user_email'],$_POST['user_pwd']);
	if ($utilisateurObj){
		$_SESSION['userID'] = $utilisateurObj;
		$utilisateurObj = Utilisateur::getUtilisateurDepuisID($utilisateurObj);
		$_SESSION['userClass'] = get_class($utilisateurObj);
	}
} else if (isset($_SESSION['userID'])){
	$utilisateurObj = Utilisateur::getUtilisateurDepuisID($_SESSION['userID']);
}
?>

