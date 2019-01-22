<?
	$channel['theme']->setTitle('Thông tin liên hệ');
?>
@include('themes.admin.inc.header')

<link type="text/css" href="{{asset('assets/flags-img/16x16/sprite-flags-16x16.css')}}" rel="stylesheet"/>
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				@include('themes.admin.partial.menuManageService')
			</div>
			<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input id="item-channelDomain" name="channelDomain" value="{{$channel['domain']->domain}}" type="hidden" class="form-control" placeholder="Tên miền">
				<input id="item-channelId" name="channelId" value="{{$channel['info']->id}}" type="hidden" class="form-control" placeholder="Id kênh">
				<div class="panel panel-primary">
					<div class="panel-body">
						<div class="message"></div>
						<div class="form-group">
							<label class="control-label" for="name">Tên doanh nghiệp: <span class="text-danger">(*)</span></label>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-briefcase"></i></span>
								<input id="name" name="channelCompanyName" value="@if(!empty($channel['info']->companyJoin->company->company_name)){{$channel['info']->companyJoin->company->company_name}}@endif" type="text" class="form-control" placeholder="Tên doanh nghiệp...">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="address">Địa chỉ: <span class="text-danger">(*)</span></label>
							<div class="form-group input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span>
								<input id="address" name="channelCompanyAddress" value="@if(!empty($channel['info']->companyJoin->company->company_address)){{$channel['info']->companyJoin->company->company_address}}@endif" type="text" class="form-control" placeholder="Địa chỉ doanh nghiệp...">
								<span class="input-group-btn">
									<button class="btn btn-primary" type="button" id="addNewAddress"><span class="glyphicon glyphicon-plus"></span> <span class="hidden-xs">Thêm</span></button>
								</span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone">Điện thoại: <span class="text-danger">(*)</span></label>
							<div class="input-group">
								<span class="input-group-addon"><i class="flag flag-16 flag-{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}"></i><span style="font-size:12px;">{{$channel['info']->channelJoinRegion->region->phone_prefix}}</span></span>
								<input id="phone" name="channelPhone" value="@if(!empty($channel['info']->phoneJoin->phone->phone_number)){{$channel['info']->phoneJoin->phone->phone_number}}@endif" type="number" class="form-control" placeholder="Số điện thoại...">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="item-channelEmail">Email: <span class="text-danger">(*)</span></label>
							<div class="input-group">
								<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
								<input id="email" name="channelEmail" value="@if(!empty($channel['info']->emailJoin->email->email_address)){{$channel['info']->emailJoin->email->email_address}}@endif" type="email" class="form-control" placeholder="Địa chỉ email...">
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
									<input type="hidden" name="channelSubRegion" id="subRegion" value="{{$channel['info']->channelJoinSubRegion->subregion->id}}">
									<span class="glyphicon glyphicon-menu-down btnAdd"></span>
								</div>
							@endif 
						</div>
					</div>
					<div class="panel-footer text-right">
						<button id="saveContactChannel" class="btn btn-primary" type="button"><i class="glyphicon glyphicon-ok"></i> Lưu </button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modalRegion" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row addContentRegion"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<div id="loading">
	<ul class="bokeh">
		<li></li>
		<li></li>
		<li></li>
	</ul>
</div>
<script>
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
	$('#saveContactChannel').click(function () {
		$('.message').empty(); 
		$('#loading').css('visibility', 'visible');
		var formData = new FormData();
		formData.append("channelId", $('input[name=channelId]').val()); 
		formData.append("channelDomain", $('input[name=channelDomain]').val()); 
		formData.append("channelCompanyName", $('input[name=channelCompanyName]').val()); 
		formData.append("channelCompanyAddress", $('input[name=channelCompanyAddress]').val()); 
		formData.append("channelPhone", $('input[name=channelPhone]').val()); 
		formData.append("channelEmail", $('input[name=channelEmail]').val()); 
		formData.append("channelRegion", $('input[name=channelRegion]').val()); 
		formData.append("channelSubRegion", $('input[name=channelSubRegion]').val()); 
        $.ajax({
            url: "{{route('channel.admin.contact.update',$channel['domain']->domain)}}",
            type: "POST",
            cache: false,
			contentType: false,
			processData: false,
			dataType:'json',
			data:formData,
			headers: {'X-CSRF-TOKEN': $('meta[name=_token]').attr('content')},
            success: function (result) {
				$('#loading').css('visibility', 'hidden');
				if(result.messageType=='validation'){
					$('.message').append('<div class="alert alert-danger alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></div>'); 
					var res = jQuery.parseJSON(JSON.stringify(result.message)); 
					var name;
					jQuery.each(res, function(i, val) {
							if(i=='channelCompanyName'){
								name='Tên doanh nghiệp:';
							}else if(i=='channelCompanyAddress'){
								name='Địa chỉ doanh nghiệp';
							}else if(i=='channelPhone'){
								name='Số điện thoại';
							}else if(i=='channelEmail'){
								name='Địa chỉ email';
							}else{
								name='';
							}
							$('#alertError').append('<li><b>'+name+'</b> '+val+'</li>');
					});
				}
				else if(result.messageType=='success'){
					$('.message').append('<div class="alert alert-success alert-dismissable" id="alertError"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+result.message+'</div>'); 
					$("#alertError").alert();
					$("#alertError").fadeTo(2000, 500).slideUp(500, function(){
						$("#alertError").slideUp(500);
					});
				}
				
            }
        });
    });
</script>
@include('themes.admin.inc.footer')