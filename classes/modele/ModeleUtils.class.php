<?php
class ModeleUtils{
	public static function getFileFromDirectory($dir_nom){
		$dir = @opendir($dir_nom);
		if($dir == false){
			return false;
		}
		$fichier= array();
		while($element = readdir($dir)) {
			if($element != '.' && $element != '..') {
				if (!is_dir($dir_nom . DIRECTORY_SEPARATOR .$element)) {
					$fichier[] = $element;
				}
			}
		}
		closedir($dir);
		return $fichier;
	}

	public static function Rib2Iban($codebanque,$codeguichet,$numerocompte,$cle){
		$charConversion = array("A" => "10","B" => "11","C" => "12","D" => "13","E" => "14","F" => "15","G" => "16","H" => "17",
		"I" => "18","J" => "19","K" => "20","L" => "21","M" => "22","N" => "23","O" => "24","P" => "25","Q" => "26",
		"R" => "27","S" => "28","T" => "29","U" => "30","V" => "31","W" => "32","X" => "33","Y" => "34","Z" => "35");
	 
		$tmpiban = strtr($codebanque.$codeguichet.$numerocompte.$cle."FR00",$charConversion);
	 
		// Soustraction du modulo 97 de l'IBAN temporaire � 98
		$cleiban = strval(98 - intval(bcmod($tmpiban,"97")));
	 
		if (strlen($cleiban) == 1)
			$cleiban = "0".$cleiban;
	 
		return "FR".$cleiban.$codebanque.$codeguichet.$numerocompte.$cle;
	}

	public static function sendEvenementAlbumDisponible($evt, $mails){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
		     	$headers .='Reply-To: no-reply@photomentiel.fr'."\n"; 
		     	$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
		     	$headers .='Content-Transfer-Encoding: 8bit' . "\n";
		     	$headers .='Bcc:' . $mails; 
			return mail('',
			"Photomentiel - Nouvel album disponible !",
			"Ce message vous a été envoyé suite à votre demande de notification de publication d'un album.\n\n".
			"Un nouvel album vient d'être publié pour l'événement '" . htmlspecialchars_decode ($evt->getDescription()) . "'\n" .
			"Allez vérifier sur www.photomentiel.fr !!\n\n".
			"Merci d'utiliser photomentiel.fr\n\n\n".
			"Veuillez ne pas répondre à cet email, celui-ci a été généré automatiquement.\n",
			$headers
			);
		}
	}

	public static function sendAlbumDisponible($album, $sid, $mails){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
	     	$headers .='Reply-To: no-reply@photomentiel.fr'."\n"; 
	     	$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
	     	$headers .='Content-Transfer-Encoding: 8bit' . "\n";
	     	$headers .='Bcc:' . $mails;
	     	$content = "Ce message vous a été envoyé suite à votre demande de notification de publication d'un album.\n\n";
	     	$content .= "L'album intitulé '" . htmlspecialchars_decode ($album->getNom()) . "' est maintenant disponible !\n";
			$content .= "Vous pouvez le consulter dès à présent en vous rendant à l'adresse suivante :\n";
			if($album->isPublique()){
				$content .= "http://www.photomentiel.fr/album-".$sid->getStringID().".php\n\n";
			}else{
				$content .= "http://www.photomentiel.fr\n";
				$content .= "En entrant le code album suivant : ".$sid->getStringID()."\n\n";
			}
			$content .= "Merci d'utiliser photomentiel.fr\n\n\n";
			$content .= "Veuillez ne pas répondre à cet email, celui-ci a été généré automatiquement.\n";
			return mail('',
			"Photomentiel - Nouvel album disponible !",
			$content,
			$headers
			);
		}
	}
}
?>
