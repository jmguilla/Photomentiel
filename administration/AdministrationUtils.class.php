<?php
class AdministrationUtils {
	public static function sendMailAlbumAValider(){
	if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
     		$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
     		$headers .='Content-Transfer-Encoding: 8bit'; 
			return mail("jl@photomentiel.fr, jm@photomentiel.fr",
			"Album en attente de validation sur www.photomentiel.fr",
			"Au moins un album est en attente de validation\n".
			"RDV sur http://admin.photomentiel.fr/album.php",
			$headers
			);
		}else{
			return true;
		}
	}
	public static function sendMailCommandeATraiter(){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
     		$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
     		$headers .='Content-Transfer-Encoding: 8bit'; 
			return mail("jl@photomentiel.fr, jm@photomentiel.fr",
			"Commande en attente de traitement sur www.photomentiel.fr",
			"Au moins une commande est en attente de traitement\n".
			"RDV sur http://admin.photomentiel.fr/commande.php",
			$headers
			);
		}else{
			return true;
		}
	}	
}
?>