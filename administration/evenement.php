<?php
@session_start();
$dir_administration_evenement_php = dirname(__FILE__);
include_once $dir_administration_evenement_php . "/../classes/modele/Evenement.class.php";
include_once $dir_administration_evenement_php . "/../classes/modele/Utilisateur.class.php";
include_once $dir_administration_evenement_php . "/../classes/modele/Departement.class.php";
include_once $dir_administration_evenement_php . "/../classes/modele/Region.class.php";
include_once $dir_administration_evenement_php . "/../classes/modele/Ville.class.php";
include_once $dir_administration_evenement_php . "/../classes/Config.php";
include $dir_administration_evenement_php . "/header.php";

if(isset($_SESSION['message'])){
	echo $_SESSION['message'];
	unset($_SESSION['message']);
}
$_SESSION['message'] = '';
?>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<h3>Evenements</h3>
<?php
$date = date("Y-m-d", time() - (60 * 60 * 24 * 31 * 3));
echo '<span>10 événements antérieurs à ' . $date . ':<br/>';
$assocs = Evenement::getNProchainsEvenementsEntreDates(10, NULL, $date);
if($assocs){
?>
<form action="dispatcher.php" method="POST">
	<table>
<?php
	foreach($assocs as $assoc){
		$event = $assoc['Evenement'];
		$user = $assoc['Utilisateur'];
		echo '<tr><td>#' . $event->getEvenementID() . ' - </td><td><a target="blank" href="../events.php?ev=' . $event->getEvenementID() . '">' . $EVENTS_TYPES[$event->getType()] . '</a></td><td> - ' . $event->getDate() . '</td><td><form action="dispatcher.php" method="post"><input type="hidden" name="action" value="supprimer_evenement"/><input type="hidden" name="id" value="' . $event->getEvenementID() . '"/><input type="submit" onclick="return confirm(\'Vous êtes sur le point de supprimer un événement!\nLes albums associés ne seront pas supprimés.\nContinuer?\');" value="supprimer"/></form></td></tr>' . "\n";
	}
?>
	</table>
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
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<?php
include $dir_administration_evenement_php . "/footer.php";
?>