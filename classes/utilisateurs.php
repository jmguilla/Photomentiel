<?php
$dir_utilisateurs_php = dirname(__FILE__);
include_once $dir_utilisateurs_php . "/modele/Utilisateur.class.php";

echo '
	<div>Utilisateurs:<br />
		<div>logon:<br />
			<form id="formUtilisateurs" method="GET">
				email:&nbsp;<input id="emailUtilisateurs" type="text" name="email" value="email"/><br />
				pwd:&nbsp;<input id="pwdUtilisateurs" type="password" name="pwd"><br />
				<input type="submit" id="submitUtilisateurs" name="submit" value="OK!"/>
			</form>
		</div>
		<div>create utilisateur:<br />
			<form id="formUtilisateurs1" method="GET">
				nom:&nbsp;<input id="nomUtilisateurs1" type="text" value="nom"><br />
				prenom:&nbsp;<input id="prenomUtilisateurs1" type="text" value="prenom"><br />
				pwd:&nbsp;<input id="pwdUtilisateurs1" type="password"><br />
				email:&nbsp;<input id="emailUtilisateurs1" type="text" value="email"/><br />
				<input type="submit" id="submitUtilisateurs1" name="submit" value="OK!"/>
			</form>
		</div>
		<div>create photographe (renseigner aussi ci-dessus):<br />
			<form id="formUtilisateurs3" method="GET">
				nom entreprise:&nbsp;<input id="neUtilisateurs3" type="text"><br />
				siren:&nbsp;<input id="sirenUtilisateurs3" type="text"><br />
				telephone:&nbsp;<input id="telUtilisateurs3" type="text"><br />
				siteweb:&nbsp;<input id="webUtilisateurs3" type="text" value="www."/><br />
				<input type="submit" id="submitUtilisateurs3" name="submit" value="OK!"/>
			</form>
		</div>
		<div>delete utilisateur:<br />
			<form id="formUtilisateurs2" method="GET">
			id:<select id="idUtilisateurs2" name="idp">
						<option value="-1">ID Utilisateur</option>';
$users = Utilisateur::getUtilisateurs();
foreach($users as $user){
	echo '<option value="' . $user->getUtilisateurID() . '">' . get_class($user) . ': ' . $user->getNom() . '</option>';
}
echo '				</select><br />
				<input type="submit" id="submitUtilisateurs2" name="submit" value="OK!"/>
			</form>
		</div>
	</div>
';
?>