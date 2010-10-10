<?php
@session_start();
$dir_administration_error_php = dirname(__FILE__);
include_once $dir_administration_error_php . "/../classes/modele/Error.class.php";
include_once $dir_administration_error_php . "/../classes/Config.php";
include $dir_administration_error_php . "/header.php";

if(isset($_SESSION['message'])){
	echo $_SESSION['message'];
	unset($_SESSION['message']);
}
$_SESSION['message'] = '';
?>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>
<h3>Erreurs</h3>
<?php 
$errors = Error::getErrors();
if($errors){
	echo "<table>\n";
	foreach($errors as $error){
		echo "<tr><td>#" . $error->getErrorID() . "</td><td> - " . $error->getMessage() . '</td><td><form action="dispatcher.php" method="post"><input type="hidden" name="action" value="supprimer_error"/><input type="hidden" name="id" value="'.$error->getErrorID().'"/><input type="submit" value="supprimer" onclick="return confirm("supprimer?");"/></form></td></tr>' . "\n";
	}
	echo "</table>\n";
}else{
	echo "<h2>Aucunes!!</h2>";
}
?>
<form method="post" action="index.php">
	<input type="submit" value="retour accueil"/>
</form>