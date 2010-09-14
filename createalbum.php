<?php
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
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Evenement.class.php");
include_once("classes/modele/TaillePapier.class.php");
include_once("classes/modele/PrixTaillePapierAlbum.class.php");

if (!(isset($_SESSION['userClass']) && $_SESSION['userClass'] == 'Photographe')){
	photomentiel_die(new PMError("Accés interdit !","Cet accés est strictement réservé à nos photographes, que faites vous là ?"));
}

include("header.php");

$updateMode = false;
$albumCreated = false;
$albumSaved = false;
if (isset($_GET['action']) && $_GET['action'] == 'update'){
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
	if(isset($_POST['mails']) && $_POST['mails'] != $albumObj->getMailing()){
		$albumObj->setMailing($_POST['mails']);
		$albumObj->save();
		$albumSaved = true;
	}
	//check if state change is demanded
	if (isset($_POST['cb_gonext']) && $albumObj->getEtat() == 0){
		$albumObj->etatSuivant();
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
		if(isset($_POST['mails'])){
			$albToCreate->setMailing($_POST['mails']);
		}
		$i=1;
		while(isset($_POST["$i"])){
			if ($_POST["$i"] != ''){
				$albToCreate->addPrixTaillePapier(new PrixTaillePapierAlbum(-1,$_POST["$i"],$i));
			}
			$i++;
		}
		$albToCreate = $albToCreate->create();
		if (!$albToCreate){
			photomentiel_die(new PMError("L'album n'a pas été créé !","Un problème est survenu lors de la création de l'album, veuillez réessayer ultérieurement."), false);
		}
		$albumObj = $albToCreate;
		$albumCreated = true;
		$updateMode = true;
		$_SESSION['lastCreatedAlbum'] = $albumObj->getAlbumID();
	} else {
		if (!isset($_SESSION['lastCreatedAlbum'])){
			photomentiel_die(new PMError("Erreur lors de la création de l'album !","Une tentative de duplication de l'album a généré un problème."),false);
		}
		$albumCreated = true;
		$updateMode = true;
		$albumObj = Album::getAlbumDepuisID($_SESSION['lastCreatedAlbum']);
	}
}
if ((isset($_GET['action']) && $_GET['action'] == 'update') || isset($_POST['title'])){
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
		<table>
			<tr>
				<td colspan="3" height="30px;">
					<u>Description de l'album :</u>
				</td>
			</tr>
			<tr>
				<td width="180px">
					Intitulé (titre)* : 
				</td><td>
					<input name="title" class="textfield" type="text" id="title" required="required"/>
				</td><td>
					<div class="checkform" id="rtitle"></div>
				</td>
			</tr>
			<tr>
				<td>
					Album public ? : 
				</td><td>
					<input type="radio" id="public1" name="public" value="0" checked="true"/> Non (Album privé)
					<input type="radio" id="public2" name="public" value="1"/> Oui (Album public)
				</td><td>
					<div class="checkform" id="rpublic"></div>
				</td>
			</tr>
			<tr>
				<td colspan="3" height="15px;"></td>
			</tr>
			<tr>
				<td colspan="3" height="84px;">
					<span class="note">Un événement peut être associé à cet album, le spécifier nous permet d'envoyer un E-mail<br/>
					à toutes les personnes qui se sont inscrites à cet événement pour les prévenir de sa disponibilité.<br/>
					Ceci permet d'augmenter le nombre de visites sur cet album.<br/></span>
					<u>S'il existe un événement associé, merci de le sélectionner :</u>
				</td>
			</tr>
			<tr>
				<td>
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
			<tr>
				<td colspan="3" height="15px;"></td>
			</tr>
			<tr>
				<td colspan="3" height="100px;">
					<span class="note">Pour les formats, les prix indiqués sont donnés à titre indicatif et sont, selon nos statistiques, ceux qui offrent<br/>
					les meilleurs rendements. Ils sont aussi les prix minimum que nous acceptons. Certains peuvent vous paraître bas, mais n'oubliez pas que vos clients préférent<br/>
					acheter 5 photos à 6&#8364; plutôt qu'une à 20&#8364;. (les prix ne seront pas modifiables par la suite)</span><br/>
					<u>Sélectionnez les formats de photos qui seront disponibles pour cet album, ainsi que les prix<br/>
					que vous souhaitez leur attribuer :</u><br/>
				</td>
			</tr>
			<tr>
				<td>
					Formats & tarifs : <br/>
					<span class="note2">(Remplissez seulement les formats que vous souhaitez vendre)</span>
				</td><td>
					<table>
						<?php
							$papers = TaillePapier::getTaillePapiers();
							foreach($papers as $paper){
								echo 
								'<tr>
									<td>
										'.$paper->getDimensions().' :&nbsp;
									</td><td class="price">
										<input type="text" class="textfield" regexp="^([0-9]{1,3}|[0-9]{1,3}[.,][0-9]{1,2})$" min="'.$paper->getPrixConseille().'" id="'.$paper->getTaillePapierID().'" name="'.$paper->getTaillePapierID().'"/>&nbsp;&#8364; <span class="prix_conseille">( Prix conseillé : <b>'.$paper->getPrixConseille().' &#8364;</b> )</span>
									</td><td>
										<div class="checkform" style="width:230px;" id="r'.$paper->getTaillePapierID().'"></div>
									</td>
								</tr>';
							}
						?>
					</table>
				</td><td>
					<div class="checkform" id="rfota"></div>
				</td>
			</tr>
			<tr>
				<td colspan="3" height="15px;"></td>
			</tr>
			<tr>
				<td colspan="3" height="66px;">
					<span class="note">La zone suivante vous permet de créer une liste de mails, que vous pouvez remplir ou compléter plus tard.<br/>
					Photomentiel enverra un E-mail à toutes ces personnes dès que l'album sera prêt, ainsi qu'un rappel 2 semaines plus tard.</span><br/>
					<u>Si vous avez déjà une liste de mails, veuillez les insérer maintenant :</u>
				</td>
			</tr>
			<tr>
				<td>
					Liste de mails : <br/>
					<span class="note2">Séparez les mails par des points-virgules '<b>;</b>'</span>
				</td><td>
					<textarea name="mails" cols="55" rows="6" id="mails" regexp="^([^@]+@[^.]+[.][^;]+)([;][^@]+@[^.]+[.][^;]+)*$"></textarea>
				</td><td>
					<div class="checkform" id="rmails"></div>
				</td>
			</tr>
		</table>
		<div class="separator10"></div>
		<center><input type="button" class="button" value="Annuler" onClick="history.back();"/><input id="create_submit" type="button" class="button" value="Créer mon album" onClick="validForm();"/><center/>
		</form>
		<?php
			} else if ($updateMode){
				$sid = StringID::getStringIDDepuisID_Album($albumObj->getAlbumID())->getStringID();
		?>
		<form id="update_form" method="POST" action="?action=update&al=<?php echo $sid ?>">
		<table>
			<tr>
				<td colspan="3" height="30px;">
					<u>Description de l'album :</u>
				</td>
			</tr>
			<tr>
				<td width="180px">
					Intitulé : 
				</td><td colspan="2">
					<?php echo toNchar($albumObj->getNom(),180); ?>
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
			<tr>
				<td colspan="3" height="15px;"></td>
			</tr>
			<tr>
				<td>
					Gain pour le mois : 
				</td><td colspan="2">
					<b><?php echo $albumObj->getBalance(); ?> &#8364;</b>
				</td>
			</tr>
			<tr>
				<td>
					Gain total : 
				</td><td colspan="2">
					<?php echo $albumObj->getGainTotal(); ?> &#8364;
				</td>
			</tr>
			<tr>
				<td colspan="3" height="15px;"></td>
			</tr>
			<?php
				if ($continueUpdateState){
			?>
			<tr>
				<td colspan="3" height="66px;">
					<span class="note">La zone suivante vous permet de gérer votre liste de mails.<br/>
					Photomentiel enverra un E-mail à toutes ces personnes dès que l'album sera prêt, ainsi qu'un rappel 2 semaines plus tard.</span><br/>
					<u>Si vous avez de nouveaux mails, veuillez les insérer maintenant :</u>
				</td>
			</tr>
			<tr>
				<td>
					Liste de mails : <br/>
					<span class="note2">Séparez les mails par des points-virgules '<b>;</b>'</span>
				</td><td>
					<textarea name="mails" cols="55" rows="6" id="mails" regexp="^([^@]+@[^.]+[.][^;]+)([;][^@]+@[^.]+[.][^;]+)*$"><?php echo $albumObj->getMailing(); ?></textarea>
				</td><td>
					<div class="checkform" id="rmails"></div>
				</td>
			</tr>
			<?php
				}
			?>
		</table>
			<?php
				if ($continueUpdateState){
			?>
		<div class="separator10"></div>
		<center><input type="button" class="button" value="Retour" onClick="history.back();"/><input id="update_submit" type="button" class="button" value="Mettre à jour" onClick="validForm(true);"/><center/>
			<?php
				}
			?>
		</form>
		<div class="separator10"></div>
		
			<?php
				if($albumObj->getEtat() == 0){
					echo '<div id="catitle3">Votre album est <u>en attente de téléchargement</u> de photos :</div>';
				} else if($albumObj->getEtat() == 1){
					echo '<div id="catitle3">Votre album est <u>en attente de validation</u> par Photomentiel.<br/>Cette action sera effectuée dans les plus brefs délais...</div>';
					echo '<div class="separator10" style="height:50px;"></div>';
				} else if($albumObj->getEtat() == 2){
					echo '<div id="catitle2">Votre album est actuellement ouvert à la vente.&nbsp;&nbsp;<a href="viewalbum.php?al='.StringID::getStringIDDepuisID_Album($albumObj->getAlbumID())->getStringID().'">Aller voir l\'album...</a></div>';
					echo '<div class="separator10" style="height:150px;"></div>';
				} else {
					echo '<div id="catitle3">Votre album a été <u>fermé</u>, il n\'est plus accessible à la vente.<br/>les photos seront supprimées sous peu.</div>';
					echo '<div class="separator10" style="height:150px;"></div>';
				}

				if($albumObj->getEtat() == 0){
		?>
					là on explique comment télécharger des photos et il faut un bouton pour valider la fin du téléchargement.(checkbox + bouton)<br/>
					Vous avez terminé de télécharger vos photos pour cet album ? Veuillez cocher la case ci-dessous et valider.<br/>
					Attention, une fois validée, vous ne pourrez plus ajouter de photos. Vous pourrez encore ajouter des mails jusqu'à la validation de votre album par nos équipes.
					<div class="separator10"></div>
					<form method="POST" action="?action=update&al=<?php echo $sid; ?>" onSubmit="return changeAlbumState();">
						<input type="checkbox" name="cb_gonext" id="cb_gonext"/> J'ai terminé de télécharger mes photos
						<input type="submit" class="button" name="b_gonext" id="b_gonext" value="valider"/>
					</form>
					<div class="separator10" style="height:50px;"></div>
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
?>
