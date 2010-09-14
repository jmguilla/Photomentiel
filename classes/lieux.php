<?php
include_once("modele/Region.class.php");

$regions = Region::getRegions();
 
echo '
<div>Lieux:<br />
<div id="champsRegion"><label for="region">R&eacute;gion: </label>
<select id="region" name="region"> 
	<option value="-1">R&eacute;gions</option>';
$regions = Region::getRegions();
foreach($regions as $region){
	echo '<option value="'.$region->getID_Region().'">'.$region->getNom().'</option>';
}
echo '</select>
</div>
<div id="champsDpt"><label for="dpt">D&eacute;partement: </label>
	<select id="dpt" name="dpt">
		<option value="-1">Choisir une r&eacute;gion</option> 
	</select> 
</div>
<div id="champsVille">
<label for="ville">Ville: </label>
	<select id="ville" name="ville"> 
		<option value="-1">Choisir un d&eacute;partement</option>
	</select>
</div>
</div>
';
?>