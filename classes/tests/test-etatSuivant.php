<?php
include_once "../modele/Album.class.php";
include_once "../modele/Commande.class.php";

$album = Commande::getCommandeDepuisID(3);
echo $album->etatSuivant() . '<br/>';
echo $album->getEtat();

?>