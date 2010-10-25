<?php
@session_start();
$dir_administration_dispatcher_php = dirname(__FILE__);
include_once $dir_administration_dispatcher_php . "/controleur/externalization.php";
if(isset($_POST['action'])){
	$action = $_POST['action'];
}else{
	$action = 'dummy';
}
switch($action){
	case supprimer_error:
		include $dir_administration_dispatcher_php . "/controleur/controleur_error.php";
	break;
	case detail_retrait:
	case supprimer_retrait:
	case suppression_demande_retrait:
		include $dir_administration_dispatcher_php . "/controleur/controleur_retrait.php";
	break;
	case valider_album:
	case activer_album:
	case montrer_album:
	case detail_album:
	case cloturer_album:
	case supprimer_album:
	case montrer_album_cree:
	case valider_upload:
	case supprimer_photo:
		include_once $dir_administration_dispatcher_php . "/controleur/controleur_album.php";
	break;
	case set_commande_foto:
	case detail_commande:
	case traiter_commande:
	case commande_expediee:
	case supprimer_commande:
	case down_xml:
	case offrir_commande:
		include_once $dir_administration_dispatcher_php . "/controleur/controleur_commande.php";
	break;
	case supprimer_evenement:
		include_once $dir_administration_dispatcher_php . "/controleur/controleur_evenement.php";
	break;
	case envoyer_confirmation_paiement:
	case renvoyer_email_confirmation:
	case activer_utilisateur:
	case reinitialiser_mdp:
	case payer:
	case modifier_photographe:
		include_once $dir_administration_dispatcher_php . "/controleur/controleur_utilisateur.php";
	break;
	//pour empecher mauvais formattage
	case 'dummy':
	break;
	default:
		echo "action inconnue dans dispatcher " . $action;
	break;
}
?>