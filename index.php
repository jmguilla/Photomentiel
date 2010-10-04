<?php
/*
 * index.php is the first page on which user arrive
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 24 juil. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
?>
<?php
include("header.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Evenement.class.php");

?>
<script type="text/javascript" src="js/calendar.js"></script>
<script type="text/javascript">
	stringIdLength = "<?php echo STRINGID_LENGTH; ?>";
</script>
<div id="square_left">
	<div id="square_top">
		Vous avez un code album ?
	</div>
	<div id="square_mid">
		<div class="separator5"></div>
		<div id="card">
			<form id="card_form" method="POST" action="viewalbum.php">
				<input name="al" id="flash_border" class="card_code" type="text" maxlength="<?php echo STRINGID_LENGTH; ?>" onChange="$('#tl_error').hide();"/></input>
				<input class="button" id="card_valider" type="submit" value="Valider" title="Accéder à l'album" onClick="return displayAlbum($('#flash_border').val());"></input>
			</form>
			<div id="tl_error">Cet album n'existe pas !</div>
		</div>
	</div>
	<div id="square_bot"></div>
</div>
<div id="square_right">
	<div id="square_top">
		<div id="divsquare_top" style="float:left;">
			Albums publics
		</div>
		<div style="float:right;padding-right:10px;">
			<a class="on_blue" href="albums.php">Rechercher un album...</a>
		</div>
	</div>
	<div id="square_mid">
		<div class="separator2"></div>
		<div id="albums">
			<?php
				$nb_alb = 3;
				$albumsPlus = Album::getNDerniersAlbumsEtImageEtStringID($nb_alb, true, 2);
				if($albumsPlus){
					$i=1;
					foreach($albumsPlus as $tmp){
						if ($i%2==0){
							$idi = 'id="impair"'; 
						} else {
							$idi = '';
						}
						echo '<a '.$idi.' class="last_album" href="viewalbum.php?al='.$tmp["StringID"]->getStringID().'"><div id="album_pic"><img height="37px" src="'.$tmp["Thumb"].'"/></div><div id="album_link"><span id="date">'.date("d/m/Y",strtotime($tmp["Album"]->getDate())).'</span><br/>'.toNchar($tmp["Album"]->getNom(),65).'</div></a>';
						if ($i == $nb_alb) {break;}
						$i++;
					}
				}
			?>
			<div style="width:90%;text-align:right;margin-left:20px;"><a title="Afficher plus d'albums" class="on_blue" href="albums.php">Plus d'albums...</a></div>
			<div class="separator5"></div>
			<?php
					$nb_slide = 6;
					$tmp = Image::getRandomImageThumbPathEtStringID(true,$nb_slide,true,2);
			?>
			<script language="javascript">
				imgTabs = new Array();
				var o;
				<?php
					$i=1;
					foreach($tmp as $assoc){
						echo "o=new Object();";
						echo "o.album_id='".$assoc["StringID"]->getStringID()."';";
						echo "o.thumb='".$assoc["Thumb"]."';";
						echo "imgTabs.push(o);";
						if ($i==$nb_slide){break;}
						$i++;
					}
				?>
			</script>
			<div class="slide_show">
				<?php
					$i=1;
					foreach($tmp as $assoc){
						echo '<a href="viewalbum.php?al='.$assoc["StringID"]->getStringID().'&pic='.substr($assoc["Thumb"],strrpos($assoc["Thumb"],"/")+1).'"><img class="slide_show_image" src="'.$assoc["Thumb"].'"/></a>';
						if ($i==$nb_slide){break;}
						$i++;
					}
				?>
			</div>
		</div>
	</div>
	<div id="square_bot"></div>
</div>
<div class="separator10"></div>
<div id="square_left">
	<div id="square_top">
		<div id="divsquare_top" style="float:left;">
			Evénements
		</div>
		<div style="float:right;padding-right:10px;">
			<a class="on_blue" href="events.php?action=add">Ajouter un événement...</a>
		</div>
	</div>
	<div id="square_mid">
		<div class="separator2"></div>
		<div id="events">
			<div class="separator10"></div>
			<div id="events_height">
				<?php
					$nb_events = 3;
					$eventsPlus = Evenement::getNProchainsEvenements($nb_events);
					if($eventsPlus){
						$i=1;
						foreach($eventsPlus as $event){
							if ($i%2==0){
								$idi = 'id="impair"'; 
							} else {
								$idi = '';
							}
							$date_e = date("d/m/Y à G\hi",strtotime($event->getDate()));
							echo '<a '.$idi.' class="last_event" href="events.php?ev='.$event->getEvenementID().'"><div class="event"><span id="event">Date : '.$date_e.'</span><span id="event">Lieu : '.$event->getVille()->getNom().'('.$event->getDepartement()->getNom().')</span><br/>'.toNchar($event->getDescription(),70).'</div></a>';
							if ($i == $nb_events) {break;}
							$i++;
						}
					}
				?>
			</div>
			<div class="separator10"></div>
			<div style="width:90%;text-align:right;margin-left:20px;"><a title="Afficher plus d'événements" class="on_blue" href="events.php">Plus d'événements...</a></div>
			<div class="separator10" style="height:15px"></div>
			Evénements du <input id="dc_from" class="textfield" type="text" onClick="GetDate(this);" onBlur="destroyCalendarOnOut();" onKeyDown="DestroyCalendar();"></input> 
			( au <input id="dc_to" class="textfield" type="text" onClick="GetDate(this);" onBlur="destroyCalendarOnOut();" onKeyDown="DestroyCalendar();"></input> )
			&nbsp;<input id="search_event" class="button" type="button" value="Chercher" title="Chercher des événements par date" onClick="getEvents();"></input>
		</div>
	</div>
	<div id="square_bot"></div>
</div>
<div id="square_right">
	<div id="square_top">
		En savoir plus...
	</div>
	<div id="square_mid">
		<div id="know_more">
		<div class="separator10"></div>
			<?php
				if (isset($_SESSION['userID'])){
			?>
					<a title="Accéder à mon espace personnel" class="know_more_item" id="my_account" href="myaccount.php"><img height="45px" width="45px" align="middle" src="design/misc/myaccount.png"/><b>Accéder à mon compte</b></a>
			<?php
				} else {
			?>
					<a title="Accéder à l'espace photographe" class="know_more_item" id="photographe" href="photograph.php"><img height="45px" width="45px" align="middle" src="design/misc/photograph.png"/>Vous êtes photographe ?</a>
			<?php
				}
			?>
			<?php $account_text = isset($_SESSION['userID'])?'Modifier mon compte':'<b>Créer un compte</b>'; ?>
			<a title="<?php echo isset($_SESSION['userID'])?'Modifier mon compte':'Créer un compte'; ?>" class="know_more_item" id="create_account" href="adduser.php"><img height="45px" width="45px" align="middle" src="design/misc/<?php echo isset($_SESSION['userID'])?'update_account.png':'create_account.png'; ?>"/><?php echo $account_text; ?></a>
			<a title="Les réponses à toutes vos questions" class="know_more_item" href="faq.php"><img height="45px" width="45px" align="middle" src="design/misc/help.png"/>Foire aux questions</a>
			<a title="Cliquez si vous désirez retirer une photo" class="know_more_item" href="privacy.php"><img height="45px" width="45px" align="middle" src="design/misc/key.png"/>Vie privée et retrait de photos</a>
			<!--<a title="Accéder au forum" class="know_more_item" href="forum.php"><img height="45px" width="45px" align="middle" src="design/misc/chat.png"/>Forum (en construction)</a>-->
		</div>
	</div>
	<div id="square_bot"></div>
</div>
<?php
include("footer.php");
?>
