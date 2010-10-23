<?php
include_once 'modele/Album.class.php';
$album = new Album();
$album->setID_Photographe(13);
$album->create();

?>