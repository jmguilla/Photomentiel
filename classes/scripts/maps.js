$(document).ready(function() {
	$("#region").change(function(){
		var val = $("#region option:selected").val();
		$("#ville").html('<option value="-1">Choisir un d&eacute;partement</option>');
		$("#dpt").html('<option value="-1">Choisir une r&eacute;gion</option>');
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=list_departement_par_region&regionID=" + val,
			dataType:"json",
			success:function(data){
			$("#dpt").html('<option value="-1">D&eacute;partements</option>');
			for(var x = 0; x < data.length; x++){
				$("#dpt").append('<option value=' + data[x].id +'>' + data[x].dpt + '</option>');
			};
			}
		});
	});

	$("#dpt").change(function(){
		var val = $("#dpt option:selected").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=list_ville_par_departement&departementID=" + val,
			dataType:"json",
			success:function(data){
			$("#ville").html('<option value="-1">Villes</option>');
			for(var x = 0; x < data.length; x++){
				$("#ville").append('<option value=' + data[x].id +'>' + data[x].dpt + '</option>');
			};
			}
		});
	});
});