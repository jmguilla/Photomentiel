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
$MYACCOUNT_PHP = true;

if ($utilisateurObj){
	$photographMode = $_SESSION['userClass'] === 'Photographe';
}

if ($utilisateurObj && $photographMode && isset($_POST['pcontrat'])){
	$utilisateurObj->validContrat();
}

$accountRemoved = false;
$showContrat = false;
if ($utilisateurObj && isset($_GET['action'])){
	if ($_GET['action']==='remove'){
		//T O D O remove account
		/*unset($_SESSION['userID']);
		unset($_SESSION['userClass']);
		$utilisateurObj = false;
		$accountRemoved = true;*/
	}
	if ($_GET['action']==='scontrat' && $photographMode){
		$showContrat = true;
	}
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
				<div style="border:1px orange solid;"></div>
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
				<?php
					if($photographMode) {
				?>
				<div class="left_c" onClick="document.location.href='myaccount.php';">
					Mon compte
				</div>
				<?php
					} else {
				?>
				<div class="left_c" onClick="document.location.href='index.php';">
					Accueil
				</div>
				<?php
					}
				?>
				<div class="left_c" onClick="document.location.href='adduser.php?np=myaccount.php';">
					Informations personnelles
				</div>
				<?php
					if($photographMode) {
				?>
				<div class="left_c" onClick="document.location.href='myaccount.php?action=scontrat';">
					Mon contrat
				</div>
				<?php
					}
				?>
				<div class="left_c" onClick="document.location.href='contact.php';">
					Contact
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
				<?php
					/************************* CONTENT HERE *****************************/
					if ($showContrat){
						echo '<div id="p_contrat" style="margin-top:0px;margin-left:5px;margin-right:5px;height:550px">';
						include("contratPhotographe.php");
						echo '</div>';
					} else {
						include("myaccount_default.php");
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
