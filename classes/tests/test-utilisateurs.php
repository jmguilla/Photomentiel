<?php
$dir_test_utilisateurs_php = dirname(__FILE__);
include_once $dir_test_utilisateurs_php . "/../modele/Utilisateur.class.php";
include_once $dir_test_utilisateurs_php . "/../modele/Adresse.class.php";
$photographe = Utilisateur::getUtilisateurDepuisID(1);
$adresse = new Adresse();
$adresse->setCodePostal("83460");
$adresse->setNomRue("6 rue du murier");
$adresse->setComplement("");
$adresse->setVille("Les Arcs");
$adresse = $adresse->create();
$photographe->setID_Adresse($adresse->getAdresseID());
$photographe->save();
?>