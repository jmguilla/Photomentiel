<?php
try {
/*
 * adduser.php is the file that will be in charge to display forms to create account (photographe or not)
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 15 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
$HEADER_TITLE = "Ajout/modification d'un utilisateur";
$HEADER_DESCRIPTION = "Ajoute ou modifie un utlisateur";

include("header.php");
include_once("classes/modele/Adresse.class.php");

if (isset($_SESSION['userID'])){
	$typeSet = true;
	$photographMode = $_SESSION['userClass'] === 'Photographe';
	$createMode = false;
} else {
	$typeSet = isset($_GET['type']);
	$photographMode = $typeSet && $_GET['type'] === 'ph';
	$createMode = true;
}

$accountCreated = false;
if (isset($_GET['action']) && $_GET['action'] === 'ac'){
	$accountCreated = true;
}

//if next page is set
if (isset($_GET["np"])){
	$nextPage = $_GET["np"];
} else {
	$nextPage = 'false';
}

?>
<div id="full_content_top">
		<?php echo ($createMode||$accountCreated)?'Création':'Modification'; ?> de compte <?php if ($photographMode){echo 'Photographe';} ?>
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a>
		<?php
			if (!$typeSet){
				echo ' &gt; Type de compte';
			} else if(!$createMode) {
				echo ' &gt; Modification de compte';
			} else {
				echo ' &gt; Nouveau compte';
			}
		?>
	</div>
	<div class="separator10"></div>
	<?php
		if ($accountCreated){
			/**************************** ACCOUNT CREATED ****************************/
	?>
			<div id="content_created">
				<div class="separator10" style="height:140px;"></div>
				Votre compte a été créé avec succés.<br/>
				Nous vous avons envoyé un E-mail contenant les instructions qui vous permettront de l'activer.<br/>
				<div class="separator10"></div>
				<input id="gohome" type="button" class="button" value="Retour Accueil" onClick="document.location.href='index.php'"></input>
				<?php
					if ($nextPage != 'false'){
						echo '<input style="width:210px;margin-left:50px;" type="button" class="button" value="Retour page précédente" onClick="document.location.href=\''.$nextPage.'\'"></input>';
					}
				?>
				<div class="separator10" style="height:150px;"></div>
			</div>
	<?php
		
		} else if (!$typeSet){
			/***************************************************************************************/
			/*********************************** Choose account type *******************************/
			/***************************************************************************************/
	?>
		<div class="separator10" style="height:20px"></div>
		<div id="choices_2">
			<b>Veuillez choisir le type de compte que vous souhaitez créer<br/>en cliquant sur le bouton correspondant :</b><br/><br/>
			<div class="separator10" style="height:60px"></div>
			<div id="choices_buttons_content">
				<div style="float:left;">
					<a href="?type=cl<?php echo $nextPage!='false'?'&np='.$nextPage:''; ?>">
						<div id="desc_cli" class="description" onMouseOver="$('#desc_cli').css('border','1px #222222 solid');$('#desc_cli').css('background-color','#AAAAFF');" onMouseOut="$('#desc_cli').css('border','1px #0099FF solid');$('#desc_cli').css('background-color','#EEEEFF');">
							<table><tr><td>
							<div style="float:left;margin-right:5px;"><img height="70px" src="design/misc/create_account.png"/></div>
							<font color="black"><b>Créer un compte <u><i>client</i></u></b> permet, si vous n'êtes pas photographe, de créer un compte simple client pour acheter des photos
							disponibles au travers des albums. <br/>Il vous sera possible de parcourir les albums publics, d'accéder aux albums dont vous avez les codes,
							ainsi que d'accéder à la gestion des événements.</font>
							</td></tr></table>
						</div>
					</a>
				</div>
				<div style="float:right;">
					<a href="?type=ph<?php echo $nextPage!='false'?'&np='.$nextPage:''; ?>">
						<div id="desc_pho" class="description" onMouseOver="$('#desc_pho').css('border','1px #222222 solid');$('#desc_pho').css('background-color','#AAAAFF');" onMouseOut="$('#desc_pho').css('border','1px #0099FF solid');$('#desc_pho').css('background-color','#EEEEFF');">
							<table><tr><td>
							<div style="float:left;margin-right:5px;"><img height="70px" src="design/misc/photograph.png"/></div>
							<font color="black"><b>Créer un compte <u><i>Photographe</i></u></b> permet, dans le cas où vous êtes photographe, de créer un compte vous permettant
							d'ajouter des albums privés ou publics et d'y charger vos photos.<br/><br/>
							Pour plus de renseignements, rendez vous dans la rubrique <i>vous êtes photographe</i></font>
							</td></tr></table>
						</div>
					</a>
				</div>
				<div class="separator2"></div>
			</div>			<div class="separator10" style="height:80px"></div>
		</div>
	<?php
		/***************************************************************************************/
		/************************************* Account selected ********************************/
		/***************************************************************************************/
		} else {
	?>
	<script language="javascript">
		photographMode = <?php echo ($photographMode)?"true":"false"; ?>;
		createMode = <?php echo ($createMode)?"true":"false"; ?>;
		nextPage = "<?php echo $nextPage; ?>";
	</script>
	<div id="addu_title">
		<?php
			if ($createMode){
				$star = '*';
				echo "Afin de créer votre compte, merci de renseigner les champs ci-dessous, puis cliquer sur <i>Créer mon compte</i> :<br/>";
			} else {
				$star = '';
				echo "Vous pouvez mettre à jour les champs souhaités, et cliquez sur <i>Modifier mon compte</i> pour valider :<br/>";
			}
		?>
	</div>
	<div class="separator10"></div>
	<div class="addu_body">
		<?php 
		echo '<form id="';
		if($createMode) {
			echo "createUser";
		} else { 
			echo "updateUser";
		}
		echo '" method="POST" action="adduser.php">';
		?>
			<table>
				<tr>
					<td colspan="3" height="30px;">
						<u>Vos identifiants de connexion :</u>
					</td>
				</tr>
				<tr>
					<td width="220px">
						Adresse E-mail<?php echo $star; ?> : 
					</td><td>
						<input name="email" class="textfield" type="text" maxlength="50" id="email" <?php echo $createMode?'required="required"':'value="'.$utilisateurObj->getEmail().'"  disabled="true"'; ?>/>
					</td><td>
						<div class="checkform" id="remail"></div>
					</td>
				</tr>
				<tr>
					<td>
						Mot de passe<?php echo $star; ?> : 
					</td><td>
						<input name="pwd" class="textfield" type="password" id="pwd" <?php echo $createMode?'required="required"':''; ?>/>
					</td><td>
						<div class="checkform" id="rpwd"></div>
					</td>
				</tr>
				<tr>
					<td>
						Confirmation<?php echo $star; ?> : 
					</td><td>
						<input name="pwd2" class="textfield" type="password" id="pwd2" <?php echo $createMode?'required="required"':''; ?>/>
					</td><td>
						<div class="checkform" id="rpwd2"></div>
					</td>
				</tr>
				<tr>
					<td colspan="3" height="5px;"></td>
				</tr>
				<tr>
				<td colspan="3" height="30px;">
					<u>Vos coordonnées personnelles :</u>
				</td>
				<?php
					if (!$createMode){
						$adresseObj = $utilisateurObj->getAdresse();
					}
				?>
				<tr>
					<td>
						Nom* : 
					</td><td>
						<input name="nom" class="textfield" type="text" id="nom" required="required" <?php echo $createMode?'':'value="'.(($adresseObj)?$adresseObj->getNom():'').'"'; ?>/>
					</td><td><div class="checkform" id="rnom"></div>
					</td>
				</tr>
				<tr>
					<td>
						Prénom* : 
					</td><td>
					<input name="prenom" class="textfield" type="text" id="prenom" required="required" <?php echo $createMode?'':'value="'.(($adresseObj)?$adresseObj->getPrenom():'').'"'; ?>/>
					</td><td><div class="checkform" id="rprenom"></div>
					</td>
				</tr>
				<tr>
					<td>
						Adresse (numéro + rue)* : 
					</td><td>
						<input name="adresse1" class="textfield" type="text" id="adresse1" required="required" <?php echo $createMode?'':'value="'.(($adresseObj)?$adresseObj->getNomRue():'').'"'; ?>/>
					</td><td>
						<div  class="checkform" id="radresse1"></div>
					</td>
				</tr>
				<tr>
					<td>
						Compl. Adresse (Bât, Entrée) : 
					</td><td>
						<input name="adresse2" class="textfield" type="text" id="adresse2" <?php echo $createMode?'':'value="' . (($adresseObj)?$adresseObj->getComplement():'').'"'; ?>/>
					</td><td>
						<div  class="checkform" id="radresse2"></div>
					</td>
				</tr>
				<tr>
					<td>
						Code postal* : 
					</td><td>
						<input name="code_postal" class="textfield" type="text" id="code_postal" maxlength="5" exactlength="5" regexp="^[0-9]+$" required="required" <?php echo $createMode?'':'value="'.(($adresseObj)?$adresseObj->getCodePostal():'').'"'; ?>/>
					</td><td>
						<div  class="checkform" id="rcode_postal"></div>
					</td>
				</tr>
				<tr>
					<td>
						Ville* : 
					</td><td>
						<input name="ville" class="textfield" type="text" id="ville" required="required" <?php echo $createMode?'':'value="'.(($adresseObj)?$adresseObj->getVille():'').'"'; ?>/>
					</td><td>
						<div  class="checkform" id="rville"></div>
					</td>
				</tr>
				<tr>
					<td>
						Pays : 
					</td><td colspan="2">
						France
					</td>
				</tr>
				<?php
					if ($photographMode){
				?>
					<tr>
						<td colspan="3" height="5px;"></td>
					</tr>
					<tr>
						<td colspan="3" height="30px;">
							<u>Vos informations professionnelles :</u>
						</td>
					</tr>
					<tr>
						<td>
							N° SIREN* : 
						</td><td>
							<input name="siren" class="textfield" type="text" id="siren" maxlength="9" exactlength="9" regexp="^[0-9]+$" required="required" <?php echo $createMode?'':'value="'.$utilisateurObj->getSiren().'"'; ?>/>
						</td><td>
							<div  class="checkform" id="rsiren"></div>
						</td>
					</tr>
					<tr>
						<td>
							Nom de l'Entreprise* : 
						</td><td>
							<input name="entreprise" class="textfield" type="text" id="entreprise" required="required" <?php echo $createMode?'':'value="'.$utilisateurObj->getNomEntreprise().'"'; ?>/>
						</td><td>
							<div  class="checkform" id="rentreprise"></div>
						</td>
					</tr>
					<tr>
						<td>
							Site Web : 
						</td><td>
							<input name="site_web" class="textfield" type="text" id="site_web" value="<?php echo $createMode?'http://':$utilisateurObj->getSiteWeb(); ?>"/>
						</td><td>
							<div  class="checkform" id="rsite_web"></div>
						</td>
					</tr>
					<tr>
						<td>
							N° Tel* : 
						</td><td>
							<input name="telephone" class="textfield" type="text" id="telephone" maxlength="12" regexp="^[+]?[0-9]+$" required="required" <?php echo $createMode?'':'value="'.$utilisateurObj->getTelephone().'"'; ?>/><br/>
							<input style="margin-top:2px;" type="checkbox" name="telephone_r" id="telephone_r" <?php echo (!$createMode && $utilisateurObj->isTelephonePublique())?'checked="checked"':''; ?> /> Rendre mon numéro public
						</td><td>
							<div  class="checkform" id="rtelephone"></div>
						</td>
					</tr>
					<tr>
						<td>
							TVA appliquée* : 
						</td><td>
							<select id="tva" name="tva">
								<?php
									$i = 0;
									foreach($TVA as $t) {
										$tvac = (!$createMode && $utilisateurObj->getTVA() == $i)?'selected="selected"':'';
										echo '<option value="'.$i.'" '.$tvac.'>'.$t.'</option>';
										$i++;
									}
								?>
							</select>
						</td><td>
							
						</td>
					</tr>
					<tr>
						<td colspan="3" height="5px;"></td>
					</tr>
					<tr>
						<td colspan="3" height="44px;">
							Les revenus de vos ventes vous seront reversés par virements.<br/>
							<u>Merci de préciser vos coordonnées bancaires (RIB) :</u>
						</td>
					</tr>
					<tr>
						<td>
							Code banque* : 
						</td><td>
							<input name="banque" class="textfield" type="text" id="banque" maxlength="5" exactlength="5" regexp="^[0-9]+$" required="required" <?php echo $createMode?'':'value="'.$utilisateurObj->getRIB_b().'"'; ?>/>
						</td><td>
							<div  class="checkform" id="rbanque"></div>
						</td>
					</tr>
					<tr>
						<td>
							Code Guichet* : 
						</td><td>
							<input name="guichet" class="textfield" type="text" id="guichet" maxlength="5" exactlength="5" regexp="^[0-9]+$" required="required" <?php echo $createMode?'':'value="'.$utilisateurObj->getRIB_g().'"'; ?>/>
						</td><td>
							<div  class="checkform" id="rguichet"></div>
						</td>
					</tr>
					<tr>
						<td>
							Numéro de compte* : 
						</td><td>
							<input name="numero_compte" class="textfield" type="text" id="numero_compte" maxlength="11" exactlength="11" regexp="^[0-9A-Z]+$" required="required" <?php echo $createMode?'':'value="'.$utilisateurObj->getRIB_c().'"'; ?>/>
						</td><td>
							<div  class="checkform" id="rnumero_compte"></div>
						</td>
					</tr>
					<tr>
						<td>
							Clé RIB* : 
						</td><td>
							<input name="cle_rib" class="textfield" type="text" id="cle_rib" maxlength="2" exactlength="2" regexp="^[0-9]+$" required="required" <?php echo $createMode?'':'value="'.$utilisateurObj->getRIB_k().'"'; ?>/>
						</td><td>
							<div  class="checkform" id="rcle_rib"></div>
						</td>
					</tr>
				<?php
					}
					if ($createMode) {
				?>
					<tr>
						<td colspan="3" height="15px;"></td>
					</tr>
					<tr>
						<td colspan="2">
							<input id="cgu" name="cgu" type="checkbox" /> J'ai lu et j'accepte les <a target="blank_" href="cgu.php">conditions générales de vente et d'utilisation</a>
						</td><td>
							<div class="checkform" id="rcgu"></div>
						</td>
					</tr>
				<?php
					}
				?>
				<tr>
					<td class="aster" colspan="3" height="44px;">
						Les champs marqués d'une astérisque (*) doivent être obligatoirement renseignés
					</td>
				</tr>
			</table>
		</div>
		<div class="separator10"></div>
		<input type="button" class="button" value="Retour" onClick="history.back();"/>
		<input class="button" type="submit" id="userSubmit" value="<?php echo $createMode?'Créer mon compte':'Sauvegarder les modifications'; ?>"/> <span id="jq_load"></span>
	</form>
	<?php
		}
	?>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
}catch (Exception $e){
	echo "Internal server error !";
}
?>
