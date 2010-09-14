<?php
/*
 * Utils.php define some usefull methods
 * 
 * Author : SCHIOUFF (All rights reserved)
 * 
 * Created on 01 aug. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
$dir_utils_php = dirname(__FILE__);
include_once $dir_utils_php . "/Config.php";
 
class Utils {

	public static function getFullDomainName(){
		return "www.".DOMAIN_NAME.".".DOMAIN_EXT;
	}
	
	public static function getScriptName() {
		$name = substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],"/")+1);
		return substr($name,0,strlen($name)-4);
	}
	
	public static function checkMail($mail){
		$mail = mysql_real_escape_string($mail);
		if (preg_match("^.+@.+[.].+$",$mail)){
			return $mail;
		} else {
			throw new UnexpectedValueException("Invalid mail adress");
		}
	}
	
	/*
	 * $link is the link to be checked
	 * $constraint is a constraint that must be in the link (can be a valid php regexp)
	 */
	public static function checkLink($link, $constraint = null){
		$link = mysql_real_escape_string($link);
		if (preg_match("^https{0,1}://.*",$link)){
			if ($constraint != null){
				if (preg_match(".*".$constraint.".*",$link)){
					return $link;
				} else {
					throw new UnexpectedValueException("Link does not match the given constraint");
				}
			}
		} else {
			throw new UnexpectedValueException("Link does not match http(s) pattern");
		}
	}
}
?>

