<?php
/*
 * functions.php is a set of usefull functions
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 15 août 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
include("phptopdf/phpToPDF.php");

/* 
 * Return the given value troncated to the given length.
 * It adds '...' at the end and the total returned string is less or equals than the given length
 */
function toNchar($str,$len){
	if (strlen($str)<=$len){
		return $str;
	} else {
		return substr($str,0,$len-3)."...";
	}
}

/* 
 * Convert a float value into the bank value. (ex 12.34 -> 1234)
 * Also convert the ',' char into the '.' char if needed (ex 12,34 -> 12.34 -> 1234)
 */
function toBankAmount($amount) {
	$amount = str_replace(",",".",$amount); 
	return round($amount*100);
}

/* 
 * Convert a bank value into the corresponding float amount. (ex 1234 -> 12.34)
 * Also convert with 2 digits after the '.' (ex 1200 -> 12.00)
 */
function toFloatAmount($amount) {
	return sprintf('%.2f',$amount/100);
}

/* 
 * Return an hashcode of the given array
 */
function getHashFromArray($kvArray){
	$h = '';
	foreach ($kvArray as $k => $v) {
		$h .= $v;
	}
	return hash('ripemd160',$h);
}

/* 
 * Return an hashcode of the given array
 */
function getRequestParamFromPost(){
	$kvArray = $_GET;
	$h = '';
	foreach ($kvArray as $k => $v) {
		$h = $h.$k."=".$v."&";
	}
	return $h;
}

/* 
 * Return an hashcode of the given command array (array of array)
 */
function getHashFromCommand($cmdArray){
	$h = '';
	for ($i=0;$i<sizeof($cmdArray);$i++){
		$h .= getHashFromArray($cmdArray[$i]);
	}
	return hash('ripemd160',$h);
}

/*
 * Remove the filename extension in the given filename
 */
function removeExtension($fileName){
	return substr($fileName,0,sizeof($fileName)-5);
}

function httpPost($url, $data, $ssl = false){
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	$result=curl_exec($ch);
	curl_close ($ch);
	return $result;
}


/*
 * Create pdf file from command, and user.
 * Display pdf on output if 'dest' is not specified or null. Otherwise, create and fill the file 'dest'
 */
