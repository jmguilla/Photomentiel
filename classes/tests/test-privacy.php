<?php

include_once "../modele/RetraitPhoto.class.php";
include_once "../modele/StringID.class.php";
$test = new RetraitPhoto();
$array = StringID::getStringIDAleatoire();
$test->setStringID($array[0]->getStringID());
$test->setNom("test nom");
$test->setPrenom("tset mkl");
$test->setMail("mlkj");
$test->setJustificatif("ste");
$test->setRaison("gtset");
$test->setRef("mlkjm");
$test = $test->create();
$tests = RetraitPhoto::getRetraitsPhoto();
foreach($tests as $rp){
	echo $rp->getMail() . " " . $rp->getNom() . " " . $rp->getPrenom() . " " . $rp->getRetraitPhotoID() . " " . $rp->getJustificatif() . " " . $rp->getRaison() . " " . $rp->getRef() . " " . $rp->getStringID() . " <br/>";
}
?>