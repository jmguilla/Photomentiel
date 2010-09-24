<?php
$dir_transactionid_class_php = dirname(__FILE__);
include_once $dir_transactionid_class_php . "/modele_dao/TransactionIDDAO.class.php";

class TransactionID {
	public static function get(){
		$dao = new TransactionIDDAO();
		return $dao->get();
	}
}
?>