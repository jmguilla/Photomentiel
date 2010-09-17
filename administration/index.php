<?php
$dir_administration_index_php = dirname(__FILE__);
include $dir_administration_index_php . "/header.php";
if((!$_SERVER['PHP_AUTH_USER'] || !$_SERVER['PHP_AUTH_USER']) 
    && preg_match('/Basics+(.*)$/i', $_SERVER['REMOTE_USER'], $matches)) { 
    list($name, $password) = explode(':', base64_decode($matches[1])); 
    $_SERVER['PHP_AUTH_USER'] = strip_tags($name); 
    $_SERVER['PHP_AUTH_PW']    = strip_tags($password); 
} 
echo $PHP_AUTH_USER . " - ";
echo $_SERVER['PHP_AUTH_USER'] . " - ";
echo $_SERVER['REMOTE_USER']
?>
<div>
Rendez-vous dans la section désirée:<br/>
<form method="post" action="album.php">
	<input type="submit" value="Gestion Albums"/>
</form>
<form method="post" action="commande.php">
	<input type="submit" value="Gestion Commandes"/>
</form>
</div>
<?php 
include $dir_administration_index_php . "/header.php";
?>