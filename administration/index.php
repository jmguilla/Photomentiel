<?php
$dir_administration_index_php = dirname(__FILE__);
include $dir_administration_index_php . "/header.php";
?>
<div>
Rendez-vous dans la section désirée:<br/>
<form method="post" action="commande.php">
	<input type="submit" value="Gestion Commandes"/>
</form>
<form method="post" action="album.php">
	<input type="submit" value="Gestion Albums"/>
</form>
<form method="post" action="evenement.php">
	<input type="submit" value="Gestion Evenements"/>
</form>
<form method="post" action="utilisateur.php">
	<input type="submit" value="Gestion Utilisateurs"/>
</form>
<form method="post" action="photographe.php">
	<input type="submit" value="Gestion Photographes"/>
</form>
<form method="post" action="retrait_photo.php">
	<input type="submit" value="Gestion Retraits Photo"/>
</form>
<form method="post" action="error.php">
	<input type="submit" value="Gestion Des Erreurs"/>
</form>
</div>
<?php 
include $dir_administration_index_php . "/header.php";
?>