<?php
include_once "../modele/Commande.class.php";

$commande = Commande::getCommandeDepuisID(1);
$commande->delete();

$commande = Commande::getCommandeDepuisID(1);
echo "exist?: " . (false == $commande);
?>