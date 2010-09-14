<?php
$dir_evenements_php = dirname(__FILE__);
include_once $dir_evenements_php . "/modele/Photographe.class.php";
include_once $dir_evenements_php . "/modele/Evenement.class.php";
include_once $dir_evenements_php . "/modele/Album.class.php";

echo '
	<div>Evenements:<br />
		<div>get evenements entre 2 dates:<br />
			<form id="formEvenements1" method="GET">
				date1:&nbsp;<input id="d1Evenements1" type="text" name="d1" value="2010-01-01"/><br />
				date2:&nbsp;<input id="d2Evenements1" type="text" name="d2" value="2011-01-01"><br />
				<input type="submit" id="submitEvenements1" name="submit" value="OK!"/>
			</form>
		</div>
		<div>get n premiers evenements entre 2 dates:<br />
			<form id="formEvenements2" method="GET">
				n:&nbsp;<input id="nEvenements2" type="text" name="n" value="2"/><br />
				date1:&nbsp;<input id="d1Evenements2" type="text" name="d1" value="2010-01-01"/><br />
				date2:&nbsp;<input id="d2Evenements2" type="text" name="d2" value="2011-01-01"><br />
				<input type="submit" id="submitEvenements2" name="submit" value="OK!"/>
			</form>
		</div>
		<div>Creer nouvel album:<br />
			<form id="formEvenements3" method="GET">
				nom:&nbsp;<input id="nomEvenements3" type="text" name="nom" value="nom album"/><br />
				id photographe:&nbsp;
					<select id="idpEvenements3" name="idp">
						<option value="-1">ID Photographe</option>';
$photographes = Photographe::getPhotographes();
foreach($photographes as $photographe){
	echo '<option value="' . $photographe->getPhotographeID() . '">' . $photographe->getNom() . '</option>';
}
echo '				</select><br />
				id evenement:&nbsp;
					<select id="ideEvenements3" type="text" name="ide">
						<option value="-1">ID Evenement</option>';
$evenements = Evenement::getEvenements();
foreach($evenements as $evenement){
	echo '<option value="' . $evenement->getEvenementID() . '">' . $evenement->getEvenementID() . '</option>';
}
echo '				</select><br />
				<input type="submit" id="submitEvenements3" name="submit" value="OK!"/>
			</form>
		</div>
		<div>d&eacute;truire un album:<br />
			<form id="formEvenements4" method="GET">
				id album:&nbsp;
					<select id="idEvenements4" name="idp">
						<option value="-1">ID Album</option>';
$albums = Album::getAlbums(false);
foreach($albums as $album){
	echo '<option value="' . $album->getAlbumID() . '">' . $album->getNom() . '</option>';
}
echo '				</select><br />
				<input type="submit" id="submitEvenements4" name="submit" value="OK!"/>
			</form>
		</div>
	</div>
';
?>