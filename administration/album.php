<?php
$dir_administration_album_php = dirname(__FILE__);
include_once $dir_administration_album_php . "/../classes/modele/Album.class.php";
include $dir_administration_album_php . "/header.php";
//traitement de l'action
if(isset($_POST['action'])){
	$action = $_POST['action']; 
	switch($action){
		case 'valider':
			if(isset($_POST['album_a_valider']) && !empty($_POST['album_a_valider'])){
				$albums = $_POST['album_a_valider'];
				Album::validerListeAlbum($albums);
			}
		break;
		default:
			echo '<h2>L\'action "' . $action . '" est inconnue</h2>';
		break;
	}
}
?>

<h3>Albums</h3>
<span>Albums nécessitant validation:
<form method="post" action="album.php">
<?php
$albums = Album::getNDerniersAlbums(0, false, 1);
if($albums){
	echo '	<select name="album_a_valider[]" size="' . count($albums) . '" multiple>';
	foreach($albums as $album){
		echo '	<option value="' . $album->getAlbumID() . '"/>' . $album->getNom() . '</option>';
	}
}
?>
	</select><br/>
	<input type="submit" value="valider"/>
	<input type="hidden" name="action" value="valider"/>
</form>
</span>
<hr/>
<?php
include $dir_administration_album_php . "/footer.php";
?>