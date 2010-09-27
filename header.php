<?php
/*
 * header.php is the header of each page 
 * (banniere centered, connection button bar)
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 24 juil. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
include("head.php");
include_once("classes/modele/Utilisateur.class.php");

include("userconnection.php");
?>
<div id="header_surround"><a href="index.php" title="<?php echo Utils::getFullDomainName(); ?> - Retour accueil"><div id="header"><span id="accueil">Accueil</span></div></a></div>
<div class="separator2"></div>
<div id="menu_barre">
	<div style="text-align:left;float:left;padding-left:10px;width:740px;">
		<!--<div id="home"><a href="index.php" title="Retour accueil"><img src="design/misc/home.png"></img></a></div>-->
		<?php
			if (!$utilisateurObj){
		?>
				<form id="form_connect" method="POST" action="?<?php echo getRequestParamFromPost(); ?>">
					E-mail : <input name="user_email" id="user_email" class="email" type="text" title="Entrez votre adresse E-mail"/>
					Mot de passe : <input name="user_pwd" id="user_pwd" class="password" style="width:100px;" type="password" title="Entrez votre mot de passe"/>
					<input class="button" id="valider" type="submit" value="Go" title="Me connecter" onClick="return checkUserOrConnect('<?php echo Utils::getScriptName();?>');"/>
					<span id="form_connect_error">Email ou mot de passe incorrect</span>
				</form>
		<?php
			} else {
				echo '<div id="user_connected">';
				echo 'Connecté sous <b>'.$utilisateurObj->getEmail().'</b>';
				if (get_class($utilisateurObj) == "Photographe"){
					echo " - (Photographe)";
				}
				echo '</div>';
			}
		?>
	</div>
	<div style="float:right;padding-right:3px;padding-top:5px;">
		<?php
			if (!$utilisateurObj){
		?>
				<a id="lost_pwd" href="?action=lostpwd" onClick="return checkEmail();" title="Cliquez ici si vous avez perdu votre mot de passe">Mot de passe perdu ?</a>
		<?php
			} else {
		?>
				<a id="my_account" href="myaccount.php" title="Cliquez ici pour accéder à votre compte">Mon compte</a> - 
				<a id="deconnexion" href="index.php?action=disc" title="Cliquez ici pour vous déconnecter">Me déconnecter</a>
		<?php		
			}
		?>
	</div>
</div>
<div class="separator2"></div>
<div id="body_content">

