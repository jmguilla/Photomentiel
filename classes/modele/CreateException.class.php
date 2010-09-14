<?php
class CreateException extends Exception{

	public function display() {
		echo "Exception!\n";
		echo "----------------------------------\n";
		echo "Code: ".$this->getCode()."\n";
		echo "File: ".$this->getFile()."\n";
		echo "Line: ".$this->getLine()."\n";
		echo "Message: ".$this->getMessage()."\n";
		echo "----------------------------------\n\n";
	}
}
class CreateAlbumException extends CreateException{}
class CreatePhotographeException extends CreateException{}
class CreateUtilisateurException extends CreateException{}
?>