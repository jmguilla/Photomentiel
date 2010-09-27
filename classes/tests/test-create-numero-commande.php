<?php
include_once "../Config.php";
include_once "../modele/modele_dao/daophp5/DAO.class.php";

class TestDAO extends DAO{
	public function __construct() {
		$dsn = DBTYPE."://".DBUSER.":".DBPWD."@".DBHOST."/".DBPHOTOMENTIEL;
		parent::__construct($dsn);
	}

	public function test(){
		$hometmp = '10083021';//date('ymdH');
		echo $hometmp . '<br/>';
		$query = "select count(*) as num from Commande where numero like '" . $hometmp . "%'";
		$tmp = $this->retrieve($query);
		foreach($tmp as $count){
			$homeDelta = $count['num'];
			break;
		}
		echo $homeDelta . '<br/>';
	}
}
$dao = new TestDAO();
$dao->test();

?>