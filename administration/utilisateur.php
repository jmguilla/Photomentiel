<?php
@session_start();
$dir_administration_utilisateur_php = dirname(__FILE__);
include_once $dir_administration_utilisateur_php . "/../classes/modele/Utilisateur.class.php";
include_once $dir_administration_utilisateur_php . "/../classes/modele/Photographe.class.php";
include_once $dir_administration_utilisateur_php . "/../classes/modele/Adresse.class.php";
include_once $dir_administration_utilisateur_php . "/../classes/Config.php";
include $dir_administration_utilisateur_php . "/header.php";

if(isset($_SESSION['message'])){
	echo $_SESSION['message'];
	unset($_SESSION['message']);
}
$_SESSION['message'] = '';
?>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<h3>Utilisateurs</h3>
<?php
echo '<span>Comptes utilisateur non actif:<br/>';
$assocs = Utilisateur::getNonActif();
if($assocs){
?>
<table>
<?php
	foreach($assocs as $assoc){
		$user = $assoc['Utilisateur'];
		$aid = $assoc['ActivateID'];
		echo '<tr><td>#' . $user->getUtilisateurID() . ' - </td><td>' . $user->getAdresse()->getPrenom() . ' ' . $user->getAdresse()->getNom() . '</td><td> - ' . $user->getEmail() . ' </td><td><form action="dispatcher.php" method="post"><input type="hidden" name="id" value="' . $aid . '"/><input type="hidden" name="action" value="activer_utilisateur"/><input type="submit" value="activer"/></form></td><td><form action="dispatcher.php" method="post"><input type="hidden" name="id" value="' . $user->getUtilisateurID() . '"/><input type="hidden" name="action" value="renvoyer_email_confirmation"/><input type="hidden" name="aid" value="' . $aid . '"/><input type="submit" value="renvoyer email"/></form></td></tr>';
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
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<?php
include $dir_administration_utilisateur_php . "/footer.php";
?>