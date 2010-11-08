<?php
/*
 * myaccount_default.php is the default content of myaccount.
 * it displays its albums, events, what he can do and its balance, etc...
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 15 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
if (!isset($MYACCOUNT_PHP)){
	echo "Invalid request";
	exit;
}
?>
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
				?>
				<script language="javascript">
					$("#displayEventsNb").html("<?php echo $eventsNb; ?>");
				</script>
				<?php
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
				?>
				<script language="javascript">
					$("#displayCommandsNb").html("<?php echo $commandsNb; ?>");
				</script>
				<?php
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
					$("#displayAlbumsNb").html("<?php echo $albumsNb; ?>");
					$("#displayFullGain").html("(Gain mensuel total : <b><?php echo sprintf('%.2f',$total_m); ?> &#8364</b> - Gain total : <b><?php echo sprintf('%.2f',$total_a); ?> &#8364</b>)");
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

