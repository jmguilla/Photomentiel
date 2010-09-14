<?php
/*
 * myaccount.php is the file that is in charge to display the user account
 * it displays its albums, events, what he can do and its balance, etc...
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 15 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
include("header.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Utilisateur.class.php");
include_once ("classes/modele/EvenementEcouteur.class.php");
include_once ("classes/modele/Commande.class.php");

if ($utilisateurObj){
	$photographMode = $_SESSION['userClass'] == 'Photographe';
}

$accountRemoved = false;
if ($utilisateurObj && isset($_GET['action']) && $_GET['action']=='remove'){
	//TODO remove account
	unset($_SESSION['userID']);
	unset($_SESSION['userClass']);
	$utilisateurObj = false;
	$accountRemoved = true;
}

?>
<div id="full_content_top">
		Gestion de mon compte
</div>
<div id="full_content_mid">
	<div class="separator10"></div>
	<div id="content">
		<?php
			if (!$utilisateurObj) {
				/**************************** DISCONNECTED USER  ****************************/
		?>
			<div id="content_unknown">
				<div class="separator10" style="height:150px;"></div>
				<?php
					if ($accountRemoved){
						/*************** DELETED USER *******************************/
				?>
						Votre compte a été supprimé avec succés.<br/>
						A bientôt sur <?php echo AUTHOR; ?>...
				<?php
					} else {	
				?>
						Vous devez vous connecter pour accéder à votre compte.<br/>
						Pour vous connecter, veuillez remplir les champs ci-dessus et valider.
				<?php
					}
				?>
				<div class="separator10" style="height:150px;"></div>
			</div>
		<?php
			} else {
				/***************************** CONNECTED USER  ******************************/
		?>
			<div id="left">
				<?php
					if($photographMode) {
					//TODO afficher fin de quota au lieu du bouton si quota épuisé.
				?>
				<input id="create_album" type="button" class="button" value="Créer un nouvel album" onClick="document.location.href='createalbum.php';"/><br>
				<br/>
				<!--<div id="quotas">
					si pas de SIREN<br/>
					quota utilisé : 123/500 &#8364;<br/>
					Albums restants : 2<br/>ou<br/>quota épuisé et plus de bouton
				</div>-->
				<?php
					}
				?>
				<input id="accueil" type="button" class="button" value="Accueil" onClick="document.location.href='index.php';"/><br>
				<input id="view_albums" type="button" class="button" value="Voir les albums publics" onClick="document.location.href='albums.php';"/><br>
				<input id="view_events" type="button" class="button" value="Voir les évènements" onClick="document.location.href='events.php';"/><br>
				<input id="update_account" type="button" class="button" value="Modifier mon compte" onClick="document.location.href='adduser.php';"/><br>
			</div>
			<div id="right">
				<div class="content_box">
					<div class="title">Les événements qui m'intéressent :</div>
					<div class="content_flow">
						<?php
							/***************************** USER EVENTS  ******************************/
							$events = EvenementEcouteur::getEvenementsAVenirDepuisID_Utilisateur($utilisateurObj->getUtilisateurID());
							if ($events) {
								$i=0;
								foreach($events as $evt){
									if ($i%2==0){
										$idi = 'id="impair"'; 
									} else {
										$idi = '';
									}
									$date_e = date("d/m/Y à G\hi",strtotime($evt->getDate()));
									echo '<a '.$idi.' class="event" href="events.php?ev='.$evt->getEvenementID().'"><div class="event"><span id="event" style="margin-left:0px;">Date : '.$date_e.'</span><span id="event">Lieu : '.$evt->getVille()->getNom().'('.$evt->getDepartement()->getNom().')</span><br/>'.toNchar($evt->getDescription(),90).'</div></a>';
									$i++;
								}
							} else {
						?>
							<table>
								<tr><td>Aucun événement enregistré</td></tr>
							</table>
						<?php
							}
						?>
					</div>
				</div>
				<div class="content_box">
					<div class="title">Mes commandes en cours :</div>
					<div class="content_flow">
						<?php
							/***************************** USER COMMANDS  ******************************/
							$commandes = Commande::getCommandesEtPhotosDepuisID_Utilisateur($utilisateurObj->getUtilisateurID());
							if($commandes){
								$i=1;
								foreach($commandes as $commande){
									$price = 0;
									$nb = 0;
									foreach($commande->getCommandesPhoto() as $cmd){
										$price += $cmd->getPrix();
										$nb += $cmd->getNombre();
									}
									$price += $commande->getFDP();
									if ($i%2==0){
										$idi = 'id="impair"'; 
									} else {
										$idi = '';
									}
									$date_e = date("d/m/Y à G\hi",strtotime($commande->getDate()));
									if ($commande->getEtat() == 0){
										$cmdst = "<b>".$COMMAND_STATES[$commande->getEtat()]."</b> (vous pouvez <u>payer</u> ou <u>supprimer</u> cette commande)";
									} else if ($commande->getEtat() == 4){
										$cmdst = "<b>".$COMMAND_STATES[$commande->getEtat()]."</b> (vous pouvez <u>supprimer</u> cette commande)";
									} else {
										$cmdst = "<b>".$COMMAND_STATES[$commande->getEtat()]."</b>";
									}
									echo '<a '.$idi.' class="event" href="viewcommand.php?cmd='.$commande->getCommandeID().'"><div class="event"><span id="date">Date : '.$date_e.'</span><span id="event">Etat : '.$cmdst.'</span><br/>Nombre de photos : '.$nb.' - Prix Total : '.$price.' &#8364;</div></a>';
									$i++;
								}
							} else {
						?>
							<table>
								<tr><td>Aucune commande en cours</td></tr>
							</table>
						<?php
							}
						?>
					</div>
				</div>
				<div class="content_box" <?php echo ($photographMode)?'':'style="border:0px"'; ?>>
				<?php
					if($photographMode) {
						/***************************** PHOTOGRAPH ALBUMS  ******************************/
				?>
					<div class="title">Mes albums :</div>
					<div class="content_flow">
						<?php
							$albums = Album::getAlbumEtImageEtStringIDDepuisID_Photographe($utilisateurObj->getPhotographeID(), false);
							if ($albums) {
								$i=1;
								foreach($albums as $alb){
									if ($i%2==0){
										$idi = 'id="impair"'; 
									} else {
										$idi = '';
									}
									if ($alb["Album"]->getEtat() == 0){
										$albst = '<b>'.$ALBUM_STATES[$alb["Album"]->getEtat()].'</b> (en attente du transfert des photos)';
									} else if($alb["Album"]->getEtat() == 1){
										$albst = '<b>'.$ALBUM_STATES[$alb["Album"]->getEtat()].'</b> (en attente de validation par Photomentiel)';
									} else {
										$albst = '<b>'.$ALBUM_STATES[$alb["Album"]->getEtat()].'</b> - Gain total : <b>'.$alb["Album"]->getBalance().' &#8364</b>';
									}
									echo '<a '.$idi.' class="album" href="createalbum.php?action=update&al='.$alb["StringID"]->getStringID().'"><div id="album_pic"><img height="38px" src="'.$alb["Thumb"].'"/></div><div id="album_link"><span id="date">'.date("d/m/Y",strtotime($alb["Album"]->getDate())).' - Etat : '.$albst.'</span><br/>'.toNchar($alb["Album"]->getNom(),90).'</div></a>';
									$i++;
								}
							} else {
						?>
							<table>
								<tr><td>Vous n'avez pas encore d'album</td></tr>
							</table>
						<?php
							}
						?>
					</div>
				<?php
					}
				?>
				</div>
			</div>
			<div class="separator2"></div>
		<?php
			}
		?>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
?>
