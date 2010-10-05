<?php
$dir_administration_controleur_retrait_php = dirname(__FILE__);
include_once $dir_administration_controleur_retrait_php . "/../../classes/modele/RetraitPhoto.class.php";
include_once $dir_administration_controleur_retrait_php . "/../../classes/Config.php";

switch($action){
	case supprimer_retrait:
		if(!isset($_POST['path']) || !isset($_POST['id']) || !isset($_POST['ref'])){
			$_SESSION['message'] .= "Pas de path fourni/id, impossible de supprimer l'image.<br/>";
			break;
		}
		if(!file_exists($_POST['path'])){
			$_SESSION['message'] .= "L'image n'existe pas, impossible de la supprimer.<br/>";
			break;
		}
		if(unlink($_POST['path'])){
			$retrait = RetraitPhoto::getRetraitPhoto($_POST['id']);
			$ref = $_POST['ref'];
			$retrait->setRef(str_replace($ref,'',$retrait->getRef()));
			if($retrait->saveRef()){
				$_SESSION['message'] .= "Image supprimée avec succes<br/>";
			}else{
				$_SESSION['message'] .= "Image supprimée avec succes mais impossible de mettre à jour l'objet en BD.<br/>";
			}
		}else{
			$_SESSION['message'] .= "Impossible de supprimer l'image<br/>";
		}
	case detail_retrait:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id retrait fournie.<br/>";
			break;
		}
		include_once $dir_administration_controleur_retrait_php . "/../../classes/modele/StringID.class.php";
		include_once $dir_administration_controleur_retrait_php . "/../../classes/modele/Album.class.php";
		$id = $_POST['id'];
		$retrait = RetraitPhoto::getRetraitPhoto($id);
		$sid = StringID::getStringIDDepuisID($retrait->getStringID());
		$album = Album::getAlbumDepuisID($sid->getID_Album());
		include $dir_administration_controleur_retrait_php . "/../header.php";
		echo '<h3>Détail du retrait #'. $retrait->getRetraitPhotoID() . ' appartenant à l\'album #'. $album->getAlbumID() . '</h3>';
		echo '<a target="_blank" href="retraits/' . $retrait->getJustificatif() . '">justificatif</a><br/>';
		$raison = $retrait->getRaison();
		if(!isset($raison) || $raison == ''){
			echo '<table border="1px"><tr><td>pas de raison donnée</td></tr></table>';
		}else{
			echo '<table border="1px"><tr><td>' . $raison . '</td></tr></table>';
		}
		echo 'liste de photos concernées: ' . $retrait->getRef() . '<br/>';
		$refs = explode(';',$retrait->getRef());
		if(isset($refs) && $refs != ''){
			echo '<table>';
			foreach($refs as $ref){
				if(trim($ref) != ''){
					echo '<tr><td><a target="_blank" href="../../pictures/' . $sid->getHomePhotographe() . "/" . $sid->getStringID() . "/" . trim($ref) . '">voir la photo</a></td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="supprimer_retrait"/><input type="hidden" name="path" value="' . PHOTOGRAPHE_ROOT_DIRECTORY . $sid->getHomePhotographe() . "/" . $sid->getStringID() . "/" . trim($ref) . '"/><input type="hidden" name="id" value="' . $retrait->getRetraitPhotoID() . '"/><input type="hidden" name="ref" value="' . $ref . '"/><input type="submit" value="supprimer"/></form></td>' ;
				}
			}
			echo '</table>';
		}
		include $dir_administration_controleur_retrait_php . "/../footer.php";
		exit();
	break;
	default:
		$_SESSION['message'] .= "Action " . $action . " inconnue dans controleur_retrait.<br/>";
	break;
	
}
header('Location: retrait_photo.php');
exit();
?>