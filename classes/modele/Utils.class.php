<?php
class Utils{
	public static function getFileFromDirectory($dir_nom){
		$dir = opendir($dir_nom);
		if($dir == false){
			return false;
		}
		$fichier= array();
		while($element = readdir($dir)) {
			if($element != '.' && $element != '..') {
				if (!is_dir($dir_nom . DIRECTORY_SEPARATOR .$element)) {$fichier[] = $element;}
			}
		}
		closedir($dir);
		return $fichier;
	}
}
?>