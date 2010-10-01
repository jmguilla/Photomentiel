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
include_once("classes/PMError.class.php");
include_once("classes/modele/Commande.class.php");
include_once("classes/modele/CommandePhoto.class.php");
include_once("classes/modele/TaillePapier.class.php");
include_once("classes/modele/PrixTaillePapierAlbum.class.php");
include_once("classes/modele/TransactionID.class.php");
include_once("classes/modele/Album.class.php");
include("header.php");

if (!$utilisateurObj){
	photomentiel_die(new PMError("Utilisateur non connecté","Vous n'êtes pas connecté, pour accéder à votre commande, veuillez vous connecter en utilisant les champs ci-dessus."),false);
}
if (isset($_GET['cmd'])){
	$commandObj = Commande::getCommandeDepuisID($_GET['cmd']);
	if (!$commandObj || $commandObj->getID_Utilisateur() != $utilisateurObj->getUtilisateurID()){
		photomentiel_die(new PMError("Commande inapropriée","Cette commande ne vous appartient pas, que faites vous là ?"),false);
	}
} else {
	photomentiel_die(new PMError("Aucune commande spécifiée","Aucune commande n'a été spécifiée, que faites vous là ?"),false);
}

if ($utilisateurObj && $commandObj){
	$commandLines = array();
	$lines = CommandePhoto::getCommandePhotosDepuisID_Commande($commandObj->getCommandeID());
	foreach($lines as $line){
		$cl = array('fileName'=>$line->getPhoto(),
					'formatID'=>$line->getID_TaillePapier(),
					'quantity'=>$line->getNombre(),
					'total'=>$line->getPrix());
		array_push($commandLines,$cl);
	}
	$fdp = $commandObj->getFDP();
	//get photo formats for this album
	$tmp = TaillePapier::getTaillePapiers();
	$photoFormatsDim = array();
	foreach($tmp as $tp){
		$photoFormatsDim[$tp->getTaillePapierID()] = $tp->getDimensions();
	}
}


