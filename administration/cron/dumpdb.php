<?php
$_SERVER['SERVER_ADDR'] = "213.186.33.16";
$dir_administration_dumpdb_php = dirname(__FILE__);
include_once $dir_administration_dumpdb_php . "/../../classes/Config.php";
include_once $dir_administration_dumpdb_php . "/../../classes/modele/ModeleUtils.class.php";
$files = ModeleUtils::getFileFromDirectory("/homez.368/photomen/cgi-bin/sauvegarde/db/");
if($files && count($files) >= 8){
	$toDelete = $files[0];
	$toDeleteTime = filemtime("/homez.368/photomen/cgi-bin/sauvegarde/db/" . $files[0]);
	foreach($files as $file){
		$currentLastModifTime = filemtime("/homez.368/photomen/cgi-bin/sauvegarde/db/".$file);
		if($currentLastModifTime && $currentLastModifTime < $toDeleteTime){
			$toDeleteTime = $currentLastModifTime;
			$toDelete = $file;
		}
	}
	unlink("/homez.368/photomen/cgi-bin/sauvegarde/db/".$toDelete);
}
$date = date("d-m-Y");
system("mysqldump --host=".DBHOST." --user=".DBUSER." --password=".DBPWD." ".DBPHOTOMENTIEL." > /homez.368/photomen/cgi-bin/sauvegarde/db/save-".$date.".sql");

?>