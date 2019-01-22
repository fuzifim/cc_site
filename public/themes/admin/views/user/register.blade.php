<?
	$channel['theme']->setTitle('Đăng ký thành viên');
?>
@include('themes.admin.inc.header')
<link type="text/css" href="{{asset('assets/flags-img/16x16/sprite-flags-16x16.css')}}" rel="stylesheet"/>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="panel panel-default formRegister">
				<div class="panel-body">
					<div class="form-group">
						<a href="{{route('channel.login',$channel['domain']->domain)}}"><i class="glyphicon glyphicon-chevron-left"></i> Trở về trang đăng nhập</a>
					</div>
					<div class="message"></div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
							<input placeholder="Nhập tên của bạn" id="name" name="name" value="" type="text" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label for="phone" class="control-label">Số điện thoại</label>
						<div class="input-group">
							<span class="input-group-addon" id="basic-addon1"><i class="flag flag-16 flag-{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}"></i>{{$channel['info']->channelJoinRegion->region->phone_prefix}}</span>
							<input placeholder="09xxxxxxxx" id="phone" name="phone" value="" type="number" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
							<input placeholder="abc@xyz.com" id="email" name="email" type="email" value="" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input placeholder="Mật khẩu" id="password" name="password" type="password" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
							<input placeholder="Nhập lại mật khẩu" id="password_confirmation" name="password_confirmation" type="password"  class="form-control">
						</div>
					</div>
					<div class="form-group">
						@if($channel['info']->channelJoinRegion->region!=false)
							<div class="fieldsAdd" id="changeRegion">
								<span id="flagIso"><i class="flag flag-16 flag-{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}"></i></span> <span id="channelRegionName">{{$channel['info']->channelJoinRegion->region->country}}</span> 
								<input type="hidden" id="region" name="channelRegion" value="{{$channel['info']->channelJoinRegion->region->id}}">
								<span class="glyphicon glyphicon-menu-down btnAdd"></span>
							</div>
						@endif 
					</div>
					<div class="form-group">
						@if($channel['info']->channelJoinSubRegion->subregion!=false)
							<div class="fieldsAdd" id="changeSubregion">
								<span id="subRegionName"><i class="glyphicon glyphicon-map-marker"></i> {{$channel['info']->channelJoinSubRegion->subregion->subregions_name}} </span>
								<input type="hidden" name="channelSubregion" id="subRegion" value="{{$channel['info']->channelJoinSubRegion->subregion->id}}">
								<span class="glyphicon glyphicon-menu-down btnAdd"></span>
							</div>
						@endif 
					</div>
					<div class="form-group">
						<input type="checkbox" class="filled-in" name="accept_term" id="accept-term"/>
						<label for="filled-in-box">
							Đồng ý <a href="#!">Điều khoản của chúng tôi</a>
						</label>
					</div>
				</div>
				<div class="panel-footer text-right">
					<button type="button" class="btn btn-primary" id="btnRegister"><i class="glyphicon glyphicon-ok"></i> Đăng ký</button>
				</div>
			</div>
		</div>
	</div>
