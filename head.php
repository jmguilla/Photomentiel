<?php
/*
 * header.php is the header of each page
 * 
 * Author : PHOTOMENTIEL (All rights reserved)
 * 
 * Created on 24 juil. 2010
 * Version : 1.0.0
 * Since : 1.0.0
 *
 */
include_once("functions.php");
include_once("classes/Utils.php");
@session_start();
ini_set('url_rewriter.tags','');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//FR" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="fr_FR" xml:lang="fr_FR">
 <head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-Language" content="fr">
	<meta http-equiv="keywords" name="keywords" content="présentation photos événement événementiel photographe visualiser acheter distribuer albums partager impression tirages papier" />
	<meta http-equiv="robots" name="Robots" content="all">	
	<meta name="robots" content="INDEX|FOLLOW" />
	<meta name="author" content="<?php echo AUTHOR; ?>" />
	
 	<link rel="icon" type="image/png" href="/design/favicon.png" />
 
	<link rel="stylesheet" type="text/css" href="/css/calendar.css" />
	<link rel="stylesheet" type="text/css" href="/css/thickbox.css" />
 	<link rel="stylesheet" type="text/css" href="/css/main.css" />
 	<link rel="stylesheet" type="text/css" href="/css/<?php echo Utils::getScriptName();?>.css" />
 	
 	<script language="javascript" src="/js/cookies.js"></script>
	<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
	<script language="javascript" src="/js/header.js"></script>
	<script type="text/javascript" src="/js/<?php echo Utils::getScriptName();?>.js"></script>

	<?php
		if (isset($HEADER_TITLE)){
			echo '<title>'.AUTHOR.' - '.$HEADER_TITLE.'</title>';
		} else {
			echo '<title>'.AUTHOR.' - Spécialiste de la photo événementielle ! Solution de présentation et de distribution d\'albums photos sur le web</title>';
		}
		if (isset($HEADER_DESCRIPTION)){
			echo '<meta http-equiv="description" name="description" content="'.$HEADER_DESCRIPTION.'" />';
		} else {
			echo '<meta http-equiv="description" name="description" content="Spécialiste de la photo événementielle ! Solution de présentation et de distribution d\'albums photos sur le web" />';
		}
	?>

 </head>
 <body>
 
