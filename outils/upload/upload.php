<?php
if (isset($_GET['path'])){
	if ($_FILES['Filedata']) {

		//creation du chemin final
		$uploaddir = $_GET['path']."/";
		$uploadfile = $uploaddir.$_FILES['Filedata']['name'];
		//check extension
		$ext = strtoupper(substr($_FILES["Filedata"]["name"],-3,3));
		if ($ext === "PDF" || $ext === "JPG"){
			//upload
			move_uploaded_file($_FILES['Filedata']['tmp_name'], $uploadfile);
		}
	}
}
?>