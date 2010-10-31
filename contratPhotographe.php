<?php
/*
 * contratPhotographe.php is used to edit contract between photomentiel and photographs
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 23 Oct 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
 //assert $utilisateurObj exist and is a photographe
?>
<div id="title">CONTRAT LIANT LE PHOTOGRAPHE ET PHOTOMENTIEL</div>
<br/><br/>
<i>Ce contrat est établi, entre d'une part le photographe, ci-aprés dénommé "le Photographe" :</i><br/>
La Société : <?php echo $utilisateurObj->getNomEntreprise(); ?><br/>
<?php $adress = $utilisateurObj->getAdresse(); ?>
représenté par : <?php echo $adress->getNom()." ".$adress->getPrenom(); ?><br/>
et dont le Numéro SIREN est : <?php echo $utilisateurObj->getSiren(); ?><br/>
<br/>
<i>et d'autre part les associés Photomentiel, ci-aprés dénommé "Photomentiel" :</i><br/>
Le service Photomentiel,<br/>
représenté par les associés : SCHEEFER Jean-Luc & GUILLAUME Jean-Michel<br/>
dont les SIREN respectifs sont : 525329272 & 0521000018.<br/>
<br/>
<br/>
Entre les 2 parties, il est convenu ce qui suit :<br/>


<h1>Article 1 : Préambule</h1>
<span class="start"></span>Le présent contrat définit les conditions dans lesquelles Photomentiel autorise le Photographe à utiliser son service par l'intermédiaire du site <span class="photomentiel">www.photomentiel.fr</span><br/>
En acceptant ce contrat, le Photographe s'engage à respecter chacun de ses articles et à s'y conformer.<br/>
Le terme "client" utilisé dans le présent contrat désigne tout client du photographe pouvant accéder au site et acheter des photos.<br/>


<h1>Article 2 : Service</h1>
<span class="start"></span>Photomentiel offre principalement au Photographe un service en ligne de mise à disposition de ses albums pour ses clients. Photomentiel assure aussi le service de vente en ligne des photos par ses clients via un paiement internet sécurisé par carte bancaire, ainsi que les services d'impression et de livraison.<br/>
Le Photographe sera informé par mail à chaque nouvelle commande effectuée sur ses albums et en recevra la facture, facturé en son nom.<br/>
De ce fait, le Photographe accepte expressément et sans condition que Photomentiel facture ses clients en son nom. La facture est donc délivrée en 2 exemplaires (sur lesquels apparaîtra le nom de la société et les nom/prénom du Photographe), dont un est destiné au client et l'autre au Photographe.<br/>


<h1>Article 3 : Compte Photographe et Album</h1>
En accédant à son compte, le Photographe peut créer des albums publics ou privés :<br/>
<ul>
<li>Les albums publics peuvent être visible par tous visiteurs sur le site <span class="photomentiel">www.photomentiel.fr</span>. Les derniers albums publiés seront visible aussi sur la page d'accueil du site.</li>
<li>Les albums privés resteront cachés de tous visiteurs qui n'aura pas le code d'accés pour cet album. Le Photographe se doit de savoir qu'il est responsable de la diffusion de ce code et de faire respecter sa discrétion.</li>
</ul>
Un album peut être créé sans contenir de photos, ce qui permet au Photographe d'obtenir ses cartes de visites et son code album. Il peut ensuite envoyer ses photos à son rythme via l'accés FTP qui lui est réservé ou sur rendez vous s'il est dans le département des Alpes Maritimes. Une fois le transfert terminé, il est validé par le Photographe puis l'album est vérifié par Photomentiel qui se réserve le droit de le publier si toutes les conditions de publication sont respectées.<br/>
L'album est alors disponible pour une période de 3 mois minimum, et reconductible par quinzaine si des nouvelles commandes sont passées.<br/>
<span class="start"></span>Lorsqu'un album est en instance d'être supprimé, il reste disponible tant qu'il y'a des commandes en cours sur cet album et jusqu'à ce que le montant dû pour cet album soit reversé au Photographe.<br/>
Le Photographe peut supprimer un album manuellement, ce qui le place dans les conditions de suppression précédemment citées.<br/>


<h1>Article 4 : Paiement et reversion</h1>
<span class="start"></span>A chaque paiement en ligne effectué par un client, la facture est émise par Photomentiel au nom du Photographe et le compte de Photomentiel est crédité pour le compte du Photographe. Le Photographe autorise de ce fait Photomentiel à collecter en son nom et pour son compte, directement ou par le biais d’un tiers les sommes correspondantes aux ventes réalisées par l’intermédiaire de la fonction de paiement en ligne sécurisée.<br/>
<span class="start"></span>Le paiement de la reversion au Photographe liée aux commandes effectuées et validées sur le site <span class="photomentiel">www.photomentiel.fr</span> s'effectue tous les 15 du mois. Photomentiel prélèvera une commission dont le montant est défini ci-aprés.<br/>
<span class="start"></span>Photomentiel facturera au Photographe le montant de cette commission et procèdera au paiement par virement bancaire de la différence. Le virement sera effectué aux coordonnées bancaires du Photographe grâce au RIB qu'il a fourni lors de la création de son compte.<br/>
Pour chaque commande payée par un client et validée par la banque, Photomentiel prélève :<br/>
<ul>
	<li>une commission de <?php echo (100-PHOTOGRAPH_INITIAL_PERCENT); ?>% (pourcentage initial par défaut : voir article 10) sur le chiffre d'affaire (montant de la commande client)
	<li>les coûts de productions et frais de port, dont voici les détails :
		<ul>
			<li>Frais de port : <?php echo SHIPPING_RATE; ?> &#8364;</li>
			<?php
				$papers = TaillePapier::getTaillePapiers();
				$tf = 0;
				foreach($papers as $paper){
					echo '<li>'.$paper->getDimensions().' : '.$paper->getPrixFournisseur().' &#8364;</li>';
				}
			?>
		</ul>
	</li>
</ul>
<br/>
<?php $nb1 = 14;$nb2=5; ?>
A titre d'exemple, pour une commande client de <?php echo $nb1; ?> formats <?php echo $papers[3]->getDimensions(); ?> à <?php echo $papers[3]->getPrixConseille(); ?>&#8364; et <?php echo $nb2; ?> formats <?php echo $papers[7]->getDimensions(); ?> à <?php echo $papers[7]->getPrixConseille(); ?>&#8364; :<br/>
<ul>
	<li>Paiement enregistré (chiffre d'affaire) : <?php echo $nb1; ?>*<?php echo $papers[3]->getPrixConseille(); ?> + <?php echo $nb2; ?>*<?php echo $papers[7]->getPrixConseille(); ?> = <?php $ca = $nb1*$papers[3]->getPrixConseille()+$nb2*$papers[7]->getPrixConseille();echo sprintf('%.2f',$ca); ?>&#8364; ttc</li>
	<li>Commission Photomentiel : <?php echo $ca."*".(100-PHOTOGRAPH_INITIAL_PERCENT); ?>% = <?php $com=$ca*(100-PHOTOGRAPH_INITIAL_PERCENT)/100;echo sprintf('%.2f',$com); ?>&#8364;</li>
	<li>Coût de production : <?php echo $nb1; ?>*<?php echo $papers[3]->getPrixFournisseur(); ?> + <?php echo $nb2; ?>*<?php echo $papers[7]->getPrixFournisseur(); ?> = <?php $cp = $nb1*$papers[3]->getPrixFournisseur()+$nb2*$papers[7]->getPrixFournisseur();echo sprintf('%.2f',$cp); ?>&#8364;</li>
	<li>Frais d'envoi : <?php echo sprintf('%.2f',SHIPPING_RATE); ?>&#8364;</li>
	<li>Revenu net Photographe : <?php echo sprintf('%.2f',$ca); ?> - <?php echo sprintf('%.2f',$com); ?> - <?php echo sprintf('%.2f',$cp); ?> - <?php echo sprintf('%.2f',SHIPPING_RATE); ?> = <?php echo sprintf('%.2f',$ca-$com-$cp-SHIPPING_RATE); ?> &#8364; ttc</li>
</ul>
<br/>

<h1>Article 5 : Responsabilité du Photographe</h1>
<span class="start"></span>Le photographe s'engage à fournir des albums photos de qualité et à respecter les lois européennes en vigueur, et notamment les dispositions légales sur la pornographie, la violence, le racisme et la pédophilie. Plus généralement, le Photographe s’engage à ne pas envoyer de photos et n’inscrire aucun commentaire :<br/>
<ul>
	<li>Contraire à l’ordre public et aux bonnes mœurs</li>
	<li>A caractères injurieux, diffamatoires, racistes, xénophobes, révisionnistes ou portant atteinte à l’honneur ou à la réputation d’autrui</li>
	<li>Incitant à la discrimination, à la haine d’une personne ou d’un groupe de personnes à raison de leur origine ou de leur appartenance ou de leur non-appartenance à une ethnie, une nation, une race ou une religion déterminée</li>
	<li>Incitant à commettre un délit, un crime ou un acte de terrorisme</li>
	<li>Faisant l’apologie de crimes de guerre ou des crimes contre l’humanité</li>
	<li>Incitant au suicide</li>
	<li>Toute autre photo violente ou choquante</li>
</ul>
Tout manquement à une de ces règles pourra être sanctionné. De plus, Photomentiel se réserve le droit de supprimer sans préavis le compte d'un photographe ne respectant pas ces règles. Il s'engage aussi à informer les personnes présentes sur les photographies de leur diffusion sur internet dans le cas d'un album public.<br/>
<br/>
Le Photographe est seul responsable de l’obtention des droits de la propriété intellectuelle des photos et/ou des objets photographiés présents dans ses albums. Le Photographe s’engage à respecter les droits des personnes et des biens, et plus précisément :<br/>
<ul>
	<li>Les droits de la personnalité, notamment le droit à l’image et le droit au respect de la vie privée</li>
	<li>Les droits des marques</li>
	<li>Les droits d’auteurs (notamment les droits sur les photographies, les droits sur les images, les droits sur les textes)</li>
	<li>Les droits voisins (artistes, interprètes et producteurs)</li>
</ul><br/>
<span class="start"></span>Bien que Photomentiel vérifie régulièrement le contenu des albums à publier, Photomentiel n’est pas responsable du contenu des images mises en ligne par les Photographes qui en sont les seuls et uniques responsables.<br/>
<span class="start"></span>Le Photographe garantit Photomentiel de toute condamnation à ces titres ainsi que de tout recours de tiers portant sur le contenu des images mises en ligne. En cas de manquement à une disposition législative ou règlementaire, constaté par une autorité judiciaire au sens de la loi pour la confiance dans l'économie numérique du 21 juin 2004 (LCEN), ou en cas d'injonction délivrée par l'autorité judiciaire de supprimer un contenu litigieux, Photomentiel pourra prendre toute disposition nécessaire pour supprimer ce contenu ou en empêcher l'accès.<br/>
En cas de réclamation amiable ou de mise en demeure d'un tiers adressée à Photomentiel, estimant que le contenu est illicite ou lui cause un préjudice, celui-ci en informera sans délai le Photographe. A défaut de suppression du contenu litigieux par le Photographe ou par Photomentiel, après refus exprès du Photographe de supprimer ledit contenu, le Photographe garantit Photomentiel, nonobstant toute clause contraire, intégralement et sans limitation, de tout recours et condamnation à des dommages et intérêts auxquelles Photomentiel pourrait être exposé à raison de cette réclamation.<br/>
Toutefois, par dérogation à ce qui précède, Photomentiel pourra prendre toute mesure utile afin de supprimer l'accès au contenu litigieux ou d'en rendre l'accès impossible.<br/>


<h1>Article 6 : Responsabilité pour l'encaissement et la rémunération</H1>
<span class="start"></span>Le Photographe déclare et garantit à Photomentiel qu’il a procédé ou procédera, à ses frais et sous sa seule responsabilité, à l’ensemble des formalités, déclarations administratives et fiscales qui pourraient être nécessaires pour l’utilisation des services, et, en particulier l’encaissement régulier de rémunération, ces formalités et déclarations variants selon la situation spécifique de chaque Photographe, qu’il s’agisse d’une personne morale ou physique soumise a l’impôt sur les société ou sur les revenus.<br/>


<h1>Article 7 : Informations personnelles et professionnelles du Photographe</h1>
<span class="start"></span>Lors de son inscription, le Photographe doit valider le présent contrat et assure qu'il a renseigné les données suivantes, dont la fourniture est obligatoire au regard des dispositions de la loi sur la confiance dans l’économie numérique (dite : LCEN) du 21 juin 2004 : <br/>
<ul>
	<li>Sa dénomination sociale, l’adresse de son siège social, son numéro de téléphone, son numéro de Siret, son adresse mail, le montant de son capital social ainsi que le nom du dirigeant</li>
	<li>Conformément aux dispositions de l’article 6 III -1 b,c,d de la LCEN, les factures émisent par Photomentiel au nom du Photographe comportera l’ensemble de ces informations, en d’autres termes ces informations seront publiques</li>
	<li>Le Photographe garantit aussi que les données qu’il communique sont exactes, complètes et à jour. Photomentiel ne disposant d’aucun moyen pour les vérifier, le Photographe s’engage à mettre à jour ses informations lui-même via l’interface d’administration qui est mise a sa disposition</li>
</ul><br/>


<h1>Article 8 : Responsabilité de Photomentiel</h1>
<span class="start"></span>Photomentiel assure techniquement l’hébergement des photos, mais n’est en aucun cas responsable de leur contenu ni de leur utilisation.<br/>
Dans le cadre du maintien de son site Internet, Photomentiel se réserve le droit d’interrompre temporairement ses services, notamment lors d’opérations ponctuelles sur les serveurs.<br/>
<span class="start"></span>La responsabilité de Photomentiel ne peut être engagée en cas d’indisponibilité de ses services à la suite d’incidents techniques du réseau Internet.<br/>
<span class="start"></span>La responsabilité de l’Hébergeur ne peut être engagée en cas de perte, partielle ou totale des données stockées, pour des raisons techniques. Il incombe aux Photographes de sauvegarder des copies personnelles de leurs fichiers.<br/>
<span class="start"></span>La responsabilité de Photomentiel ne peut être engagée pour toute interception, de la part des visiteurs ou actes de piraterie, des photos téléchargées ou en cours de téléchargement depuis le site <span class="photomentiel">www.photomentiel.fr</span>.<br/>
<span class="start"></span>La responsabilité de Photomentiel ne peut être engagée pour les préjudices subis suite à l’interception éventuelle des fichiers. Le Photographe déclare connaître et accepter ces risques liés au réseau Internet.<br/>
<span class="start"></span>Photomentiel se réserve le droit de mettre fin ou modifier les caractéristiques de ses services à tout moment, et cela, sans préavis.<br/>


<h1>Article 9 : Durée de validité du présent contrat</h1>
<span class="start"></span>Ce contrat lie Photomentiel et Le Photographe pour une durée indéterminée et tant que le Photographe détient un compte sur le site <span class="photomentiel">www.photomentiel.fr</span>.<br/>
Il peut prendre fin pour les raisons suivantes :<br/>
<ul>
	<li>Sur demande de suppression du compte du Photographe via le formulaire de contact.</li>
	<li>Par décision de Photomentiel, peu importe la raison.</li>
</ul><br/>
<span class="start"></span>Un préavis est accordé le temps que toutes les commandes en cours du Photographes soient terminées. A la fin du préavis, Photomentiel procèdera au solde du compte, au paiement des sommes dûes, au retrait de tous ses albums et à la suppression de toutes ses photos, et clôturera le compte du Photographe.<br/>


<h1>Article 10 : Pourcentage de reversion</h1>
<span class="start"></span>Photomentiel s'engage à reverser au photographe <?php echo PHOTOGRAPH_INITIAL_PERCENT; ?>% des bénéfices de ces ventes déduit des différents frais de traitement cités plus haut, le reste étant utilisé pour assurer le bon fonctionnement de ce service. Photomentiel précise aussi que le pourcentage reversé au photographe faisant foi est celui qui apparaît sur le présent contrat. Si Photomentiel est amené à augmenter ce pourcentage, il ne sera pas changé pour les photographes ayant déjà un compte sur le site sans leur accord préalable.<br/>
<span class="start"></span>En créant un compte, le photographe s'engage à accepter tous les termes évoqués dans les différents articles du présent contrat. Il s'engage aussi à conserver une copie de ce document afin de fixer le pourcentage qui lui a été attribué au jour de son inscription. Si toutefois il ne pouvait pas le prouver, le pourcentage appliqué serait alors immédiatement le pourcentage en vigueur appliqué pour les nouveaux contrats.<br/>


<h1>Article 11</h1>
<span class="start"></span>Si toutefois, une ou plusieurs clauses du présent contrat, étaient déclarées nulles en application d’une loi ou d’un règlement ou par décision de justice, les autres clauses conserveraient leur force et leur portée. Les deux parties conviennent alors de remplacer la clause déclarée nulle et non valide par une clause qui se rapprochera le plus du contenu de la clause initialement arrêtée. Toute modification apportée au présent contrat fera l'objet d'une notification à tous les photographes inscrits ainsi qu'une ré-acceptation du contrat modifié.<br/>
<span class="start"></span>En demandant l’ouverture d’un compte chez Photomentiel, le contrat qui régit les relations entre les deux parties est alors formé. Ce contrat est l’unique contrat entre les parties. Il remplace et annule notamment tout accord antérieur.
<br/><br/>
<u>IMPORTANT</u> : Conformément à la loi du 13 mars 2000 sur la signature électronique, tout contrat signé du Photographe par «double clic de validation» constitue une acceptation irrévocable qui ne peut être remise en cause que dans les cas limitativement prévus dans le présent contrat.<br/>
Le «double clic» associé à la procédure complète de création de compte, de non répudiation et de protection de l’intégrité des messages constitue une signature électronique. Cette signature électronique a valeur de signature manuscrite entre les parties pour l’ensemble du présent contrat.
<br/>
<br/>
<br/>
Le <?php echo date("d/m/Y à H:i:s"); ?><br/>
IP du Photographe : <?php echo $_SERVER['REMOTE_ADDR']; ?><br/>
<br/>