function makePDF($command, $user, $photosFormatDim, $siren, $dest=null){
	$adresse = $user->getAdresse();
	$PDF = new phpToPDF();
	$PDF->AddPage();
	//Photomentiel informations
	$x = 15;$y = 10;
	$PDF->SetFont('Times','B',12);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "PHOTOMENTIEL - www.photomentiel.fr");
	/*$y+=5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, ADRESSE2);
	$y+=5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, ADRESSE3);*/
	$y+=5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "contact@photomentiel.fr");
	$y+=5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "SIREN No ".$siren);
	$y+=5;
	$PDF->SetFont('Times','',9);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "Dispensé d'immatriculation au registre du commerce");
	$y+=5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "et des sociétés (RCS) et au répertoire des métiers (RM)");
	//User informations
	$x = 130;$y = 25;
	$PDF->SetFont('Times','',13);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, $adresse->getNom()." ".$adresse->getPrenom());
	$y += 5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, $adresse->getNomRue());
	if ($adresse->getComplement() != null && $adresse->getComplement() != ''){
		$y += 5;
		$PDF->SetXY($x,$y);
		$PDF->Write(10, $adresse->getComplement());
	}
	$y += 5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, $adresse->getCodePostal()." ". $adresse->getVille());
	//date
	$PDF->SetFont('Times','B',12);
	$x = 102;$y += 8;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "Commande du ".date("d/m/Y à H:i",strtotime($command->getDate())));
	//numero de facture
	$PDF->SetFont('Times','B',14);
	$x = 15;$y += 12;
	$PDF->SetXY($x,$y);
	$PDF->Cell(70,10,'Facture N°'.$command->getNumero(),1,1,'C');
	// Définition des propriétés du tableau.
	$proprietesTableau = array(
		'TB_ALIGN' => 'C',
		'L_MARGIN' => 1,
		'BRD_COLOR' => array(0,92,177),
		'BRD_SIZE' => '0.3',
		);
	// Définition des propriétés du header du tableau.
	$proprieteHeader = array(
		'T_COLOR' => array(150,10,10),
		'T_SIZE' => 11,
		'T_FONT' => 'Arial',
		'T_ALIGN' => 'C',
		'V_ALIGN' => 'M',
		'T_TYPE' => 'B',
		'LN_SIZE' => 7,
		'BG_COLOR_COL0' => array(170, 240, 230),
		'BG_COLOR' => array(170, 240, 230),
		'BRD_COLOR' => array(0,92,177),
		'BRD_SIZE' => 0.2,
		'BRD_TYPE' => '1',
		'BRD_TYPE_NEW_PAGE' => '',
		);
	// Contenu du header du tableau.
	$contenuHeader = array(
		80, 34, 34, 34,
		"Référence", "Format", "Quantité", "Total (Euro ttc)",
	);
	// Définition des propriétés du reste du contenu du tableau.
	$proprieteContenu = array(
		'T_COLOR' => array(0,0,0),
		'T_SIZE' => 10,
		'T_FONT' => 'Arial',
		'T_ALIGN_COL0' => 'C',
		'T_ALIGN' => 'C',
		'V_ALIGN' => 'M',
		'T_TYPE' => '',
		'LN_SIZE' => 6,
		'BG_COLOR_COL0' => array(255, 255, 255),
		'BG_COLOR' => array(255,255,255),
		'BRD_COLOR' => array(0,92,177),
		'BRD_SIZE' => 0.1,
		'BRD_TYPE' => '1',
		'BRD_TYPE_NEW_PAGE' => '',
	);
	// Contenu du tableau.
	$tabContent = array();
	$index = 0;
	$tot = 0;
	$lines = $command->getCommandesPhoto();
	foreach ($lines as $line){
		$tabContent[$index++] = removeExtension($line->getPhoto());
		$tabContent[$index++] = $photosFormatDim[$line->getID_TaillePapier()];
		$tabContent[$index++] = $line->getNombre();
		$tabContent[$index++] = sprintf('%.2f',$line->getPrix());
		$tot += $line->getPrix();
	}
	//display tab
	$y += 12;
	$PDF->SetXY($x,$y);
	$PDF->drawTableau($PDF, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $tabContent);
	//FDP
	$x = 130;$y+=sizeof($lines)*6+7;
	$PDF->SetFont('Times','',11);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "Frais de port : ");
	$PDF->SetFont('Times','',11);
	$PDF->SetXY($x+33,$y+2);
	$PDF->Cell(34,6,$command->getFDP()==0?"Offert!":sprintf('%.2f',$command->getFDP()),1,1,'C');
	//Total
	$y+=7;
	$PDF->SetFont('Times','',12);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "Total (Euro ttc) : ");
	$PDF->SetFont('Times','B',12);
	$PDF->SetXY($x+33,$y+2);
	$PDF->Cell(34,6,sprintf('%.2f',$command->getFDP()+$tot),1,1,'C');
	//mention
	$PDF->SetFont('Times','',8);
	$y += 6;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "TVA non applicable, art. 293 B du CGI");
	//payment date
	$x = 15;$y+=5;
	$PDF->SetFont('Times','',9);
	$PDF->SetXY(15,$y);
	$PDF->Write(10, "Date de paiement : ".date("d/m/Y H:i",strtotime($command->getDatePaiement())));
	//footer
	$x = 78;$y = 266;
	$PDF->SetFont('Times','',8);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "www.photomentiel.fr - Tous droits réservés");
	//Print
	if ($dest==null){
		$PDF->Output();
	} else {
		$PDF->Output($dest, "F");
	}
}

/*
 * Create pdf file for album card for the specified album and photographe.
 * Display pdf on output if 'dest' is not specified or null. Otherwise, create and fill the file 'dest'
 */
function makeCard($stringID, $album, $photographe, $dest=null){
	$adresse = $photographe->getAdresse();
	$PDF = new phpToPDF();
	$PDF->AddPage();
	//title
	$x = 84;$y = 5;
	$PDF->SetFont('Times','B',9);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "http://www.photomentiel.fr");
	$x = 30;$y += 4;
	$PDF->SetFont('Times','',9);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "Vous pouvez imprimer et découper ces cartes sur papier afin de les distribuer lors de votre événement.");
	//cards
	$x = 20;$y += 15;
	for ($i=0;$i<5;$i++){
		$yy = $y + $i*50;
		for ($j=0;$j<2;$j++){
			$xx = $x + $j*85;
			//cards png
			$PDF->Image("design/misc/card_pdf.png", $xx, $yy, 85, 50);
			//cards content
			$PDF->SetFont('Times','',10);
			$PDF->SetXY($xx+2,$yy+18);
			$PDF->Write(10, "Photographe : ".$adresse->getNom()." ".$adresse->getPrenom());
			$PDF->SetXY($xx+2,$yy+23);
			$PDF->Write(10, "Contact : ".$photographe->getEmail());
			$PDF->SetFont('Times','B',9);
			$PDF->SetXY($xx+2,$yy+32);
			$PDF->Write(10, "Rendez vous sur www.photomentiel.fr");
			$PDF->SetFont('Times','B',11);
			$PDF->SetXY($xx+2,$yy+38);
			$PDF->Write(10, "Code album :");
			$PDF->SetFont('Arial','B',12);
			$PDF->SetXY($xx+27,$yy+40);
			$PDF->Cell(30,6,$stringID,1,1,'C');
		}
	}
	//Print
	if ($dest==null){
		$PDF->Output();
	} else {
		$PDF->Output($dest, "F");
	}
}
?>
