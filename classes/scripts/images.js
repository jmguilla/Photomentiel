$(document).ready(function() {
	$("#submitImages1").click(function(){
		$.ajax({
			type: "GET",
			url: "/classes/dispatcher.php",
			data:"action=get_random_image_thumb_path&n=1",
			dataType:"json",
			success:function(data){
				if(data.result == false){
					alert("pas d'image thumb");
					return;
				}else{
					$.each(data.Thumbs,function(i, assoc){
						window.open(assoc.Thumb);
					});
				}
			},
			error:function(XMLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		return false;
	});
});