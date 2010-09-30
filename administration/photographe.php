<?php
@session_start();
$dir_administration_evenement_php = dirname(__FILE__);
include_once $dir_administration_evenement_php . "/../classes/modele/Utilisateur.class.php";
include_once $dir_administration_evenement_php . "/../classes/modele/Photographe.class.php";
include_once $dir_administration_evenement_php . "/../classes/modele/Album.class.php";
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
<h3>Photographe</h3>
<table>
<?php
$photographes = Photographe::getPhotographes();
foreach($photographes as $photographe){
	$balance = 0;
	$totalGain = 0;
	$albums = Album::getAlbumDepuisID_Photographe($photographe->getPhotographeID(), false);
	if($albums){
		foreach($albums as $album){
			$totalGain += $album->getGainTotal();
			$balance += $album->getBalance();	
		}
	}
	if($balance>0){
		echo '<tr><td>' . $photographe->getAdresse()->getPrenom() . " " . $photographe->getAdresse()->getNom() . '</td><td> - balance: <b>' . $balance . '</b></td><td> - total: ' . $totalGain . '</td><td> - rib: ' . $photographe->getRIB_b() . $photographe->getRIB_g() . $photographe->getRIB_c() . $photographe->getRIB_k() . '</td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="payer"/><input type="hidden" name="id" value="' . $photographe->getPhotographeID() . '"/><input onclick="return confirm(\'Ceci remettra la balance du photographe et de tous ses albums à 0.\nContinuer?\');"  type="submit" value="payer"/></form></td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="modifier_photographe"/><input type="hidden" name="id" value="' . $photographe->getPhotographeID() . '"/><input type="text" name="pourcentage" value="'. $photographe->getPourcentage() . '"/><input type="submit" name="modifier" value="modifier pourcentage"/></form></td></tr>' . "\n";
	}else{
		echo '<tr><td>' . $photographe->getAdresse()->getPrenom() . " " . $photographe->getAdresse()->getNom() . '</td><td> - balance: <b>' . $balance . '</b></td><td> - total: ' . $totalGain . '</td><td> - rib: ' . $photographe->getRIB_b() . $photographe->getRIB_g() . $photographe->getRIB_c() . $photographe->getRIB_k() . '</td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="payer"/><input type="hidden" name="id" value="' . $photographe->getPhotographeID() . '"/><input onclick="return confirm(\'Ceci remettra la balance du photographe et de tous ses albums à 0.\nContinuer?\');" disabled  type="submit" value="payer"/></form></td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="modifier_photographe"/><input type="hidden" name="id" value="' . $photographe->getPhotographeID() . '"/><input type="text" name="pourcentage" value="'. $photographe->getPourcentage() . '"/><input type="submit" name="modifier" value="modifier pourcentage"/></form></td></tr>' . "\n";
	}
}
?>
</table><br/>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<?php
include $dir_administration_evenement_php . "/footer.php";
?>