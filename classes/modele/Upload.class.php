<?php
$dir_upload_class_php = dirname(__FILE__);
include_once $dir_upload_class_php . '/modele_dao/UploadDAO.class.php';

class Upload{
	private $id = 0;
	private $stringID = '';
	private $nombre = '';

	function __construct(){}

	public static function getUploadDepuisStringID($sid){
		$dao = new UploadDAO();
		return $dao->getUploadDepuisStringID($sid);
	}

	public function create(){
		$dao = new UploadDAO();
		return $dao->create($this);
	}

	public function delete(){
		$dao = new UploadDAO();
		return $dao->delete($this);
	}

	/**
	 * Getters and setters
	 */
	public function getUploadID(){
		return $this->id;
	}

	public function setUploadID($id){
		$this->id = $id;
	}

	public function getStringID(){
		return $this->stringID;
	}

	public function setStringID($sid){
		$this->stringID = $sid;
	}

	public function getNombre(){
		return $this->nombre;
	}

	public function setNombre($n){
		$this->nombre = $n;
	}
}
?>