?>
<div id="full_content_top">
		Détails de votre commande n°<?php echo $commandObj->getNumero(); ?>
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a> &gt; 
		<a href="myaccount.php">Mon compte</a> &gt; 
		Commande n°<?php echo $commandObj->getNumero(); ?>
	</div>
	<div id="pictures_content">
		<div class="separator10"></div>
		<div class="recap">Voici le récapitulatif de votre commande :</div>
		<table cellspacing="0px">
			<tr id="title">
				<th>Référence</th>
				<th>Format</th>
				<th>Quantité</th>
				<th>Total (&#8364; TTC)</th>
			</tr>
		<?php
			$total = 0;
			$nb_photos = 0;
			for ($i=0;$i<sizeof($commandLines);$i++){
				$current = $commandLines[$i];
				$imp = ($i%2==0)?'pair':'impair';
				echo '<tr>';
				//ref
				echo '<td class="'.$imp.'">'.removeExtension($current['fileName']).'</td>';
				//format
				echo '<td class="'.$imp.'">'.$photoFormatsDim[$current['formatID']].'</td>';
				//quantity
				echo '<td class="'.$imp.'">'.$current['quantity'].'</td>';
				$nb_photos += $current['quantity'];
				//total
				$total += $current['total'];
				echo '<td class="'.$imp.'">'.sprintf('%.2f',$current['total']).' &#8364;</td>';
				echo '</tr>';
			}
			echo '<tr id="total_"><td style="background-color:white;"></td><td align="right">Total photos :</td><td>'.$nb_photos.'</td><td>'.sprintf('%.2f',$total).' &#8364;</td></tr>';
			if ($fdp != 0){
				$ship_rate = sprintf('%.2f',$fdp).' &#8364';
				$total += $fdp;
			} else {
				$ship_rate = '<span style="color:darkgreen;text-decoration:underline;">Offert !</span>';
			}
			echo '<tr id="total_"><td colspan="2" style="background-color:white;"></td><td align="right">Frais de port :</td><td>'.$ship_rate.'</td></tr>';
			echo '<tr id="total"><td colspan="2" style="background-color:white;"></td><td align="right">Total :</td><td>'.sprintf('%.2f',$total).' &#8364;</td></tr>';
		?>
		</table>
		<div class="separator10"></div>
	</div>
	<div id="adresses_content">
		<div id="make_cmd">
			<div class="separator10"></div>
			<div class="recap_info">
				<div id="b1">
					Vous avez commandé <i><b><?php echo $nb_photos; ?> photo<?php echo $nb_photos==1?'':'s'; ?></i></b> pour un total de 
					<i><b><?php echo sprintf('%.2f',$total); ?> &#8364;</i></b>.
				</div>
				<br/>
				<div id="b1">
					Vos photos vous <?php echo ($commandObj->getEtat() == 4)?'ont été':'seront'; ?> livrées à l'adresse suivante : <br/><br/>
					<div class="adr_b" style="font-size:14px;">
						<?php
							$adresseObj = $commandObj->getAdresse();
							echo $adresseObj->getNom()." ".$adresseObj->getPrenom()."<br/>";
							echo $adresseObj->getNomRue()."<br/>";
							if ($adresseObj->getComplement() != null && $adresseObj->getComplement() != ''){
								echo $adresseObj->getComplement()."<br/>";
							}
							echo $adresseObj->getCodePostal()." ".$adresseObj->getVille()."<br/>";
							echo 'France';
						?>
					</div>
				</div>
				<br/>
				<?php
					$etatc = $commandObj->getEtat();
					if ($etatc == 0){
						?>
						<div id="b1" style="border:2px darkred solid;">
							<span id="not_payed">Vous n'avez pas encore payé cette commande, vous pouvez :</span><br/><br/>
							<ul>
							<li>La payer en choisissant un moyen de paiement (<i>ceci vous conduira sur la page sécurisée de paiement</i><img src="e-transactions/payment/logo/CLEF.gif"/>) <br/><br/>
							<?php
								$_SESSION['last_command'] = $commandObj->getCommandeID();
								include("e-transactions/selectcard.php");
								$albumObj = Album::getAlbumDepuisID($commandObj->getID_Album());
								displayCards($albumObj->getModule(),toBankAmount($total),sprintf("%06d",TransactionID::get()),$utilisateurObj->getUtilisateurID(),$commandObj->getCommandeID());
							?>
							<br/>
							<li>Ou <a href="javascript:deleteCommand(<?php echo $commandObj->getCommandeID(); ?>);">Supprimer cette commande</a> si elle ne vous semble plus utile</li>
							</ul>
						</div>
							<div class="separator10"></div>
						<?php
					} else {
						?>
							<div class="state_ok" id="cmd1">
								<span class="state_title_ok">Votre paiement a été validé</span> 
								(<?php if($etatc == 1){echo 'La commande sera préparée sous peu - ';} ?><a target="_blank"  href="facture-<?php echo $commandObj->getCommandeID(); ?>.pdf">Télécharger ma facture</a>)
							</div>
							<div class="<?php echo ($etatc >= 2)?'state_ok':'state'; ?>" id="cmd2">
								<span class="<?php echo ($etatc >= 2)?'state_title_ok':'state_title'; ?>">Votre commande est en cours de préparation</span>
								<?php if($etatc == 2){echo '(Les photos seront imprimées sous peu)';} ?>
							</div>
							<div class="<?php echo ($etatc >= 3)?'state_ok':'state'; ?>" id="cmd3">
								<span class="<?php echo ($etatc >= 3)?'state_title_ok':'state_title'; ?>">Votre commande a été expédiée</span> 
								<?php if($etatc == 3){echo '(Elle est en cours de livraison par voie postale)';} ?>
							</div>
							<div class="<?php echo ($etatc >= 4)?'state_ok':'state'; ?>" id="cmd4">
								<span class="<?php echo ($etatc >= 4)?'state_title_ok':'state_title'; ?>">Votre commande est terminée</span> 
								<?php if($etatc == 4){echo '(Vous pouvez <a href="javascript:deleteCommand('.$commandObj->getCommandeID().');">supprimer cette commande</a>)';} ?>
							</div>
						<?php
					}
				?>
				<div class="separator10"></div>
				<center><input type="button" class="button" id="retour" value="Retour à mon compte" onClick="document.location.href='myaccount.php';"/></center>
			</div>
			<div class="separator10"></div>
		</div>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
?>
