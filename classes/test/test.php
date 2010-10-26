<?php
include_once '../controleur/ControleurUtils.class.php';
include_once '../modele/Utilisateur.class.php';
$user = new Utilisateur();
$user->setEmail("guillauj@gmail.com");
echo (false == ControleurUtils::sendMailEtPDF($user, "sujet au hasard", "contenu au hasard", "/homez.368/photomen/cgi-bin/5_Facturier.pdf"));