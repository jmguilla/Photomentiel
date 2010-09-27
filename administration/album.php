<?php
@session_start();
$dir_administration_album_php = dirname(__FILE__);
include_once $dir_administration_album_php . "/../classes/modele/Album.class.php";
include_once $dir_administration_album_php . "/../classes/modele/Evenement.class.php";
include_once $dir_administration_album_php . "/../classes/modele/Utilisateur.class.php";
include $dir_administration_album_php . "/header_album.php";

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
Albums ouverts:<br/>
<form target="blank" method="post" action="dispatcher.php">
	<input type="hidden" name="action" value="montrer_album"/>
	<input type="submit" value="montrer"/>
</form>
<hr/>
<span>Albums nécessitant validation:<br/>
<?php
$assocs = Album::getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates(0,NULL,NULL, false, 1);
if($assocs){
?>
<form action="dispatcher.php" method="POST" onsubmit="return OnSubmitValidationForm();">
	<table>
<?php
	foreach($assocs as $assoc){
		$album = $assoc["Album"];
		$stringid = $assoc['StringID'];
		$photographe = $assoc['Photographe'];
		echo "\t\t" . '<tr><td><input type="checkbox" name="albumID' . $album->getAlbumID() . '" value="albumID' . $album->getAlbumID() . '"/></td><td><a target="_blank" href="../viewalbum.php?al=' . $stringid->getStringID() . '">' . $album->getNom() . '</a></td>';
		echo '<td>[' . $photographe->getAdresse()->getPrenom() . ' ' . $photographe->getAdresse()->getNom() . ' - ' . $photographe->getTelephone() . ' - <a href="mailto:' . $photographe->getEmail() . '">' . $photographe->getEmail() . '</a>]</td><td><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" onclick="document.pressed=this.name" name="supprimer_album" value="supprimer"/></td></tr>' . "\n";
	}
?>
	</table>
	<input type="hidden" id="actionValidation" name="action" value=""/>
	<input type="submit"  onclick="document.pressed=this.name" name="valider_album" value="valider"/>
</form>
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
<form action="dispatcher.php" method="POST" onsubmit="return OnSubmitActivationForm();">
	<table>
<?php
	foreach($assocs as $assoc){
		$album = $assoc["Album"];
		$stringid = $assoc['StringID'];
		$photographe = $assoc['Photographe'];
		echo "\t\t" . '<tr><td><input type="checkbox" name="albumID' . $album->getAlbumID() . '" value="albumID' . $album->getAlbumID() . '"/></td><td><a target="_blank" href="../viewalbum.php?al=' . $stringid->getStringID() . '">' . $album->getNom() . '</a></td>';
		echo '<td>[' . $photographe->getAdresse()->getPrenom() . ' ' . $photographe->getAdresse()->getNom() . ' - ' . $photographe->getTelephone() . ' - <a href="mailto:' . $photographe->getEmail() . '">' . $photographe->getEmail() . '</a>]</td><td><input type="hidden" name="action" value="supprimer_album"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" onclick="document.pressed=this.name" name="supprimer_album"  value="supprimer"/></td></tr>' . "\n";
	}
?>
	</table>
	<input type="hidden" id="actionActivation" name="action" value=""/>
	<input type="submit" onclick="document.pressed=this.name" name="activer_album" value="activer"/>
</form>
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