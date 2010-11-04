<?php
try {
/*
 * createalbum.php is the page that is in charge to create a new album or update an existing one
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 13 août 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
session_start();
include_once("classes/PMError.class.php");
include_once("classes/modele/StringID.class.php");
include_once("classes/controleur/ControleurUtils.class.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Evenement.class.php");
include_once("classes/modele/TaillePapier.class.php");
include_once("classes/modele/PrixTaillePapierAlbum.class.php");

if (!(isset($_SESSION['userClass']) && $_SESSION['userClass'] === 'Photographe')){
	photomentiel_die(new PMError("Accés interdit !","Cet accés est strictement réservé à nos photographes, que faites vous là ?"));
}

$HEADER_TITLE = "Création et gestion d'album";
$HEADER_DESCRIPTION = "Créer et gérer vos albums sur cette page";
include("header.php");

$updateMode = false;
$albumCreated = false;
$albumSaved = false;
if (isset($_GET['action']) && $_GET['action'] === 'update'){
	if (!isSet($_GET['al']) || $_GET['al'] == ''){
		photomentiel_die(new PMError("Aucun album spécifié !","Aucun code album n'a été spécifié, que faites vous là ?"), false);
	}
	//check stringID
	$sidObj = StringID::getStringIDDepuisID($_GET['al']);
	if (!$sidObj){
		photomentiel_die(new PMError("Album inexistant !","L'album spécifié n'existe pas ou plus..."), false);
	}
	//get album obj	
	$albumObj = Album::getAlbumDepuisID($sidObj->getID_Album());
	//check album owner
	if ($albumObj->getID_Photographe() != $utilisateurObj->getPhotographeID()){
		photomentiel_die(new PMError("Album inaproprié !","Cet album ne vous appartient pas, que faites vous là ?"), false);
	}
	//update mailing if needed
	if(isset($_POST['mails']) && $_POST['mails'] != $albumObj->getMailing()){
		$albumObj->setMailing($_POST['mails']);
		$albumObj->save();
		$albumSaved = true;
	}
	//check if state change is demanded
	if (isset($_POST['cb_gonext']) && $albumObj->getEtat() == 0){
		$albumObj->etatSuivant();
		//decrement create album count
		$utilisateurObj->decOpenFTP();
		//send request for FTP
		if (!isset($sidObj)){
			$sidObj = StringID::getStringIDDepuisID_Album($albumObj->getAlbumID());
		}
		$postParam = "login=".$utilisateurObj->getEmail().
			"&homePhotograph=".$sidObj->getHomePhotographe().
			"&stringID=".$sidObj->getStringID().
			"&openAlbum=".$utilisateurObj->getOpenFTP().
			"&watermark=".$albumObj->getFiligramme();
		$retcode = httpPost("http://".FTP_TRANSFER_IP.":".HTTP_PORT."/private/close_ftp.php",$postParam);
		if ($retcode !== "0"){
			ControleurUtils::addError(
					"Erreur d'appel sur http://".FTP_TRANSFER_IP.":".HTTP_PORT."/private/close_ftp.php\n".
					$postParam."\n" .
					"Code retour : ".($retcode?$retcode:"Serveur semble injoignable"));
		}
	}
	$updateMode = true;
} else if (isset($_POST['title'])) {
	//Manage duplication (F5, history back, etc.)
	$createAlbum = false;
	$postHash = getHashFromArray($_POST);
	if (isset($_SESSION['albumPostHash'])){
		if ($_SESSION['albumPostHash'] != $postHash){
			$_SESSION['albumPostHash'] = $postHash;
			$createAlbum = true;
		}
	} else {
		$_SESSION['albumPostHash'] = $postHash;
		$createAlbum = true;
	}
	if ($createAlbum){
		//create new album
		$albToCreate = new Album();
		$albToCreate->setID_Photographe($utilisateurObj->getPhotographeID());
		$albToCreate->setNom($_POST['title']);
		if (isset($_POST['event']) && $_POST['event'] > 0){
			$albToCreate->setID_Evenement($_POST['event']);
		}
		$albToCreate->setIsPublique($_POST['public']);
		if (isset($_POST['filigrane']) && $_POST['filigrane'] != ''){
			$albToCreate->setFiligramme(preg_replace("[ ]","_",$_POST['filigrane']));
		}
		if(isset($_POST['mails'])){
			$albToCreate->setMailing($_POST['mails']);
		}
		//create format and prices
		$papers = TaillePapier::getTaillePapiers();
		foreach($papers as $paper){
			if (isset($_POST[$paper->getTaillePapierID()]) && $_POST[$paper->getTaillePapierID()] != ''){
				if ($_POST[$paper->getTaillePapierID()] >= $paper->getPrixMinimum()){
					$albToCreate->addPrixTaillePapier(new PrixTaillePapierAlbum(-1,$_POST[$paper->getTaillePapierID()],$paper->getTaillePapierID()));
				}
			}
		}
		//finally create album
		$albToCreate = $albToCreate->create();
		if (!$albToCreate){
			photomentiel_die(new PMError("L'album n'a pas été créé !","Un problème est survenu lors de la création de l'album, veuillez réessayer ultérieurement."), false);
		}
		$albumObj = $albToCreate;
		$albumCreated = true;
		$updateMode = true;
		$_SESSION['lastCreatedAlbum'] = $albumObj->getAlbumID();
		//increment create album count
		$utilisateurObj->incOpenFTP();
		//send request for FTP
		if (!isset($sidObj)){
			$sidObj = StringID::getStringIDDepuisID_Album($albumObj->getAlbumID());
		}
		$postParam = "login=".$utilisateurObj->getEmail().
			"&homePhotograph=".$sidObj->getHomePhotographe().
			"&stringID=".$sidObj->getStringID().
			"&passwordHash=".$utilisateurObj->getMDP();
		$retcode = httpPost("http://".FTP_TRANSFER_IP.":".HTTP_PORT."/private/open_ftp.php", $postParam);
		if ($retcode !== "0"){
			ControleurUtils::addError(
					"Erreur d'appel sur http://".FTP_TRANSFER_IP.":".HTTP_PORT."/private/open_ftp.php\n".
					$postParam."\n" .
					"Code retour : ".($retcode?$retcode:"Serveur semble injoignable"));
		}
	} else {
		if (!isset($_SESSION['lastCreatedAlbum'])){
			photomentiel_die(new PMError("Erreur lors de la création de l'album !","Une tentative de duplication de l'album a généré un problème."),false);
		}
		$albumCreated = true;
		$updateMode = true;
		$albumObj = Album::getAlbumDepuisID($_SESSION['lastCreatedAlbum']);
		if (!isset($sidObj)){
			$sidObj = StringID::getStringIDDepuisID_Album($albumObj->getAlbumID());
		}
	}
}
if ((isset($_GET['action']) && $_GET['action'] === 'update') || isset($_POST['title'])){
	//prepare photo formats and price
	$tmp = TaillePapier::getTaillePapiers();
	$photoFormatsDim = array();
	foreach($tmp as $tp){
		$photoFormatsDim[$tp->getTaillePapierID()] = $tp->getDimensions();
	}
	$photoFormatsPrice = array();
	$tmp = PrixTaillePapierAlbum::getPrixTaillePapiersDepuisID_Album($albumObj->getAlbumID());
	foreach($tmp as $ptpa){
		$photoFormatsPrice[$ptpa->getID_TaillePapier()] = $ptpa->getPrix();
	}
}

?>
<script type="text/javascript" src="js/calendar.js"></script>
<div id="full_content_top">
		<?php
		if ($updateMode){
			echo 'Gestion des Albums';
		} else {
			echo 'Création d\'un nouvel album';
		}
		?>
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a> &gt; 
		<a href="myaccount.php">Mon compte</a> &gt; 
		<?php
			if ($updateMode){
				$sid = $sidObj->getStringID();
				echo "Album <b>".$sid."</b>"; 
			} else {
				echo "Nouvel album"; 
			}
		?>
	</div>
	<div class="separator10"></div>
	<div id="catitle">
		<?php
		if ($updateMode){
			if ($albumCreated){
				echo '<div id="catitle2">Votre album a été créé avec succés !</div>';
			}
			$continueUpdateState = $albumObj->getEtat() == 0 || $albumObj->getEtat() == 1;
			if ($continueUpdateState){
				echo 'Pour modifier votre album veuillez remplir ou compléter les champs suivants et appuyer sur <i>Mettre à jour</i>';
			} else {
				echo 'Voici l\'état actuel de votre album :';
			}
		} else {
			echo 'Pour créer un album, veuillez renseigner les champs suivants et appuyer sur le bouton <i>Créer mon album</i> :';
		}
		?>
	</div>
	<div id="cacontent">
		<?php
			if (!$updateMode && !$albumCreated){
		?>
		<form id="create_form" method="POST">
		<fieldset>
			<legend> Description de l'album </legend>
			<table>
			<tr>
				<td width="180px">
					Intitulé (titre)* : 
				</td><td>
					<input name="title" class="textfield" type="text" id="title" required="required" maxlength="40"/>
				</td><td>
					<div class="checkform" id="rtitle"></div>
				</td>
			</tr>
			<tr>
				<td height="25px">
					Album public ? : 
				</td><td>
					<input type="radio" id="public1" name="public" value="0"/> Non (Album privé)
					<input type="radio" id="public2" name="public" value="1"/> Oui (Album public)
				</td><td>
					<div class="checkform" id="rpublic"></div>
				</td>
			</tr>
			<tr>
				<td colspan="3" height="66px;">
					<span class="note">Les photos exposées seront filigranées, indiquez ici si vous souhaitez choisir notre filigrane par défaut,<br/>
					ou le filigrane de votre choix en entrant le texte désiré dans le champ suivant.</span><br/>
					<u>Si vous souhaitez un filigrane personnalisé, veuillez renseigner le champ suivant, sinon laissez le vide :</u>
				</td>
			</tr>
			<tr>
				<td>
					Filigrane personnalisé :
				</td><td>
					<input name="filigrane" class="textfield" type="text" id="filigrane" maxlength="20" regexp="^[A-Za-z0-9.\-_ ]+$"/>
				</td><td>
					<div class="checkform" id="rfiligrane"></div>
				</td>
			</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Evénement </legend>
			<table>
			<tr>
				<td colspan="3" height="84px;">
					<span class="note">Un événement <u>peut être</u> associé à cet album, le spécifier nous permet d'envoyer un E-mail
					à toutes les personnes qui se sont inscrites à cet événement pour les prévenir de sa disponibilité.<br/>
					Ceci permet d'augmenter le nombre de visites sur cet album.<br/></span>
					<u>Si vous souhaitez associer un événement, merci de le sélectionner :</u>
				</td>
			</tr>
			<tr>
				<td width="180px">
					Evénement associé : 
				</td><td>
					<input id="filter_date" class="textfield" value="jj/mm/aaaa" type="text" onKeyUp="if(this.value.length==2 || this.value.length==5){this.value+='/';}" onFocus="this.select()"/>
					<input type="text" class="textfield" id="filter_tf" value="mots-clés" onFocus="this.select()"/> <a href="javascript:filterEvent();" title="Filtrer les événements">filtrer</a><br/>
					<select size="2" id="event" name="event">
						<option value="0" selected="true">Aucun événement associé</option>
						<?php
							$evts = Evenement::getNDerniersEvenements(20);
							if($evts){
								foreach($evts as $tmp){
									echo '<option value="'.$tmp->getEvenementID().'">'.date("d/m/y",strtotime($tmp->getDate())).' - '.$tmp->getDescription().'</option>';
								}
							}
						?>
					</select>
				</td><td>
					<div class="checkform" id="revent"></div>
				</td>
			</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Formats & tarifs </legend>
			<table>
			<tr>
				<td colspan="3" height="100px;">
					<span class="note">Pour les formats, les prix conseillés indiqués sont donnés à titre indicatif et sont, selon nos statistiques, ceux qui offrent
					les meilleurs rendements. Vous y trouverez aussi les prix minimum que nous acceptons. N'oubliez pas que vos clients préférent
					acheter 5 photos à 6&#8364; plutôt qu'une seule à 20&#8364;.<br/>(les prix ne seront pas modifiables par la suite)<br/>
					<font color="darkred"><b>Veuillez aussi prendre soin de choisir les formats en respectant vos ratios de résolution</b></font>.<br/>
					(Par exemple, gardez le format 10x15 si vos photos sont larges, 10x13 pour des photos en 4/3 - Les formats en <b>gras</b> sont les formats larges).<br/>
					En règle générale, vous ne devez pas avoir à choisir 2 formats dont le premier nombre est équivalent (ex. <b>10</b>x13 et <b>10</b>x15).</span><br/>
					<u>Sélectionnez les formats de photos qui seront disponibles pour cet album, ainsi que les prix que vous <br/>souhaitez leur attribuer :</u><br/>
				</td>
			</tr>
			<tr>
				<td width="180px">
					Formats & tarifs : <br/>
					<span class="note2">(Remplissez seulement les formats que vous souhaitez vendre)</span>
				</td><td colspan="2">
					<table>
						<?php
							$papers = TaillePapier::getTaillePapiers();
							$tf = 0;
							foreach($papers as $paper){
								$tft = ($tf%2==0)?$paper->getDimensions():'<b>'.$paper->getDimensions().'</b>';
								$tf++;
								echo 
								'<tr>
									<td width="65px">
										'.$tft.' :
									</td><td class="price" width="350px">
										<input type="text" class="textfield" regexp="^([0-9]{1,3}|[0-9]{1,3}[.,][0-9]{1,2})$" min="'.$paper->getPrixMinimum().'" id="'.$paper->getTaillePapierID().'" name="'.$paper->getTaillePapierID().'"/>&nbsp;&#8364;<span class="prix_conseille">( Prix min: <b>'.$paper->getPrixMinimum().' &#8364;</b> - conseillé: <b>'.$paper->getPrixConseille().' &#8364;</b> )</span>
									</td><td width="290px">
										<div class="checkform" id="r'.$paper->getTaillePapierID().'"></div>
									</td>
								</tr>';
							}
						?>
					</table>
				</td>
			</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Liste de mails </legend>
			<table>
			<tr>
				<td colspan="3" height="66px;">
					<span class="note">La zone suivante vous permet de créer une liste de mails, que vous pouvez remplir ou compléter plus tard.<br/>
					Photomentiel enverra un E-mail à toutes ces personnes dès que l'album sera prêt.</span><br/>
					<u>Si vous avez déjà une liste de mails, veuillez les insérer maintenant :</u>
				</td>
			</tr>
			<tr>
				<td width="180px">
					Liste de mails : <br/>
					<span class="note2">Séparez les mails par des points-virgules '<b>;</b>'</span>
				</td><td>
					<textarea name="mails" cols="55" rows="6" id="mails" regexp="^([^@]+@[^.@]+[.][^@;]+)([;][^@]+@[^.@]+[.][^@;]+)*$"></textarea>
				</td><td>
					<div class="checkform" id="rmails"></div>
				</td>
			</tr>
		</table>
	</fieldset>
		<div class="separator10"></div>
		<center>
			<input type="button" class="button" value="Annuler" onClick="document.location.href='myaccount.php'"/>
			<input id="create_submit" type="button" class="button" value="Créer mon album" onClick="validForm();"/>
		<center/>
		</form>
		<?php
			} else if ($updateMode){
				//set at top
				//$sid = StringID::getStringIDDepuisID_Album($albumObj->getAlbumID())->getStringID();
		?>
		<form id="update_form" method="POST" action="?action=update&al=<?php echo $sid ?>">
		<fieldset>
			<legend> Description de l'album </legend>
			<table>
			<tr>
				<td width="180px;">
					Intitulé : 
				</td><td colspan="2">
					<?php echo toNchar($albumObj->getNom(),180); ?>
				</td>
			</tr>
			<tr>
				<td>
					Code : 
				</td><td colspan="2">
					<font color="blue"><?php echo $sid; ?></font>
					<?php
						if ($albumObj->getEtat() <= 2) {
							echo ' - <a id="card_pdf" target="_blank" href="cartes-de-visite-'.$sid.'.pdf">Télécharger vos cartes de visites</a>';
						}
					?>
				</td>
			</tr>
			<tr>
				<td>
					Album public : 
				</td><td colspan="2">
					<?php echo $albumObj->isPublique()?"Oui":"Non"; ?>
				</td>
			</tr>
			<tr>
				<td>
					Filigrane : 
				</td><td colspan="2">
					<i><?php echo $albumObj->getFiligramme(); ?></i>
				</td>
			</tr>
			<tr>
				<td>
					Evénement associé : 
				</td><td colspan="2">
					<?php 
						$evt = Evenement::getEvenementDepuisID($albumObj->getID_Evenement());
						if ($evt){
							echo '<a class="intitule" href="events.php?ev='.$evt->getEvenementID().'">'.toNchar($evt->getDescription(),180).'</a>';
						} else {
							echo '<span class="intitule">Pas d\'événement associé</span>';
						}
					?>
				</td>
			</tr>
			<tr>
				<td>
					Formats & tarifs : 
				</td><td colspan="2">
					<table width="200px" style="margin-top:4px;" class="fandp">
					<?php
						foreach ($photoFormatsPrice as $id => $p) {
							echo "<tr><td>".$photoFormatsDim[$id]." cm</td><td>".sprintf('%.2f',$p)." &#8364;</td></tr>";
						}
					?>
					</table>
				</td>
			</tr>
			</table>
		</fieldset>
		<fieldset>
			<legend> Gains pour l'album </legend>
			<table cellspacing="0px">
			<tr <?php echo ($albumObj->getEtat()<2)?'':'style="background-color:lightgreen;"'; ?>>
				<td width="182px;" height="22px">
					Gains pour le mois : 
				</td><td colspan="2">
					<b><?php echo $albumObj->getBalance(); ?> &#8364;&nbsp;</b>
				</td>
			</tr>
			<tr>
				<td height="22px">
					Gains depuis la création : 
				</td><td colspan="2">
					<?php echo $albumObj->getGainTotal(); ?> &#8364;
				</td>
			</tr>
			</table>
		</fieldset>
			<?php
				if ($continueUpdateState){
			?>
		<fieldset>
			<legend> Gestion de votre liste de mails </legend>
			<table>
			<tr>
				<td colspan="3" height="66px;">
					<span class="note">La zone suivante vous permet de gérer votre liste de mails.<br/>
					Photomentiel enverra un E-mail à toutes ces personnes dès que l'album sera prêt, ainsi qu'un rappel 2 semaines plus tard.</span><br/>
					<u>Si vous avez de nouveaux contacts, vous pouvez les insérer maintenant :</u>
				</td>
			</tr>
			<tr>
				<td width="180px;">
					Liste de mails : <br/>
					<span class="note2">Séparez les mails par des points-virgules '<b>;</b>'</span>
				</td><td>
					<textarea name="mails" cols="55" rows="6" id="mails" regexp="^([^@]+@[^.@]+[.][^@;]+)([;][^@]+@[^.@]+[.][^@;]+)*$"><?php echo $albumObj->getMailing(); ?></textarea>
				</td><td>
					<div class="checkform" id="rmails"></div>
				</td>
			</tr>
			</table>
		</fieldset>
			<?php
				}
				if ($continueUpdateState){
			?>
		<div class="separator10"></div>
		<center>
			<input type="button" class="button" value="Retour" onClick="document.location.href='myaccount.php'"/>
			<input id="update_submit" type="button" class="button" value="Mettre à jour" onClick="validForm(true);"/>
		<center/>
			<?php
				}
			?>
		</form>
		<div class="separator10" style="height:20px;"></div>
			<?php
				if($albumObj->getEtat() == 0){
					echo '<div id="catitle3">Votre album est <u>en attente de téléchargement</u> de photos : (voir les instructions ci-dessous)</div>';
				} else if($albumObj->getEtat() == 1){
					echo '<div id="catitle3">Votre album est <u>en attente de validation</u> par Photomentiel.<br/>Cette action sera effectuée dans les plus brefs délais...</div>';
					echo '<div class="separator10" style="height:50px;"></div>';
				} else if($albumObj->getEtat() == 2){
					echo '<div id="catitle2">Votre album est actuellement ouvert à la vente.&nbsp;&nbsp;<a href="viewalbum.php?al='.StringID::getStringIDDepuisID_Album($albumObj->getAlbumID())->getStringID().'">Aller voir l\'album...</a></div>';
				} else {
					echo '<div id="catitle3">Votre album a été <u>fermé</u>, il n\'est plus accessible à la vente.<br/>les photos seront supprimées sous peu.</div>';
					echo '<div class="separator10" style="height:150px;"></div>';
				}

				if($albumObj->getEtat() == 0){
					if (FTP_MAINTENANCE){
						
					?>
					<div id="dl">
						<span class="warning">Le système de téléchargement FTP est en cours de maintenance. Il sera rétabli dans les plus brefs délais.<br/>
						Veuillez nous excuser pour l'éventuelle gêne occasionnée.</span>
					</div>
					<?php

					} else {
		?>
					<div id="dl">
						Il existe plusieurs moyens de nous faire parvenir vos photos :
						<ol>
							<li>Utiliser <a href="#cftpq" onClick="$('#ftp_help').css('display','inline');return true;">un client FTP quelconque</a>* en vous connectant à cette adresse : <b><?php echo "ftp://".FTP_TRANSFER_IP."</b> sur le port <b>".FTP_PORT; ?></b> avec vos identifiants Photomentiel. Un dossier <b><?php echo $sid; ?></b> a été créé pour vous, vous n'aurez plus qu'à placer vos photos à l'intérieur. (<i>Aucun autre dossier ne doit être créé</i>)</li>
							<li>Utiliser notre client FTP <a href="/pictures/<?php echo $sidObj->getHomePhotographe()."/".$sid; ?>/client.jnlp">en cliquant ici.</a> (<i>Java doit être installé sur votre ordinateur</i>)</li>
							<li>Ou enfin en main propre, sur rendez-vous si vous êtes de la région. <a href="contact.php">Nous contacter...</a></li>
						</ol>
						<br/>
						Si <span class="h">vous avez terminé de télécharger vos photos pour cet album</span>, veuillez cocher la case ci-dessous et valider.<br/>
						<span class="note">Attention, une fois validé, vous ne pourrez plus ajouter de photos. Vous pourrez encore ajouter des mails à contacter jusqu'à la validation de votre album par notre équipe.<br/>
						<span class="warning"><u><b>IMPORTANT</b></u> : Ignorez cette étape si vous ne transférez pas vous même vos photos (cas décrit dans le point 3).</span></span>
					</div>
					<div class="separator10"></div>
					<div id="enddl">
						<form method="POST" action="?action=update&al=<?php echo $sid; ?>" onSubmit="return changeAlbumState();">
							<input type="checkbox" name="cb_gonext" id="cb_gonext"/> J'ai terminé de télécharger mes photos
							<input type="submit" class="button" name="b_gonext" id="b_gonext" value="valider"/>
						</form>
					</div>
					<div class="separator10"></div>
					<div id="ftp_help" id="ftp_help">
						<center><hr/></center>
						<a name="cftpq"></a>*Si vous n'êtes pas familier avec ce genre de client, nous en avons choisi un pour vous dont voici la procédure à suivre pour télécharger vos photos :
						<h4>Téléchargement du client sur votre ordinateur :</h4>
						<ol>
						<li>Télécharger FileZilla à l'adresse suivante : <a target="_blank" href="http://filezilla-project.org/download.php?type=client">http://filezilla-project.org/download.php?type=client</a></li>
						<li>Décompresser ou installer, puis lancer le client.</li>
						<ol>
						<h4>Connexion au server :</h4>
						En haut du client vous trouverez cette barre de connexion :<br/>
						<img src="/design/fztuto/connexion.png"></img><br/>
						Entrer ici les informations suivantes (utilisez votre mot de passe Photomentiel) :
						<ul>
							<li>Hôte : <b><?php echo FTP_TRANSFER_IP; ?></b></li>
							<li>Identifiant : <b><?php echo $utilisateurObj->getEmail(); ?></b></li>
							<li>Mot de passe : <b>*******</b></li>
							<li>Port : <b><?php echo FTP_PORT; ?></b></li>
						</ul><br/>
						Puis cliquez sur le bouton <i><u>Connexion rapide</u></i> (La petite flèche à droite vous permettra de vous reconnecter avec les mêmes informations)
						<h4>Téléchargement de vos fichiers sur les serveurs de Photomentiel</h4>
						Une fois connecté, dirigez vous vers les 4 zones centrales dont voici une illustration :<br/>
						<img src="/design/fztuto/transfert.png"></img><br/>
						Comment ça marche ?
						<ul>
							<li>Zone 1 : Cette zone représente votre disque dur, choisissez ici le dossier contenant vos photos</li>isPubli
							<li>Zone 2 : Une fois votre dossier de photos sélectionné, cette zone affichera toutes les photos qu'il contient</li>
							<li>Zone 3 : Dans cette zone, sélectionnez le dossier de votre album : <i><?php echo $sid; ?></i></li>
							<li>Zone 4 : Cette zone représente votre album chez nous. <br/>Pour nous envoyer vos photos, sélectionnez les dans la zone 2 et faites les glisser vers la zone 4<br/>
							<span class="warning"><u>Attention</u> : pour des raisons de qualité d'impression, seul les <i>jpg</i> et <i>jpeg</i> de plus de 5.5 Million de pixels seront pris en compte</span></li>
						</ul><br/>
						<b>C'est tout ! Il ne vous reste plus qu'à attendre que le client vous notifie de la fin du téléchargement.</b>
					</div>
		<?php		
					}
				} else if($albumObj->getEtat() == 2){
		?>
					<div id="dl">
					Vous pouvez inclure le code suivant dans votre site web personnel afin de créer un lien vers votre album Photomentiel depuis votre site :
					<div class="separator5"></div>
<textarea id="code_web" cols="95" rows="5" readonly="true" style="padding:2px;">
<form method="POST" action="http://www.photomentiel.fr/viewalbum.php">
	Veuillez entrer le code album : 
	<input name="al" type="text" maxlength="<?php echo STRINGID_LENGTH; ?>"/>
	<input type="submit" value="Valider" title="Accéder à l'album" style="width:70px;"/>
</form></textarea>
					<?php
					if ($albumObj->isPublique()){
					?>
					<div class="separator10"></div>
					Ou vous pouvez aussi inclure le lien suivant :
					<div class="separator5"></div>
					<font color="darkblue">http://www.photomentiel.fr/viewalbum.php?al=<?php echo $sid; ?></font>
					<div class="separator10"></div>
					<?php
					}
					?>
					</div>
		<?php
				}
			}
		?>
		<div class="separator10"></div>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
if ($albumSaved){
	echo '<script type="text/javascript">alert("Votre liste de mails a bien été mise à jour");</script>';
}
}catch (Exception $e){
	echo "Internal server error !";
}
?>
