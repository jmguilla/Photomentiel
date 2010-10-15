<?php
/*
 * start_upload.php is called when an upload from the server is started.
 * 
 * Author : SCHIOUFF (All rights reserved)
 * 
 * Created on 9 oct. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
 
include_once("../classes/modele/StringID.class.php");
include_once("../classes/modele/Upload.class.php");

if (!isset($_GET['stringIDcount'])){
	echo 1;
	exit;
}
$tmp = explode("-",$_GET['stringIDcount']);
$sid=$tmp[0];
$count=$tmp[1];
$sidObj = StringID::getStringIDDepuisID($sid);
if (!$sidObj){
	echo 3;
	exit;
}

$uploadObj = new Upload();
$uploadObj->setStringID($sidObj->getStringID());
$uploadObj->setNombre($count);
$uploadObj->create();

echo 0;

?>
