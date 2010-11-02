<?php
try {
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
$HEADER_TITLE = "Votre compte";
$HEADER_DESCRIPTION = "Page de gestion de votre compte";
include("header.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Utilisateur.class.php");
include_once ("classes/modele/EvenementEcouteur.class.php");
include_once ("classes/modele/Commande.class.php");

if ($utilisateurObj){
	$photographMode = $_SESSION['userClass'] === 'Photographe';
}

if ($utilisateurObj && $photographMode && isset($_POST['pcontrat'])){
	$utilisateurObj->validContrat();
}

$accountRemoved = false;
if ($utilisateurObj && isset($_GET['action']) && $_GET['action']==='remove'){
	//T O D O remove account
	/*unset($_SESSION['userID']);
	unset($_SESSION['userClass']);
	$utilisateurObj = false;
	$accountRemoved = true;*/
}

?>
<div id="full_content_top">
		Gestion de mon compte
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a> &gt; 
		Mon compte
	</div>
	<div class="separator10"></div>
	<div id="content">
		<?php
			if ($utilisateurObj && !$utilisateurObj->isReady()){
				/***************************** DISPLAY CONTRACT *****************************/
				echo '<div class="contr">Afin de pouvoir utiliser votre compte, vous devez avoir pris connaissance et accepter le présent contrat :</div>';
				echo '<div id="p_contrat">';
				include("contratPhotographe.php");
				echo '</div>';
				echo '<form method="POST" action="myaccount.php" onSubmit="return checkContrat();">';
				echo '<div id="finalize"><br/><input id="pcontrat" name="pcontrat" type="checkbox"></input> En cochant la case ci-contre et en finalisant mon inscription, je déclare ("le Photographe" désigné dans le présent contrat) avoir pris connaissance et accepte de manière inconditionnelle le présent contrat d\'utilisation des services fournis par Photomentiel au travers de son site internet <span class="photomentiel">www.photomentiel.fr</span></div><br/>';
				echo '<center><input class="button" type="submit" value="Finaliser mon inscription" style="width:220px;"></input></center>';
				echo '</form>';
			} else {

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
						if (FTP_MAINTENANCE){
				?>
						<div class="left_c_indispo">
							La création d'album est temporairement indisponible. Elle sera rétablie dans les plus brefs délais.
						</div>
						
				<?php
						} else {
				?>
						<div class="left_c" onClick="document.location.href='createalbum.php';">
							Créer un nouvel album
						</div>
				<?php
						}
				?>
				<div style="border:1px orange solid;"></div>
				<?php
					}
				?>
				<div class="left_c" onClick="document.location.href='index.php';">
					Accueil
				</div>
				<div class="left_c" onClick="document.location.href='adduser.php?np=myaccount.php';">
					Modifier mon compte
				</div>
				<div class="left_c" onClick="document.location.href='contact.php';">
					Nous contacter
				</div>
				<div style="border:1px orange solid;"></div>
				<div class="left_c" onClick="document.location.href='albums.php';">
					Voir les albums publics
				</div>
				<div class="left_c" onClick="document.location.href='events.php';">
					Voir les événements
				</div>
			</div>
			<div id="right">
				<div class="content_box" <?php echo $photographMode?'style="height:140px;"':'style="height:230px;"'; ?>>
					<div class="title">Les événements qui m'intéressent [<span id="displayEventsNb">0</span>] :</div>
					<div class="content_flow" <?php echo $photographMode?'style="height:115px;"':'style="height:205px;"'; ?>>
						<?php
							/***************************** USER EVENTS  ******************************/
							$events = EvenementEcouteur::getEvenementsAVenirDepuisID_Utilisateur($utilisateurObj->getUtilisateurID());
							$eventsNb=0;
							if ($events) {
								foreach($events as $evt){
									if ($eventsNb%2==0){
										$idi = 'id="impair"'; 
									} else {
										$idi = '';
									}
									$date_e = date("d/m/Y à G\hi",strtotime($evt->getDate()));
									echo '<a '.$idi.' class="event" href="events.php?ev='.$evt->getEvenementID().'"><div class="event"><span id="event" style="margin-left:0px;">Date : '.$date_e.'</span><span id="event">Lieu : '.$evt->getVille()->getNom().'('.$evt->getDepartement()->getNom().')</span><br/>'.toNchar($evt->getDescription(),90).'</div></a>';
									$eventsNb++;
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
				<div class="content_box" <?php echo $photographMode?'style="height:140px;"':'style="height:230px;"'; ?>>
					<div class="title">Mes commandes [<span id="displayCommandsNb">0</span>] :</div>
					<div class="content_flow" <?php echo $photographMode?'style="height:115px;"':'style="height:205px;"'; ?>>
						<?php
							/***************************** USER COMMANDS  ******************************/
							$commandes = Commande::getCommandesEtPhotosDepuisID_Utilisateur($utilisateurObj->getUtilisateurID());
							$commandsNb=0;
							if($commandes){
								foreach($commandes as $commande){
									$price = 0;
									$nb = 0;
									foreach($commande->getCommandesPhoto() as $cmd){
										$price += $cmd->getPrix();
										$nb += $cmd->getNombre();
									}
									$price += $commande->getFDP();
									if ($commandsNb%2==0){
										$idi = '';
									} else {
										$idi = 'id="impair"'; 
									}
									$date_e = date("d/m/Y à G\hi",strtotime($commande->getDate()));
									if ($commande->getEtat() == 0){
										$cmdst = "<b>".$COMMAND_STATES[$commande->getEtat()]."</b> (vous pouvez <u>payer</u> ou <u>supprimer</u> cette commande)";
									} else if ($commande->getEtat() == 4){
										$cmdst = "<b>".$COMMAND_STATES[$commande->getEtat()]."</b> (vous pouvez <u>supprimer</u> cette commande)";
									} else {
										$cmdst = "<b>".$COMMAND_STATES[$commande->getEtat()]."</b>";
									}
									echo '<a '.$idi.' class="event" href="viewcommand.php?cmd='.$commande->getCommandeID().'"><div class="event"><span id="date">Date : '.$date_e.'</span><span id="event">Etat : '.$cmdst.'</span><br/>Nombre de photos : '.$nb.' - Prix Total : '.sprintf('%.2f',$price).' &#8364;</div></a>';
									$commandsNb++;
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
				<?php
					if($photographMode) {
						/***************************** PHOTOGRAPH ALBUMS  ******************************/
				?>
				<div class="content_box" style="height:280px;">
					<div class="title" style="text-decoration:none;"><u>Mes albums [<span id="displayAlbumsNb">0</span>] :</u> <span id="displayFullGain"></span></div>
					<div class="content_flow" style="height:255px;">
						<?php
							$albums = Album::getAlbumEtImageEtStringIDDepuisID_Photographe($utilisateurObj->getPhotographeID(), false);
							$albumsNb=0;
							if ($albums) {
								$total_a = 0;
								$total_m = 0;
								foreach($albums as $alb){
									if ($albumsNb%2==0){
										$idi = '';
									} else {
										$idi = 'id="impair"'; 
									}
									if ($alb["Album"]->getEtat() == 0){
										$albst = '<b>'.$ALBUM_STATES[$alb["Album"]->getEtat()].'</b> (en attente du transfert des photos)';
									} else if($alb["Album"]->getEtat() == 1){
										$albst = '<b>'.$ALBUM_STATES[$alb["Album"]->getEtat()].'</b> (en attente de validation par Photomentiel)';
									} else {
										$total_a += $alb["Album"]->getGainTotal();
										$total_m += $alb["Album"]->getBalance();
										$albst = '<b>'.$ALBUM_STATES[$alb["Album"]->getEtat()].'</b> - Gain mensuel : <b>'.$alb["Album"]->getBalance().' &#8364</b>';
									}
									if ($alb["Thumb"]){
										$picThumb = $alb["Thumb"];
									} else {
										$picThumb = "/design/misc/waiting.png";
									}
									echo '<a '.$idi.' class="album" href="createalbum.php?action=update&al='.$alb["StringID"]->getStringID().'"><div id="album_pic"><img src="'.$picThumb.'"/></div><div id="album_link"><span id="date">'.date("d/m/Y",strtotime($alb["Album"]->getDate())).' - Code : <b>'.$alb["StringID"]->getStringID().'</b> - Etat : '.$albst.'</span><br/>'.toNchar($alb["Album"]->getNom(),90).'</div></a>';
									$albumsNb++;
								}
								?>
								<script language="javascript">
									$("#displayEventsNb").html("<?php echo $eventsNb; ?>");
									$("#displayCommandsNb").html("<?php echo $commandsNb; ?>");
									$("#displayAlbumsNb").html("<?php echo $albumsNb; ?>");
									$("#displayFullGain").html("(Gain mensuel : <b><?php echo sprintf('%.2f',$total_m); ?> &#8364</b> - Gain total : <b><?php echo sprintf('%.2f',$total_a); ?> &#8364</b>)");
								</script>
								<?php
							} else {
						?>
							<table>
								<tr><td>Vous n'avez pas encore d'album</td></tr>
							</table>
						<?php
							}
						?>
					</div>
				</div>
				<?php
					}
				?>
			</div>
			<div class="separator2"></div>
		<?php
			}
		}
		?>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
}catch (Exception $e){
	echo "Internal server error !";
}
?>
