<?php
include_once "../modele/EvenementEcouteur.class.php";
include_once "../modele/Utilisateur.class.php";

$user = new Utilisateur();
$user->setUtilisateurID(2);
$evts = EvenementEcouteur::getEvenementsAVenirDepuisID_Utilisateur($user);
foreach($evts as $evt){
	echo $evt->getEvenementID() . "<br/>";
}
?>