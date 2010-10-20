<?php
try {
/*
 * events.php displays all events
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 4 août 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
$HEADER_TITLE = "Recherche et visualisation des événements";
$HEADER_DESCRIPTION = "Page de recherche, visualisation et gestion des événements";
include("header.php");
include_once("classes/PMError.class.php");
include_once("classes/modele/Evenement.class.php");
include_once("classes/modele/Photographe.class.php");
include_once("classes/modele/Adresse.class.php");
include_once("classes/modele/Region.class.php");
include_once("classes/modele/Departement.class.php");
include_once("classes/modele/Ville.class.php");
include_once("classes/modele/EvenementEcouteur.class.php");
include_once("classes/modele/StringID.class.php");
include_once("classes/modele/Album.class.php");

$eventSelected = false;
if (isset($_GET['ev'])) {
	$eventSelected = $_GET['ev'];
}

$addEvent = false;
if (isset($_GET['action']) && $utilisateurObj){
	$addEvent = $_GET['action'] === 'add';
}

$evtectMatches = false;
if ($eventSelected && isset($_GET['action']) && $_GET['action'] === 'mailing' && $utilisateurObj){
	if (!EvenementEcouteur::exists($_GET['ev'],$utilisateurObj->getUtilisateurID())){
		$evtEc = new EvenementEcouteur($utilisateurObj->getUtilisateurID(),$_GET['ev']);
		$evtEc->create();
	}
	$evtectMatches = true;
}

$eventAdded = false;
if (isset($_POST['ftype'])){
	//Manage duplication (F5, history back, etc.)
	$createEvent = false;
	$postHash = getHashFromArray($_POST);
	if (isset($_SESSION['eventPostHash'])){
		if ($_SESSION['eventPostHash'] != $postHash){
			$_SESSION['eventPostHash'] = $postHash;
			$createEvent = true;
		}
	} else {
		$_SESSION['eventPostHash'] = $postHash;
		$createEvent = true;
	}
	if ($createEvent){
		$event = new Evenement();
		$event->setType($_POST['ftype']);
		$dateT = preg_split('[/]',$_POST['fdate']);
		$hourT = preg_split('[:]',$_POST['fhour']);
		$d = $dateT[2]."-".$dateT[1]."-".$dateT[0]." ".$hourT[0].":".$hourT[1].":00";
		$event->setDate($d);
		$event->setID_Utilisateur($utilisateurObj->getUtilisateurID());
		$event->setRegion(new Region($_POST['fregion']));
		$event->setDepartement(new Departement($_POST['fdepartement']));
		$event->setVille(new Ville($_POST['fville']));
		$event->setDescription($_POST['fdesc']);
		$event->setAdresse($_POST['faddr']);
		$event->setWeb($_POST['fweb']);
		$d = $event->create();
		if (!$d){
			photomentiel_die(new PMError("Erreur lors de la commande !","Un problème est survenu lors de la création de la commande, veuillez réessayer ultérieurement."),false);
		}
		$eventAdded = true;
		$eventAdded_ID = $d->getEvenementID();
		$_SESSION['lastCreatedEvent'] = $eventAdded_ID;
	} else {
		if (!isset($_SESSION['lastCreatedEvent'])){
			photomentiel_die(new PMError("Erreur lors de la création de l'événement !","Une tentative de duplication de l'événement a généré un problème."),false);
		}
		$eventAdded = true;
		$eventAdded_ID = $_SESSION['lastCreatedEvent'];
	}
}
?>
<script type="text/javascript" src="js/calendar.js" ></script>
<div id="full_content_top">
		<?php
			if ($addEvent || $eventAdded){
				echo 'Ajout d\'un événement';
			} elseif ($eventSelected){
				echo 'Détails de l\'événement';
			} else {
				echo 'Liste des événements';
			}
		?>
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a>
		<?php
			if ($addEvent){
				echo ' &gt; <a href="events.php">Evénements</a>';
				echo ' &gt; Nouvel événement';
			} elseif ($eventSelected) {
				echo ' &gt; <a href="events.php">Evénements</a>';
				echo ' &gt; Evénement';
			} else {
				echo ' &gt; Evénements';
			}
		?>
	</div>
	<div class="separator10"></div>
	<div id="events_content">
		<div id="search">
			<div id="stitle">Chercher des Evénements :</div>
			<form onSubmit="getEvents();return false;">
				Par dates :<br/>
				du <input id="dc_from" class="textfield" type="text" onClick="GetDate(this,false);" onBlur="destroyCalendarOnOut();" onKeyDown="DestroyCalendar();" onFocus="this.select()"/>
				( au <input id="dc_to" class="textfield" type="text" onClick="GetDate(this,false);" onBlur="destroyCalendarOnOut();" onKeyDown="DestroyCalendar();" onFocus="this.select()"/> )<br/>
				Par mots-clés :<br/>
				<input id="keywords" class="textfield" type="textfield" onFocus="this.select()"/><br/>
				Par région :<br/>
				<select id="region">
					<option value="0"></option>
					<?php
						$regionList = Region::getRegions();
						foreach($regionList as $region) {
							echo '<option value="'.$region->getID_Region().'">'.$region->getNom().'</option>';
						}
					?>
				</select><br/>
				Par type :<br/>
				<select id="type">
					<option value="0"></option>
					<?php
						foreach($EVENTS_TYPES as $t) {
							echo '<option value="'.$t.'">'.$t.'</option>';
						}
					?>
				</select><br/>
				<div class="sbutton_holder"><input id="search" class="button" type="submit" value="Chercher" title="Chercher des événements avec les critères sélectionnés"/></div>
			</form>
			<div id="create_event">
			<?php
				if ($utilisateurObj){
					echo '<input id="create_event" class="button" type="button" value="Ajouter un événement" title="Ajouter un nouvel événement" onClick="document.location.href=\'events.php?action=add\';"/>';
				} else {
					echo 'Pour ajouter un événement, vous devez vous connecter ou <br/><a href="adduser.php?np=events.php">créer un compte</a>';
				}
			?>
			</div>
		</div>
		<div id="right">
			<div id="rtitle">
			<?php
				if (!$addEvent && !$eventAdded) {
					if ($eventSelected) {
						echo 'Détails de l\'événement :';
					} else {
						echo 'Voici les derniers événements publics déposés :';
					}
				} else {
					if ($eventAdded){
						echo '<font color="darkgreen">Votre événement a été ajouté avec succés !</font> <a href="events.php?ev='.$eventAdded_ID.'">(voir l\'événement)</a>';
					} else {
						echo 'Pour ajouter un événement, veuillez renseigner les champs suivants :';
					}
				}
			?>
			</div>
			<div id="rcontent" style="height:525px;">
			<?php
				if (!$addEvent) {
					/***************************** VIEWING MODE  ******************************/
					if (!$eventSelected) {
						/***************************** ALL EVENTS  ******************************/
						$nb_evt = 10;
						$evts = Evenement::getNProchainsEvenements($nb_evt);
						if($evts){
							$i=1;
							foreach($evts as $tmp){
								$utilisateur = Utilisateur::getUtilisateurDepuisID($tmp->getID_Utilisateur());
								if ($i%2==0){
									$idi = 'id="impair"'; 
								} else {
									$idi = '';
								}
								echo '<div '.$idi.' class="event">';
								echo '<span class="date"><b>Date</b> : '.date("d/m/Y à G\hi",strtotime($tmp->getDate())).'&nbsp;&nbsp;&nbsp;&nbsp;<b>Type</b> : '.$EVENTS_TYPES[$tmp->getType()].'</span><br/><span class="content">';
								echo '<span class="intitule"><a class="intitule" href="events.php?ev='.$tmp->getEvenementID().'"><b>Intitulé</b> : '.toNchar($tmp->getDescription(),84).'</a></span><br/>';
								if ($tmp->getWeb() != ''){
									echo '<a target="_blank" href="'.$tmp->getWeb().'">Plus de détails sur le lien officiel...</a><br/>';
								} else {
									echo 'Site web non communiqué<br/>';
								}
								echo '</span></div>';
								if ($i == $nb_evt) {break;}
								$i++;
							}
						}
					} else {
						/***************************** SELECTED EVENT  ******************************/
						$evt = Evenement::getEvenementDepuisID($eventSelected);
						if ($evt){
							$utilisateur = Utilisateur::getUtilisateurDepuisID($evt->getID_Utilisateur());
							if ($utilisateur){
								$alb = Album::getAlbumEtImageEtStringIDEtPhotographeEtEvenementDepuisID_Evenement($evt->getEvenementID());
							} else {
								$evt = false;
							}
						}
						if ($evt){
							echo '<div class="event_desc">
								<div class="mailing"><b>Date : </b>'.date("d/m/Y à G\hi",strtotime($evt->getDate())).'&nbsp;&nbsp;&nbsp;&nbsp;<b>Type</b> : '.$EVENTS_TYPES[$evt->getType()].'<br/>
								<span class="desc"><b>Description : </b>'.$evt->getDescription().'</span></div><br/>
								<table CELLSPACING="4px;">
								<tr><td><b>Région : </b></td><td>'.$evt->getRegion()->getNom().'</td></tr>
								<tr><td><b>Département : &nbsp;&nbsp;</b></td><td>'.$evt->getDepartement()->getNom().'</td></tr>
								<tr><td><b>Ville : </b></td><td><span class="highlight">'.$evt->getVille()->getNom().'</span></td></tr>
								<tr><td><b>Date & heure : </b></td><td><span class="highlight">'.date("d/m/Y à G\hi",strtotime($evt->getDate())).'</span></td></tr>
								<tr><td><b>Adresse : </b></td><td><span class="highlight">'.$evt->getAdresse().'</span></td></tr>';
								if ($evt->getWeb() != ''){
									echo '<tr><td colspan="2"><b>Lien vers l\'événement officiel : </b><a target="_blank" href="'.$evt->getWeb().'">'.toNchar($evt->getWeb(),60).'</a></td></tr>';
								} else {
									echo '<tr><td colspan="2">Site internet non communiqué</td>';
								}
								echo '</table></div>';
								//this event is interesting you
								echo '<div class="event_desc">';
								if ($alb){
									echo '<b>Accéder aux albums disponibles pour cet événement :</b>';
									for ($s=0;$s<sizeof($alb);$s++){
										$strID = StringID::getStringIDDepuisID_Album($alb[$s]['Album']->getAlbumID());
										echo   '<br/>&nbsp;&nbsp;&nbsp;<b><a href="viewalbum.php?al='.$strID->getStringID().'">'.toNChar($alb[$s]['Album']->getNom(),80).'</a></b>';
									}
								} else {
									echo   'Vous souhaitez être prévenu dès qu\'un album est disponible sur cet événement ?<br><span id="span_mailing">';
									if (!$utilisateurObj){
										echo 'Veuillez nous préciser votre E-mail ici : <input id="mailing" type="textfield" class="textfield"/> puis <a href="javascript:addMailing(\''.$evt->getEvenementID().'\');">cliquer ici...</a>';
									} else if($utilisateurObj){
										if (!$evtectMatches){
											$evtectMatches = EvenementEcouteur::exists($_GET['ev'],$utilisateurObj->getUtilisateurID());
										}
										if ($evtectMatches){
											echo '<u>Vous êtes inscrit à cet événement</u>';
										} else {
											echo 'Veuillez <a href="events.php?ev='.$evt->getEvenementID().'&action=mailing">cliquer ici...</a>';
										}
									}
									echo   '</span>';
								}
								echo '</div><div class="event_desc" style="height:235px;">';
								//contact host via mail
								?>
								<div class="separator2"></div>
								<b>Pour contacter le responsable par E-mail, veuillez remplir les champs suivants :</b><br/>
								Votre E-mail : 
								<div class="separator2"></div>
								<input id="p_email" type="textfield" class="textfield" name="email" style="width:200px;" value="<?php echo $utilisateurObj?$utilisateurObj->getEmail():''; ?>"/>
								<div class="separator5"></div>
								Votre message :
								<div class="separator2"></div>
								<textarea id="p_content" class="textfield" cols="79" rows="4" name="content"></textarea>
								<div class="separator5"></div>
								<span style="font-size:11px;">Veuillez recopier ces caractères en respectant les majuscules et les minuscules : </span>
								<img align="top" src="captcha.php" title="Recopiez le code"/> 
								<input id="p_captcha" type="text" class="textfield" maxlength="5" style="width:40px;"></input><br>
								<div class="separator2" style="height:3px;"></div>
								<center><input id="p_send" type="button" class="button" value="Envoyer" onClick="sendEmailToUser('<?php echo $utilisateur->getUtilisateurID(); ?>');"/></center>
								<div class="separator2" style="height:3px;"></div>
								<center><span id="p_error"></span><span id="p_success"></span></center>
								<?php
								echo   '</div>';
						} else {
							echo "<br/>Cet événement n'existe pas !";
						}
					}
				} else {
					/***************************** EVENT CREATION FORM ***************************/
				?>
					<div class="event_desc">
					<form id="event_add_form" method="POST" action="events.php">
						<table>
							<tr>
								<td width="160px">
									Type* :
								</td><td>
									<select id="ftype" name="ftype">
										<?php
											$i = 0;
											foreach($EVENTS_TYPES as $t) {
												echo '<option value="'.$i.'">'.$t.'</option>';
												$i++;
											}
										?>
									</select>
								</td><td>
									<div class="checkform" id="rftype"></div>
								</td>
							</tr><tr>
								<td width="120px">
									Date (jj/mm/aaaa)* :
								</td><td>
									<input id="fdate" name="fdate" class="textfield" type="text" regexp="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[0-9]{4}$" onKeyUp="if(this.value.length==2 || this.value.length==5){this.value+='/';}" required="required"/>
								</td><td>
									<div class="checkform" id="rfdate"></div>
								</td>
							</tr><tr>
								<td>
									Heure (hh:mm)* :
								</td><td>
									<input id="fhour" name="fhour" class="textfield" type="text" regexp="([01][1-9]|[2][0-3]):[0-5][0-9]$" onKeyUp="if(this.value.length==2){this.value+=':';}" required="required"/>
								</td><td>
									<div class="checkform" id="rfhour"></div>
								</td>
							</tr><tr>
								<td>
									Region* :
								</td><td>
									<select id="fregion" name="fregion">
										<?php
											foreach($regionList as $region) {
												echo '<option value="'.$region->getID_Region().'">'.$region->getNom().'</option>';
											}
										?>
									</select>
								</td><td>
									<div class="checkform" id="rfregion"></div>
								</td>
							</tr><tr>
								<td>
									Département* :
								</td><td>
									<select id="fdepartement" name="fdepartement">
										<?php
											$depts = Departement::getDepartementDepuisID_Region($regionList[0]->getID_Region());
											foreach($depts as $dept) {
												echo '<option value="'.$dept->getID_Departement().'">'.$dept->getNom().'</option>';
											}
										?>
									</select>
								</td><td>
									<div class="checkform" id="rfdepartement"></div>
								</td>
							</tr><tr>
								<td>
									Ville* :
								</td><td>
									<select id="fville" name="fville">
										<?php
											$villes = Ville::getVilleDepuisID_Departement($depts[0]->getID_Departement());
											foreach($villes as $ville) {
												echo '<option value="'.$ville->getID_Ville().'">'.$ville->getNom().'</option>';
											}
										?>
									</select>
								</td><td>
									<div class="checkform" id="rfville"></div>
								</td>
							</tr><tr>
								<td>
									Description* :
								</td><td>
									<input id="fdesc" name="fdesc" class="textfield" type="text" required="required"/>
								</td><td>
									<div class="checkform" id="rfdesc"></div>
								</td>
							</tr><tr>
								<td>
									Adresse physique :
								</td><td>
									<input id="faddr" name="faddr" class="textfield" type="text"/>
								</td><td>
									<div class="checkform" id="raddr"></div>
								</td>
							</tr><tr>
								<td>
									Adresse web :
								</td><td>
									<input id="fweb" name="fweb" class="textfield" type="text" value="http://"/>
								</td><td>
									<div class="checkform" id="rfweb"></div>
								</td>
							</tr>
						</table>
						<div class="separator10"></div>						
						<center><input type="submit" class="button" value="Soumettre"/></center>
						<div class="separator5"></div>
					</form>
					</div>
				<?php
				}
			?>
			</div>
		</div>
		<div class="separator10"></div>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
}catch (Exception $e){
	echo "Internal server error !";
	include_once("classes/controleur/ControleurUtils.class.php");
	ControleurUtils::addError("Erreur dans events.php\n" . $e->getMessage(), true);
}
?>

