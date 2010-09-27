<?php 
@session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr_FR" xml:lang="fr_FR">
 <head>
 	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-Language" content="fr"/>
</head>
<script type="text/javascript">
function OnSubmitValidationForm()
{
  if(document.pressed == 'valider_album')
  {
   document.getElementById("actionValidation").setAttribute("value","valider_album");
  }
  else
  if(document.pressed == 'supprimer_album')
  {
	document.getElementById("actionValidation").setAttribute("value","supprimer_album");
    return confirm("Vous êtes sur le point de supprimer un album.\nContinuer?");
  }
}
function OnSubmitActivationForm()
{
  if(document.pressed == 'activer_album')
  {
   document.getElementById("actionActivation").setAttribute("value","activer_album");
  }
  else
  if(document.pressed == 'supprimer_album')
  {
	document.getElementById("actionActivation").setAttribute("value","supprimer_album");
    return confirm("Vous êtes sur le point de supprimer un album.\nContinuer?");
  }
}
</script>
<body>