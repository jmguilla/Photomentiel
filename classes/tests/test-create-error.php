<?php
$dir_test_create_error_php = dirname(__FILE__);
include_once $dir_test_create_error_php . '/../controleur/ControleurUtils.class.php';

echo false == ControleurUtils::addError("test creation erreur", true);
?>