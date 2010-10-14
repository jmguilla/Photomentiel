<?php
$dir_administration_controleur_retrait_php = dirname(__FILE__);
include_once $dir_administration_controleur_retrait_php . "/../../classes/modele/RetraitPhoto.class.php";
include_once $dir_administration_controleur_retrait_php . "/../../classes/Config.php";

switch($action){
	case suppression_demande_retrait:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Pas d'id fourni, impossible de supprimer.<br/>";
			break;
		}
		$retrait = RetraitPhoto::getRetraitPhoto($_POST['id']);
		if($retrait && $retrait->delete()){
			$_SESSION['message'] .= "Suppression de la demande de retrait #" . $_POST['id'] . " OK.<br/>";
			break;
		}else{
			$_SESSION['message'] .= "Suppression de la demande de retrait #" . $_POST['id'] . " impossible.<br/>";
			break;
		}
	break;
	case supprimer_retrait:
		if(!isset($_POST['thumb']) || !isset($_POST['path']) || !isset($_POST['id']) || !isset($_POST['ref'])){
			$_SESSION['message'] .= "Pas de path fourni/id, impossible de supprimer l'image.<br/>";
			break;
		}
		if(!file_exists($_POST['path'])){
			$_SESSION['message'] .= "L'image n'existe pas, impossible de la supprimer.<br/>";
			break;
		}
		if(!file_exists($_POST['thumb'])){
			$_SESSION['message'] .= "La miniature n'existe pas, impossible de la supprimer.<br/>";
			break;
		}
		if(unlink($_POST['path']) && unlink($_POST['thumb'])){
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
		if(isset($_SESSION['message'])){
			echo $_SESSION['message'];
			unset($_SESSION['message']);
		}
		$_SESSION['message'] = '';
		echo '<h3>Détail du retrait #'. $retrait->getRetraitPhotoID() . ' appartenant à l\'album #'. $album->getAlbumID() . '</h3>';
		echo '<a target="_blank" href="http://admin.photomentiel.fr/retraits/' . $retrait->getJustificatif() . '">justificatif</a><br/>';
		$raison = $retrait->getRaison();
		if(!isset($raison) || $raison == ''){
			echo '<table border="1px"><tr><td>pas de raison donnée</td></tr></table>';
		}else{
			echo '<table border="1px"><tr><td>' . $raison . '</td></tr></table>';
		}
		echo '<form action="dispatcher.php" method="post">suppression de la demande:<input type="hidden" name="action" value="suppression_demande_retrait"/><input type="hidden" name="id" value="' . $retrait->getRetraitPhotoID() . '"/><input type="submit" onclick="return confirm(\'Continuer la suppression?\');" value="supprimer"/></form>';
		echo 'liste de photos concernées: ' . $retrait->getRef() . '<br/>';
		$listExtensions = array(".jpg", ".jpeg", ".tif", ".png");
		$refs = str_replace(',',';',$retrait->getRef());
		if(strpos($refs,";")){
			$refs = explode(';',$refs);
		}else{
			$refs = array($refs);
		}
		$toRemove = array();
		foreach($refs as $ref){
			foreach($listExtensions as $extension){
				$picPath = PHOTOGRAPHE_ROOT_DIRECTORY . $sid->getHomePhotographe() . "/" . $sid->getStringID() . "/" . PICTURE_DIRECTORY . trim($ref) . $extension;
				$thumbPath = PHOTOGRAPHE_ROOT_DIRECTORY . $sid->getHomePhotographe() . "/" . $sid->getStringID() . "/" . THUMB_DIRECTORY . trim($ref) . $extension;
				if(file_exists($picPath) && file_exists($thumbPath)){
					$toRemove[] = array("Thumb" => $thumbPath, "Picture" => $picPath, "Ref" => (trim($ref)));
				}
				if((file_exists($picPath) && !file_exists($thumbPath))){
					echo '<div>Path de picture :' . $picPath . ' n\'a aucune miniature associée, supprimer à la mains SVP & controler svp</div><br/>';
				}
				if( (!file_exists($picPath) && file_exists($thumbPath))){
					echo '<div>Path de miniature :' . $picPath . ' n\'a aucune image associée, supprimer à la mains SVP & controler svp</div><br/>';
				}
			}
		}
		if(count($toRemove) > 0){
			echo '<table>';
			foreach($toRemove as $assoc){
				$ref = $assoc["Ref"];
				$thumbPath = $assoc["Thumb"];
				$picPath = $assoc["Picture"];
				echo '<tr><td><a target="_blank" href="http://www.photomentiel.fr/pictures/' . $sid->getHomePhotographe() . "/" . $sid->getStringID() . "/" . PICTURE_DIRECTORY . $ref . '">voir la photo</a></td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="supprimer_retrait"/><input type="hidden" name="path" value="' . $picPath . '"/><input type="hidden" name="thumb" value="' . $thumbPath . '"/><input type="hidden" name="id" value="' . $retrait->getRetraitPhotoID() . '"/><input type="hidden" name="ref" value="' . $ref . '"/><input type="submit"  onclick="return confirm(\'Continuer la suppression?\');" value="supprimer"/></form></td>' ;
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