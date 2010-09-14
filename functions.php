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

?>
