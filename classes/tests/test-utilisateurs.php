<?php
$dir_test_utilisateurs_php = dirname(__FILE__);
include_once $dir_test_utilisateurs_php . "/../modele/Utilisateur.class.php";
include_once $dir_test_utilisateurs_php . "/../modele/Adresse.class.php";
$photographe = Utilisateur::getUtilisateurDepuisID(7);
$note = rand(0, 10);
echo "nouvelle note: " . $note . "<br/>";
echo "note courrant: " .$photographe->getNote() . " - pour " . $photographe->getNombreVotant() . " votants<br/>";
$photographe->voter($note);
echo "note après vote: " . $photographe->getNote() . " - pour " . $photographe->getNombreVotant() . " votants<br/>";
?>