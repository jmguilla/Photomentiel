<?php
/*
 * contact.php is the page used to contact support photomentiel team
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 24 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
include("header.php");
include_once("classes/modele/Utilisateur.class.php");
include_once("classes/controleur/ControleurUtils.class.php");

$contacted = false;
if ($utilisateurObj && isset($_POST['content'])){
	//Manage duplication (F5, history back, etc.)
	$sendMail = false;
	$postHash = getHashFromArray($_POST);
	if (isset($_SESSION['contactPostHash'])){
		if ($_SESSION['contactPostHash'] != $postHash){
			$_SESSION['contactPostHash'] = $postHash;
			$sendMail = true;
		}
	} else {
		$_SESSION['contactPostHash'] = $postHash;
		$sendMail = true;
	}
	if ($sendMail){
		ControleurUtils::sendContactmail($utilisateurObj->getUtilisateurID(),$_POST['email'],$_POST['content']);
	}
	$contacted = true;
}

?>
<div id="full_content_top">
		Contact
</div>
<div id="full_content_mid">
	<div class="separator10" style="height:20px"></div>
	<div id="form_content">
		<?php
			if ($contacted) {
				/**************************** TEXT SENT ****************************/
		?>
				<div id="content_sent">
					<div class="separator10" style="height:140px;"></div>
					Votre message a bien été envoyé.<br/>
					Nous nous efforcerons de répondre dans les plus brefs délais.<br/>
					<input id="gohome" type="button" class="button" value="Retour Accueil" onClick="document.location.href='index.php'"></input>
					<div class="separator10" style="height:150px;"></div>
				</div>
		<?php
			} else {
				/*************************** WRITE TEXT ****************************/
		?>
			<div id="form_title">
				Pour toutes demandes ou suggestions, veuillez <?php echo $utilisateurObj?'':'<u>vous connecter</u> et '; ?> utiliser le formulaire ci-dessous :
			</div>
			<div class="separator10" style="height:20px"></div>
			<form id="form_contact" method="POST" action="contact.php">
				Votre E-mail  : <input id="email" type="textfield" class="textfield" name="email" <?php echo ($utilisateurObj)?'value="'.$utilisateurObj->getEmail().'"':'DISABLED="true"'; ?>/>
				<div class="separator10"></div>
				Votre message (<span id="char_left">500</span> caractères restants) :
				<div class="separator10" style="height:2px"></div>
				<textarea id="content" class="textfield" cols="100" rows="10" name="content" <?php echo ($utilisateurObj)?'':'DISABLED="true"'; ?>></textarea>
				<div class="separator10" style="height:20px"></div>
				<center>
					<input id="goback" type="button" class="button" value="Retour" onClick="history.back();" <?php echo ($utilisateurObj)?'':'DISABLED="true"'; ?>/>
					<input type="submit" class="button" value="Envoyer" id="send_button" <?php echo ($utilisateurObj)?'':'DISABLED="true"'; ?>/>
				</center>
			</form>
			<div class="separator10" style="height:40px"></div>
		<?php
			}
		?>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
?>

