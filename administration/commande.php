<?php
@session_start();
$dir_administration_commande_php = dirname(__FILE__);
include_once $dir_administration_commande_php . "/../classes/modele/Commande.class.php";
include_once $dir_administration_commande_php . "/../classes/modele/CommandePhoto.class.php";
include $dir_administration_commande_php . "/header.php";

if(isset($_SESSION['message'])){
	echo $_SESSION['message'];
	unset($_SESSION['message']);
}
$_SESSION['message'] = '';
?>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<h3>Commandes</h3>
<span><h4>Commandes à traiter:</h4>
<?php
$commandes = Commande::getCommandeEtPhotosDepuisEtat(1);
if($commandes){
	echo '	<table>';
	foreach($commandes as $commande){
		$prix = $commande->getFDP();
		$commandesPhoto = $commande->getCommandesPhoto();
		if(isset($commandesPhoto) && !empty($commandesPhoto)){
			foreach($commandesPhoto as $commandePhoto){
				$prix += $commandePhoto->getPrix();
			}
		}
		echo '<tr><td>#' . $commande->getCommandeID() . ' - </td><td> ' . $commande->getAdresse()->getPrenom() . " " . $commande->getAdresse()->getNom() . "</td><td>" . $commande->getDate() . "</td><td> pour " . $prix . ' &#x20AC; </td><td><form action="dispatcher.php" method="post" target="_blank"><input type="hidden" name="action" value="detail_commande"/><input type="hidden" name="id" value="' . $commande->getCommandeID() . '"/><input type="submit" value="détail"/></form></td><td><form action="dispatcher.php" method="post"><input type="hidden" name="action" value="traiter_commande"/><input type="hidden" name="id" value="' . $commande->getCommandeID() . '"/><input type="submit" value="traiter"/></form></td></tr>' . "\n";
	}
	echo '	</table>';
?>
<?php
}else{
?>
Aucune!<br/>
<?php
}
?>
</span>
<hr/>
<span><h4>Commandes en cours de préparation:</h4>
<?php
$commandes = Commande::getCommandeEtPhotosDepuisEtat(2);
if($commandes){
	echo '	<table>';
	foreach($commandes as $commande){
		$prix = $commande->getFDP();
		$commandesPhoto = $commande->getCommandesPhoto();
		if(isset($commandesPhoto) && !empty($commandesPhoto)){
			foreach($commandesPhoto as $commandePhoto){
				$prix += $commandePhoto->getPrix();
			}
		}
		echo '<tr><td>#' . $commande->getCommandeID() . ' - </td><td> ' . $commande->getAdresse()->getPrenom() . " " . $commande->getAdresse()->getNom() . "</td><td>" . $commande->getDate() . "</td><td> pour " . $prix . ' &#x20AC; </td><td>préparée par ' . $commande->getPreparateur() . '</td><td><form action="dispatcher.php" method="post" target="_blank"><input type="hidden" name="action" value="detail_commande"/><input type="hidden" name="id" value="' . $commande->getCommandeID() . '"/><input type="submit" value="détail"/></form></td><td><form action="dispatcher.php" method="post" target="_blank"><input type="hidden" name="action" value="download_commande_xml"/><input type="hidden" name="id" value="' . $commande->getCommandeID() . '"/><input type="submit" value="download xml"/></form></td><td><form action="dispatcher.php" method="post"><input type="hidden" name="action" value="commande_expediee"/><input type="hidden" name="id" value="' . $commande->getCommandeID() . '"/><input type="submit"  onclick="return confirm(\'Vous êtes sur le point de changer un état de commande.\nContinuer?\');"  value="état = expédié"/></form></td></tr>' . "\n";
	}
	echo '	</table>';
?>
<?php
}else{
?>
Aucune!<br/>
<?php
}
?>
</span>
<hr/>
<span><h4>Commandes en attente de paiement:</h4>
<?php
$commandes = Commande::getCommandeEtPhotosDepuisEtat(0);
if($commandes){
	echo '	<table>';
	foreach($commandes as $commande){
		$prix = $commande->getFDP();
		$commandesPhoto = $commande->getCommandesPhoto();
		if(isset($commandesPhoto) && !empty($commandesPhoto)){
			foreach($commandesPhoto as $commandePhoto){
				$prix += $commandePhoto->getPrix();
			}
		}
		echo '<tr><td>#' . $commande->getCommandeID() . ' - </td><td> ' . $commande->getAdresse()->getPrenom() . " " . $commande->getAdresse()->getNom() . "</td><td>" . $commande->getDate() . "</td><td> pour " . $prix . ' &#x20AC; </td><td><form action="dispatcher.php" method="post" target="_blank"><input type="hidden" name="action" value="detail_commande"/><input type="hidden" name="id" value="' . $commande->getCommandeID() . '"/><input type="submit" value="détail"/></form></td><td><form action="dispatcher.php" method="post"><input type="hidden" name="action" value="supprimer_commande"/><input type="hidden" name="id" value="' . $commande->getCommandeID() . '"/><input onclick="return confirm(\'Vous êtes sur le point de supprimer une commande.\nContinuer?\');"  type="submit" value="supprimer"/></form></td></tr>' . "\n";
	}
	echo '	</table>';
?>
<?php
}else{
?>
Aucune!<br/>
<?php
}
?>
</span>
<hr/>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<?php
include $dir_administration_commande_php . "/footer.php";
?>