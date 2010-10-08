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
$HEADER_TITLE = "Contact";
$HEADER_DESCRIPTION = "Contactez nous";
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
		$errorCaptcha = (strcmp($_SESSION['Captcha'],$_POST['Captcha']) != 0);
		if (!$errorCaptcha){
			ControleurUtils::sendContactmail($utilisateurObj->getUtilisateurID(),$_POST['email'],$_POST['content']);
		}
	}
	$contacted = true;
}

?>
<div id="full_content_top">
		Contact
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a> &gt; 
		Contact
	</div>
	<div class="separator10" style="height:20px"></div>
	<div id="form_content">
		<?php
			if ($contacted) {
				/**************************** TEXT SENT ****************************/
		?>
				<div id="content_sent">
					<div class="separator10" style="height:140px;"></div>
					<?php
						if ($errorCaptcha){
					?>
						Erreur, le code de vérification n'est pas le bon.<br/>
						Veuillez réessayer.<br/>
						<form method="post" action="contact.php">
						<input type="hidden" name="_email" value="<?php echo $_POST['email']; ?>"></input>
						<input type="hidden" name="_content" value="<?php echo $_POST['content']; ?>"></input>
						<input id="goback" type="submit" class="button" value="Réessayer" onClick="history.back();"></input>
					<?php
						} else {
					?>
						Votre message a bien été envoyé.<br/>
						Nous nous efforcerons de répondre dans les plus brefs délais.<br/>
					<?php
						}
					?>
					<input id="gohome" type="button" class="button" value="Retour Accueil" onClick="document.location.href='index.php'"></input><?php if ($errorCaptcha){echo "</form>";} ?>
					<div class="separator10" style="height:150px;"></div>
				</div>
		<?php
			} else {
				/*************************** WRITE TEXT ****************************/
		?>
			<div id="form_title">
				Pour toutes demandes ou suggestions, veuillez <?php echo $utilisateurObj?'':'<u>vous connecter</u> et '; ?> utiliser le formulaire ci-dessous :
			</div>
			<div id="form_info">
				(Avant de remplir ce formulaire, veuillez vous assurez que vous n'avez pas trouvé la réponse à votre demande dans les pages 
				<a href="faq.php">utilisateurs</a> ou <a href="photograph.php">photographes</a>)
			</div>
			<div class="separator10" style="height:20px"></div>
			<form id="form_contact" method="POST" action="contact.php">
				Votre E-mail  : <input id="email" type="textfield" class="textfield" name="email" <?php echo ($utilisateurObj)?'value="'.(isset($_POST["_email"])?$_POST["_email"]:$utilisateurObj->getEmail()).'"':'DISABLED="true"'; ?>/>
				<div class="separator10"></div>
				Votre message (<span id="char_left">500</span> caractères restants) :
				<div class="separator10" style="height:2px"></div>
				<textarea id="content" class="textfield" cols="100" rows="10" name="content" <?php echo ($utilisateurObj)?'':'DISABLED="true"'; ?>><?php if (isset($_POST["_content"])){echo $_POST["_content"];} ?></textarea>
				<div class="separator10" style="height:20px"></div>
				<?php
					if ($utilisateurObj){
						echo 'Veuillez recopier ces caractères en respectant les majuscules et les minuscules : <img align="top" src="captcha.php" title="Recopiez le code"/> ';
						echo '<input name="Captcha" id="captcha" type="text" class="textfield" maxlength="5"></input><br/><br/>';
					}
				?>
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

