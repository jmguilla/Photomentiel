<?php
try {
/*
 * albums.php is the page that displays every public album with search options
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 13 août 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
$HEADER_TITLE = "Visualisation des albums photos";
$HEADER_DESCRIPTION = "Page de recherche et visualisation des albums";
include("header.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Evenement.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Adresse.class.php");
?>
<script type="text/javascript" src="js/calendar.js"></script>
<div id="full_content_top">
		Liste des albums publics
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a> &gt; 
		Albums publics
	</div>
	<div class="separator10"></div>
	<div id="albums_content">
		<div id="search">
			<div id="stitle">Chercher des Albums :</div>
			<form onSubmit="getAlbums();return false;">
				Par dates :
				du <input id="dc_from" class="textfield" type="text" onClick="GetDate(this,false);" onBlur="destroyCalendarOnOut();" onKeyDown="DestroyCalendar();" onFocus="this.select()"/>
				( au <input id="dc_to" class="textfield" type="text" onClick="GetDate(this,false);" onBlur="destroyCalendarOnOut();" onKeyDown="DestroyCalendar();" onFocus="this.select()"/> )
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Par mots-clés :
				<input id="keywords" class="textfield" type="textfield" onFocus="this.select()"/>
				<input id="search" class="button" type="submit" value="Chercher" title="Chercher des albums avec les critères sélectionnés" />
			</form>
		</div>
		<div class="separator5"></div>
		<div id="right">
			<div id="rtitle">Voici les derniers albums publics déposés :</div>
			<div id="rcontent">
				<?php
					$nb_alb = 10;
					$albumsPlus = Album::getNDerniersAlbumsEtImageEtStringID($nb_alb, true, 2);
					if($albumsPlus){
						$i=1;
						foreach($albumsPlus as $tmp){
							$event = Evenement::getEvenementDepuisID($tmp["Album"]->getID_Evenement());
							$photograph = Photographe::getPhotographeDepuisID($tmp["Album"]->getID_Photographe());
							$adress = $photograph->getAdresse();
							if ($i%2==0){
								$idi = 'id="impair"';
							} else {
								$idi = '';
							}
							echo '<div '.$idi.' class="album">';
							echo '<div class="album_pic"><a href="viewalbum.php?al='.$tmp["StringID"]->getStringID().'"><img src="'.$tmp["Thumb"].'"/></a></div>';
							echo '<div class="album_link">';
							echo '<span class="date"><b>Date</b> : '.date("d/m/Y",strtotime($tmp["Album"]->getDate())).'</span><br/><span class="content">';
							echo '<span class="intitule"><a class="intitule" href="viewalbum.php?al='.$tmp["StringID"]->getStringID().'"><b>Intitulé</b> : '.toNchar($tmp["Album"]->getNom(),110).'</a></span><br/>';
							echo '<b>Photographe</b> : '.$adress->getPrenom().' '.$adress->getNom().'<br/>';
							echo '<b>Contact</b> : '.(($photograph->getTelephone()=='' || !$photograph->isTelephonePublique())?'Non communiqué':$photograph->getTelephone()).'<br/>';
							if ($event) {
								echo '<b>Evénement</b> : <a href="events.php?ev='.$tmp["Album"]->getID_Evenement().'">'.$event->getDescription().'</a><br/>';
							}
							echo '</span></div></div>';
							if ($i == $nb_alb) {break;}
							$i++;
						}
					}
				?>
			</div>
		</div>
		<div class="separator10"></div>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
}catch (Exception $e){
	echo "Internal server error !";
}
?>

