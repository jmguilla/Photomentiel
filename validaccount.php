<?php
/*
 * photograph.php
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 20 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
include("header.php");
include_once("classes/PMError.class.php");

if (!isset($_GET['uid'])){
	photomentiel_die(new PMError("Erreur de validation !","Une erreur est survenue lors de la validation de ce compte, que faites vous là ?"),false);
}
$activated = Utilisateur::activerUtilisateur($_GET['uid']);
if(!$activated){
	photomentiel_die(new PMError("Erreur de validation !","Une erreur est survenue lors de la validation de votre compte.<br/>Si vous ne parvenez pas à le valider, n'hésitez pas à nous contacter."),false);
}

?>
<div id="full_content_top">
		Votre compte est maintenant activé !
</div>
<div id="full_content_mid">
	<div class="separator10"></div>
	<div id="content_validate">
		<div class="separator10" style="height:140px;"></div>
		Votre compte a été activé avec succés, nous vous souhaitons la bienvenue parmi nous.<br/>
		Vous pouvez maintenant vous identifier en utilisant les champs ci-dessus.<br/>
		<input id="gohome" type="button" class="button" value="Retour Accueil" onClick="document.location.href='index.php'"></input>
		<div class="separator10" style="height:150px;"></div>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
?>
