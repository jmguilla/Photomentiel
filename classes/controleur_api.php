<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
	<script type="text/javascript" src="scripts/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="scripts/utilisateurs.js"></script>
	<script type="text/javascript" src="scripts/maps.js"></script>
	<script type="text/javascript" src="scripts/evenements.js"></script>
	<script type="text/javascript" src="scripts/stringids.js"></script>
	<script type="text/javascript" src="scripts/images.js"></script>
</head>
<body>
<?php
	echo '<hr />';
	include('utilisateurs.php');
	echo '<hr />';
	include('lieux.php');
	echo '<hr />';
	include('evenements.php');
	echo '<hr />';
	include('stringids.php');
	echo '<hr />';
	include('images.php');
	echo '<hr />';
?>
</body>
</html>