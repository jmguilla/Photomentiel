<?php
$dir_administration_imap_factures = dirname(__FILE__);
include_once $dir_administration_imap_factures . "/../../classes/modele/CommandeFoto.class.php";
include_once $dir_administration_imap_factures . "/../../classes/modele/Commande.class.php";
include_once $dir_administration_imap_factures . "/../../classes/controleur/ControleurUtils.class.php";

$prefix_path_to_save = "/homez.368/photomen/cgi-bin/factures/foto.com/" . date("mY") . "/";
//$prefix_path_to_save = "D:\\EasyPHP-5.3.3\\www\\" . date("mY") . "\\";
if(!is_dir($prefix_path_to_save)){
	if(!mkdir($prefix_path_to_save, 0755)){
		echo "Impossible de creer repertoire pour sauvegarde factures<br/>";
		ControleurUtils::addError("Impossible de creer repertoire pour sauvegarde factures $prefix_path_to_save");
		return;
	}
}

echo "Traitement commande foto.com:<br/>";
$mbox = imap_open ("{pop3.photomentiel.fr:110/pop3}INBOX", "jean-michel.guillaume@photomentiel.fr", "adJL13adJM08");
$numMessage = imap_num_msg($mbox);
$commandesTraitees = array();
for($j = 1; $j <= $numMessage; $j++){
	$header = imap_header ($mbox,$j);
	$from = $header->from[0];
		if((strstr($from->host, "foto.com") != false) && (strstr($header->subject,"Facture") != false)){
//	if((strstr($from->mailbox, "guillauj") != false)){
		$commandes = array();
		preg_match_all('/>N&#176;&nbsp;(\d+)</', imap_body($mbox, $j), $commandes, PREG_SET_ORDER);
		foreach($commandes as $commande){
			//$commande[1] vaut un numero de commande foto.com
			$commandeFoto = CommandeFoto::getCommandeFotoDepuisCommandeFoto($commande[1]);
			if($commandeFoto){
				if($commandeFoto->expediee()){
					echo $commandeFoto->getCommandeFotoID() . " marque expediee<br/>";
					$commandesTraitees[$commandeFoto->getID_Commande()] = true;				
				}
			}else{
				echo "Aucune commande foto ne correspond au numero " . $commande[1] . "<br/>";
			}
		}
		//maintenant on extrait le pdf si pdf il y a
		$sujet = $header->subject;	
		$struct = imap_fetchstructure($mbox,$j);
		if ($struct->type == 1){
			$nbrparts = !$struct->parts ? "1" : count($struct->parts);
			$piece = array();
			for($h=2;$h<=$nbrparts;$h++){
				$part = $struct->parts[1];
				$piece = imap_fetchbody($mbox,$j,$h);
				if ($part->encoding == "3") {
					$nbparam =  count($part->parameters);
					for ($i = 0; $i < $nbparam ; $i++){
						//Récupération du nom de la pièce jointe
						$param = $part->parameters[$i];
						if ($param->attribute == "NAME"){
							$nom_fichier = $param->value;
						}else{
							$nom_fichier = "Nom de fichier introuvable";
						}
					}
					$piece = imap_base64($piece);
					$nom_fichier = str_replace(".pdf","",$nom_fichier);
					$path_to_save= $prefix_path_to_save.$nom_fichier.$h.".pdf";
					$newfichier = fopen($path_to_save,"w+");
					fwrite($newfichier,$piece);
					fclose($newfichier);
					echo "facture " . $path_to_save . " sauvee<br/>";
				}
			}
//		imap_mail_copy($mbox, $i, "INBOX.factures"); //move email non gere en pop3?
		}else{
			//pas de multipart
		}
	}
}
imap_close($mbox);

//maintenant on fait le point sur les commandes photomentiel
echo "<br/><br/><br/>Traitement commande Photomentiel:<br/>";
foreach($commandesTraitees as $i => $bool){
	if($bool == true){
		$commandesFotoAssociees = CommandeFoto::getCommandeFotoDepuisID_Commande($i);
		$expedie = true;
		foreach($commandesFotoAssociees as $cf){
			$expedie = $expedie && $cf->isExpediee();
		}
		if($expedie){
			$commandePhotomentiel = Commande::getCommandeDepuisID($i);
			if($commandePhotomentiel->setExpediee()){
				echo "Commande #$i marquee expediee<br/>";
			}
		}
	}
}
?>