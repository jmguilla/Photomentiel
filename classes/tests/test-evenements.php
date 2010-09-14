<?php
$dir_test_evenements_php = dirname(__FILE__);
include_once $dir_test_evenements_php . "/../modele/Evenement.class.php";
include_once $dir_test_evenements_php . "/../modele/EvenementEcouteur.class.php";
include_once $dir_test_evenements_php . "/../modele/Utilisateur.class.php";

$ee = new EvenementEcouteur();
$ee->setEvenementEcouteurID(6);
$ee->delete();
//$evts = Evenement::getEvenements();
//foreach($evts as $evt){
//	echo "Evenement#" . $evt->getEvenementID() . "<br/>";
//	echo "&nbsp;&nbsp;region: " . $evt->getRegion()->getID_Region() . " - " . $evt->getRegion()->getNom() . "<br/>";
//	echo "&nbsp;&nbsp;departement: " . $evt->getDepartement()->getID_Departement() . " - " . $evt->getDepartement()->getNom() . "<br/>";
//	echo "&nbsp;&nbsp;ville: " . $evt->getVille()->getID_Ville() . " - " . $evt->getVille()->getNom() . "<br/>";
//}
//
//$evt = new Evenement();
//$users = Utilisateur::getUtilisateurs();
//$user = $users[rand(0, (count($users) - 1))];
//$evt->setID_Utilisateur($user->getUtilisateurID());
//$types = array('Mariage','Evenement Sportif','Shooting Perso','Divers');
//$type = $types[rand(0, (count($types) - 1))];
//$evt->setType($type);
//$isPubliqueRand = rand(0, 10);
//if($isPubliqueRand % 2 == 1){
//	$isPublique = true;
//}else{
//	$isPublique = false;
//}
//$evt->setIsPublique($isPublique);
//$date = '201' . rand(1,9) . "-" . rand(1,12) . "-" . rand(1,28) . " " . rand(0,23) . ":" . rand(0,59);
//$evt->setDate($date);
//$evt = $evt->create();
//if($evt){
//	echo "nouvel evenement cree:<br/>";
//}else{
//	echo "echec de la creation de levenement<br/>";
//}

//ici on teste l'ajout d'un mail a la mailing liste
//foreach($evts as $evt){
//	echo $evt->getMailing() . " avant <br/>" ;
//	$evt->addMailAMailing("jean-michel.guillaume@activeeon.com");
//	echo $evt->getMailing() . " apres <br/>" ;
//}
?>