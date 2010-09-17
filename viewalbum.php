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
if (!isSet($_GET[$get_var_album]) || $_GET[$get_var_album] == ''){
	photomentiel_die(new PMError("Aucun album spécifié !","Aucun code album n'a été spécifié, que faites vous là ?"));
}
$albumStringID = $_GET[$get_var_album];
//get photographe home pictures dir
$sidObj = StringID::getStringIDDepuisID($albumStringID);
if (!$sidObj){
	photomentiel_die(new PMError("Album inexistant !","L'album spécifié n'existe pas ou plus..."));
}
$_SESSION['albumID'] = $sidObj->getID_Album();
$photographeHome = $sidObj->getHomePhotographe();
//build pictures pathes
$picturesPath = PICTURE_ROOT_DIRECTORY.$photographeHome."/".$albumStringID."/";
$thumbsDir = THUMB_DIRECTORY;
$picsDir = PICTURE_DIRECTORY;
//get the corresponding album
$albumObj = Album::getAlbumDepuisID($sidObj->getID_Album());
$_SESSION['photographID'] = $albumObj->getID_Photographe();
//get the corresponding photographe
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

if (!is_numeric($page) || $page<1 || ($nb_photos_per_page*($page-1)>sizeof($picsArray))){
	$page=1;
}

include("head.php");
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
	<div id="header_alb"><a href="index.php" title="<?php echo Utils::getFullDomainName(); ?> - Retour accueil"><div id="header_alb_left"><span id="accueil">Accueil</span></div></a><div id="header_alb_right"></div></div>
	<div id="leftpanel">
			<div id="leftpanel_top">
				<span id="album_title"><?php echo $albumObj->getNom(); ?></span>
			</div>
			<div id="leftpanel_mid">
				<div class="separator5"></div>
				<div id="album_infos">
					<span>Date :</span> <?php echo date("d/m/Y",strtotime($albumObj->getDate())); ?>
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
					<br/>
					<span>Evènement :</span> <a target="_blank" href="events.php?ev=<?php echo $albumObj->getID_Evenement(); ?>">détails...</a>
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
	<?php if (!$autopager){ ?>
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
	<?php } ?>
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
  </body>
</html>
