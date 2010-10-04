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
include("header.php");
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
	<div id="privacy_title">
		Si vous souhaitez faire retirer une photo, merci de bien vouloir remplir le formulaire ci-dessous :
	</div>
	<div class="privacy_body">
		<form id="privacy" method="POST" action="adduser.php">
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
						<div class="checkform" id="rnom"></div>
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
						Pièce d'identité :
					</td><td>
						<input name="id_file" class="button" type="file" id="id_file" maxlength="20"/>
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
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
?>
