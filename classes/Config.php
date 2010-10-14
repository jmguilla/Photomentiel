<?php
$prdPossibleRootDir = array();
array_push($prdPossibleRootDir,"D:/test/www/pictures/");
array_push($prdPossibleRootDir,"D:/WorkingDir/eclipse/Photomentiel/pictures/");
array_push($prdPossibleRootDir,"C:/EasyPHP-5.3.3/www/pictures/");
array_push($prdPossibleRootDir,"D:/EasyPHP-5.3.3/www/pictures/");
array_push($prdPossibleRootDir,"D:/test/www/pictures/");
array_push($prdPossibleRootDir,"D:/www/pictures/");
array_push($prdPossibleRootDir,"/var/www/html/pictures/");
for ($i=0;$i<sizeof($prdPossibleRootDir);$i++) {
	if (is_dir($prdPossibleRootDir[$i])){
		$prdRootDir = $prdPossibleRootDir[$i];
	}
}
$EVENTS_TYPES=array('Mariage','Evenement Sportif','Shooting Perso','Autre');
$COMMAND_STATES=array('En attente','Payée','En cours de préparation','Expédiée','Terminée');
$ALBUM_STATES=array('Créé','Prêt','Ouvert','Cloturé');
$MODULES=array('521000018');

define('DBTYPE', 'mysql');
if($_SERVER['SERVER_ADDR'] == "213.186.33.16"){
	define('DBHOST', 'mysql5-17.bdb');
	define('DBUSER', 'photomentiel');
	define('DBPWD', 'adlt8f3j1y8d');
	define('PHOTOGRAPHE_ROOT_DIRECTORY', '/homez.368/photomen/www/pictures/');
}else{
	define('DBHOST', '127.0.0.1');
	define('DBUSER', 'jmguilla');
	define('DBPWD', 'jmguilla');
	define('PHOTOGRAPHE_ROOT_DIRECTORY', $prdRootDir);
}
define('DBMAPS', 'photomentiel');
define('DBPHOTOMENTIEL', 'photomentiel');
define('APPLICATION_ROOT_DIRECTORY', '/');
define('PICTURE_ROOT_DIRECTORY', 'pictures/');
define('ADMIN_DIRECTORY', 'administration');
define('RETRAIT_DIRECTORY', 'retraits');
define('THUMB_DIRECTORY', 'thumbs/');
define('PICTURE_DIRECTORY', 'pics/');
define('STRINGID_LENGTH', 8);

define('AUTHOR','Photomentiel');
define('DOMAIN_NAME','photomentiel');
define('DOMAIN_EXT','fr');
define('SHIPPING_RATE',2.90);
define('SHIPPING_RATE_UNTIL',30);
define('PHOTOGRAPH_INITIAL_PERCENT', 73);

define('ADRESSE1','Photomentiel');
define('ADRESSE2','Pouet Pouet CEDEX 3');
define('ADRESSE3','Sophia Antipolis');

define('FTP_TRANSFER_IP','upload.photomentiel.fr');
define('FTP_PORT','21000');
define('HTTP_PORT','21080');
?>
