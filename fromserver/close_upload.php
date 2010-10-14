<?php
/*
 * close_upload.php is just the request to close pictures upload comming from storage server
 * 
 * Author : SCHIOUFF (All rights reserved)
 * 
 * Created on 9 oct. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
 
include_once("../classes/modele/StringID.class.php");
include_once("../classes/modele/Album.class.php");
include_once("../classes/modele/Upload.class.php");

if (!isset($_GET['stringID'])){
	echo 1;
	exit;
}
$sidObj = StringID::getStringIDDepuisID($_GET['stringID']);
if (!$sidObj){
	echo 2;
	exit;
}
$albumObj = Album::getAlbumDepuisID($sidObj->getID_Album());
if (!$albumObj){
	echo 3;
	exit;
}
$retCode = $albumObj->finirTransfert();
if (!$retCode){
	echo 4;
	exit;
}
$uploadObj = Upload::getUploadDepuisStringID($_GET['stringID']);
if (!$uploadObj){
	echo 5;
	exit;
}
$uploadObj->delete();

echo 0;

?>
