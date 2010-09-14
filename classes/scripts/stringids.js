$(document).ready(function() {
	$("#submitStringID1").click(function(){
		var stringid = $("#idStringID1").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=get_stringid&sid=" + stringid,
			dataType:"json",
			success:function(data){
				if(data.result == false){
					alert("pas de stringid correspondant");
					return;
				}
				if(data.klass == "StringID"){
					var tmp = "StringID:\n";
					$.each(data.fields, function(key, val){
						tmp = tmp + "\t" + key + ": " + val + "\n";
					});
					alert(tmp);
				}else{
					alert("failed!!");
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		return false;
	});
	$("#submitStringID2").click(function(){
		var aid = $("#idStringID2").val();
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=get_stringid_par_idalbum&ida=" + aid,
			dataType:"json",
			success:function(data){
				if(data.result == false){
					alert("pas de stringid correspondant");
					return;
				}
				if(data.klass == "StringID"){
					var tmp = "Album:\n";
					$.each(data.fields, function(key, val){
						tmp = tmp + "\t" + key + ": " + val + "\n";
					});
					alert(tmp);
				}else{
					alert("failed!!");
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		return false;
	});
});