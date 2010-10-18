<?php
$dir = dirname(__FILE__);
include_once $dir . "/../functions.php";
include_once $dir . "/../classes/Config.php";

echo httpPost("http://".FTP_TRANSFER_IP.":".HTTP_PORT."/private/rmrf.php","stringID=is8n5f8q", false);
?>
