<?php
/*
 * viewalbum.php is the page that will display an album
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 28 juil. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
?>
<?php
session_start();
ini_set('url_rewriter.tags','');
include_once("classes/PMError.class.php");
include_once("classes/modele/StringID.class.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Utilisateur.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Adresse.class.php");
include_once("classes/modele/TaillePapier.class.php");
include_once("classes/modele/PrixTaillePapierAlbum.class.php");

$get_var_album='al';
$get_var_picture='pic';
$get_var_page='page';
$nb_photos_per_page=60;
$page=1;

//$_GET['autopager']=true if the call comes from autopager (Light content in this case)
$autopager = isset($_GET['autopager']) && $_GET['autopager'] == 'true';
	
//check if a given picture must be displayed at startup
if(isset($_GET[$get_var_picture])){
	$pictureSelected = $_GET[$get_var_picture];
} else {
	$pictureSelected = false;
}
	
if(isset($_GET[$get_var_page])){
	$page=$_GET[$get_var_page];
}
if ((!isSet($_GET[$get_var_album]) || $_GET[$get_var_album] == '') && (!isSet($_POST[$get_var_album]) || $_POST[$get_var_album] == '')){
	photomentiel_die(new PMError("Aucun album spécifié !","Aucun code album n'a été spécifié."));
}
$albumStringID = isSet($_GET[$get_var_album])?$_GET[$get_var_album]:$_POST[$get_var_album];
//get photographe home pictures dir
$sidObj = StringID::getStringIDDepuisID($albumStringID);
if (!$sidObj){
	photomentiel_die(new PMError("Album inexistant !","L'album spécifié n'existe pas ou n'est plus disponible..."));
}
$_SESSION['albumID'] = $sidObj->getID_Album();
$photographeHome = $sidObj->getHomePhotographe();
//build pictures pathes
$picturesPath = PICTURE_ROOT_DIRECTORY.$photographeHome."/".$albumStringID."/";
$thumbsDir = THUMB_DIRECTORY;
$picsDir = PICTURE_DIRECTORY;
//get the corresponding album
$albumObj = Album::getAlbumDepuisID($sidObj->getID_Album());
//check if it is opened
if ($albumObj->getEtat() == 3){
	photomentiel_die(new PMError("L'album a été fermé !","Cet album a été fermé, il n'est plus disponible."));
}
$displayMailing = ($albumObj->getEtat() != 2);
//check if mail must be added
$mailingAdded = 0;
if (isset($_POST['mailing'])){
	if ($albumObj->addMailAMailing($_POST['mailing'])){
		$mailingAdded = 1;
	} else {
		$mailingAdded = 2;
	}
}
//get the corresponding photographe
$_SESSION['photographID'] = $albumObj->getID_Photographe();
$photographObj = Photographe::getPhotographeDepuisID($albumObj->getID_Photographe());
//get photo formats for this album
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
$_SESSION['photoFormatsDim'] = $photoFormatsDim;
$_SESSION['photoFormatsPrice'] = $photoFormatsPrice;
//check picture path exists
if (!is_dir($picturesPath)){
	photomentiel_die(new PMError("Album inexistant !","Le chemin vers cet album n'a pas été trouvé"));
}
//create pictures array or get it in session if exists
if (!$displayMailing) {
	if (!isSet($_SESSION['albumStringID']) || $_SESSION['albumStringID'] != $albumStringID){
		$picsArray = array();
		$scan = opendir($picturesPath.$thumbsDir);
		while ($fileName = readdir($scan)) {
		    if ($fileName != "." && $fileName != "..") {
			array_push($picsArray,$fileName);
		    }
		}
		closedir($scan);
		sort($picsArray);
		$_SESSION['albumStringID'] = $albumStringID;
		$_SESSION['picturesName'] = $picsArray;
	} else {
		//avoid loading several time the same list of pictures in the same session
		$picsArray = $_SESSION['picturesName'];
	}
}

if (!is_numeric($page) || $page<1 || ($nb_photos_per_page*($page-1)>sizeof($picsArray))){
	$page=1;
}


//if a backToAlbum command has been sent, just store the content in session
if (isset($_POST["pictur_0"])){
	//put pictures from POST in SESSION
	$commandLines = array();
	$i=0;
	while (isset($_POST["pictur_$i"])){
		$cl = array('fileName'=>$_POST["pictur_$i"],'formatID'=>$_POST["format_$i"],'quantity'=>$_POST["number_$i"]);
		array_push($commandLines, $cl);
		$i++;
	}
	//$commandLines contains every command lines as it is represented in the session
	$_SESSION['COMMAND_LINES'] = $commandLines;
} else {
	unset($_SESSION['COMMAND_LINES']);
}

if (isset($_SESSION['userID']) && $displayMailing){
	$utilisateurObj = Utilisateur::getUtilisateurDepuisID($_SESSION['userID']);
}

//and display
$HEADER_TITLE = "Visualisation de l'album photo ".$albumStringID;
$HEADER_DESCRIPTION = "Page de visualisation des albums et de selection des photos";
include("head.php");
?>
	<?php
		if (!$displayMailing){
	?>
		<script language="javascript" src="js/jquery.autopager-1.0.0.js"></script>
		<script language="javascript" src="js/thickbox.js"></script>
		<script language="javascript">
			$(function() {
				$.autopager({
					link: 'a[rel=next_page]',
					content: '.album_content'
				});
				initBasket();
			});
			thumbsFullDir = "<?php echo $picturesPath.$thumbsDir; ?>";
			picsFullDir = "<?php echo $picturesPath.$picsDir; ?>";
			albumCookieName = "ptmtl_<?php echo $albumStringID; ?>";
		</script>
	<?php
		}
	?>
	<div id="header_alb"><a href="index.php" title="<?php echo Utils::getFullDomainName(); ?> - Retour accueil"><div id="header_alb_left"><span id="accueil">Accueil</span></div></a><div id="header_alb_right"></div></div>
	<div id="leftpanel">
			<div id="leftpanel_top">
				<span id="album_title"><?php echo $albumObj->getNom(); ?></span>
			</div>
			<div id="leftpanel_mid">
				<div class="separator5"></div>
				<div id="album_infos">
					<span>Code :</span> <?php echo $albumStringID; ?>
					<br/>
					<span>Création :</span> <?php echo date("d/m/Y",strtotime($albumObj->getDate())); ?>
					<br/>
					<?php 
					$adresseObj = $photographObj->getAdresse();
					if($adresseObj){
					?>
					<span>Photographe :</span> <?php echo $adresseObj->getPrenom()." ".$adresseObj->getNom(); ?>
					<?php }else{ ?>
					<span>Photographe :</span> <?php echo $photographObj->getEmail(); ?>	
					<?php } ?>
					<br/>
					<span>Contact :</span> <a href="mailto:<?php echo $photographObj->getEmail(); ?>"> <?php echo $photographObj->getEmail(); ?></a>
					<?php
						if ($albumObj->getID_Evenement() != null && $albumObj->getID_Evenement() != ''){
					?>
						<br/>
						<span>Evénement :</span> <a target="_blank" href="events.php?ev=<?php echo $albumObj->getID_Evenement(); ?>">détails...</a>
					<?php
						}
					?>
				</div>
				<div class="separator10"></div>
				<u>Formats disponibles et tarifs :</u>
				<table width="80%" style="margin-top:4px;">
					<?php
						foreach ($photoFormatsPrice as $id => $p) {
							echo "<tr><td>".$photoFormatsDim[$id]." cm</td><td>".sprintf('%.2f',$p)." &#8364;</td></tr>";
						}
					?>
				</table>
				<div class="separator10"></div>
				<div id="basket_img"><div style="margin-top:3px;"><div id="basket_info">0 photo</div></div></div>
				<div id="basket_info_plus"></div>
				<div class="separator2"></div>
				<div id="basket">
					
				</div>
			</div>
			<div id="leftpanel_bot"></div>
	</div>
	<div id="album_top"></div>
	<?php if (!$displayMailing && !$autopager){ ?>
		<div id="hidden_links" style="visibility:hidden;">
			<?php
				//print link for thick box pictures links and count
				$picsFullDir = $picturesPath.$picsDir;
				$picsSize = sizeof($picsArray);
				for ($i=0;$i<$picsSize;$i++) {
					echo '<a id="hl_'.$i.'" class="thickbox" rel="'.$albumStringID.'" href="'.$picsFullDir.$picsArray[$i].'"></a>';
				}
			?>
		</div>
	<?php } 
	
	if ($displayMailing) {
	?>
	<div id="content_mailing">
		<span>Cet album n'est pas encore disponible.</span><br/>
		<?php
			if ($mailingAdded == 0){
		?>
			<u>Pour être prévenu par mail dès sa publication, veuillez remplir le champ suivant et appuyer sur <i>Valider</i> :</u><br/>
			<br/>
			<form id="form_mailing" method="POST" onSubmit="return mailingCheckMail();">
				Votre E-mail : <input name="mailing" id="mailing" type="text" class="texfield" <?php echo isset($utilisateurObj)?'value="'.$utilisateurObj->getEmail().'"':''; ?> ></input>
				<input id="mailing_submit" type="submit" class="button" value="Valider"></input>
			</form>
		<?php
			} else if ($mailingAdded == 1){
		?>
			Votre Email a été correctement ajouté, vous recevrez un mail dès la publication de cet album.
		<?php
			} else {
		?>
			Vous êtes déjà inscrit à la publication de cet évènement.
		<?php
			}
		?>
		<br/><br/>
		<input type="button" class="button" value="Retourner à l'accueil" onClick="document.location.href='index.php';"/>
	</div>
	<?php
	} else {
	?>
	<div class="album_content">
		<?php
		$picsFullDir = $picturesPath.$picsDir;
		$thumbsFullDir = $picturesPath.$thumbsDir;
		$addto_i = $nb_photos_per_page*($page-1);
		$picsSize = sizeof($picsArray);
		for ($i=0;$i<$nb_photos_per_page && ($i+$addto_i)<$picsSize;$i++) {
			$addi = $i+$addto_i;
			echo 
				'<div class="td">
					<div class="td_in">
						<a title="Agrandir" href="'.$picsFullDir.$picsArray[$addi].'" onClick="$(\'#hl_'.$addi.'\').displayHidden();return false;"><img src="'.$thumbsFullDir.$picsArray[$addi].'"/></a>
					</div>
					<table width="100%"><tr><td align="center">
						<a class="a_basket" title="ajouter au panier" href="javascript:addToBasket(\''.$picsArray[$addi].'\');"/>
						</td><td>
						<a class="a_zoom" title="Agrandir" href="'.$picsFullDir.$picsArray[$addi].'" onClick="$(\'#hl_'.$addi.'\').displayHidden();return false;"/>
						</td></tr></table>
				</div>';
		}
		?>
		<span id='end_section'></span>
	</div>
	<?php if ($nb_photos_per_page*$page<=sizeof($picsArray)) { ?>
		<a href="<?php echo 'viewalbum.php?autopager=true&'.$get_var_album.'='.$albumStringID.'&'.$get_var_page.'='.($page+1); ?>" rel="next_page"></a>
 	<?php } ?>
	<?php if ($pictureSelected) { ?>
		<script language="javascript">
			$(document).ready(function(){$("a[id^='hl_'][href*='<?php echo $pictureSelected; ?>']").displayHidden();});
		</script>
 	<?php } ?>
 	<form id="form_viewbag" method="POST" action="viewbag.php">
 		<input id="form_input" type="hidden" name="pics"/>
 	</form>
 	<?php
	}
	?>
  </body>
</html>
