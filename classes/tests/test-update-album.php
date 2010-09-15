<?php
$dir_test_visu_php = dirname(__FILE__);
include_once $dir_test_visu_php . "/../modele/StringID.class.php";
include_once $dir_test_visu_php . "/../modele/Image.class.php";
include_once $dir_test_visu_php . "/../modele/Album.class.php";
include_once $dir_test_visu_php . "/../modele/Evenement.class.php";
include_once $dir_test_visu_php . "/../modele/Utilisateur.class.php";
include_once $dir_test_visu_php . "/../modele/PrixTaillePapierAlbum.class.php";

$album = Album::getAlbumDepuisID(1);
echo "balance: " . $album->getBalance() . " gain total: " . $album->getGainTotal(). "<br/>";
$album->updateAmounts(6);
echo "balance: " . $album->getBalance() . " gain total: " . $album->getGainTotal(). "<br/>";
$album->updateAmounts(123);
echo "balance: " . $album->getBalance() . " gain total: " . $album->getGainTotal(). "<br/>";
?>