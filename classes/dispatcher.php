<?php
@session_start();
$dir_dispatcher_php = dirname(__FILE__);
include_once $dir_dispatcher_php . "/controleur/externalization.php";

//TODO passer de GET a POST?
try{
	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}else{
		$action = $_POST['action'];
	}
	switch($action){
		case l_region:
		case l_dpt_reg:
		case l_ville_dpt:
		case g_ville_from_cp:
		case g_ville_from_nom:
			include_once("controleur/controleur_lieux.php");
		break;
		case logon:
		case c_usr:
		case u_usr:
		case a_usr:
		case c_photographe:
		case u_photographe:
		case check_email:
		case lost_pwd:
		case s_email_contact:
		case send_facture:
		case s_email_photographe:
			include_once $dir_dispatcher_php . "/controleur/controleur_utilisateurs.php";
		break;
		case g_evt_id:
		case gnp_evt_entre_dates:
		case gnp_evt:
		case gnp_evt_apres_date:
		case g_evt_date:
		case g_evt_entre_dates:
		case gnd_evt:
		case gnd_album:
		case gnd_album_plus:
		case c_album:
		case s_album:
		case c_evt:
		case s_evt:
		case d_album:
		case ss_album:
		case ss_evt:
		case gnd_album_plus_entre_dates:
		case a_m_evt:
		case a_m_album:
			include_once $dir_dispatcher_php . "/controleur/controleur_evenements.php";
		break;
		case g_sid:
		case g_sid_p_ida:
			include_once $dir_dispatcher_php . "/controleur/controleur_stringid.php";
		break;
		case gr_image_thumb_path:
			include_once $dir_dispatcher_php . "/controleur/controleur_images.php";
		break;
		case s_commande:
			include_once $dir_dispatcher_php . "/controleur/controleur_commandes.php";
		break;
		default:
			throw new InvalidArgumentException("Action inconnue dans dispatcher: " . $action);
		break;
	}
}catch(Exception $e){
	include_once $dir_dispatcher_php . "/controleur/ControleurUtils.class.php";
	ControleurUtils::serialize_object_json(NULL, false, $e->getMessage());
}
?>