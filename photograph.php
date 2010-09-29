<?php
/*
 * photograph.php
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 20 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
include("header.php");

$TAILLE_MAX = 2;//Go
$PERCENT = 23;
?>
<div id="full_content_top">
		Vous êtes photographe, Bienvenue parmi nous !
</div>
<div id="full_content_mid">
	<div class="path">
		<a href="index.php">Accueil</a> &gt; 
		Vous êtes photographe !
	</div>
	<div class="separator10"></div>
	<div id="photographc">
		 
		<a href="#1"><div class="question">Je suis photographe, si je passe par votre service, qu'est ce que cela va me coûter ?</div></a>
		<a href="#2"><div class="question">Ok, je ne peux rien perdre, mais je pourrais gagner plus ?</div></a>
		<a href="#3"><div class="question">Pourquoi devrais-je perdre une commission au lieu de mettre ma solution en place ?</div></a>
		<a href="#4"><div class="question">Vous parlez de stockage sécurisé, c'est à dire ?</div></a>
		<a href="#5"><div class="question">Moi je privilégie la qualité, je ne veux pas me retrouver au milieu de photographes débutants logés à la même enseigne.</div></a>
		<a href="#6"><div class="question">Je viens de recevoir mon nouveau téléphone qui dispose d'un capteur énorme, puis-je m'inscrire ?</div></a>
		<a href="#7"><div class="question">À ce que je vois votre entreprise est toute jeune, vous vous êtes établi en 2009 c'est ça ?</div></a>
		<a href="#8"><div class="question">Bon d'accord tout ceci semble tentant, mais qui vais-je photographier ?</div></a>
		<a href="#9"><div class="question">Oui d'accord je suis emballé, je signe où ?</div></a>
		<a href="#10"><div class="question">Mais au fait, comment puis-je vous transmettre mes photos ?</div></a>
		<div class="separator5"></div>
		<center><hr/></center>
		<div class="question"><a name="1"></a>Je suis photographe, si je passe par votre service, qu'est ce que cela va me coûter ?</div> 
		<div class="answer">
			<span class="start"></span>D'un certain point de vue rien. En effet vous ne pouvez en aucun cas perdre de l'argent. Nous nous rémunerons en prenant une commission sur la vente de vos photos. Cette commission initialisée à <?php echo $PERCENT; ?>% est variable et diminue au fur et à mesure de votre fidélité et de vos préstations.
		</div>
		<div class="question"><a name="2"></a>Ok, je ne peux rien perdre, mais je pourrais gagner plus ?</div>
		<div class="answer">
			<span class="start"></span>Réellement ? Avez vous considéré les coûts de mise à disposition des photographies, le temps nécessaire et le risque ? Et oui le risque puisque dans un tel cas vous êtes obligé d'investir du temps et de l'argent.<br/>
			En participant à l'aventure <b>Photomentiel</b>, vous ne prenez aucun risque, vous bénéficiez d'ailleurs de notre renommée, de notre sérieux et aussi de notre système de notation qui permet aux clients de vous faire confiance parce que nous vous faisons confiance !
		</div>
		<div class="question"><a name="3"></a>Pourquoi devrais-je perdre une commission au lieu de mettre ma solution en place ?</div>
		<div class="answer">
			<span class="start"></span>Vous vous sentez d'écrire un site internet dynamique complet à la pointe des innovations en terme de technologie internet, qui suit les codes et évolution du monde internet ? Vous étes capable financièrement et techniquement d'assurer un stockage sûr et redondant de vos photographies ? Savez vous que la durée de vie moyenne d'un disque dur fortement sollicité ne dépasse les trois ans ?<br/>
			<span class="start"></span>Parce que nous ne savons pas composer et capturer la réalité de façon professionnelle nous avons besoin de vous, si vous voulez vous concentrer sur la partie noble du travail photographique vous avez besoin de nous. 
		</div>
		<div class="question"><a name="4"></a>Vous parlez de stockage sécurisé, c'est à dire ?</div>
		<div class="answer">
			<span class="start"></span>Étant informaticien de formation, nous sommes à même de mettre en place l'ensemble des solutions de vérification, redondance, sauvegardes, etc ... pour assurer la conservation de vos données. L'ensemble de nos disques durs sont monitorés en temps réel et lorsque des symptômes de pannes se font ressentir, ils sont immédiatement remplacés.<br/>
			Si une panne devait survenir ? Aucun problème, chaque parcelle de donnée est répliquée sur plusieurs disques durs, ce qui nous permet, à chaud sans interruption de service, de remplacer le disque dur défectueux, sans aucune pertes de donnée. Et si cela ne vous rassure toujours pas, sachez que nous effectuons quotidiennement une copie de sauvegarde de l'ensemble des données, cette copie est ensuite entreposée dans un endroit distant du système de stockage principal et ne disposant pas d'accés internet. Nous sommes donc capable de faire face à tous type de pannes.
		</div>
		<div class="question"><a name="5"></a>Moi je privilégie la qualité, je ne veux pas me retrouver au milieu de photographes débutants logés à la même enseigne.</div>
		<div class="answer">
			<span class="start"></span>C'est notre vision première, la qualité ! C'est pour cela que notre système d'évaluation des photographes à été mis en place.
			Il permettra à nos clients (nos clients communs donc) d'avoir une garantie de qualité. Idéalemment nous souhaitons avoir un
			contact direct avec le photographe après son inscription dans le but, premièremment de donner un réponse à toutes les questions
			qui restent en suspens mais aussi de vérifier le sérieux du photographe. 
		</div>
		<div class="question"><a name="6"></a>Je viens de recevoir mon nouveau téléphone qui dispose d'un capteur énorme, puis-je m'inscrire ?</div>
		<div class="answer">
			<span class="start"></span>Non. Définitivement non. Notre but étant la qualité, vous devez avoir de solides connaissances en photographie ainsi que le matériel 
			nécessaire à fournir des clichés de qualité. 
		</div>
		<div class="question"><a name="7"></a>À ce que je vois votre entreprise est toute jeune, vous vous êtes établi en 2009 c'est ça ?</div>
		<div class="answer">
			<span class="start"></span>Oui. C'est exact nous sommes une société qui démarre, néanmoins n'hésitez pas à rentrer en contact avec nous, nous ne sommes pas qu'une vitrine internet,
			nous vous recevrons avec plaisir et vous pourrez vous faire une idée précise de ce que vous risqu... (à non vous ne risquer rien c'est vrai !), des gens avec qui vous serez amener à travailler. N'oubliez pas que dans le monde des technologies informatiques et plus particulièrement lorsque celui ci porte le nom de start-up et est ouvert sur Internet, tout peux aller très vite et nous n'oublierons pas que l'aventure à commencer grâce à vous ! Rejoignez nous vite, vous n'avez rien à perdre ! 
		</div>
		<div class="question"><a name="8"></a>Bon d'accord tout ceci semble tentant, mais qui vais-je photographier ?</div>
		<div class="answer">
			<span class="start"></span>Nous ciblons principalement l'évènementiel, les sorties des associations, les réunions de comités d'entreprises, tous ces évènements qui sont riches en souvenir mais qui ne sont que rarement couvertes par des photographes professionnels. Si vous n'avez pas le temps de parcourir les offices du tourismes, les calendriers d'associations des mairies, etc ... et bien ce n'est pas un problème, nous l'avons fait pour vous ! Rendez vous dans la rubrique <a href="events.php">événements</a> et faites votre choix ! Vous n'aurez plus qu'à contacter un responsable d'événement pour proposer vos services.<br/>
			<b>Notre but est de vous permettre de vous concentrer sur votre passion : la photo.</b>
		</div>
		<div class="question"><a name="9"></a>Oui d'accord je suis emballé, je signe où ?</div>
		<div class="answer">
			<span class="start"></span>Et bien tout d'abord la loi française sur le travail ne nous permet pas de rémunérer un particulier, si vous êtes un professionnel que faites vous encore là ? La rubrique <a href="adduser.php?type=ph">inscription</a> du site vous attend !<br/>
			<span class="start"></span>Si vous êtes photographe amateur avec un réel talent, le statut d'auto-entrepreneur semble être la solution idéale, vous pouvez vous inscrire directement <a target="_blank" href="https://www.cfe.urssaf.fr/autoentrepreneur/CFE_Bienvenue">par internet en moins de 20 minutes</a> et vous recevrez votre numéro SIRET par la poste sous 15 jours. En terme de charges sociales vous ne serez imposé que sur vos bénéfices à hauteur de <?php echo $PERCENT; ?>%, n'hésitez pas à nous contacter pour de plus amples renseignements, nous pourrons vous conseiller au mieux. 
		</div>
		<div class="question"><a name="10"></a>Mais au fait, comment puis-je vous transmettre mes photos ?</div>
		<div class="answer">
			<span class="start"></span>Pour cela plusieurs solutions, tout d'abord, vous ne souhaitez pas vous déplacer et l'ensemble des données que vous souhaitez transmettre ne dépasse pas <?php echo $TAILLE_MAX; ?> Go, alors vous pouvez transmettre vos photos directement au travers d'internet, les explications sur le transfert des photos vous seront communiquées à la création d'un album.<br/>
			<span class="start"></span>Si le temps de transfert est un problème, vous pouvez aussi nous transmettre vos photographies sur n'importe quel support numérique (cartes mémoire, CD-ROM, DVD-ROM, clé usb) par les services postaux à l'adresse :
			<div class="photograph_adress">
			<?php
				echo ADRESSE1.'<br/>';
				echo ADRESSE2.'<br/>';
				echo ADRESSE3;
			?>
			</div>
			Ou bien tout simplement en nous contactant pour nous remettre les photos en main propre !
			<br/>
			<br/>
			<a href="adduser.php?type=ph">Allez, je vais m'inscrire !</a>
			<br/>
			<br/>
		</div>
	</div>
</div>
<div id="full_content_bot"></div>
<?php
include("footer.php");
?>
