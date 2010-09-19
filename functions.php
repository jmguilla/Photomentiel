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
 * Create pdf file from command, and user.
 * Display pdf on output if 'dest' is not specified or null. Otherwise, create and fill the file 'dest'
 */
function makePDF($command, $user, $dest=null){
	$adresse = $user->getAdresse();
	$PDF = new phpToPDF();
	$PDF->AddPage();
	//Photomentiel informations
	$x = 25;$y = 10;
	$PDF->SetFont('Times','B',12);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "PHOTOMENTIEL");
	$y+=5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "www.photomentiel.fr");
	$y+=5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "contact@photomentiel.fr");
	$y+=5;
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "SIREN 132456789");
	//User informations
	$x = 140;$y = 20;
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
	/*$PDF->SetXY(35,64);
	$PDF->Write(10, "Date : ".$date."\n");
	// Définition des propriétés du tableau.
	$proprietesTableau = array(
		'TB_ALIGN' => 'C',
		'L_MARGIN' => 6,
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
		60, 30, 30, 30,
		"Numéro", "Format", "Quantité", "Total",
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
	$contenuTableau = array();
	$index = 0;
	$tot = 0;
	for ($i=0;$i<count($pictures);$i++){
		$tmp = split($property_separator,$pictures[$i]);
		$contenuTableau[$index++] = $tmp[0];
		$contenuTableau[$index++] = $tmp[1];
		$contenuTableau[$index++] = $tmp[2];
		$contenuTableau[$index++] = $_prices[$tmp[1]]*$tmp[2]." €";
		$tot += $_prices[$tmp[1]]*$tmp[2];
	}
	$contenuTableau[$index++] = "";
	$contenuTableau[$index++] = "";
	$contenuTableau[$index++] = "TOTAL :";
	$contenuTableau[$index++] = $tot." €";
	$PDF->SetXY(0,78);
	$PDF->drawTableau($PDF, $proprietesTableau, $proprieteHeader, $contenuHeader, $proprieteContenu, $contenuTableau);
	*/
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
	$PDF->Write(10, "www.photomentiel.fr");
	$x = 25;$y += 3;
	$PDF->SetFont('Times','',9);
	$PDF->SetXY($x,$y);
	$PDF->Write(10, "Vous pouvez imprimer et découper ces cartes sur du papier cartonné afin de les distribuer lors de votre évènement.");
	//cards
	$x = 20;$y += 15;
	for ($i=0;$i<5;$i++){
		$yy = $y + $i*47;
		for ($j=0;$j<2;$j++){
			$xx = $x + $j*86;
			//cards png
			$PDF->Image("design/misc/card_pdf.png", $xx, $yy, 86, 48);
			//cards content
			$PDF->SetFont('Times','',10);
			$PDF->SetXY($xx+2,$yy+17);
			$PDF->Write(10, "Photographe : ".$adresse->getNom()." ".$adresse->getPrenom());
			$PDF->SetXY($xx+2,$yy+22);
			$PDF->Write(10, "E-mail : ".$photographe->getEmail());
			$PDF->SetFont('Times','B',9);
			$PDF->SetXY($xx+2,$yy+29);
			$PDF->Write(10, "Rendez vous sur www.photomentiel.fr");
			$PDF->SetFont('Times','B',11);
			$PDF->SetXY($xx+2,$yy+36);
			$PDF->Write(10, "Code album :");
			$PDF->SetFont('Arial','B',12);
			$PDF->SetXY($xx+26,$yy+36);
			$PDF->Write(10,$stringID);
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
