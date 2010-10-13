<?php
@session_start();
$dir_administration_album_php = dirname(__FILE__);
include_once $dir_administration_album_php . "/../classes/modele/Album.class.php";
include_once $dir_administration_album_php . "/../classes/modele/Evenement.class.php";
include_once $dir_administration_album_php . "/../classes/modele/Utilisateur.class.php";
include $dir_administration_album_php . "/header.php";

if(isset($_SESSION['message'])){
	echo $_SESSION['message'];
	unset($_SESSION['message']);
}
$_SESSION['message'] = '';
?>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<h3>Albums</h3>
Albums créés:<br/>
<form target="_blank" method="post" action="dispatcher.php">
	<input type="hidden" name="action" value="montrer_album_cree"/>
	<input type="submit" value="montrer"/>
</form>
<hr/>
Albums ouverts:<br/>
<form target="_blank" method="post" action="dispatcher.php">
	<input type="hidden" name="action" value="montrer_album"/>
	<input type="submit" value="montrer"/>
</form>
<hr/>
<span>Albums nécessitant validation:<br/>
<?php
$assocs = Album::getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates(0,NULL,NULL, false, 1);
if($assocs){
?>
<table>
<?php
	foreach($assocs as $assoc){
		$album = $assoc["Album"];
		$stringid = $assoc['StringID'];
		$photographe = $assoc['Photographe'];
		echo "\t\t" . '<tr><td><a target="_blank" href="http://admin.photomentiel.fr/visu_validation_album.php?sid=' . $stringid->getStringID() . '">' . $album->getNom() . '</a></td>';
		echo '<td>[' . $photographe->getAdresse()->getPrenom() . ' ' . $photographe->getAdresse()->getNom() . ' - ' . $photographe->getTelephone() . ' - <a href="mailto:' . $photographe->getEmail() . '">' . $photographe->getEmail() . '</a>]</td><td> - ' . ($album->getTransfert()? 'transfert en cours': 'pas de transfert en cours') . '</td><td><form action="dispatcher.php" method="POST"><input type="hidden" name="action" value="valider_album"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" onclick="return validate(\"Confirmer validation album.\");" name="valider_album"  value="valider"/></form></td><td><form action="dispatcher.php" method="POST"><input type="hidden" name="action" value="supprimer_album"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" onclick="return confirm(\"Confirmer suppression album.\");" name="supprimer_album"  value="supprimer"/></form></td></tr>' . "\n";
	}
?>
</table>
<?php
}else{
?>
Aucun!<br/>
<?php
}
?>
</span>
<hr/>
<span>Albums en instance de suppression:<br/>
<?php
$assocs = Album::getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates(0,NULL,NULL, false, 3);
if($assocs){
?>
<table>
<?php
	foreach($assocs as $assoc){
		$album = $assoc["Album"];
		$stringid = $assoc['StringID'];
		$photographe = $assoc['Photographe'];
		echo "\t\t" . '<tr><td><a target="_blank" href="http://www.photomentiel.fr/viewalbum.php?al=' . $stringid->getStringID() . '">' . $album->getNom() . '</a></td>';
		echo '<td>[' . $photographe->getAdresse()->getPrenom() . ' ' . $photographe->getAdresse()->getNom() . ' - ' . $photographe->getTelephone() . ' - <a href="mailto:' . $photographe->getEmail() . '">' . $photographe->getEmail() . '</a>]</td><td><form action="dispatcher.php" method="POST"><input type="hidden" name="action" value="activer_album"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" onclick="return validate(\"Confirmer réactivation album.\");" name="activer_album"  value="réactiver"/></form></td><td><form action="dispatcher.php" method="POST"><input type="hidden" name="action" value="supprimer_album"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" onclick="return confirm(\"Confirmer activation album.\");" name="supprimer_album"  value="supprimer"/></form></td></tr>' . "\n";
	}
?>
</table>
<?php
}else{
?>
Aucun!<br/>
<?php
}
?>
<hr/>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<?php
include $dir_administration_album_php . "/footer.php";
?>