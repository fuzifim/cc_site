<?
	$channel['theme']->setTitle('Thông tin tên miền');
	$channel['theme']->setKeywords('Thông tin tên miền');
	$channel['theme']->setDescription('Nhập thông tin quản lý tên miền đăng ký '); 
	$getUser=Auth::user(); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-ui-1.10.3.min', 'js/jquery-ui-1.10.3.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap-timepicker.min', 'js/bootstrap-timepicker.min.js', array('core-script'))!!}
{!!Theme::asset()->usePath()->add('bootstrap-timepicker.min', 'css/bootstrap-timepicker.min.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('js/jquery.maskedinput.min', 'js/jquery.maskedinput.min.js', array('core-script'))!!}
<section>
<div class="mainpanel">
	{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		<form id="infoCustomers" class="panel panel-primary form-group">
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6 form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> 
							<input type="text" class="form-control" id="customerName" name="customerName" placeholder="Họ tên" required=""@if(!empty($getUser->name)) value="{!!$getUser->name!!}"@endif>
						</div>
					</div>
					<div class="col-md-6 form-group">
						<select class="form-control" name="customerSex" id="customerSex">
							<?
								$maleSelected='';
								$femaleSelected=''; 
								$ortherSelected=''; 
								if($getUser->gender=='male'){
									$maleSelected='selected';
								}else if($getUser->gender=='female'){
									$femaleSelected='selected';
								}else if($getUser->gender=='other'){
									$ortherSelected='selected';
								}
							?>
							<option value="0">Chọn giới tính</option>
							<option value="male" {{$maleSelected}}>Nam</option>
							<option value="female" {{$femaleSelected}}>Nữ</option>
							<option value="other" {{$ortherSelected}}>Khác</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span> 
							<input type="text" class="form-control datepicker" id="customerBirthday" name="customerBirthday" placeholder="Ngày sinh" required=""@if(!empty($getUser->birthday))value="{!!\Carbon\Carbon::parse($getUser->birthday)->format('d/m/Y')!!}"@endif>
						</div>
					</div>
					<div class="col-md-6 form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span> 
							<input type="text" class="form-control" id="customerEmail" name="customerEmail" placeholder="Email" required="" value="@if(!empty($getUser->email)){!!$getUser->email!!}@endif">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span> 
							<input type="text" class="form-control" id="customerPhone" name="customerPhone" placeholder="Số điện thoại" required=""@if(!empty($getUser->phone))value="{!!$getUser->phone!!}"@endif>
						</div>
					</div>
					<div class="col-md-6 form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span> 
							<input type="text" class="form-control" id="customerAddress" name="customerAddress" placeholder="Địa chỉ" required=""@if(!empty($getUser->joinAddress->address->address)) value="{!!$getUser->joinAddress->address->address!!}"@endif>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 form-group">
						<input type="hidden" name="idRegion" value="@if(!empty($getUser->getRegion->region->id)){!!$getUser->getRegion->region->id!!}@else{{$channel['info']->channelJoinRegion->region->id}}@endif">
						<input type="hidden" name="regionIso" value="@if(!empty($getUser->getRegion->region->id)){{mb_strtolower($getUser->getRegion->region->iso)}}@else{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}@endif">
						<div class="addSelectRegion"></div>
					</div>
					<div class="col-md-6 form-group">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-map-marker"></i></span> 
							<input type="hidden" name="idSubRegion" value="@if(!empty($getUser->getSubRegion->subRegion->subregions_name)){!!$getUser->getSubRegion->subRegion->id!!}@else{{$channel['info']->channelJoinSubRegion->subregion->id}}@endif">
							<div class="addSelectSubRegion"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer text-right">
				<a class="btn btn-sm btn-default" href="{{route('pages.domain',$channel['domainPrimary'])}}">Quay lại</a> <button type="submit" class="btn btn-sm btn-success" id="btnPayment"><i class="fa fa-check"></i> Tiếp tục</button>
			</div>
		</form>
	</div>
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		$(".datepicker").datepicker(); 
		$(".datepicker").mask("99/99/9999"); 
		getRegions();
		function getRegions(){
			$(".addSelectRegion").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải quốc gia, vui lòng chờ...</small></div>"); 
			$.ajax({
				url: "'.route("regions.json.list",$channel["domainPrimary"]).'",
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
			url: "'.route("subregion.json.list.post",$channel["domainPrimary"]).'",
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
		var $validator = jQuery("#infoCustomers").validate({
			highlight: function(element) {
			  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
			},
			success: function(element) {
			  jQuery(element).closest(".form-group").removeClass("has-error");
			}
		});
		$("#infoCustomers").on("click","#btnPayment",function(){ 
			var dataDomain={};
			$("#infoCustomers .btnPayment").addClass("disabled"); 
			var formData = new FormData();
			formData.append("customerName", $("input[name=customerName]").val()); 
			formData.append("customerSex", $("#customerSex :selected").val()); 
			formData.append("customerBirthday", $("input[name=customerBirthday]").val()); 
			formData.append("customerPhone", $("input[name=customerPhone]").val()); 
			formData.append("customerEmail", $("input[name=customerEmail]").val()); 
			formData.append("customerAddress", $("input[name=customerAddress]").val()); 
			formData.append("customerCity", $("select[name=channelSubRegion]").select2("val")); 
			formData.append("customerCountry", $("select[name=channelRegion]").select2("val")); 
			$("#preloaderInBox").css("display", "block"); 
			$.ajax({
				url: "'.route("cart.domain.info.process",$channel["domainPrimary"]).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					console.log(result); 
					if(result.success==true){
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						window.location.href = "'.route("pay.cart",$channel["domainPrimary"]).'";
					}else{
						$("#preloaderInBox").css("display", "none");  
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						});
					}
				}
			});
			return false; 
		}); 
	', $dependencies);
?>