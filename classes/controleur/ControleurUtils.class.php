<?php
$dir_controleurutils_class_php = dirname(__FILE__);
include_once $dir_controleurutils_class_php . "/../vue/JSONVue.class.php";
include_once $dir_controleurutils_class_php . "/externalization.php";
include_once $dir_controleurutils_class_php . "/../modele/TaillePapier.class.php";
include_once $dir_controleurutils_class_php . "/../modele/Error.class.php";

class ControleurUtils{
	public static function sendMailEtPDF($utilisateur, $sujet, $content, $pathVersPDF){
		if(($_SERVER['SERVER_ADDR'] != "127.0.0.1") && $utilisateur){
			$boundary = "azertyuioppoiuytrezaqsdfghjklmmlkjhgfdsq";
			$headers = 'From: "Photomentiel"<contact@photomentiel.fr>'."\n";
	     	$headers .= 'Reply-To: no-reply@photomentiel.fr'."\n"; 
			$headers .= "MIME-Version: 1.0\n";
			$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\n\n";
     		$body = '--'. $boundary . "\n";
     		$body .= 'Content-Type: text/plain; charset="utf-8"'."\n\n";
     		$body .= $content . "\n\n";
     		$body .= '--' . $boundary . "\n";
     		$body .= 'Content-Type: application/pdf; name="fichier.pdf"'."\n";
     		$body .= "Content-Transfer-Encoding: base64\n";
			$body .= "Content-Disposition: attachment; filename=\"fichier.pdf\"\n\n";
     		$fichier=file_get_contents($pathVersPDF);
			$fichier=chunk_split(base64_encode($fichier));
			$body .= $fichier;
			return mail($utilisateur->getEmail(),
			$sujet,
			$body,
			$headers
			);
		}
	}

	public static function sendPaiementPhotographeEmail($utilisateur){
		if(($_SERVER['SERVER_ADDR'] != "127.0.0.1") && $utilisateur){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n";
	     	$headers .='Reply-To: no-reply@photomentiel.fr'."\n"; 
     		$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
     		$headers .='Content-Transfer-Encoding: 8bit'; 
			return mail($utilisateur->getEmail(),
			"Paiement photomentiel.fr",
			"Un paiement vient d'être éffectué en votre faveur par".
			" l'équipe photomentiel.\n\n".
			"Merci d'utiliser www.photomentiel.fr",
			$headers
			);
		}
	}

