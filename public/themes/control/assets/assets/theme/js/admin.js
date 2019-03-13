jQuery(document).ready(function($){
	$("#btnJoinChannel").click(function() {
		var rootUrl=$('meta[name=root]').attr('content'); 
		$.ajax({
			url: rootUrl+"/profile/joinchannel",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			success:function(result){
				//console.log(result); 
				location.reload(); 
			},
			error: function(result) {
			}
		});
	});
});