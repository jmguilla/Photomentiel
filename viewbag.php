<?php
try {
/*
 * viewBag.php displays the content of the bag, format, and number of units per format.
 * One step before validation !
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 3 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
?>
<?php
session_start();
include_once("classes/PMError.class.php");
include_once("classes/modele/Album.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Adresse.class.php");

if (!isSet($_SESSION['albumStringID'])){
	photomentiel_die(new PMError("Aucun album spécifié !","Aucun code album n'a été spécifié, que faites vous là ?"));
}
$albumStringID = $_SESSION['albumStringID'];
//get the corresponding album
$albumObj = Album::getAlbumDepuisID($_SESSION['albumID']);
//get the corresponding photographe
$photographObj = Photographe::getPhotographeDepuisID($_SESSION['photographID']);
$photographeHome = $photographObj->getHome();

if (!isSet($_POST['pics']) && !isSet($_SESSION['pics'])){
	photomentiel_die(new PMError("Aucune photo spécifiée !","Aucune photo n'est présente dans le panier ! Que faites vous là ?"));
}
if (isSet($_POST['pics'])){
	$_SESSION['pics'] = $_POST['pics'];
} else {
	$_POST['pics'] = $_SESSION['pics'];
}
//pathes
$picturesPath = PICTURE_ROOT_DIRECTORY.$photographeHome."/".$albumStringID."/";
$thumbsDir = THUMB_DIRECTORY;
$picsDir = PICTURE_DIRECTORY;
//pictures to be ordered
$pictures = preg_split("[-]",$_POST['pics']);
//photos formats
$photoFormatsDim = $_SESSION['photoFormatsDim'];
$photoFormatsPrice = $_SESSION['photoFormatsPrice'];

if (isset($_SESSION['COMMAND_LINES'])){
	$commandLines = $_SESSION['COMMAND_LINES'];
	$formats = array();
	$quantities = array();
	for ($i=0;$i<sizeof($commandLines);$i++){
		$current = $commandLines[$i];
		$formats[$current['fileName']] = $current['formatID'];
		$quantities[$current['fileName'].$current['formatID']] = $current['quantity'];
	}
}

$HEADER_TITLE = "Visualisation de votre panier";
$HEADER_DESCRIPTION = "Visualisation et gestion de votre panier";
include("header.php");
?>
	<script language="javascript" src="js/thickbox.js"></script>
	<script language="javascript">
		shippingRate = <?php echo SHIPPING_RATE; ?>;
		shippingRateUntil = <?php echo SHIPPING_RATE_UNTIL; ?>;
		albumCookieName = "ptmtl_<?php echo $albumStringID; ?>";
		album_ID = "<?php echo $albumStringID; ?>";
	</script>
	<div id="full_content_top">
		Détails de votre panier
	</div>
	<div id="full_content_mid">
		<div class="path">
			<a href="index.php">Accueil</a> &gt; 
			<a href="javascript:goPrevious();">Album</a> &gt; 
			Panier
		</div>
		<div class="separator10"></div>
		<div id="bag_information">
			<div class="separator5"></div>
			<div id="album_infos">
				<span>Album :</span> <b><?php echo $albumObj->getNom(); ?></b>
				<br/>
				<span>Code :</span> <?php echo $albumStringID; ?>
				<br/>
				<span>Création :</span> <?php echo date("d/m/Y",strtotime($albumObj->getDate())); ?>
				<br/>
				<?php 
				$adresseObj = $photographObj->getAdresse();
				?>
				<span>Photographe :</span> <?php echo $adresseObj->getPrenom()." ".$adresseObj->getNom(); ?>
				<br/>
				<span>Contact :</span> <?php echo ($photographObj->getTelephone()=='')?'Non communiqué':$photographObj->getTelephone(); ?>
				<?php
					if ($albumObj->getID_Evenement() != null && $albumObj->getID_Evenement() != ''){
				?>
					<br/>
					<span>Evènement :</span> <a target="_blank" href="events.php?ev=<?php echo $albumObj->getID_Evenement(); ?>">détails...</a>
				<?php
					}
				?>
				<div class="separator5"></div>
				<span style="font-size:11px;">
					* Frais de port (<?php echo sprintf('%.2f',SHIPPING_RATE); ?>&#8364;), offert à partir de <?php echo SHIPPING_RATE_UNTIL; ?>&#8364; d'achat.
				<span>
			</div>
			<div id="album_formats">
				<u>Formats disponibles et tarifs* :</u>
				<table width="100%" style="margin-top:4px;">
					<?php
						foreach ($photoFormatsPrice as $id => $p) {
							echo "<tr><td>".$photoFormatsDim[$id]." cm</td><td>".sprintf('%.2f',$p)." &#8364;</td></tr>";
						}
					?>
				</table>
			</div>
			<div class="separator2"></div>
		</div>
		<div class="separator10"></div>
		<div id="pictures_content">
			<div id="explainpm">Cliquer sur les boutons <img src="/design/buttons/minus_img.png"></img> et <img src="/design/buttons/plus_img.png"></img> pour retirer ou ajouter des photos.</div>
			<div class="separator2"></div>
			<table cellspacing="0px">
				<tr id="title">
					<th>Référence</th>
					<th>Photo</th>
					<th>Formats et quantités</th>
					<th>Ss Total (&#8364; TTC)</th>
					<th width="140px">Total (&#8364; TTC)</th>
				</tr>
			<?php
				$total_cmd = 0;
				for ($i=0;$i<sizeof($pictures);$i++){
					$imp = ($i%2==0)?'pair':'impair';
					$ref = removeExtension($pictures[$i]);
					//ref + photo
					echo '<tr><td class="'.$imp.'"><u>'.$ref.'</u></td>'.
							 '<td class="'.$imp.'"><a class="thickbox" rel="bag_confirm" title="Agrandir" href="'.$picturesPath.$picsDir.$pictures[$i].'"><img src="'.$picturesPath.$thumbsDir.$pictures[$i].'"></a></td>';
					//format + quantity
					$formatStr = "";
					foreach ($photoFormatsPrice as $id => $p) {
						$text_value = isset($quantities[$pictures[$i].$id])?'value="'.$quantities[$pictures[$i].$id].'"':'';
						$f = $photoFormatsDim[$id];
						$ref_nb = $ref.$f.'nb';
						$formatStr .= '<br/><div class="faq_f">'.$f.'</div><a class="a_minus" title="Retirer 1" href="javascript:" onClick="changePrice(\''.$ref.'\',\''.$f.'\',-'.$p.');"></a><input type="text" class="faq_q" id="'.$ref_nb.'" picture="'.$pictures[$i].'" format="'.$f.'" formatId="'.$id.'" '.$text_value.' disabled="true"></div><a class="a_plus" title="Ajouter 1" href="javascript:" onClick="changePrice(\''.$ref.'\',\''.$f.'\','.$p.');"></a>';
					}
					echo '<td class="'.$imp.'" width="180px">'.(substr($formatStr,5)).'</td>';
					//ss tot
					$sstot_tot = 0;
					$formatStr = "";
					foreach ($photoFormatsPrice as $id => $p) {
						$sstot_price = isset($quantities[$pictures[$i].$id])?$quantities[$pictures[$i].$id]*$photoFormatsPrice[$id]:'0';
						$sstot_tot += $sstot_price;
						$ref_stot = $ref.$photoFormatsDim[$id].'stot';
						$formatStr .= '<br/><span class="span_lh" id="'.$ref_stot.'">'.sprintf('%.2f',$sstot_price).'</span>';
					}
					echo '<td class="'.$imp.'">'.(substr($formatStr,5)).'</td>';
					//tot
					$total_cmd += $sstot_tot;
					$tot_text = ($sstot_tot > 0)?sprintf('%.2f',$sstot_tot):'<img src="design/misc/trash.png"></img><br/><font size="1">Non commandée</font>';
					$ref_tot = $ref.'tot';
					echo '<td class="'.$imp.'"><span class="stot" id="'.$ref_tot.'">'.$tot_text.'</span></td></tr>'."\n";
				}
			?>
				<tr id="total_"><td colspan="3" style="text-align:left;background-color:white;">
					<span style="font-size:11px;color:#333333;">
					* Frais de port (<?php echo sprintf('%.2f',SHIPPING_RATE); ?>&#8364;), offert à partir de <?php echo SHIPPING_RATE_UNTIL; ?>&#8364; d'achat.
					<span>
				</td><td align="right">Total photos :</td><td><span id="total_pic"><?php echo sprintf('%.2f',$total_cmd); ?></span> &#8364;</td></tr>
				<tr id="total_"><td colspan="3" style="background-color:white;"></td><td align="right">Frais de port * :</td><td><span id="shipping_rate"><?php echo sprintf('%.2f',($total_cmd<=SHIPPING_RATE_UNTIL && $total_cmd!=0)?SHIPPING_RATE:0); ?></span> &#8364;</td></tr>
				<tr id="total"><td colspan="3" style="background-color:white;"></td><td align="right">Total :</td><td><span id="total_total"><?php echo sprintf('%.2f',$total_cmd+(($total_cmd<=SHIPPING_RATE_UNTIL && $total_cmd!=0)?SHIPPING_RATE:0)); ?></span> &#8364;</td></tr>
			</table>
		</div>
		<div class="separator10"></div>
		<table width="90%" style="margin:auto;" id="buttons">
			<tr>
				<td><input class="button" type="button" value="&lt;&lt; retour à l'album" onClick="goPrevious();"/></td>
				<td><input style="width:300px" class="button" type="button" value="Confirmer le panier et commander &gt;&gt;" onClick="confirmAndGoNext();"/></td>
			</tr>
		</table>
		<div class="separator10" style="height:20px"></div>
	</div>
	<form id="form_confirmbag" method="POST" action="confirmbag.php">
		<!-- filled by js -->
	</form>
	<form id="form_backToAlbum" method="POST" action="viewalbum.php">
		<input type="hidden" name="al" value="<?php echo $albumStringID; ?>"></input>
		<!-- filled by js -->
	</form>
	<div id="full_content_bot"></div>
	<script language="javascript">
		tb_init('a.thickbox');
	</script>
<?php
include("footer.php");
}catch (Exception $e){
	echo "Internal server error !";
}
?>
