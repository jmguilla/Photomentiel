<?php
/*
 * parameters.php define every site parameters
 * 
 * Author : SCHIOUFF (All rights reserved)
 * 
 * Created on 24 juil. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
class Parameters {
    private static $root = "D:/WorkingDir/eclipse/Photomentiel/";
	private static $author = "Photomentiel";
	private static $domain_name = "photomentiel";
	private static $domain_ext = "fr";
	
	public static function getRootDir(){
		return self::$root;
	}
	
	public static function getAuthor(){
		return self::$author;
	}
	
	public static function getDomainName(){
		return self::$domain_name;
	}
	
	public static function getFullDomainName(){
		return "www.".self::$domain_name.".".self::$domain_ext;
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

