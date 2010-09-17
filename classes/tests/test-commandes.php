<?php
$dir_test_commandes_php = dirname(__FILE__);
include_once $dir_test_commandes_php . "/../modele/Utilisateur.class.php";
include_once $dir_test_commandes_php . "/../modele/Photographe.class.php";
include_once $dir_test_commandes_php . "/../modele/Commande.class.php";
include_once $dir_test_commandes_php . "/../modele/CommandePhoto.class.php";
include_once $dir_test_commandes_php . "/../modele/TypePapier.class.php";
include_once $dir_test_commandes_php . "/../modele/TaillePapier.class.php";
include_once $dir_test_commandes_php . "/../modele/PrixTaillePapierAlbum.class.php";
include_once $dir_test_commandes_php . "/../modele/Couleur.class.php";
include_once $dir_test_commandes_php . "/../modele/Album.class.php";
include_once $dir_test_commandes_php . "/../modele/AdresseCommande.class.php";
//on recup_re utilisateur & photographe
$users = Utilisateur::getUtilisateurs();
$user = $users[rand(0, (count($users) - 1))];
$photographes = Photographe::getPhotographes();
$photographe = $photographes[rand(0, (count($photographes) - 1))];

//Creation de la commande
$commande = new Commande();
$commande->setID_Utilisateur($user->getUtilisateurID());

$types = TypePapier::getTypePapiers();
$tailles = TaillePapier::getTaillePapiers();
$couleurs = Couleur::getCouleurs();
$albums = Album::getAlbums(false);
//on ajoute un peu des lignes
$max = rand(2, 10);
$commande->setID_Album($albums[rand(0,(count($albums)-1))]->getAlbumID());
$adresse = new AdresseCommande();
$commande->setAdresse($adresse);
for($i = 1; $i < $max; $i++){
	$commandePhoto = new CommandePhoto();
	$commandePhoto->setID_Commande($commande->getCommandeID());
	$commandePhoto->setPhoto("bon on sen fou");
	$commandePhoto->setNombre(rand(1,5));
	$commandePhoto->setID_TypePapier($types[rand(0, (count($types)-1))]->getTypePapierID());
	$commandePhoto->setID_TaillePapier($tailles[rand(0, (count($tailles)-1))]->getTaillePapierID());
	$commandePhoto->setID_Couleur($couleurs[rand(0, (count($couleurs)-1))]->getCouleurID());
	$commandePhoto->setID_Album($albums[rand(0, (count($albums)-1))]->getAlbumID());
	$prixTaille = PrixTaillePapierAlbum::getPrixTaillePapiersDepuisID_Album($commandePhoto->getID_Album(), $commandePhoto->getID_TaillePapier());
	$prix = 12;
	$commandePhoto->setPrix($prix);
	$commande->addCommandePhoto($commandePhoto);
	echo 'nouvelle photo ajoutée à la commande : ' . $commandePhoto->getCommandePhotoID() . '<br/>';
}

$commande = $commande->create();
if($commande){
	echo 'commande & commandesPhoto créées<br/>';
	echo 'récapitulatif:<br/>';
	echo 'commande#' . $commande->getCommandeID() . "<br/>";
	foreach($commande->getCommandesPhoto() as $cp){
		echo 'cp#' . $cp->getCommandePhotoID() . "<br/>";
	}
}else{
	echo 'la commande & les commandesPhoto n\'ont pas �t� cr��es<br/>';
}

$user = $users[rand(0, (count($users)-1))];
$commandes = Commande::getCommandesEtPhotosDepuisID_Utilisateur($user->getUtilisateurID());
if($commandes){
	echo 'commandes pour l\'utilisateur#' . $user->getUtilisateurID() . ' - ' . $user->getPrenom() . " - " .$user->getNom() . "<br/>";
	foreach($commandes as $commande){
		echo "Commande#" . $commande->getCommandeID() . " avec la methode getCommandesEtPhotosDepuisID_Utilisateur<br/>";
		foreach($commande->getCommandesPhoto() as $photo){
			echo "&nbsp;&nbsp;photo#" . $photo->getCommandePhotoID() . " - nom: &apos;" . $photo->getPhoto() . "&apos; album#" . $photo->getID_Album() . "<br/>";
		}
		$controleCommande = Commande::getCommandeEtPhotosDepuisID($commande->getCommandeID());
		echo "Commande#" . $controleCommande->getCommandeID() . " avec la methode getCommandeEtPhotosDepuisID pour controle.<br/>";
			foreach($controleCommande->getCommandesPhoto() as $photo){
			echo "&nbsp;&nbsp;photo#" . $photo->getCommandePhotoID() . " - nom: &apos;" . $photo->getPhoto() . "&apos; album#" . $photo->getID_Album() . "<br/>";
		}
		echo "<hr/>";
	}
}else{
	echo "pas de commandes pour l'utilisateur#" . $user->getUtilisateurID();
}
?>