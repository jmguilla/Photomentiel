<?php
/*
 * privacy.php manage the way to delete a picture
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 20 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
$HEADER_TITLE = "Vie privée et retrait de photos";
$HEADER_DESCRIPTION = "Si vous souhaitez faire retirer une photo, c'est ici que ça se passe";
include("header.php");
include_once("classes/modele/RetraitPhoto.class.php");
include_once("classes/controleur/ControleurUtils.class.php");
include_once("classes/modele/StringID.class.php");

$target_path = "administration/retraits/";
$msgSent=false;
if (isset($_POST['Captcha'])){
	$msgSent=true;
	$error_code = 0;
	//test captcha
	if ($_SESSION['Captcha'] != $_POST['Captcha']) {
		$error_code = 1;
	} else {
		//continue
		//Manage duplication (F5, history back, etc.)
		$sendMail = false;
		$postHash = getHashFromArray($_POST);
		if (isset($_SESSION['privacyPostHash'])){
			if ($_SESSION['privacyPostHash'] != $postHash){
				$_SESSION['privacyPostHash'] = $postHash;
				$sendMail = true;
			}
		} else {
			$_SESSION['contactPostHash'] = $postHash;
			$sendMail = true;
		}
		//check file type
		echo $_FILES["id_file"]["type"];
		if (!($_FILES["id_file"]["type"] == 'application/pdf' || $_FILES["id_file"]["type"] == 'image/jpeg')){
			$error_code = 2;
		}
		//check file size
		if ($error_code == 0 && $_FILES["id_file"]["size"] > 512000){
			$error_code = 3;
		}
		//check StringID
		if($error_code == 0 && !(StringID::getStringIDDepuisID($_POST['album']))){
			$error_code = 4;
		}
		if ($sendMail){
			//try upload
			if ($error_code == 0) {
				if ($_FILES["id_file"]["type"] == 'application/pdf'){
					$newFileName .= "pdf";
				} else {
					$newFileName .= "jpg";
				}
				if(!move_uploaded_file($_FILES['id_file']['tmp_name'], $target_path.$newFileName)) {
				   	$error_code = 5;
				}
			}
			//create DB line and send mail
			if ($error_code == 0) {
				//create object and save it
				$retrait = new RetraitPhoto();
				$retrait->setNom($_POST['nom']);
				$retrait->setPrenom($_POST['prenom']);
				$retrait->setMail($_POST['email']);
				$retrait->setStringID($_POST['album']);
				$retrait->setRef($_POST['ref']);
				$retrait->setJustificatif($newFileName);
				$retrait->setRaison($_POST['raison']);
				$retrait->create();
				//send email notification
				$msg = "Nom:".$_POST['nom']." - Prenom:".$_POST['prenom']."\n";
				$msg .= "Email :".$_POST['email']."\n";
				$msg .= "Album :".$_POST['album']."\n";
				$msg .= "Ref(s):".$_POST['ref']."\n";
				$msg .= "just. :".$newFileName."\n";
				$msg .= "Raison:\n";
				ControleurUtils::sendRetraitMail($_POST['email'],$msg.$_POST['raison']);
			}
		}
	}
}

?>
<div id="full_content_top">
	Vie privée et retrait de photos
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a> &gt; 
		Vie privée
	</div>
	<div class="separator10"></div>
	<?php
		if ($msgSent) {
	?>
		<div id="content_sent">
			<div class="separator10" style="height:140px;"></div>
			<?php
				if ($errorCaptcha){
			?>
				Erreur, le code de vérification n'est pas le bon.<br/>
				Veuillez réessayer.<br/>
				<input id="goback" type="button" class="button" value="Réessayer" onClick="history.back();"></input>
			<?php
				} else {
					switch ($error_code) {
						case 0:
							echo 'Votre demande a bien été envoyée.<br/>';
							echo 'Nous nous efforcerons de la satisfaire dans les plus brefs délais.<br/>';
							break;
						case 1:
							echo 'Erreur, le code de vérification n\'est pas le bon.<br/>';
							break;
						case 2:
							echo 'Erreur, le fichier n\'a pas le type requis.<br/>';
							break;
						case 3:
							echo 'Erreur, le fichier est trop volumineux.<br/>';
							break;
						case 4:
							echo 'L\'album spécifié n\'existe pas ou plus.<br/>';
							break;
						case 5:
							echo 'Il y\'a eu un problème durant le transfert de votre pièce d\'identité.<br/>';
							break;
					}
					if ($error_code > 0){
						echo 'Veuillez réessayer.<br/>';
						echo '<input id="goback" type="button" class="button" value="Réessayer" onClick="history.back();"></input>';
					}
				}
			?>
			<input id="gohome" type="button" class="button" value="Retour Accueil" onClick="document.location.href='index.php'"></input>
			<div class="separator10" style="height:150px;"></div>
		</div>
	<?php
		} else {
	?>
	<div id="privacy_title">
		Si vous souhaitez faire retirer une photo, merci de bien vouloir remplir le formulaire ci-dessous :
	</div>
	<div class="privacy_body">
		<form id="privacy_form" method="POST" action="privacy.php" enctype="multipart/form-data">
			<fieldset>
				<legend> Coordonnées personnelles </legend>
				<table>
				<tr>
					<td width="180px">
						Nom :
					</td><td>
						<input name="nom" class="textfield" type="text" id="nom" required="required"/>
					</td><td>
						<div class="checkform" id="rnom"></div>
					</td>
				</tr>
				<tr>
					<td>
						Prenom :
					</td><td>
						<input name="prenom" class="textfield" type="text" id="prenom" required="required"/>
					</td><td>
						<div class="checkform" id="rprenom"></div>
					</td>
				</tr>
				<tr>
					<td>
						Adresse E-mail :
					</td><td>
						<input name="email" class="textfield" type="text" id="email" required="required"/>
					</td><td>
						<div class="checkform" id="remail"></div>
					</td>
				</tr>
				</table>
			</fieldset>
			<fieldset>
				<legend> Références photos </legend>
				<table>
				<tr>
					<td colspan="3" height="40px;">
						<span class="note">Le code album est celui spécifié sur la carte que vous a donné votre photographe ou encore celui que vous avez reçu par mail.<br/>
						Ce code est aussi visible sur toutes les pages concernant le-dit album.</span><br/>
					</td>
				</tr>
				<tr>
					<td width="180px">
						Code de l'album :
					</td><td>
						<input name="album" class="textfield" type="text" id="album" required="required" maxlength="<?php echo STRINGID_LENGTH; ?>"/>
					</td><td>
						<div class="checkform" id="ralbum"></div>
					</td>
				</tr>
				<tr>
					<td colspan="3" height="10px"></td>
				</tr>
				<tr>
					<td colspan="3" height="35px;">
						<span class="note">La référence de la photo est visible lors de la visualisation de l'album en dessous de chaque photo.<br/>
						Vous pouvez spécifier plusieurs références en les séparant par des virgules.</span><br/>
					</td>
				</tr>
				<tr>
					<td>
						Référence de la photo :
					</td><td>
						<input name="ref" class="textfield" type="text" id="ref" required="required"/>
					</td><td>
						<div class="checkform" id="rref"></div>
					</td>
				</tr>
			</table>
			</fieldset>
			<fieldset>
				<legend> Justificatifs </legend>
				<table>
				<tr>
					<td colspan="3" height="55px;">
						<span class="note">Afin d'éviter tout abus, nous vous demandons une pièce d'identité en format <i>JPG</i> ou <i>PDF</i> dans le but de vérifier
						que vous êtes bien la personne sur la photo que vous souhaitez voir retirer.<br/>
						La personne concernée par le retrait doit apparaître sur cette pièce d'identité.</span><br/>
					</td>
				</tr>
				<tr>
					<td width="180px">
						Pièce d'identité :<br/><span class="note">(inf. à 500Ko)</span>
					</td><td>
						<input type="hidden" name="MAX_FILE_SIZE" value="500000" />
						<input name="id_file" type="file" id="id_file" maxlength="20" required="required"/>
					</td><td>
						<div class="checkform" id="rid_file"></div>
					</td>
				</tr>
				<tr>
					<td colspan="3" height="10px"></td>
				</tr>
				<tr>
					<td colspan="3" height="25px;">
						Veuillez renseigner ci-dessous les raisons de ce retrait :
					</td>
				</tr>
				<tr>
					<td colspan="3" height="25px;">
						<textarea id="raison" class="textfield" cols="100" rows="3" name="raison"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="3" height="10px"></td>
				</tr>
				<tr>
					<td colspan="3" height="25px;">
						<span class="note">Veuillez recopier ces caractères en respectant les majuscules et les minuscules : </span><img align="top" src="captcha.php" title="Recopiez le code"/> 
						<input name="Captcha" id="captcha" type="text" class="textfield" maxlength="5" style="width:50px;"></input><br/><br/>
					</td>
				</tr>
				</table>
			</fieldset>
			<div class="separator10"></div>
			<center>
				<input id="goback" type="button" class="button" value="Retour" onClick="history.back();"/>
				<input type="submit" class="button" value="Envoyer" id="send_button"/>
			</center>
			<div class="separator10"></div>
		</form>
	</div>
	<?php
		}
	?>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
?>