	public static function sendNouveauPhotographeEmail($utilisateur){
		if(($_SERVER['SERVER_ADDR'] != "127.0.0.1") && $utilisateur){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n";
	     	$headers .='Reply-To: '.$utilisateur->getEmail()."\n"; 
     		$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
     		$headers .='Content-Transfer-Encoding: 8bit'; 
			return mail("jl@photomentiel.fr, jm@photomentiel.fr",
			"Nouveau photographe sur photomentiel.fr",
			"Répondez lui directement...\n\n".
			"Nom: ".$utilisateur->getAdresse()->getNom().
			"\nPrenom: ".$utilisateur->getAdresse()->getPrenom().
			"\ntel: ".$utilisateur->getTelephone().
			"\nhabite: ".$utilisateur->getAdresse()->getCodePostal() . " - " .$utilisateur->getAdresse()->getVille(),
			$headers
			);
		}
	}
	public static function addError($message, $sendEmail = false){
		$error = new Error();
		$error->setMessage($message);
		$error->create();
		if($sendEmail){
			ControleurUtils::sendErrorEmail("Une erreur est survenue sur le backend photomentiel!!\n\n" . $message);
		}
	}
	public static function sendErrorEmail($content){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
     		$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
     		$headers .='Content-Transfer-Encoding: 8bit'; 
			return mail("jl@photomentiel.fr, jm@photomentiel.fr",
			"Error Photomentiel backend",
			$content,
			$headers
			);
		}
	}

	public static function serialize_object_json($obj, $result = true, $cause = NULL){
		if(is_object($obj)){
			echo '{"result" : ' . json_encode($result) . ",";
			echo '"cause" : ' . json_encode($cause) . ",";
			$vue = new JSONVue($obj);
			echo '"value" : ' . $vue->getJSON() . "}";
		}else{
			echo '{"result" : ' . json_encode($result) . ",";
			echo '"cause" : ' . json_encode($cause) . ",";
			echo '"value" : ' . json_encode($obj) . "}";
		}
	}
	public static function serialize_object_array_json($array, $result = true, $cause = NULL, $filtre = array()){
		if($result == true && $array && is_array($array)){
			echo '{"result" : ' . json_encode($result) . ",";
			echo '"cause" : ' . json_encode($cause) . ",";
			echo '"value" : ';
			echo '[';
			$len = count($array);
			$i = 0;
			foreach($array as $obj){
				$jsonVue = new JSONVue($obj);
				echo $jsonVue->getJSON($filtre);
				if($i != ($len - 1)){
					echo ',';
				}
				$i++;
			}
			echo ']}';
		}else{
			self::serialize_object_json($array, $result, $cause);
		}
	}
	public static function serialize_assoc_array_json($tab, $result = true, $cause = NULL, $filtre = array()){
		if($result == true && $tab && is_array($tab)){
			echo '{"result" : ' . json_encode($result) . ", " ;
			echo '"cause" : ' . json_encode($cause) . ", " ;
			echo '"value" : ' . "[";
			$len = count($tab);
			$i = 0;
			foreach($tab as $assoc){
				echo '{';
				$lenAssoc = count($assoc);
				foreach($assoc as $key => $obj){
					echo json_encode($key) . ":";
					if(is_object($obj)){
						$jsonVue = new JSONVue($obj);
						echo $jsonVue->getJSON($filtre);
					}else{
						echo json_encode($obj);
					}
					if($lenAssoc > 1){
						echo ',';
					}
					$lenAssoc--;
				}
				echo '}';
				if($i != ($len - 1)){
					echo ',';
				}
				$i++;
			}
			echo ']}';
		}else{
			self::serialize_object_json($tab, $result, $cause, $filtre);
		}
	}

	public static function sendValidationEmail($utilisateur, $activateID){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
	     	$headers .='Reply-To: no-reply@photomentiel.fr'."\n"; 
	     	$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
	     	$headers .='Content-Transfer-Encoding: 8bit'; 
			return mail($utilisateur->getEmail(),
			"Photomentiel - Validez votre compte !",
			"Bienvenue sur Photomentiel !\n\n".
			"Ce message vous a été envoyé suite à la création de votre compte sur www.photomentiel.fr.\n".
			"Afin de le rendre actif, veuillez cliquer sur le lien suivant :\n" .
			"http://".$_SERVER['SERVER_NAME']."/active-".$activateID.$utilisateur->getUtilisateurID()."\n\n".
			"Vous pouvez également copier et coller cette adresse dans votre navigateur.\n\n" .
			"Merci d'utiliser photomentiel.fr\n\n\n".
			"Veuillez ne pas répondre à cet email, celui-ci a été généré automatiquement.\n",
			$headers
			);
		}
	}

	public static function sendNouveauMDPEmail($utilisateur, $mdp, $generated = false){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
	     	$headers .='Reply-To: no-reply@photomentiel.fr'."\n"; 
	     	$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
	     	$headers .='Content-Transfer-Encoding: 8bit';
	     	$content = "Ce message vous a été envoyé suite à votre demande de changement de mot de passe.\n".
			"Changement de mot de passe pour votre compte dont l'Email est : ".$utilisateur->getEmail()."\n";
			if($generated){
				$content .= "Voici votre nouveau mot de passe : ".$mdp."\n\n".
				"Nous vous recommandons de changer ce mot de passe dans la rubrique mon compte sur www.photomentiel.fr.\n\n";
			}else{
				$content .= "Le changement a bien été effectué.\n\n";
			}
			$content.="Merci d'utiliser photomentiel.fr\n\n\n".
			"Veuillez ne pas répondre à cet email, celui-ci a été généré automatiquement.\n";
			return mail($utilisateur->getEmail(),
			"Photomentiel - Nouveau mot de passe",$content,$headers);
		}
	}

	public static function sendFacture($commande){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			//on récupère l'utilisateur
			$id_utilisateur = $commande->getID_Utilisateur();
			$utilisateur = Utilisateur::getUtilisateurDepuisID($id_utilisateur);

			//on récupère la commande entière
			$commandePhotos = $commande->getCommandesPhoto();
			if(!isset($commandePhotos) || !is_array($commandePhotos) || count($commandePhotos) == 0){
				$commande = Commande::getCommandeEtPhotosDepuisID($commande->getCommandeID());
				$commandePhotos = $commande->getCommandesPhoto();
			}

			//on calcul le montant
			$total = 0;
			//on envoie
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
	     	$headers .='Reply-To: no-reply@photomentiel.fr'."\n"; 
	     	$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
	     	$headers .='Content-Transfer-Encoding: 8bit'; 
	     	$content = "Vous venez d'effectuer une commande et nous vous en remercions.\n";
			$content .= "Voici le récapitulatif de votre commande n°" . $commande->getNumero() . "\n\n";

			$taillePapier = TaillePapier::getTaillePapiers();
		     	foreach($commandePhotos as $commandePhoto){
		     		$prix = $commandePhoto->getPrix();
		     		$total += $prix;
		     		$content .= substr($commandePhoto->getPhoto(),0,sizeof($commandePhoto->getPhoto())-5)." | ".$taillePapier[$commandePhoto->getID_TaillePapier()]->getDimensions()." | ".$commandePhoto->getNombre()." | ".$prix." €\n";
		     	}
		     	$content .= "                                Port : ".sprintf('%.2f',$commande->getFDP())." €\n";
		     	$total += $commande->getFDP();
		     	$content .= "                                Total: ".sprintf('%.2f',$total)." €\n\n";
			$content .= "Vous pouvez suivre votre commande ou l'imprimer en vous connectant à votre espace personnel sur www.photomentiel.fr\n";
			$content .= "Merci d'utiliser photomentiel.fr\n\n\n";
			$content .= "Veuillez ne pas répondre à cet email, celui-ci a été généré automatiquement.\n";
			return mail($utilisateur->getEmail(),
			"Photomentiel - Votre commande n°".$commande->getNumero(),
			$content,
			$headers
			);
		}
	}

	public static function sendContactmail($userID, $email, $content){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
     		$headers .='Reply-To: ' . $email ."\n"; 
     		$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
     		$headers .='Content-Transfer-Encoding: 8bit'; 
			return mail("jl@photomentiel.fr, jm@photomentiel.fr",
			"Contact Photomentiel [".$userID."]",
			$content,
			$headers
			);
		}
	}
	
	public static function sendRetraitMail($email, $content){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<retrait@photomentiel.fr>'."\n";
     		$headers .='Reply-To: ' . $email ."\n";
     		$headers .='Content-Type: text/plain; charset="utf-8"'."\n";
     		$headers .='Content-Transfer-Encoding: 8bit';
			return mail("jl@photomentiel.fr, jm@photomentiel.fr",
			"Nouveau retrait demandé",
			$content,
			$headers
			);
		}
	}

	public static function sendPhotographemail($email, $content, $photographe){
		if($_SERVER['SERVER_ADDR'] != "127.0.0.1"){
			$headers ='From: "Photomentiel"<contact@photomentiel.fr>'."\n"; 
     		$headers .='Reply-To: ' . $email ."\n"; 
     		$headers .='Content-Type: text/plain; charset="utf-8"'."\n"; 
     		$headers .='Content-Transfer-Encoding: 8bit'; 
			return mail($photographe->getEmail(),
			"Contact Photomentiel",
			"Un utilisateur vous a envoyé un message (utilisez répondre pour répondre à son adresse mail) :\n\n".
			$content,
			$headers
			);
		}
	}
	/**
	 * C/P from wikipédia: http://fr.wikipedia.org/wiki/Basic_Bank_Account_Number
	 * @param $cbanque
	 * @param $cguichet
	 * @param $nocompte
	 * @param $clerib
	 */
	public static function check_rib($cbanque, $cguichet, $nocompte, $clerib) {
		$tabcompte = "";
		$len = strlen($nocompte);
		if ($len != 11) {
			return false;
		}
		for ($i = 0; $i < $len; $i++) {
			$car = substr($nocompte, $i, 1);
			if (!is_numeric($car)) {
				$c = ord($car) - (ord('A') - 1);
				$b = ($c + pow(2, ($c - 10)/9)) % 10;
				$tabcompte .= $b;
			}
			else {
				$tabcompte .= $car;
			}
		}
		$int = $cbanque . $cguichet . $tabcompte . $clerib;
		return (strlen($int) >= 21 && bcmod($int, 97) == 0);
	}

	public static function upload_album($stringID){
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	    header("Cache-Control: no-store, no-cache, must-revalidate");
	    header("Cache-control: post-check=0, pre-check=0, false");
	    header("Pragma: no-cache");
	    header("Content-Type: application/x-java-jnlp-file");
		echo 
'<?xml version="1.0" encoding="utf-8"?> 
<jnlp 
  spec="1.0+" 
  codebase="http://www.photomentiel.fr/client"> 
  <information> 
    <title>Photomentiel - Photo Uploader</title> 
    <vendor>www.photomentiel.fr</vendor> 
    <!-- <homepage href="docs/help.html"/> --> 
    <description>Application d upload de photos dediee au site www.photomentiel.fr</description> 
    <description kind="short">Application d upload de photos dediee au site www.photomentiel.fr</description>
    <!-- <icon href="images/swingset2.jpg"/> -->
    <!-- <icon kind="splash" href="images/splash.gif"/> --> 
  </information> 
  <security> 
      <all-permissions/> 
  </security> 
  <resources> 
    <j2se version="1.6"/> 
    <jar href="http://www.photomentiel.fr/client/client.jar"/> 
  </resources> 
  <application-desc main-class="photomentiel.controler.Controler">
  <argument>' . $stringID->getHomePhotographe() . '</argument>
  <argument>' . $stringID->getStringID() . '</argument>
  </application-desc>
</jnlp>';	
	}
}
?>
