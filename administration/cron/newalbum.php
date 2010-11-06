<?php
$_SERVER['SERVER_ADDR'] = "213.186.33.16";
//controle s'il y a des commandes en attente de traitement et envoie un mail les cas où...
$dir_administration_cron_newalbum_php = dirname(__FILE__);
include_once $dir_administration_cron_newalbum_php . '/../../classes/modele/Album.class.php';
include_once $dir_administration_cron_newalbum_php . '/../AdministrationUtils.class.php';

$albums = Album::getAlbumDepuisEtat(1);
//au moins un album à valider
if($albums && count($albums)>0){
	AdministrationUtils::sendMailAlbumAValider();
}

?>