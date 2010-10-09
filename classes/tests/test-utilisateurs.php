<?php
$dir_test_utilisateurs_php = dirname(__FILE__);
include_once $dir_test_utilisateurs_php . "/../modele/Utilisateur.class.php";
include_once $dir_test_utilisateurs_php . "/../modele/Adresse.class.php";
$photographe = Utilisateur::getUtilisateurDepuisID(7);
echo "ftp " . $photographe->getOpenFTP() . "<br/>";
$photographe->decOpenFTP();
echo "ftp " . $photographe->getOpenFTP() . "<br/>";
?>