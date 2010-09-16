<?php
/*
 * ok.php manages the bad answer payment request
 * 
 * Author : SCHIOUFF (All rights reserved)
 * 
 * Created on 12 sept. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
//$dir_okko_php = dirname(__FILE__);
//include($dir_okko_php."/buildresponse.php");
@session_start();
$numCmd = $_SESSION['last_command'];
unset($_SESSION['last_command']);
header('Location:/viewcommand.php?cmd='.$numCmd);
?>
