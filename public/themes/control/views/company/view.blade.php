@if(!empty($company->joinAddress[0]->address->address_full))
	<?
		$addressFull=$company->joinAddress[0]->address->address_full;
	?>
@else
	<?
		$addressFull=''; 
	?>
@endif
<?
	$channel['theme']->setTitle($company->company_name);
	$channel['theme']->setKeywords($company->company_name);
	$channel['theme']->setDescription($addressFull); 
	if(!empty($channel['info']->channelAttributeBanner[0]->media->media_name)){$channel['theme']->setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.'thumb/'.$channel['info']->channelAttributeBanner[0]->media->media_name);} 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}

{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
@if($channel['security']==true)
{!!Theme::asset()->usePath()->add('bootstrap-timepicker.min', 'css/bootstrap-timepicker.min.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-ui-1.10.3.min', 'js/jquery-ui-1.10.3.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap-timepicker.min', 'js/bootstrap-timepicker.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('js/jquery.maskedinput.min', 'js/jquery.maskedinput.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
@endif
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!!$company->company_name!!}</h1>
		@if(!empty($company->joinAddress[0]->address->address_full))<span>{!!$company->joinAddress[0]->address->address_full!!}</span>@endif
	</div>
	<div class="contentpanel section-content">
		<div class="panel panel-default row-pad-5">
			<div class="panel-body groupCompany">
				<div class="itemCompany">
					<h3 class="subtitle"><strong>{!!$company->company_name!!}</strong></h3>
					@if(count($company->joinAddress)>0)
						@foreach($company->joinAddress as $joinAddress)
						<p><i class="glyphicon glyphicon-map-marker"></i> 
							{{$joinAddress->address->address}} 
							@if(!empty($joinAddress->address->joinWard->ward->id)) - {!!$joinAddress->address->joinWard->ward->ward_name!!}@endif
							@if(!empty($joinAddress->address->joinDistrict->district->id)) - {!!$joinAddress->address->joinDistrict->district->district_name!!}@endif
							@if(!empty($joinAddress->address->joinSubRegion->subregion->id)) - {!!$joinAddress->address->joinSubRegion->subregion->subregions_name!!}@endif
							@if(!empty($joinAddress->address->joinRegion->region->id)) - <i class="flag flag-{{mb_strtolower($joinAddress->address->joinRegion->region->iso)}}"></i> {!!$joinAddress->address->joinRegion->region->country!!}@endif
						</p>
						@endforeach
					@else
						<p><small><i class="glyphicon glyphicon-map-marker"></i> <span style="font-style:italic; ">Chưa cập nhật địa chỉ...</span></small></p>
					@endif
					@if(!empty($company->company_tax_code))
						<p><i class="fa fa-barcode"></i> <strong>Mã số thuế:</strong> {!!$company->company_tax_code!!}</p>
					@endif
					@if(!empty($company->admin_name))
						<p><i class="fa fa-user"></i> <strong>Người đại diện:</strong> {!!$company->admin_name!!}</p>
					@endif
					@if(!empty($company->ngay_cap))
						<p><i class="glyphicon glyphicon-time"></i> <strong>Ngày cấp:</strong> {!!Site::Date($company->ngay_cap)!!}</p>
					@endif
					@if(!empty($company->nganh_chinh))
						<?
							$getField=\App\Model\Fields::find($company->nganh_chinh); 
						?>
						<p><i class="glyphicon glyphicon-time"></i> <strong>Ngành nghề:</strong> {!!$getField->name!!}</p>
					@endif
					<p>
						<small><span class="time-update"><i class="glyphicon glyphicon-time"></i> Cập nhật cách đây: {!!WebService::time_request($company->company_updated_at)!!}</span></small>
						<small><i class="glyphicon glyphicon-eye-open"></i> <span class="post-view text-danger">{{$company->company_views}} lượt xem</span></small>
					</p>
					@if(count($company->joinChannel)>0)
						@foreach($company->joinChannel as $joinChannel)
							@if(count($joinChannel->channel->domainAll)>0)
								@foreach($joinChannel->channel->domainAll as $domainAll)
									<p><a href="{{route('channel.home',$domainAll->domain->domain)}}"><i class="glyphicon glyphicon-globe"></i> {{route('channel.home',$domainAll->domain->domain)}}</a></p>
								@endforeach
							@endif
						@endforeach
					@else
						<p><small><i class="glyphicon glyphicon-globe"></i> <span style="font-style:italic; ">Chưa có địa chỉ website...</span> <a href="{{route('channel.add',$channel["domain"]->domain)}}"><i class="glyphicon glyphicon-plus"></i> Tạo website</a></small></p>
					@endif
				</div>
				@if($channel['security']==true)
				<div class="formEdit">
					<div class="form-group">
						<input type="" name="companyName" id="companyName" class="form-control" value="" placeholder="Nhập tên công ty...">
					</div>
					<div class="form-group">
						<div class="input-group">
							<input type="" name="companyTaxcode" id="companyTaxcode" class="form-control" value="" placeholder="Mã số thuế...">
							<span class="input-group-btn"><button type="button" class="btn btn-primary btnFindTaxcode"><i class="glyphicon glyphicon-search"></i> <span class="hidden-xs">Tìm</span></button></span>
						</div>
					</div>
					<div class="form-group row-pad-5">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
								<input type="text" name="companyAddress" value="" class="form-control" placeholder="Địa chỉ..." />
							</div>
							<label class="error" for="companyAddress"></label>
						</div>
					</div>
					<div class="form-group row-pad-5">
						<div class="col-sm-6">
							<input type="hidden" name="idRegion" value="@if(!empty($sessionChannel['channelRegion'])){!!$sessionChannel['channelRegion']!!}@else{{$channel['info']->channelJoinRegion->region->id}}@endif">
							<input type="hidden" name="regionIso" value="{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}">
							<div class="addSelectRegion"></div>
							<div class="mb10"></div>
						</div>
						<div class="col-sm-6">
							<input type="hidden" name="idSubRegion" value="">
							<div class="addSelectSubRegion"></div>
							<div class="mb10"></div>
						</div>
					</div>
					<div class="form-group row-pad-5">
						<div class="col-sm-6">
							<input type="hidden" name="idDistrict" value="">
							<div class="addSelectDistrict"></div>
							<div class="mb10"></div>
						</div>
						<div class="col-sm-6">
							<input type="hidden" name="idWard" value="">
							<div class="addSelectWard"></div>
							<div class="mb10"></div>
						</div>
					</div>
					<div class="form-group">
						<input type="" name="companyDateActive" id="companyDateActive" class="form-control datepicker" value="" placeholder="Ngày cấp">
					</div>
					<div class="form-group">
						<input type="" name="companyAdmin" id="companyAdmin" class="form-control" value="" placeholder="Người đại diện">
					</div>
					<div class="form-group">
						<div class="addFieldsPrimary"></div>
					</div>
					<div class="form-group">
						<div class="addFields"></div>
					</div>
					<div class="form-group">
						<button type="button" class="btn btn-primary"> Lưu</button>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
		
	', $dependencies);
?>
@if($channel['security']==true)
<?

	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('customAdmin','
		$(".datepicker").datepicker(); 
		$(".datepicker").mask("99/99/9999"); 
		getFieldsPrimary(); 
		function getFieldsPrimary(){
			$(".addFieldsPrimary").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải lĩnh vực, vui lòng chờ...</small></div>"); 
			$.ajax({
				url: "'.route('channel.json.fields',$channel['domain']->domain).'",
				type: "GET",
				dataType: "json",
				success: function (result) {
					$(".addFieldsPrimary .loading").empty(); 
					$(".addFieldsPrimary").append("<select class=\"selectFieldPrimary\" data-placeholder=\"Lĩnh vực chính...\" name=\"channelFields\" required>"
						+"<option value=\"\"></option></select><label class=\"error\" for=\"channelFields\"></label>"); 
					$.each(result.fields, function(i, item) {
						$(".addFieldsPrimary .selectFieldPrimary").append("<option value="+item.id+">"+item.name+"</option>");
					}); 
					jQuery(".addFieldsPrimary .selectFieldPrimary").select2({
						width: "100%"
					});
				}
			});
		}
		getFields(); 
		function getFields(){
			$(".addFields").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải lĩnh vực, vui lòng chờ...</small></div>"); 
			$.ajax({
				url: "'.route('channel.json.fields',$channel['domain']->domain).'",
				type: "GET",
				dataType: "json",
				success: function (result) {
					$(".addFields .loading").empty(); 
					$(".addFields").append("<select class=\"selectField\" data-placeholder=\"Chọn lĩnh vực hoạt động...\" name=\"channelFields\" multiple required>"
						+"<option value=\"\"></option></select><label class=\"error\" for=\"channelFields\"></label>"); 
					$.each(result.fields, function(i, item) {
						$(".addFields .selectField").append("<option value="+item.id+">"+item.name+"</option>");
					}); 
					jQuery(".addFields .selectField").select2({
						width: "100%"
					});
				}
			});
		}
		$(".addSelectRegion").on("change",".selectRegion",function() {
			getSubregion($(this).val()); 
			getDistrict(0); 
			getWard(0); 
			$("input[name=regionIso]").val($(this).find("option:selected").attr("data-iso")); 
			$("input[name=idRegion]").val($(this).val()); 
			$("input[name=idSubRegion]").val(0); 
			$("input[name=idDistrict]").val(0); 
		});
		$(".addSelectSubRegion").on("change",".selectSubRegion",function() {
			getDistrict($(this).val()); 
			getWard(0);
			$("input[name=idSubRegion]").val($(this).val()); 
			$("input[name=idDistrict]").val(0); 
		});
		$(".addSelectDistrict").on("change",".selectDistrict",function() {
			getWard($(this).val()); 
			$("input[name=idDistrict]").val($(this).val()); 
			$("input[name=idWard]").val(0); 
		});
		$(".addSelectWard").on("change",".selectWard",function() {
			$("input[name=idWard]").val($(this).val()); 
		});
		getRegions(); 
		function getRegions(){
			$(".addSelectRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải quốc gia, vui lòng chờ...</small></div>"); 
			$.ajax({
				url: "'.route("regions.json.list",$channel["domain"]->domain).'",
				type: "GET",
				dataType: "json",
				success: function (result) {
					$(".addSelectRegion .loading").empty(); 
					if(result.success==true){
						getSubregion($("input[name=idRegion]").val()); 
						$(".addSelectRegion").append("<div class=\"input-group\"><span class=\"input-group-addon\"><i class=\"flag flag-"+$("input[name=regionIso]").val()+"\"></i></span><select class=\"selectRegion\" data-placeholder=\"Chọn quốc gia...\" name=\"channelRegion\" required>"
						+"<option value=\"\"></option></select></div><label class=\"error\" for=\"channelRegion\"></label>"); 
						$.each(result.region, function(i, item) {
							if(item.id==$("input[name=idRegion]").val()){
								$(".addSelectRegion .selectRegion").append("<option value="+item.id+" data-icon=\"flag-"+item.iso.toLowerCase()+"\"  data-iso="+item.iso.toLowerCase()+" selected>"+item.country+"</option>");
							}else{
								$(".addSelectRegion .selectRegion").append("<option value="+item.id+"  data-icon=\"flag-"+item.iso.toLowerCase()+"\"  data-iso="+item.iso.toLowerCase()+" >"+item.country+"</option>");
							}
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"flag " + $(originalOption).data("icon") + "\"></i> " + icon.text;
						}
						jQuery(".addSelectRegion .selectRegion").select2({
							width: "100%",
							formatResult: format
						});
					}else{
						
					}
				}
			});
		} 
		function getSubregion(idRegion){
			$(".addSelectSubRegion").empty(); 
			$(".addSelectSubRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải thành phố, vui lòng chờ...</small></div>"); 
			var formData = new FormData();
			formData.append("idRegion", idRegion); 
			$.ajax({
				url: "'.route("subregion.json.list.post",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					$(".addSelectSubRegion .loading").empty(); 
					$(".addSelectRegion .input-group-addon").html("<i class=\"flag flag-"+$("input[name=regionIso]").val()+"\"></i>"); 
					if(result.success==true){
						getDistrict($("input[name=idSubRegion]").val()); 
						$(".addSelectSubRegion").append("<select class=\"selectSubRegion\" data-placeholder=\"Chọn thành phố...\" name=\"channelSubRegion\">"
						+"<option value=\"\"></option></select><label class=\"error\" for=\"channelSubRegion\"></label>"); 
						$.each(result.subregion, function(i, item) {
							if(item.id==$("input[name=idSubRegion]").val()){
								$(".addSelectSubRegion .selectSubRegion").append("<option value="+item.id+" selected>"+item.subregions_name+"</option>");
							}else{
								$(".addSelectSubRegion .selectSubRegion").append("<option value="+item.id+">"+item.subregions_name+"</option>");
							}
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
						}
						jQuery(".addSelectSubRegion .selectSubRegion").select2({
							width: "100%",
							formatResult: format
						});
					}else{
						$(".addSelectSubRegion").empty(); 
					}
				}
			});
		}
		function getDistrict(idSubRegion){
			$(".addSelectDistrict").empty(); 
			$(".addSelectDistrict").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải quận huyện, vui lòng chờ...</small></div>"); 
			var formData = new FormData();
			formData.append("idSubRegion", idSubRegion); 
			$.ajax({
				url: "'.route("district.json.list.post",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					$(".addSelectDistrict .loading").empty(); 
					if(result.success==true){
						getWard($("input[name=idDistrict]").val()); 
						$(".addSelectDistrict").append("<select class=\"selectDistrict\" data-placeholder=\"Chọn quận huyện...\" name=\"channelDistrict\">"
							+"<option value=\"\"></option></select><label class=\"error\" for=\"channelDistrict\"></label>"); 
						$.each(result.district, function(i, item) {
							if(item.id=='.$channel['info']->channelJoinSubRegion->subregion->id.'){
								$(".addSelectDistrict .selectDistrict").append("<option value="+item.id+" selected>"+item.district_name+"</option>");
							}else{
								$(".addSelectDistrict .selectDistrict").append("<option value="+item.id+">"+item.district_name+"</option>");
							}
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
						}
						jQuery(".addSelectDistrict .selectDistrict").select2({
							width: "100%",
							formatResult: format
						});
					}else{
						$(".addSelectDistrict").empty(); 
					}
				}
			});
		}
		function getWard(idDistrict){
			$(".addSelectWard").empty(); 
			$(".addSelectWard").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải phường xã, vui lòng chờ...</small></div>"); 
			var formData = new FormData();
			formData.append("idDistrict", idDistrict); 
			$.ajax({
				url: "'.route("ward.json.list.post",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					//console.log(result); 
					$(".addSelectWard .loading").empty(); 
					if(result.success==true){
						$(".addSelectWard").append("<select class=\"selectWard\" data-placeholder=\"Chọn phường xã...\" name=\"channelWard\">"
							+"<option value=\"\"></option></select><label class=\"error\" for=\"channelWard\"></label>"); 
						$.each(result.ward, function(i, item) {
							if(item.id=='.$channel['info']->channelJoinSubRegion->subregion->id.'){
								$(".addSelectWard .selectWard").append("<option value="+item.id+" selected>"+item.ward_name+"</option>");
							}else{
								$(".addSelectWard .selectWard").append("<option value="+item.id+">"+item.ward_name+"</option>");
							}
						}); 
						function format(icon) {
							var originalOption = icon.element;
							return "<i class=\"fa fa-map-marker\"></i> " + icon.text;
						}
						jQuery(".addSelectWard .selectWard").select2({
							width: "100%",
							formatResult: format
						});
					}else{
						$(".addSelectWard").empty(); 
					}
				}
			});
		}
	', $dependencies);
?>
@endif