</div>
	<script>
	$('.formRegister').on("click","#btnRegister",function() {
		$('.formRegister .message').empty(); 
		var rootUrl=$('meta[name=root]').attr('content'); 
		var fullName=$('.formRegister input[name=name]').val(); 
		var phone=$('.formRegister input[name=phone]').val(); 
		var email=$('.formRegister input[name=email]').val(); 
		var password=$('.formRegister input[name=password]').val(); 
		var password_confirmation=$('.formRegister input[name=password_confirmation]').val();  
		var region=$('.formRegister input[name=channelRegion]').val();  
		var subRegion=$('.formRegister input[name=channelSubregion]').val();  
		var rootUrl=$('meta[name=root]').attr('content'); 
		var formData = new FormData();
		formData.append("fullName", fullName); 
		formData.append("phone", phone); 
		formData.append("email", email); 
		formData.append("password", password); 
		formData.append("password_confirmation", password_confirmation); 
		formData.append("region", region); 
		formData.append("subRegion", subRegion); 
		$.ajax({
			url: rootUrl+"/register",
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
			type: 'post',
			cache: false,
			contentType: false,
			processData: false,
			data: formData,
			dataType:'json',
			success:function(result){
				console.log(result); 
				if(result.success==false){
					if(result.messageType=='validation'){
						$('.formRegister .message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'); 
						var res = jQuery.parseJSON(JSON.stringify(result.message)); 
						var name;
						jQuery.each(res, function(i, val) {
							$('#alertError').append('<li>'+val+'</li>');
						});
					}else{
						$('.formRegister .message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					}
				}else if(result.success==true){
					$('.formRegister .message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					window.location.href = result.return_url;
				}
			},
			error: function(result) {
			}
		});
	});
	$('#changeRegion').click(function () {
		$('#loading').css('visibility', 'visible');
		$('.message').empty(); 
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
        $.ajax({
            url: "{{route('regions.json.list',$channel['domain']->domain)}}",
            type: "GET",
            dataType: "json",
            success: function (result) {
				$('#loading').css('visibility', 'hidden');
				$('#myModal .modal-title').text(result.message); 
				$('#myModal .modal-body').append('<div class="row addContentRegion"></div>'); 
				$.each(result.region, function(i, item) {
					$('#myModal .modal-body .addContentRegion').append('<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><div class="form-group"><button class="btn btn-xs checkRegion" style="white-space: inherit; background: none; text-align:left; " type="button" name="checkRegion" value="'+item.country+'" data-id="'+item.id+'" data-flagiso="'+item.iso.toLowerCase()+'" id="checkRegion"> <i class="flag flag-16 flag-'+item.iso.toLowerCase()+'"></i> '+item.country+'</button></div></div>');
				})
				$('#myModal').modal('show');
            }
        });
    });
	$('#changeSubregion').click(function () {
		$('#loading').css('visibility', 'visible');
		$('.message').empty(); 
		$('#myModal .modal-title').empty(); 
		$('#myModal .modal-body').empty(); 
		$('#myModal .modal-footer').empty(); 
		var idRegion=$('#region').val(); 
		$.ajax({
            url: "{{route('channel.home',$channel['domain']->domain)}}/regions/json/subregion/list/"+idRegion,
            type: "GET",
            dataType: "json",
            success: function (result) {
				$('#loading').css('visibility', 'hidden');
				$('#myModal .modal-title').text(result.message); 
				$('#myModal .modal-body').append('<div class="row addContentRegion"></div>'); 
				$.each(result.subregion, function(i, item) {
					$('#myModal .modal-body .addContentRegion').append('<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6"><div class="form-group"><button class="btn btn-xs checkSubRegion" style="white-space: inherit; background: none; text-align:left; " type="button" name="checkSubRegion" value="'+item.subregions_name+'" data-id="'+item.id+'" id="checkSubRegion"><i class="glyphicon glyphicon-map-marker"></i> '+item.subregions_name+'</button></div></div>');
				})
				$('#myModal').modal('show');
            }
        });
	});
	$('#myModal').on("click",".checkRegion",function() {
		var regionName=$(this).val(); 
		var regionId=$(this).attr("data-id");
		var flagIso=$(this).attr("data-flagiso");
		$('#region').val(regionId); 
		$('#channelRegionName').text(regionName);
		$('#flagIso').html('<i class="flag flag-16 flag-'+flagIso+'"></i>');
		$('#myModal').modal('hide');
	});
	$('#myModal').on("click",".checkSubRegion",function() {
		var subRegionName=$(this).val(); 
		var subRegionId=$(this).attr("data-id");
		var flagIso=$(this).attr("data-flagiso");
		$('#subRegion').val(subRegionId); 
		$('#subRegionName').html('<i class="glyphicon glyphicon-map-marker"></i> '+subRegionName);
		$('#myModal').modal('hide');
	});
	</script>
@include('themes.admin.inc.footer')