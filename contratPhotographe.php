<?php
/*
 * contratPhotographe.php is used to edit contract between photomentiel and photographs
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 23 Oct 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
include_once("phptopdf/phpToPDF.php");
include_once("classes/Config.php");

$nom = ".......................";
$prenom = "......................";

if (isset($_GET['nom'])){
	$nom = $_GET['nom'];
}
if (isset($_GET['prenom'])){
	$prenom = $_GET['prenom'];
}

/* Start building document */
$PDF = new phpToPDF();
$PDF->AddPage();
//Photomentiel informations
$x = 15;$y = 10;
$PDF->SetFont('Times','B',12);
$PDF->SetXY($x,$y);
$PDF->Write(10, "PHOTOMENTIEL - www.photomentiel.fr");
/*$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, ADRESSE2);
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, ADRESSE3);*/
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "contact@photomentiel.fr");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "SIREN No 525329272 & 0521000018");
$y+=5;
$PDF->SetFont('Times','',9);
$PDF->SetXY($x,$y);
$PDF->Write(10, "Dispensé d'immatriculation au registre du commerce");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "et des sociétés (RCS) et au répertoire des métiers (RM)");
//title
$PDF->SetFont('Times','B',14);
$y+=15;
$PDF->SetXY($x+50,$y);
$PDF->Write(10, "CONTRAT DE PARTENARIAT");
$y+=5;
$PDF->SetFont('Times','',10);
$PDF->SetXY($x+70,$y);
$PDF->Write(10, "Le ".date("d/m/Y à H:i"));
//texte
$PDF->SetFont('Times','',9);
$y+=15;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Ce contrat est établit entre d'une part les associés de Photomentiel désignés par leurs immatriculations respectives,");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "et d'autre part le photographe soussigné ".$nom." ".$prenom);
$y+=10;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Entre les deux partis, il est convenu ce qui suit :");
//article1
$PDF->SetFont('Times','U',11);
$y+=10;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Article 1 :");
$PDF->SetFont('Times','',9);
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Le photographe s'engage à fournir des albums photos de qualité et ne contenant pas de photo à caractère vulgaire, pornographique,");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "ou pouvant porter atteinte à la moralité et au bon sens collectif. Tout manquement à cette règle d'éthique pourra être sanctionné.");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Photomentiel se réserve le droit de supprimer sans préavis le compte d'un photographe ne respectant pas ces règles.");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Il s'engage aussi à informer les personnes présentes sur les photographies de leur diffusion sur internet dans le cas d'un album public.");
//article 2
$PDF->SetFont('Times','U',11);
$y+=10;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Article 2 :");
$PDF->SetFont('Times','',9);
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Photomentiel informe le photographe que toutes les photographies qu'il dépose sur le présent site sont sa propriété et ne seront jamais");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "utilisées à d'autres fins que la diffusion de celles-ci sur le présent site sans accord préalable du photographe.");
//article 3
$PDF->SetFont('Times','U',11);
$y+=10;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Article 3 :");
$PDF->SetFont('Times','',9);
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Photomentiel s'engage à reverser au photographe ".PHOTOGRAPH_INITIAL_PERCENT."% des bénéfices de ces ventes, le reste étant utilisé pour assurer le bon");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "fonctionnement de ce service. Photomentiel précise aussi que le pourcentage reversé au photographe faisant foi est celui qui apparaît");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "sur le présent contrat. Si Photomentiel est amené à augementer ce pourcentage, il ne sera pas changé pour les photographes");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "ayant déjà un compte sur le site.");
//article 4
$PDF->SetFont('Times','U',11);
$y+=10;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Article 4 :");
$PDF->SetFont('Times','',9);
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "En créant un compte, le photographe s'engage à accepter les termes évoqués dans les différents articles du présent contrat.");
$y+=5;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Il s'engage aussi à imprimer ce document afin de conserver une trace du pourcentage qui lui a été attribué au jour de son inscription.");
$y+=20;
$PDF->SetXY($x,$y);
$PDF->Write(10, "Merci d'avoir choisi Photomentiel, nous vous souhaitons une bonne expérience à nos côtés.");

//footer
$x = 78;$y = 266;
$PDF->SetFont('Times','',8);
$PDF->SetXY($x,$y);
$PDF->Write(10, "www.photomentiel.fr - Tous droits réservés");
//Print
$PDF->Output();

?>
