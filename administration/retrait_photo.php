<?php
@session_start();
$dir_administration_retraitphoto_php = dirname(__FILE__);
include_once $dir_administration_retraitphoto_php . "/../classes/modele/RetraitPhoto.class.php";
include_once $dir_administration_retraitphoto_php . "/../classes/Config.php";
include $dir_administration_retraitphoto_php . "/header.php";

if(isset($_SESSION['message'])){
	echo $_SESSION['message'];
	unset($_SESSION['message']);
}
$_SESSION['message'] = '';
?>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<h3>Liste des retraits demandés:</h3>
<table>
<?php 
$retraits = RetraitPhoto::getRetraitsPhoto();
if($retraits){
foreach($retraits as $retrait){
	echo '<tr><td>#' . $retrait->getRetraitPhotoID() . '</td><td> - ' . $retrait->getStringID() . '</td><td><form method="post" action="dispatcher.php" target="_blank"><input type="hidden" name="action" value="detail_retrait"/><input type="hidden" name="id" value="' . $retrait->getRetraitPhotoID() . '"/><input type="submit" name="detail" value="detail"/></form></td></tr>';
}
}else{
	echo'<tr><td>Aucun!!</td><tr>';
}
?>
</table>
<br/>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<?php
include $dir_administration_retraitphoto_php . "/footer.php";
?>
