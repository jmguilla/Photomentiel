<?php
/*
 * captcha.php is used to display a little captcha
 * 
 * Author : SCHIOUFF (All rights reserved)
 * 
 * Created on 28 sept. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
@session_start();
header("Content-type: image/png");
$nbcar = 5;
$chaine = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
srand((double)microtime()*1000000);
$variable='';
for($i=0; $i<$nbcar; $i++) {
	$variable .= $chaine[rand(0,strlen($chaine)-1)];
}
$_SESSION['Captcha'] = $variable;

$img = imagecreate (60,20);
$background_color = imagecolorallocate ($img, 200, 200, 200);
$ecriture_color = imagecolorallocate($img, 0, 0, 0);
imagestring ($img, 5, 8, 2, $variable , $ecriture_color);
imagepng($img);

?>
 

