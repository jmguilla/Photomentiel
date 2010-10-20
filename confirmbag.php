<?php
try {
/*
 * confirmbag.php displays the validated content of the bag, format, and number of units per format.
 * One step before payment !
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 5 aug. 2010
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
include_once("classes/modele/Utilisateur.class.php");
include_once("classes/modele/Commande.class.php");
include_once("classes/modele/CommandePhoto.class.php");
include_once("classes/modele/Adresse.class.php");
include_once("classes/modele/AdresseCommande.class.php");
include_once("classes/modele/TransactionID.class.php");
include_once("classes/modele/StringID.class.php");

if (!isSet($_SESSION['albumStringID'])){
	photomentiel_die(new PMError("Aucun album spécifié !","Aucun code album n'a été spécifié, que faites vous là ?"));
}
$albumStringID = $_SESSION['albumStringID'];

//checked commands
if (!isset($_POST["pictur_0"]) && !isset($_SESSION['COMMAND_LINES'])){
	photomentiel_die(new PMError("Aucune photo commandée !","Aucune photo n'a été commandée, que faites vous là ?"));
}
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
	$commandLines = $_SESSION['COMMAND_LINES'];
}

//photos formats
$photoFormatsDim = $_SESSION['photoFormatsDim'];
$photoFormatsPrice = $_SESSION['photoFormatsPrice'];

$HEADER_TITLE = "Confirmation de votre panier";
$HEADER_DESCRIPTION = "Page de confirmation de votre panier";
include("header.php");

if ($utilisateurObj && isset($_POST['payment']) && $_POST['payment'] == 'true'){
	//Manage duplication (F5, history back, etc.)
	$createCommand = false;
	$commandLinesHash = getHashFromCommand($commandLines);
	$postHash = getHashFromArray($_POST);
	if (isset($_SESSION['commandLinesHash']) && isset($_SESSION['commandPostHash'])){
		if ($_SESSION['commandLinesHash'] != $commandLinesHash || $_SESSION['commandPostHash'] != $postHash){
			$_SESSION['commandLinesHash'] = $commandLinesHash;
			$_SESSION['commandPostHash'] = $postHash;
			$createCommand = true;
		}
	} else {
		$_SESSION['commandLinesHash'] = $commandLinesHash;
		$_SESSION['commandPostHash'] = $postHash;
		$createCommand = true;
	}

	if ($createCommand){
		//make command and save it in DB
		$adress = new AdresseCommande();
		if ($_POST['adresses'] == "1"){
			$adresseObj = $utilisateurObj->getAdresse();
			$adress->setNom($adresseObj->getNom());
			$adress->setPrenom($adresseObj->getPrenom());
			$adress->setNomRue($adresseObj->getNomRue());
			$adress->setComplement($adresseObj->getComplement());
			$adress->setCodePostal($adresseObj->getCodePostal());
			$adress->setVille($adresseObj->getVille());
		} else {
			$adress->setNom($_POST['nom']);
			$adress->setPrenom($_POST['prenom']);
			$adress->setNomRue($_POST['adresse1']);
			$adress->setComplement($_POST['adresse2']);
			$adress->setCodePostal($_POST['code_postal']);
			$adress->setVille($_POST['ville']);
		}
	
		//Creation de la commande
		$commande = new Commande();
		$commande->setAdresse($adress);
		$commande->setID_Utilisateur($utilisateurObj->getUtilisateurID());
		$commande->setID_Album($_SESSION['albumID']);
		$total = 0;
		for ($i=0;$i<sizeof($commandLines) && $total<SHIPPING_RATE_UNTIL;$i++){
			$current = $commandLines[$i];
			if ($current['quantity']<1){photomentiel_die(new PMError("Error","You damn cheat !?"),false);}
			$total += $current['quantity']*$photoFormatsPrice[$current['formatID']];
		}
		if ($total < SHIPPING_RATE_UNTIL){
			$commande->setFDP(SHIPPING_RATE);
		}
		//$types = TypePapier::getTypePapiers();
		//$couleurs = Couleur::getCouleurs();
		//add command lines
		for ($i=0;$i<sizeof($commandLines);$i++){
			$currentline = $commandLines[$i];
			$commandePhoto = new CommandePhoto();
			$commandePhoto->setPhoto($currentline['fileName']);
			$commandePhoto->setNombre($currentline['quantity']);
			//$commandePhoto->setID_TypePapier($types[rand(0, (count($types)-1))]->getTypePapierID());
			//$commandePhoto->setID_Couleur($couleurs[rand(0, (count($couleurs)-1))]->getCouleurID());
			$commandePhoto->setID_TaillePapier($currentline['formatID']);
			$commandePhoto->setID_TypePapier(1);
			$commandePhoto->setID_Couleur(1);
			$commandePhoto->setID_Album($_SESSION['albumID']);
			$commandePhoto->setPrix($currentline['quantity']*$photoFormatsPrice[$currentline['formatID']]);
			$commande->addCommandePhoto($commandePhoto);
		}
		//save in DB
		$commande = $commande->create();
		if(!$commande){
			photomentiel_die(new PMError("Erreur lors de la commande !","Un problème est survenu lors de la création de la commande, veuillez réessayer ultérieurement."),false);
		}
		$_SESSION['lastCreatedCommand'] = $commande->getCommandeID();
	} else {
		if (!isset($_SESSION['lastCreatedCommand'])){
			photomentiel_die(new PMError("Erreur lors de la commande !","Une tentative de duplication de la commande a généré un problème."),false);
		}
		$commande = Commande::getCommandeDepuisID($_SESSION['lastCreatedCommand']);
	}
	$cmdConfirmed = true;
} else {
	$cmdConfirmed = false;
}

?>
	<div id="full_content_top">
		Confirmation de votre commande
	</div>
	<div id="full_content_mid">
		<div class="path">
			<a href="index.php">Accueil</a> &gt; 
			Album &gt; 
			<?php
				if ($cmdConfirmed){
					echo 'Panier &gt; ';
				} else {
					echo '<a href="javascript:history.back();">Panier</a> &gt; ';
				}
			?>
			Identification
			<?php
				 if ($utilisateurObj){
				 	echo ' &gt; Livraison';
				 }
				 if ($cmdConfirmed){
				 	echo ' &gt; Paiement';
				 }
			?>
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
					$partial = $current['quantity']*$photoFormatsPrice[$current['formatID']];
					$total += $partial;
					echo '<td class="'.$imp.'">'.sprintf('%.2f',$partial).' &#8364;</td>';
					echo '</tr>';
				}
				echo '<tr id="total_"><td style="background-color:white;"></td><td align="right">Total photos :</td><td>'.$nb_photos.'</td><td>'.sprintf('%.2f',$total).' &#8364;</td></tr>';
				if ($total < SHIPPING_RATE_UNTIL){
					$ship_rate = sprintf('%.2f',SHIPPING_RATE).' &#8364';
					$total += SHIPPING_RATE;
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
				<?php
					if (!$utilisateurObj){
				?>
						<div id="div_continue">
						<ul>
							<b>Afin de poursuivre votre commande, vous devez vous identifier ou créer un compte :</b>
							<br/><br/>
							<li>Si vous venez de créer un compte, veuillez l'activer en suivant le lien qui vous a été envoyé par E-mail, puis connectez vous en utilisant les champs ci-dessus.</li>
							<br/>
							<li>
								Pour vous connecter à votre compte, veuillez vous identifier en utilisant les champs ci-dessous :<br/>
								<div id="bag_login">
									<form id="form_connect2" method="POST" action="?<?php echo getRequestParamFromPost(); ?>">
									<table><tr><td width="100px">
									E-mail  </td><td width="160px"><input style="width:150px;" name="user_email" id="user_email2" class="email" type="text" title="Entrez votre adresse E-mail"/><br/>
									</td></tr><tr><td>
									Mot de passe  </td><td><input style="width:150px;" name="user_pwd" id="user_pwd2" class="password" style="width:100px;" type="password" title="Entrez votre mot de passe"/>
									</td><td>
									<input style="margin-top:3px;" class="button" id="valider2" type="submit" value="Go" title="Me connecter" onClick="return checkUserOrConnect('<?php echo Utils::getScriptName();?>',2);"/>
									</td><td>
									<span id="form_connect_error2">Email ou mot de passe incorrect</span>
									</td></tr></table>
								</div>
							</form>
							</li>
							<br/>
							<li>Pour créer un compte, <a href="adduser.php?type=cl&np=confirmbag.php">cliquez ici.</a></li>
						</ul>
						</div>
						<div class="separator10" style="height:20px"></div>
						<center>
							<input style="width:260px;" type="button" class="button" value="Abandonner - Retour à l'accueil" onClick="document.location.href='index.php'"/>
							<input style="width:260px;" type="button" class="button" value="Retour - Modifier ma sélection" onClick="history.back();" id="back_button"/>
						</center>
						<div class="separator10" style="height:20px"></div>
				<?php
					} else {
						
						$adresseObj = $utilisateurObj->getAdresse();
						
						if ($cmdConfirmed){
				?>
							<div class="separator10"></div>
							<div class="recap_info">
								<div id="div_continue">
									Vous avez commandé <i><b><?php echo $nb_photos; ?> photo<?php echo $nb_photos==1?'':'s'; ?></i></b> pour un total de 
									<i><b><?php echo sprintf('%.2f',$total); ?> &#8364;</i></b>.<br/>
								</div>
								<br/>
								<div id="div_continue">
									Vos photos vous seront livrées à l'adresse suivante : <br/><br/>
									<div class="adr_b" style="font-size:14px;">
										<?php
											if ($_POST['adresses'] == "1"){
												$adresseObj = $utilisateurObj->getAdresse();
												echo $adresseObj->getNom()." ".$adresseObj->getPrenom()."<br/>";
												echo $adresseObj->getNomRue()."<br/>";
												if ($adresseObj->getComplement() != null && $adresseObj->getComplement() != ''){
													echo $adresseObj->getComplement()."<br/>";
												}
												echo $adresseObj->getCodePostal()." ".$adresseObj->getVille()."<br/>";
												echo 'France';
											} else {
												echo $_POST['nom']." ".$_POST['prenom']."<br/>";
												echo $_POST['adresse1']."<br/>";
												if ($_POST['adresse2'] != ''){
													echo $_POST['adresse2']."<br/>";
												}
												echo $_POST['code_postal']." ".$_POST['ville']."<br/>";
												echo 'France';
											}
										?>
									</div>
								</div>
								<br/>
								<div id="div_continue" style="border:2px #000099 solid;">
									Veuillez choisir un moyen de paiement (<i>ceci vous conduira sur la page sécurisée de paiement</i><img src="e-transactions/payment/logo/CLEF.gif"/>) <br/><br/>
									<?php
										if (!PAYMENT_MAINTENANCE){
											$_SESSION['last_command'] = $commande->getCommandeID();
											include("e-transactions/selectcard.php");
											$albumObj = Album::getAlbumDepuisID(StringID::getStringIDDepuisID($albumStringID)->getID_Album());
											$transactionID = TransactionID::get();
											if ($transactionID){ $transactionID = sprintf("%06d",$transactionID);}
											displayCards($albumObj->getModule(),toBankAmount($total),$transactionID,$utilisateurObj->getUtilisateurID(),$commande->getCommandeID());
										} else {
											?>
											<span class="warning">Le service de paiement est momentanément indisponible. 
											Il sera rétabli dans les plus brefs délais.<br/>
											Veuillez nous excuser pour l'éventuelle gêne occasionnée.</span><br/>
											<?php
										}
									?>
								</div>
								<div class="separator10"></div>
							</div>
				<?php
						} else {
				?>
							<u>Veuillez choisir votre adresse de livraison :</u>
							<div class="separator10"></div>
							<form id="adress_selection" method="POST" action="confirmbag.php">
								<div id="adress_left">
									<input id="main_adr" type="radio" name="adresses" value="1" checked="true"/> Utiliser mon adresse - (<a href="adduser.php?np=confirmbag.php">modifier mon adresse</a>)<br/>
									<br/>
									<div class="adr_b">
									<?php
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
								<div id="adress_separator"></div>
								<div id="adress_right">
									<input id="main_adr2" type="radio" name="adresses" value="2"/> Utiliser une autre adresse<br/>
									<br/>
									<div class="adr_b">
										<table>
											<tr>
												<td>
													Nom : 
												</td><td>
													<input name="nom" class="textfield" type="text" id="nom" required="required"/>
												</td><td>
													<div  class="checkform" id="rnom"></div>
												</td>
											</tr>
											<tr>
												<td>
													Prénom : 
												</td><td>
													<input name="prenom" class="textfield" type="text" id="prenom" required="required"/>
												</td><td>
													<div  class="checkform" id="rprenom"></div>
												</td>
											</tr>
											<tr>
												<td>
													Adr. (numéro + rue) : 
												</td><td>
													<input name="adresse1" class="textfield" type="text" id="adresse1" required="required"/>
												</td><td>
													<div  class="checkform" id="radresse1"></div>
												</td>
											</tr>
											<tr>
												<td>
													Compl. (Bât, Entrée) : 
												</td><td>
													<input name="adresse2" class="textfield" type="text" id="adresse2"/>
												</td><td>
													<div  class="checkform" id="radresse2"></div>
												</td>
											</tr>
											<tr>
												<td>
													Code postal : 
												</td><td>
													<input name="code_postal" class="textfield" type="text" id="code_postal" maxlength="5" exactlength="5" regexp="^[0-9]+$" required="required"/>
												</td><td>
													<div  class="checkform" id="rcode_postal"></div>
												</td>
											</tr>
											<tr>
												<td>
													Ville : 
												</td><td>
													<input name="ville" class="textfield" type="text" id="ville" required="required"/>
												</td><td>
													<div  class="checkform" id="rville"></div>
												</td>
											</tr>
										</table>
									</div>
								</div>
								<div class="separator10" style="height:20px"></div>
								<input type="hidden" name="payment" value="true"/>
								<center>
									<input style="width:250px;" type="button" class="button" value="Retour - Annuler ma commande" onClick="$('#backToAlbum').submit();"/>
									<input style="width:250px;" type="submit" class="button" value="Continuer - Valider ma commande" id="valid_button"/>
								</center>
							</form>
							<form id="backToAlbum" method="POST" action="viewalbum.php">
								<input type="hidden" name="al" value="<?php echo $albumStringID; ?>"></input>
							</form>
							<div class="separator10"></div>
				<?php
						}
					}
				?>
			</div>
		</div>
	</div>
	<div id="full_content_bot"></div>
<?php
include("footer.php");
}catch (Exception $e){
	echo "Internal server error !";
}
?>
