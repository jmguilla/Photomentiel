<?php
include_once '../modele/Upload.class.php';
include_once '../modele/StringID.class.php';
$up = Upload::getUploadDepuisStringID('0ijdiit3');
$up->delete();

?>