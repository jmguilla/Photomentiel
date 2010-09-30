<?php
$dir_administration_controleur_album_php = dirname(__FILE__);
include_once $dir_administration_controleur_album_php . "/../../classes/modele/Album.class.php";

switch($action){
	case valider_upload:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id fourni, impossible de valider l'upload<br/>";
			break;
		}
		$album = Album::getAlbumDepuisID($_POST['id']);
		if($album->validerUpload()){
			$_SESSION['message'] .= "Changement d'état de l'album #" . $album->getAlbumID() . " effectué avec success<br/>";
		}else{
			$_SESSION['message'] .= "Impossible de changer l'état de l'album #" . $album->getAlbumID() . "<br/>";
		}
	break;
	case montrer_album_cree:
		include_once $dir_administration_controleur_album_php . "/../../classes/modele/Evenement.class.php";
		include $dir_administration_controleur_album_php . "/../header.php";
		$assocs = Album::getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates(0, NULL, NULL, false, 0);
		if($assocs){
			echo '<form action="album.php"><input type="submit" value="retour gestion albums"/></form>' . "\n";
			echo "<table><tr><td>id</td><td>nom album</td><td>photographe</td><td>gain total</td></tr>";
			foreach($assocs as $assoc){
				$album = $assoc['Album'];
				$photo = $assoc['Photographe'];
				$stringid = $assoc['StringID'];
				echo '<tr><td>#' . $album->getAlbumID() . ' - </td><td> <a target="_blank" href="../viewalbum.php?al=' . $stringid->getStringID() . '">' . $album->getNom() . '</a> </td><td>' . $photo->getAdresse()->getPrenom() . " - " . $photo->getAdresse()->getNom() . ' </td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="valider_upload"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" onclick="return confirm(\'Confirmez action:\');"  value="valider upload"/></form></td></tr>' . "\n";
			}
			echo "</table>";
			echo '<form action="album.php"><input type="submit" value="retour gestion albums"/></form>' . "\n";
		}else{
			echo "Aucun!";
		}
		include $dir_administration_controleur_album_php . "/../footer.php";
		exit();
	break;
	case supprimer_album:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id fourni, impossible de supprimer l'album<br/>";
			break;
		}
		$album = Album::getAlbumDepuisID($_POST['id']);
		if($album->delete()){
			$_SESSION['message'] .= "Album #" . $album->getAlbumID() . " supprimé avec success<br/>";
		}else{
			$_SESSION['message'] .= "Impossible de supprimer l'album #" . $album->getAlbumID() . "<br/>";
		}
	break;
	case cloturer_album:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id fourni, impossible de cloturer l'album<br/>";
			break;
		}
		$album = Album::getAlbumDepuisID($_POST['id']);
		if($album->cloturer()){
			$_SESSION['message'] .= "Album #" . $album->getAlbumID() . " cloturé avec success<br/>";
		}else{
			$_SESSION['message'] .= "Impossible de cloturer l'album #" . $album->getAlbumID() . "<br/>";
		}
	break;
	case detail_album:
		$dir_administration_controleur_album_php = dirname(__FILE__);
		include_once $dir_administration_controleur_album_php . "/../../classes/modele/Commande.class.php";
		include_once $dir_administration_controleur_album_php . "/../../classes/modele/CommandePhoto.class.php";
		include $dir_administration_controleur_album_php . "/../header.php";
		if(!isset($_POST['id'])){
			echo 'Aucun id_album fourni<br/>';
		}else{
			$ida = $_POST['id'];
			echo '<form action="dispatcher.php" method="post"><input type="hidden" name="action" value="montrer_album"/><input type="submit" value="retour liste album"/></form>' . "\n";
			$album = Album::getAlbumDepuisID($ida);
			list($usec, $sec) = explode(" ", microtime());
			echo 'Détails de l\'album <b>#' . $ida . "</b> - " . $album->getNom() . " - publié le <b>" . $album->getDate() . "</b><br/>\n";
			echo 'Crée il y a <b>' . round((($sec - strtotime($album->getDate())) / (60 * 60 * 24)), 0) . ' jours</b>' . "<br/>\n";
			$commandes = Commande::getCommandeEtPhotosDepuisID_Album($ida);
			if($commandes){
				echo '<br/>' . "\n" . '<h3>Commandes</h3><table>' . "\n";
				$total = 0;
				$totalDernierMois = 0;
				$totalCommandesDernierMois = 0;
				foreach($commandes as $commande){
					$totalCommande = $commande->getFDP();
					$photos = $commande->getCommandesPhoto();
					if($photos){
						foreach($photos as $photo){
							$totalCommande += $photo->getPrix();
						}
					}
					$total += $totalCommande;
					$datePaiement = $commande->getDatePaiement();
					switch($commande->getEtat()){
						case 0: $bgcolor=' bgcolor="red"' ;break;
						case 1: $bgcolor=' bgcolor="orange"' ;break;
						case 2: $bgcolor=' bgcolor="yellow"' ;break;
						case 3: $bgcolor=' bgcolor="blue"' ;break;
						case 4: $bgcolor=' bgcolor="green"' ;break;
					}
					$jours =round((($sec - strtotime($commande->getDate())) / (60 * 60 * 24)), 0);
					if($jours <= 30 && $commande->getEtat() >= 1){
						$totalDernierMois += $totalCommande;
						$totalCommandesDernierMois ++;
					}
					echo '<tr' . $bgcolor . '><td>#' . $commande->getCommandeID() . '</td><td> - ' . $COMMAND_STATES[$commande->getEtat()] . '</td><td> - passée il y a <b>' . $jours . ' jours</b></td><td> - ' . ((isset($datePaiement))?('payée le ' . $datePaiement):('')) . '</td><td> - pour ' . $totalCommande . '&#x20AC;</td><td><form action="dispatcher.php" method="post" target="_blank"><input type="hidden" name="action" value="download_commande_xml"/><input type="hidden" name="id" value="' . $commande->getCommandeID() . '"/><input type="submit" value="download xml"/></form></td></tr>' . "\n";
				}
				echo '</table><br/>' . "\n";
				echo 'Nombre de commandes passées: <b>' . count($commandes) . '</b> pour un total de <b>' . $total . '&#x20AC;</b><br/>' . "\n";
				echo 'Dont <b>' . $totalCommandesDernierMois . ' commandes encaissées</b> au cours des 30 derniers jours pour un total de <b>' . $totalDernierMois . '&#x20AC;</b><br/>' . "\n";
			}else{
				echo 'Aucune commande passée pour cet album<br/>' . "\n";
			}
			echo '<form action="dispatcher.php" method="post"><input type="hidden" name="action" value="montrer_album"/><input type="submit" value="retour liste album"/></form>' . "\n";
		}
		include $dir_administration_controleur_album_php . "/../footer.php";
	exit();
	case montrer_album:
		include_once $dir_administration_controleur_album_php . "/../../classes/modele/Evenement.class.php";
		include $dir_administration_controleur_album_php . "/../header.php";
		$assocs = Album::getNDerniersAlbumsEtImageEtStringIDEtPhotographeEtEvenementEntreDates(0, NULL, NULL, false, 2);
		if($assocs){
			echo '<form action="album.php"><input type="submit" value="retour gestion albums"/></form>' . "\n";
			echo "<table><tr><td>id</td><td>nom album</td><td>photographe</td><td>gain total</td></tr>";
			foreach($assocs as $assoc){
				$album = $assoc['Album'];
				$photo = $assoc['Photographe'];
				$stringid = $assoc['StringID'];
				echo '<tr><td>#' . $album->getAlbumID() . ' - </td><td> <a target="_blank" href="../viewalbum.php?al=' . $stringid->getStringID() . '">' . $album->getNom() . '</a> </td><td>' . $photo->getAdresse()->getPrenom() . " - " . $photo->getAdresse()->getNom() . ' </td><td> ' . $album->getGainTotal() . ' </td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="detail_album"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" value="détails"/></form></td><td><form method="post" action="dispatcher.php"><input type="hidden" name="action" value="cloturer_album"/><input type="hidden" name="id" value="' . $album->getAlbumID() . '"/><input type="submit" onclick="return confirm(\'Vous êtes sur le point de cloturer un album.\nContinuer?\');"  value="cloturer"/></form></td></tr>' . "\n";
			}
			echo "</table>";
			echo '<form action="album.php"><input type="submit" value="retour gestion albums"/></form>' . "\n";
		}else{
			echo "Aucun!";
		}
		include $dir_administration_controleur_album_php . "/../footer.php";
		exit();
	break;
	case valider_album:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id fourni, validation impossible<br/>";
			return ;
		}
		$album = Album::getAlbumDepuisID($_POST['id']);
		if(!$album){
			$_SESSION['message'] .= "Aucun album ne correspond à l'id " . $_POST['id'] . "<br/>";
			return ;
		}
		$result = Album::validerListeAlbum(array($album));
		if($result){
			$_SESSION['message'] .= "Album #" . $album->getAlbumID() . " validé avec succés<br/>";
		}else{
			$_SESSION['message'] .= "Un problème est survenue pendant la validation de l'album #" . $album->getAlbumID() . "<br/>";
		}
	break;
	case activer_album:
		if(!isset($_POST['id'])){
			$_SESSION['message'] .= "Aucun id fourni, validation impossible<br/>";
			return ;
		}
		$album = Album::getAlbumDepuisID($_POST['id']);
		if(!$album){
			$_SESSION['message'] .= "Aucun album ne correspond à l'id " . $_POST['id'] . "<br/>";
			return ;
		}
		$result = Album::activerListeAlbum(array($album));
		if($result){
			$_SESSION['message'] .= "Album #" . $album->getAlbumID() . " activée<br/>";
		}else{
			$_SESSION['message'] .= "Un problème est survenue pendant l'activation de la l'album #" . $album->getAlbumID() . "<br/>";
		}
	break;
	default:
		$_SESSION['message'] .= "action inconnue dans controleur_album " . $action . "<br/>";
	break;
}
header('Location: album.php');
exit();
?>