<?php
$dirname = dirname(__FILE__);
include_once $dirname . "/ControleurUtils.class.php";
include_once $dirname . "/../modele/Utilisateur.class.php";
include_once $dirname . "/../modele/Photographe.class.php";
include_once $dirname . "/../modele/Adresse.class.php";
include_once $dirname . "/../vue/JSONVue.class.php";
include_once $dirname . "/externalization.php";

switch($action){
	case s_email_photographe:
		if(!isset($_POST['idphotographe']) || !isset($_POST['msg']) || !isset($_POST['captcha']) || !isset($_POST['email'])){
			ControleurUtils::serialize_object_json(false, true, "Informations manquantes, impossible d'envoyer email au photographe.");
			return;
		}
		$idp = $_POST['idphotographe'];
		$message = $_POST['msg'];
		$captcha = $_POST['captcha'];
		$email = $_POST['email'];
		if($captcha != $_SESSION['Captcha']){
			ControleurUtils::serialize_object_json(false, true, "Captcha non valide." . $captcha . " - " . $_SESSION['Captcha']);
			return;
		}
		$photographe = Photographe::getPhotographeDepuisID($idp);
		if(!$photographe){
			ControleurUtils::serialize_object_json(false, true, "Aucun photographe avec l'id #" . $idp);
			return;
		}
		ControleurUtils::serialize_object_json(ControleurUtils::sendPhotographemail($email, $message, $photographe), true, "Impossible d'envoyer l'email au photographe #" . $idp);
	break;
	case send_facture:
		$dirname = dirname(__FILE__);
		include_once $dirname . "/../modele/Commande.class.php";
		include_once $dirname . "/../modele/TaillePapier.class.php";
		if(isset($_POST['id'])){
			$id = $_POST['id'];
		}else{
			$id = $_GET['id'];
		}
		ControleurUtils::serialize_object_json(ControleurUtils::sendFacture(Commande::getCommandeEtPhotosDepuisID($id)));
	break;
	case logon:
		if(isset($_POST['email'])){
			$email = $_POST['email'];
		}else{
			$email = $_GET['email'];
		}if(isset($_POST['pwd'])){
			$pwd = $_POST['pwd'];
		}else{
			$pwd = $_GET['pwd'];
		}
		if(!isset($pwd) || !isset($email)){
			throw new InvalidArgumentException("Mot de passe et E-mail requis pour s'identifier.");
		}
		$utilisateur = Utilisateur::logon($email,$pwd);
		ControleurUtils::serialize_object_json($utilisateur);
	break;
	case c_photographe:
		$ne = $_POST['entreprise'];
		$siren = $_POST['siren'];
		$tel = $_POST['telephone'];
		$web = $_POST['site_web'];
		$rib_b = $_POST['banque'];
		$rib_g = $_POST['guichet'];
		$rib_c = $_POST['numero_compte'];
		$rib_k = $_POST['cle_rib'];
		if(!ControleurUtils::check_rib($rib_b, $rib_g, $rib_c, $rib_k)){
			throw new InvalidArgumentException("RIB invalide. Vous devez fournir un RIB correct.");
		}
	case c_usr:
		if($action == c_photographe){
			$utilisateur = new Photographe();
			$utilisateur->setNomEntreprise($ne);
			$utilisateur->setSiren($siren);
			$utilisateur->setTelephone($tel);
			$utilisateur->setSiteWeb($web);
			$utilisateur->setRIB_b($rib_b);
			$utilisateur->setRIB_g($rib_g);
			$utilisateur->setRIB_c($rib_c);
			$utilisateur->setRIB_k($rib_k);
		}else{
			$utilisateur = new Utilisateur();			
		}
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$pwd = $_POST['pwd'];
		$email = trim($_POST['email']);
		$adresse1 = $_POST['adresse1'];
		$adresse2 = $_POST['adresse2'];
		$cp = $_POST['code_postal'];
		$ville = $_POST['ville'];
		$isValid = Utilisateur::controleEmail($email);
		if(!$isValid){
			ControleurUtils::serialize_object_json($isValid, false, "Cet E-mail est déjà utilisé.");
			break;
		}
		$utilisateur->setEmail($email);
		$utilisateur->setMDP($pwd);
		$adresse = new Adresse();
		$adresse->setNom($nom);
		$adresse->setPrenom($prenom);
		$adresse->setNomRue($adresse1);
		$adresse->setComplement($adresse2);
		$adresse->setVille($ville);
		$adresse->setCodePostal($cp);
		$utilisateur->setAdresse($adresse);
		$activateID = session_id();
		$utilisateur = $utilisateur->create($activateID);
		if($utilisateur){//maintenant on va valider l'email par retour
			if(ControleurUtils::sendValidationEmail($utilisateur,$activateID)){				
				ControleurUtils::serialize_object_json($utilisateur);
				return;
			}else{
				//erreur, on detruit l'utilisateur & retourne.
				$utilisateur->delete();
				ControleurUtils::serialize_object_json(false, false, "Impossible d'envoyer l'E-mail de validation. Création interrompue.");
				return;
			}
		}else{
			//erreur, on retourne
			ControleurUtils::serialize_object_json(false, false, $adresseError . "Impossible de créer l'utilisateur.");
			return;
		}
	break;
	case check_email:
		$email = trim($_GET['email']);
		$isValid = Utilisateur::controleEmail($email);
		ControleurUtils::serialize_object_json($isValid);
	break;
	case lost_pwd:
		$email = $_GET['email'];
		$isValid = Utilisateur::controleEmail($email);
		if($isValid){
			ControleurUtils::serialize_object_json(false, false, "Cet E-mail ne correspond à aucun compte.");
			break;
		}
		$array = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n',
		'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4',
		'5', '6', '7', '8', '9');
		$mdp = '';
		for($i = 0; $i < 6; $i++){
			$mdp = $mdp . $array[rand(0, (count($array) - 1))];
		}
		$user = new Utilisateur();
		$user->setEmail($email);
		$res = $user->saveMDPEtEnvoyerEmail($mdp);
		if($res){//on envoie un email avec le nouveau mdp
			ControleurUtils::serialize_object_json(true, true);
		}else{
			ControleurUtils::serialize_object_json(false, false, "Impossible de générer un nouveau mot de passe.");
		}
	break;
	case u_photographe:
	case u_usr:
		if(isset($_SESSION['userID'])){
			$uid = $_SESSION['userID'];
		}else{
			throw new InvalidArgumentException("Impossible de modifier le compte d'un utilisateur non identifié!");
		}
		$utilisateur = Utilisateur::getUtilisateurDepuisID($uid);//utilisateur est un usr || photographe
		if($action == u_photographe){
			if(get_class($utilisateur) == "Utilisateur"){
				throw new InvalidArgumentException("Demande de modification de compte photographe sur un simple utilisateur.");
			}
			$ne = $_POST['entreprise'];
			$siren = $_POST['siren'];
			$tel = $_POST['telephone'];
			$web = $_POST['site_web'];
			$rib_b = $_POST['banque'];
			$rib_g = $_POST['guichet'];
			$rib_c = $_POST['numero_compte'];
			$rib_k = $_POST['cle_rib'];
			if(!ControleurUtils::check_rib($rib_b, $rib_g, $rib_c, $rib_k)){
				throw new InvalidArgumentException("RIB invalide. Vous devez fournir un RIB correct.");
			}
			$utilisateur->setNomEntreprise($ne);
			$utilisateur->setSiren($siren);
			$utilisateur->setTelephone($tel);
			$utilisateur->setSiteWeb($web);
			$utilisateur->setRIB_b($rib_b);
			$utilisateur->setRIB_g($rib_g);
			$utilisateur->setRIB_c($rib_c);
			$utilisateur->setRIB_k($rib_k);
		}
		$pwd = $_POST['pwd'];
		$adresse = $utilisateur->getAdresse();
		if(!$adresse){
			$adresse = new Adresse();
		}
		$nom = $_POST['nom'];
		$prenom = $_POST['prenom'];
		$adresse1 = $_POST['adresse1'];
		$adresse2 = $_POST['adresse2'];
		$cp = $_POST['code_postal'];
		$ville = $_POST['ville'];
		$adresse->setNomRue($adresse1);
		$adresse->setComplement($adresse2);
		$adresse->setVille($ville);
		$adresse->setCodePostal($cp);
		$adresse->setNom($nom);
		$adresse->setPrenom($prenom);
		$utilisateur->setAdresse($adresse);
		$utilisateur = $utilisateur->save();
		if(!$utilisateur){
			throw new Exception(((isset($errorMess))?$errorMess:'') . "Impossible de sauver les modifications du compte utilisateur.");
		}else{
			if(isset($pwd) && $pwd != ''){
				$utilisateur = $utilisateur->saveMDP($pwd);
				if(!$utilisateur){
					$errorMess .= "Impossible de changer le mot de passe du compte utilisateur.";
				}
			}
			if(isset($errorMess)){
				throw new Exception(((isset($errorMess))?$errorMess:'') . "Les modifications du compte utilisateur ont tout de même été sauvées.");
			}else{
				ControleurUtils::serialize_object_json($utilisateur, true, NULL);
			}		
		}
	break;
	default:
		throw new InvalidArgumentException("Action inconnue dans controlleur utilisateur: " . $action);
	break;
}

?>
