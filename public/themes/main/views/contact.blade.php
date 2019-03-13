<?
	$channel['theme']->setTitle('Liên hệ');
	$channel['theme']->setKeywords('Liên hệ');
	$channel['theme']->setDescription('Thông tin liên hệ '.$channel['info']->channel_name); 
	if(count($channel['info']->channelAttributeBanner)>0 && !empty($channel['info']->channelAttributeBanner[0]->media->media_name)){$channel['theme']->setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.'thumb/'.$channel['info']->channelAttributeBanner[0]->media->media_name);}
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.autocomplete.min', 'js/jquery.autocomplete.min.js', array('core-script'));
	if($channel['security']==true){
		Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'));
	}
	?>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<div class="form-group">
							<div class="groupCompany">
								<div class="itemCompany">
									<h4 class="panel-title">@if(!empty($channel['info']->companyJoin->company))<a href="@if(!empty($company->joinChannelParent->channel->id) && !empty($channel['info']->channelParent->id) && $company->joinChannelParent->channel->id==$channel['info']->id){{route('company.view.slug',array($channel['domainParentPrimary'],$channel['info']->companyJoin->company->id.'-'.Str::slug($channel['info']->companyJoin->company->company_name)))}}@else{{route('company.view.slug',array(config('app.url'),$channel['info']->companyJoin->company->id.'-'.Str::slug($channel['info']->companyJoin->company->company_name)))}}@endif">{{$channel['info']->companyJoin->company->company_name}}</a>@else{{$channel['info']->channel_name}}@endif 
									@if($channel['security']==true)<a href="" class="btnCompany"><small class="text-danger"><i class="fa fa-pencil"></i> sửa</small></a> <a href="" class="btnCompanyDel"><small class="text-danger"><i class="fa fa-trash-o"></i> xóa</small></a>@endif</h4>
								</div>
								@if($channel['security']==true)
								<div class="appendCompany hidden">
									<div class="form-group">
										<input type="text" class="form-control" value="@if(!empty($channel['info']->companyJoin->company->id)){{$channel['info']->companyJoin->company->company_name}}@endif" name="companyName" id="companyName" placeholder="Nhập tên công ty hoặc tìm bằng mã số thuế...">
										<input type="hidden" name="idCompany" id="idCompany" value="@if(!empty($channel['info']->companyJoin->company->id)){{$channel['info']->companyJoin->company->id}}@endif">
									</div>
									<div class="form-group">
										<button type="button" class="btn btn-xs btn-primary btnSaveCompany">Lưu</button> <button type="button" class="btn btn-xs btn-default btnCancleCompany">Hủy</button>
									</div>
								</div>
								@endif
							</div>
						</div>
						<div class="form-group">
							@if(count($channel['info']->joinAddress)>0)
								@foreach($channel['info']->joinAddress as $joinAddress)
									<div class="mb5 addressItemGroup">
										<div class="addressItem">
											<i class="glyphicon glyphicon-map-marker"></i> {{$joinAddress->address->address}} 
											@if(!empty($joinAddress->address->joinWard->ward->id)) - {!!$joinAddress->address->joinWard->ward->ward_name!!}@endif
											@if(!empty($joinAddress->address->joinDistrict->district->id)) - {!!$joinAddress->address->joinDistrict->district->district_name!!}@endif
											@if(!empty($joinAddress->address->joinSubRegion->subregion->id)) - {!!$joinAddress->address->joinSubRegion->subregion->subregions_name!!}@endif
											@if(!empty($joinAddress->address->joinRegion->region->id)) - <i class="flag flag-{{mb_strtolower($joinAddress->address->joinRegion->region->iso)}}"></i> {!!$joinAddress->address->joinRegion->region->country!!}@endif
											@if($channel['security']==true)<small><span style="font-style:italic; "><a href="" id="changeAddress" class="text-danger" data-id="{!!$joinAddress->address->id!!}" data-address="{!!$joinAddress->address->address!!}" data-ward="@if(!empty($joinAddress->address->joinWard->ward->id)){!!$joinAddress->address->joinWard->ward->id!!}@endif" data-district="@if(!empty($joinAddress->address->joinDistrict->district->id)){!!$joinAddress->address->joinDistrict->district->id!!}@endif" data-subregion="@if(!empty($joinAddress->address->joinSubRegion->subregion->id)){!!$joinAddress->address->joinSubRegion->subregion->id!!}@endif" data-region="@if(!empty($joinAddress->address->joinRegion->region->id)){!!$joinAddress->address->joinRegion->region->id!!}@endif" data-region-iso="@if(!empty($joinAddress->address->joinRegion->region->id)){{mb_strtolower($joinAddress->address->joinRegion->region->iso)}}@endif"><i class="fa fa-pencil"></i> sửa</a>@if(count($channel['info']->joinAddress)>1) / <a href="" class="text-danger delAddress" data-id="{{$joinAddress->address->id}}"><i class="fa fa-trash-o"></i> xóa</a>@endif</span></small>@endif
										</div>
										<div class="changeAddressItem"></div>
									</div>
								@endforeach
								@if($channel['security']==true)
								<div class="mb5 addressItemGroup">
									<div class="changeAddressItem"></div>
									<small><a href="" class="addressAddNew text-danger"><i class="fa fa-plus"></i> thêm địa chỉ</a></small>
								</div>
								@endif
							@else
								<div class="mb5 addressItemGroup">
									<div class="changeAddressItem"></div>
									<div class="addressItem">
										<small><i class="glyphicon glyphicon-map-marker"></i> <span style="font-style:italic; ">Chưa cập nhật địa chỉ...</small>
										@if($channel['security']==true)
										<small><a href="" class="addressAddNew text-danger"><i class="fa fa-plus"></i> thêm địa chỉ</a></span></small>
										@endif
									</div>
								</div>
							@endif
							@if(count($channel['info']->joinEmail)>0)
								@foreach($channel['info']->joinEmail as $joinEmail)
									<span class="mb5 emailItemGroup">
										<span class="emailItem">
											<i class="glyphicon glyphicon-envelope"></i> <a href="mailto:{{$joinEmail->email->email_address}}">{{$joinEmail->email->email_address}}</a>
											@if($channel['security']==true)<small><a href="" class="emailChange text-danger" data-email="{{$joinEmail->email->email_address}}" data-id="{{$joinEmail->email->id}}"><i class="fa fa-pencil"></i> sửa</a>@if(count($channel['info']->joinEmail)>1) / <a href="" class="text-danger delEmail" data-id="{{$joinEmail->email->id}}"><i class="fa fa-trash-o"></i> xóa</a>@endif</small>@endif
										</span>
										<span class="changeEmailItem"></span>
									</span>
								@endforeach
								 <i class="glyphicon glyphicon-globe"></i> Website: <a href="{{route('channel.home',$channel['domainPrimary'])}}">http://{!!$channel['domainPrimary']!!}</a>
								@if($channel['security']==true)
								<div class="mb5 emailItemGroup">
									<div class="changeEmailItem"></div>
									<small><a href="" class="emailAddNew text-danger"><i class="fa fa-plus"></i> thêm email</a></small>
								</div>
								@endif
							@else
								<div class="mb5 emailItemGroup">
									<div class="changeEmailItem"></div>
									<div class="emailItem">
										<small><i class="glyphicon glyphicon-map-marker"></i> <span style="font-style:italic; ">Chưa cập nhật Email...</span></small>
										@if($channel['security']==true)
										<small><a href="" class="emailAddNew text-danger"><i class="fa fa-plus"></i> thêm địa chỉ</a></span></small>
										@endif
									</div>
								</div>
							@endif
							@if(count($channel['info']->joinPhone)>0)
								@foreach($channel['info']->joinPhone as $joinPhone)
									<span class="mb5 phoneItemGroup">
										<span class="phoneItem">
											<i class="glyphicon glyphicon-earphone"></i><a href="tel:{{$joinPhone->phone->phone_number}}">{{$joinPhone->phone->phone_number}}</a> 
											@if($channel['security']==true)<small><a href="" class="phoneChange text-danger" data-phone="{{$joinPhone->phone->phone_number}}" data-id="{{$joinPhone->phone->id}}"><i class="fa fa-pencil"></i> sửa</a> @if(count($channel['info']->joinPhone)>1)/ <a href="" class="text-danger delPhone" data-id="{{$joinPhone->phone->id}}"><i class="fa fa-trash-o"></i> xóa</a>@endif</small>@endif
										</span>
										<span class="changePhoneItem"></span>
									</span>
								@endforeach
								@if($channel['security']==true)
								<div class="mb5 phoneItemGroup">
									<div class="changePhoneItem"></div>
									<small><a href="" class="phoneAddNew text-danger"><i class="fa fa-plus"></i> thêm số điện thoại</a></small>
								</div>
								@endif
							@else
								<div class="mb5 phoneItemGroup">
									<div class="changePhoneItem"></div>
									<div class="phoneItem">
										<i class="glyphicon glyphicon-earphone"></i> <span style="font-style:italic; ">Chưa cập nhật số điện thoại...</span>
										@if($channel['security']==true)
										<small><a href="" class="phoneAddNew text-danger"><i class="fa fa-plus"></i> thêm số điện thoại</a></span></small>
										@endif
									</div>
								</div>
							@endif
						</div> 
					</div>
					<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
						<form class="form" id="contactForm">
							<div class="message"></div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> 
									<input type="text" class="form-control" id="name" name="name" placeholder="Họ tên" @if(!empty(Auth::user()->name)) value=" {!!Auth::user()->name!!}" disabled @endif required>
								</div>
								<label class="error" for="name"></label>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span> 
									<input type="text" class="form-control" id="email" name="email" placeholder="Email" @if(!empty(Auth::user()->email)) value="{!!Auth::user()->email!!}" disabled @endif required>
								</div>
								<label class="error" for="email"></label>
							</div>
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span> 
									<input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" @if(!empty(Auth::user()->phone)) value="{!!Auth::user()->phone!!}" disabled @endif required>
								</div>
								<label class="error" for="phone"></label>
							</div>
							<div class="form-group">
								<input type="text" class="form-control" id="title" name="title" placeholder="Tiêu đề" required>
								<label class="error" for="title"></label>
							</div>
							<div class="form-group">
								<textarea class="form-control" type="textarea" id="content" name="content" placeholder="Nội dung liên hệ" maxlength="140" rows="7" required></textarea>
								<label class="error" for="content"></label>
							</div>
							<div class="form-group text-right">
								<button type="button" class="btn btn-primary" id="btnSendContact"><i class="glyphicon glyphicon-send"></i> Gửi yêu cầu</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('customJsUser','
		jQuery(document).ready(function(){
			"use strict"; 
			var rootUrl=$("meta[name=root]").attr("content"); 
			var $validator = jQuery("#contactForm").validate({
				highlight: function(element) {
				  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
				},
				success: function(element) {
				  jQuery(element).closest(".form-group").removeClass("has-error");
				}
			});
			$("#btnSendContact").click(function(){
				var $valid = jQuery("#contactForm").valid();
				if(!$valid) {
					$validator.focusInvalid();
					return false;
				}else{
					var formData = new FormData();
					formData.append("name", $("#contactForm input[name=name]").val()); 
					formData.append("email", $("#contactForm input[name=email]").val()); 
					formData.append("phone", $("#contactForm input[name=phone]").val()); 
					formData.append("title", $("#contactForm input[name=title]").val()); 
					formData.append("content", $("#contactForm textarea[name=content]").val()); 
					$.ajax({
						url: rootUrl+"/contact",
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						type: "post",
						cache: false,
						contentType: false,
						processData: false,
						data: formData,
						dataType:"json",
						success:function(result){
							//console.log(result); 
							if(result.success==true){
								jQuery.gritter.add({
									title: "Thông báo!",
									text: result.msg, 
									class_name: "growl-success",
									sticky: false,
									time: ""
								});
								 $("#contactForm")[0].reset();
							}else{
								jQuery.gritter.add({
									title: "Thông báo!",
									text: result.msg, 
									class_name: "growl-danger",
									sticky: false,
									time: ""
								});
							}
						},
						error: function(result) {
						}
					});
				}
			});
		}); 
	', $dependencies);
?>
@if($channel['security']==true)
<?
	if(!empty($channel['info']->joinAddress[0]->address->joinRegion->region->id)){
		$regionDefaultId=$channel['info']->joinAddress[0]->address->joinRegion->region->id; 
		$regionDefaultIso=mb_strtolower($channel['info']->joinAddress[0]->address->joinRegion->region->iso); 
	}else{
		$regionDefaultId=""; 
		$regionDefaultIso=""; 
	}
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom',' 
		if($("#companyName").length>0){ 
			$(".groupCompany #companyName").autocomplete({ 
				serviceUrl: "'.route("search.company",$channel["domain"]->domain).'",
				type:"GET",
				paramName:"txt",
				dataType:"json",
				minChars:2,
				deferRequestBy:100,
				onSearchComplete: function(){
					$(".autocomplete-suggestions").css({
						"width":+$(this).outerWidth()
					}); 
				},
				//lookup: currencies,
				onSelect: function (suggestion) {
					$("#idCompany").val(suggestion.data); 
					//console.log(suggestion); 
				}
			});
		}
		$(".groupCompany").on("click",".btnCompany",function() {
			var itemCompany=$(this).parent().closest(".groupCompany").find(".itemCompany"); 
			var appendCompany=$(this).parent().closest(".groupCompany").find(".appendCompany"); 
			itemCompany.addClass("hidden"); 
			appendCompany.removeClass("hidden"); 
			return false; 
		}); 
		$(".groupCompany").on("click",".btnCompanyDel",function() {
			$.ajax({
				url: "'.route("company.channel.delete",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					if(result.success==true){
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload();
					}else{
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						});
					}
				},
				error: function(result) {
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Không thể xóa, vui lòng thử lại! ", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					});
				}
			}); 
			return false; 
		}); 
		$(".groupCompany").on("click",".btnCancleCompany",function() {
			var itemCompany=$(this).parent().closest(".groupCompany").find(".itemCompany"); 
			var appendCompany=$(this).parent().closest(".groupCompany").find(".appendCompany"); 
			itemCompany.removeClass("hidden"); 
			appendCompany.addClass("hidden"); 
			return false; 
		}); 
		$(".groupCompany").on("click",".btnSaveCompany",function() {
			var idCompany=$("#idCompany").val(); 
			var companyName=$("#companyName").val(); 
			var groupCompany=$(this).parent().closest(".groupCompany"); 
			groupCompany.css("position", "relative"); 
			groupCompany.append("<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
			var formData = new FormData();
			formData.append("idCompany", idCompany); 
			formData.append("companyName", companyName); 
			$.ajax({
				url: "'.route("company.save",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					//console.log(result); 
					if(result.success==true){
						groupCompany.find(".appendPreload").remove(); 
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload();
					}else{
						groupCompany.find(".appendPreload").remove(); 
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-danger",
							sticky: false,
							time: ""
						});
					}
				},
				error: function(result) {
					groupCompany.find(".appendPreload").remove(); 
					jQuery.gritter.add({
						title: "Thông báo!",
						text: "Không thể cập nhật, vui lòng thử lại! ", 
						class_name: "growl-danger",
						sticky: false,
						time: ""
					});
				}
			});
		}); 
		$(".phoneItemGroup").on("click",".delPhone",function() {
			var phoneId=$(this).attr("data-id"); 
			var phoneItemGroup=$(this).parent().closest(".emailItemGroup"); 
			var formData = new FormData();
			formData.append("phoneId", phoneId); 
			$.ajax({
				url: "'.route("channel.phone.delete",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					//console.log(result); 
					if(result.success==true){
						phoneItemGroup.find(".appendPreload").empty(); 
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload();
					}else{
						phoneItemGroup.find(".appendPreload").empty(); 
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
		$(".emailItemGroup").on("click",".delEmail",function() {
			var emailId=$(this).attr("data-id"); 
			var emailItemGroup=$(this).parent().closest(".emailItemGroup"); 
			var formData = new FormData();
			formData.append("emailId", emailId); 
			$.ajax({
				url: "'.route("channel.email.delete",$channel["domain"]->domain).'",
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
						emailItemGroup.find(".appendPreload").empty(); 
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload();
					}else{
						emailItemGroup.find(".appendPreload").empty(); 
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
		$(".addressItemGroup").on("click",".delAddress",function() {
			var addressId=$(this).attr("data-id"); 
			var addressItemGroup=$(this).parent().closest(".addressItemGroup"); 
			var formData = new FormData();
			formData.append("addressId", addressId); 
			$.ajax({
				url: "'.route("channel.address.delete",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					//console.log(result); 
					if(result.success==true){
						addressItemGroup.find(".appendPreload").empty(); 
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload();
					}else{
						addressItemGroup.find(".appendPreload").empty(); 
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
		$(".phoneItemGroup").on("click",".phoneChange",function() {
			var phoneAddress=$(this).attr("data-phone"); 
			var idPhone=$(this).attr("data-id"); 
			var phoneItem=$(this).parent().closest(".phoneItemGroup").find(".phoneItem"); 
			var changePhoneItem=$(this).parent().closest(".phoneItemGroup").find(".changePhoneItem"); 
			phoneItem.hide(); 
			changePhoneItem.show(); 
			changePhoneItem.append("<div class=\"form-group\">"
				+"<div class=\"input-group\">"
					+"<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-earphone\"></i></span>"
					+"<input type=\"phone\" name=\"channelPhone\" value=\""+phoneAddress+"\" class=\"form-control\" placeholder=\"Nhập số điện thoại...\" required />"
				+"</div>"
				+"<label class=\"error\" for=\"channelPhone\"></label></div>");
			changePhoneItem.append("<div class=\"form-group\"><button type=\"button\" class=\"btn btn-xs btn-primary btnSavePhone\" data-id=\""+idPhone+"\"><i class=\"fa fa-check\"></i> Lưu</button> <button type=\"button\" class=\"btn btn-xs btn-default btnCancelPhone\">Hủy</button> </div>");
			return false;
		});
		$(".phoneItemGroup").on("click",".btnCancelPhone",function() {
			var phoneItem=$(this).parent().closest(".phoneItemGroup").find(".phoneItem"); 
			var changePhoneItem=$(this).parent().closest(".phoneItemGroup").find(".changePhoneItem"); 
			phoneItem.show(); 
			changePhoneItem.empty(); 
		});
		$(".phoneItemGroup").on("click",".phoneAddNew",function() {
			var phoneItem=$(this).parent().closest(".phoneItemGroup").find(".phoneItem"); 
			var changePhoneItem=$(this).parent().closest(".phoneItemGroup").find(".changePhoneItem"); 
			phoneItem.hide(); 
			changePhoneItem.show(); 
			changePhoneItem.append("<div class=\"form-group\">"
				+"<div class=\"input-group\">"
					+"<span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-earphone\"></i></span>"
					+"<input type=\"phone\" name=\"channelPhone\" value=\"\" class=\"form-control\" placeholder=\"Nhập số điện thoại...\" required />"
				+"</div>"
				+"<label class=\"error\" for=\"channelPhone\"></label></div>");
			changePhoneItem.append("<div class=\"form-group\"><button type=\"button\" class=\"btn btn-xs btn-primary btnSavePhone\" data-id=\"\"><i class=\"fa fa-check\"></i> Lưu</button> <button type=\"button\" class=\"btn btn-xs btn-default btnCancelPhone\">Hủy</button> </div>");
			return false;
		}); 
		$(".phoneItemGroup").on("click",".btnSavePhone",function() {
			var idPhone=$(this).attr("data-id"); 
			var phone=$("input[name=channelPhone]").val(); 
			var phoneItemGroup=$(this).parent().closest(".phoneItemGroup"); 
			phoneItemGroup.css("position", "relative"); 
			phoneItemGroup.append("<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
			var formData = new FormData();
			formData.append("idPhone", idPhone); 
			formData.append("phone", phone); 
			$.ajax({
				url: "'.route("channel.phone.save",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					//console.log(result); 
					if(result.success==true){
						phoneItemGroup.find(".appendPreload").empty(); 
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload();
					}else{
						phoneItemGroup.find(".appendPreload").empty(); 
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
		}); 
		$(".emailItemGroup").on("click",".emailChange",function(){
			var emailAddress=$(this).attr("data-email"); 
			var idEmail=$(this).attr("data-id"); 
			var emailItem=$(this).parent().closest(".emailItemGroup").find(".emailItem"); 
			var changeEmailItem=$(this).parent().closest(".emailItemGroup").find(".changeEmailItem"); 
			emailItem.hide(); 
			changeEmailItem.show(); 
			changeEmailItem.append("<div class=\"form-group\">"
				+"<div class=\"input-group\">"
					+"<span class=\"input-group-addon\"><i class=\"fa fa-envelope-o\"></i></span>"
					+"<input type=\"email\" name=\"channelEmail\" value=\""+emailAddress+"\" class=\"form-control\" placeholder=\"Nhập địa chỉ email...\" required />"
				+"</div>"
				+"<label class=\"error\" for=\"channelEmail\"></label></div>");
			changeEmailItem.append("<div class=\"form-group\"><button type=\"button\" class=\"btn btn-xs btn-primary btnSaveEmail\" data-id=\""+idEmail+"\"><i class=\"fa fa-check\"></i> Lưu</button> <button type=\"button\" class=\"btn btn-xs btn-default btnCancelEmail\">Hủy</button> </div>");
			return false;
		});
		$(".emailItemGroup").on("click",".btnCancelEmail",function() {
			var emailItem=$(this).parent().closest(".emailItemGroup").find(".emailItem"); 
			var changeEmailItem=$(this).parent().closest(".emailItemGroup").find(".changeEmailItem"); 
			emailItem.show(); 
			changeEmailItem.empty(); 
		});
		$(".emailItemGroup").on("click",".emailAddNew",function() {
			var emailItem=$(this).parent().closest(".emailItemGroup").find(".emailItem"); 
			var changeEmailItem=$(this).parent().closest(".emailItemGroup").find(".changeEmailItem"); 
			emailItem.hide(); 
			changeEmailItem.show(); 
			changeEmailItem.append("<div class=\"form-group\">"
				+"<div class=\"input-group\">"
					+"<span class=\"input-group-addon\"><i class=\"fa fa-envelope-o\"></i></span>"
					+"<input type=\"email\" name=\"channelEmail\" value=\"\" class=\"form-control\" placeholder=\"Nhập địa chỉ email...\" required />"
				+"</div>"
				+"<label class=\"error\" for=\"channelEmail\"></label></div>");
			changeEmailItem.append("<div class=\"form-group\"><button type=\"button\" class=\"btn btn-xs btn-primary btnSaveEmail\" data-id=\"\"><i class=\"fa fa-check\"></i> Lưu</button> <button type=\"button\" class=\"btn btn-xs btn-default btnCancelEmail\">Hủy</button> </div>");
			return false;
		}); 
		$(".emailItemGroup").on("click",".btnSaveEmail",function() {
			var idEmail=$(this).attr("data-id"); 
			var email=$("input[name=channelEmail]").val(); 
			var emailItemGroup=$(this).parent().closest(".emailItemGroup"); 
			emailItemGroup.css("position", "relative"); 
			emailItemGroup.append("<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
			var formData = new FormData();
			formData.append("idEmail", idEmail); 
			formData.append("email", email); 
			$.ajax({
				url: "'.route("channel.email.save",$channel["domain"]->domain).'",
				type: "POST",
				cache: false,
				contentType: false,
				processData: false,
				dataType:"json",
				data:formData,
				headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
				success: function (result) {
					//console.log(result); 
					if(result.success==true){
						emailItemGroup.find(".appendPreload").empty(); 
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload();
					}else{
						emailItemGroup.find(".appendPreload").empty(); 
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
		}); 
		$(".addressItemGroup").on("click",".addressAddNew",function() {
			var addressItem=$(this).parent().closest(".addressItemGroup").find(".addressItem"); 
			var changeAddressItem=$(this).parent().closest(".addressItemGroup").find(".changeAddressItem"); 
			addressItem.hide(); 
			changeAddressItem.show(); 
			changeAddressItem.append("<div class=\"form-group\">"
				+"<div class=\"input-group\">"
					+"<span class=\"input-group-addon\"><i class=\"fa fa-map-marker\"></i></span>"
					+"<input type=\"text\" name=\"channelAddress\" value=\"\" class=\"form-control\" placeholder=\"Địa chỉ đường, số nhà công ty, cửa hàng...\" required />"
				+"</div>"
				+"<label class=\"error\" for=\"channelAddress\"></label></div>"); 
			changeAddressItem.append("<div class=\"addSelectRegion form-group\"></div><input type=\"hidden\" name=\"idRegion\" value=\"'.$regionDefaultId.'\"><input type=\"hidden\" name=\"regionIso\" value=\"'.$regionDefaultIso.'\">"); 
			changeAddressItem.append("<div class=\"addSelectSubRegion form-group\"></div><input type=\"hidden\" name=\"idSubRegion\" value=\"\">"); 
			changeAddressItem.append("<div class=\"addSelectDistrict form-group\"></div><input type=\"hidden\" name=\"idDistrict\" value=\"\">"); 
			changeAddressItem.append("<div class=\"addSelectWard form-group\"></div><input type=\"hidden\" name=\"idWard\" value=\"\">"); 
			changeAddressItem.append("<div class=\"form-group\"><button type=\"button\" class=\"btn btn-xs btn-primary btnSaveAddress\" data-id=\"\"><i class=\"fa fa-check\"></i> Lưu</button> <button type=\"button\" class=\"btn btn-xs btn-default btnCancelAddress\">Hủy</button> </div>"); 
			getRegions(); 
			return false;
		});
		$(".addressItemGroup").on("click","#changeAddress",function() {
			var addressId=$(this).attr("data-id"); 
			var address=$(this).attr("data-address"); 
			var region=$(this).attr("data-region"); 
			var regionIso=$(this).attr("data-region-iso"); 
			var subRegion=$(this).attr("data-subregion"); 
			var district=$(this).attr("data-district"); 
			var ward=$(this).attr("data-ward");
			var addressItem=$(this).parent().closest(".addressItemGroup").find(".addressItem"); 
			var changeAddressItem=$(this).parent().closest(".addressItemGroup").find(".changeAddressItem"); 
			addressItem.hide(); 
			changeAddressItem.show(); 
			changeAddressItem.append("<div class=\"form-group\">"
				+"<div class=\"input-group\">"
					+"<span class=\"input-group-addon\"><i class=\"fa fa-map-marker\"></i></span>"
					+"<input type=\"text\" name=\"channelAddress\" value=\""+address+"\" class=\"form-control\" placeholder=\"Địa chỉ đường, số nhà công ty, cửa hàng...\" required />"
				+"</div>"
				+"<label class=\"error\" for=\"channelAddress\"></label></div>"); 
			changeAddressItem.append("<div class=\"addSelectRegion form-group\"></div><input type=\"hidden\" name=\"idRegion\" value=\""+region+"\"><input type=\"hidden\" name=\"regionIso\" value=\""+regionIso+"\">"); 
			changeAddressItem.append("<div class=\"addSelectSubRegion form-group\"></div><input type=\"hidden\" name=\"idSubRegion\" value=\""+subRegion+"\">"); 
			changeAddressItem.append("<div class=\"addSelectDistrict form-group\"></div><input type=\"hidden\" name=\"idDistrict\" value=\""+district+"\">"); 
			changeAddressItem.append("<div class=\"addSelectWard form-group\"></div><input type=\"hidden\" name=\"idWard\" value=\""+ward+"\">"); 
			changeAddressItem.append("<div class=\"form-group\"><button type=\"button\" class=\"btn btn-xs btn-primary btnSaveAddress\" data-id=\""+addressId+"\"><i class=\"fa fa-check\"></i> Lưu</button> <button type=\"button\" class=\"btn btn-xs btn-default btnCancelAddress\">Hủy</button> </div>"); 
			getRegions(); 
			return false; 
		}); 
		$(".addressItemGroup").on("click",".btnCancelAddress",function() {
			var addressItem=$(this).parent().closest(".addressItemGroup").find(".addressItem"); 
			var changeAddressItem=$(this).parent().closest(".addressItemGroup").find(".changeAddressItem"); 
			addressItem.show(); 
			changeAddressItem.hide(); 
			changeAddressItem.empty(); 
		});
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
							if(item.id==$("input[name=idDistrict]").val()){
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
							if(item.id==$("input[name=idWard]").val()){
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
		$(".addressItemGroup").on("change",".selectRegion",function() {
			getSubregion($(this).val()); 
			getDistrict(0); 
			getWard(0); 
			$("input[name=regionIso]").val($(this).find("option:selected").attr("data-iso")); 
			$("input[name=idRegion]").val($(this).val()); 
			$("input[name=idSubRegion]").val(0); 
			$("input[name=idDistrict]").val(0); 
		});
		$(".addressItemGroup").on("change",".selectSubRegion",function() { 
			getDistrict($(this).val()); 
			getWard(0);
			$("input[name=idSubRegion]").val($(this).val()); 
			$("input[name=idDistrict]").val(0); 
		});
		$(".addressItemGroup").on("change",".selectDistrict",function() {
			getWard($(this).val()); 
			$("input[name=idDistrict]").val($(this).val()); 
			$("input[name=idWard]").val(0); 
		});
		$(".addressItemGroup").on("change",".selectWard",function() {
			$("input[name=idWard]").val($(this).val()); 
		});
		$(".addressItemGroup").on("click",".btnSaveAddress",function() {
			var idAddress=$(this).attr("data-id"); 
			var address=$("input[name=channelAddress]").val(); 
			var region=$("input[name=idRegion]").val(); 
			var subRegion=$("input[name=idSubRegion]").val(); 
			var district=$("input[name=idDistrict]").val(); 
			var ward=$("input[name=idWard]").val(); 
			var addressItemGroup=$(this).parent().closest(".addressItemGroup"); 
			addressItemGroup.css("position", "relative"); 
			addressItemGroup.append("<div class=\"appendPreload\"><div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div></div>"); 
			var formData = new FormData();
			formData.append("idAddress", idAddress); 
			formData.append("address", address); 
			formData.append("region", region); 
			formData.append("subRegion", subRegion); 
			formData.append("district", district); 
			formData.append("ward", ward); 
			$.ajax({
				url: "'.route("channel.address.save",$channel["domain"]->domain).'",
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
						addressItemGroup.find(".appendPreload").empty(); 
						jQuery.gritter.add({
							title: "Thông báo!",
							text: result.message, 
							class_name: "growl-success",
							sticky: false,
							time: ""
						});
						location.reload();
					}else{
						addressItemGroup.find(".appendPreload").empty(); 
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
		}); 
	', $dependencies);
?>
@endif
