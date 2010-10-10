<?php
$dir_error_class_php = dirname(__FILE__);
include_once $dir_error_class_php . "/modele_dao/ErrorDAO.class.php";

class Error{
	private $id = 1;
	private $message = '';
	public static function getErrors(){
		$dao = new ErrorDao();
		return $dao->getErrors();
	}

	public static function getErrorDepuisErrorID($id){
		$dao = new ErrorDao();
		return $dao->getErrorDepuisErrorID($id);
	}

	public function create(){
		$dao = new ErrorDao();
		return $dao->create($this);
	}

	public function delete(){
		$dao = new ErrorDao();
		return $dao->delete($this);
	}

	/**
	 * Getters & Setters
	 */
	public function getErrorID(){
		return $this->id;
	}
	public function setErrorID($id){
		$this->id = $id;
	}
	public function getMessage(){
		return $this->message;
	}
	public function setMessage($m){
		$this->message = $m;
	}
}

?>