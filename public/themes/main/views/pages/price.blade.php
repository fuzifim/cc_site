<?
	$channel['theme']->setTitle('Bảng giá');
	$channel['theme']->setKeywords('Tạo kênh, tạo website, bảng giá tạo website');
	$channel['theme']->setDescription('Bảng giá tạo website trên Cung Cấp '); 
	$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap-website.jpg'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap-wizard', 'js/bootstrap-wizard.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
?>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
			<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"
   itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Trang chủ</span></a></li> 
			<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('pages.price',$channel['domainPrimary'])}}"><span itemprop="name">Bảng giá</span></a></li> 
		</ol> 
		{!!Theme::asset()->container('footer')->usePath()->add('bootstrap-wizard', 'js/bootstrap-wizard.min.js', array('core-script'))!!}
		{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
		<div class="form-group">
			<div id="" class="groupPackge" style="position:relative;">
				<div id="preloaderInBox" style="display:none;">
					<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
				</div>
				<form class="form" id="form1" method="post" action="{{route('channel.create.request',$channel['domainPrimary'])}}">
					<div class="row-pad-5 pricingGroup">
					<?
						$getService=\App\Model\Services::find(2); 
						
					?>
					@foreach($getService->attributeAll as $attribute)
						<?
							$attributeJson=json_decode($attribute->attribute_value); 
							if(Session::has('channelPackge') && Session::get('channelPackge')==$attribute->id){
								$active='active'; 
								$checked='checked'; 
							}else if(!Session::has('channelPackge') && $attribute->id==18){
								$active='active'; 
								$checked='checked'; 
							}else{
								$active=''; 
								$checked=''; 
							}
						?>
						<div class="col-xs-12 col-sm-6 col-md-3 appendpricing">
							<div class="list-group-item btn pricingPackge {{$active}}">
								<div class="text-center">
									<h3 class="">{{$attribute->name}}</h3>
								</div>
								<div class="text-center">
									<h1><strong>{{Site::price(($attribute->price_re_order+$attribute->price_order))}} <sup>đ</sup></strong></h1>
									/ tháng
								</div>
								<div class="price-features">
									<ul class="list-group">
										<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>Dung lượng: </strong> {{$attributeJson->limit_cloud}}MB</li>
										<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>{{Site::price($attributeJson->limit_post)}}</strong> Bài viết</li>
										<li class="list-group-item list-group-item-info">@if($attributeJson->domain=='ok')<i class="fa fa-check text-success"></i> .com/ .net/ .com.vn/ .vn @else <i class="fa fa-check text-danger"></i> tên miền .cungcap.net @endif</li>
									</ul>
								</div>
								<div class="rdio rdio-primary">
									<input type="radio" name="channelPackge" value="{{$attribute->id}}" id="radioPrimary{{$attribute->id}}" {{$checked}}>
									<label for="radioPrimary{{$attribute->id}}">Chọn</label>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</form>
			</div>
		</div><!-- panel -->
		<?
			$dependencies = array(); 
			$channel['theme']->asset()->writeScript('custom',' 
			function convertToSlug(title)
			{
			  //Đổi chữ hoa thành chữ thường
				slug = title.toLowerCase();
			 
				//Đổi ký tự có dấu thành không dấu
				slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, "a");
				slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, "e");
				slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, "i");
				slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, "o");
				slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, "u");
				slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, "y");
				slug = slug.replace(/đ/gi, "d");
				//Xóa các ký tự đặt biệt
				slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\"|\"|\:|\;|_/gi, "");
				//Đổi khoảng trắng thành ký tự gạch ngang
				slug = slug.replace(/ /gi, "");
				//Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
				//Phòng trường hợp người nhập vào quá nhiều ký tự trắng
				slug = slug.replace(/\-\-\-\-\-/gi, "-");
				slug = slug.replace(/\-\-\-\-/gi, "-");
				slug = slug.replace(/\-\-\-/gi, "-");
				slug = slug.replace(/\-\-/gi, "-");
				//Xóa các ký tự gạch ngang ở đầu và cuối
				slug = "@" + slug + "@";
				slug = slug.replace(/\@\-|\-\@|\@/gi, "");
				//In slug ra textbox có id “slug”
				
			  return slug;
			}
			$("#channelDomain").keyup(function () {
				if($(this).val().length>=3){
					var appendDomain="'.$channel['domainPrimary'].'";
					$("#changeDomain").html("http://"+convertToSlug($(this).val())+"."+appendDomain);
				}else{
					$("#changeDomain").empty(); 
				}
			});
			$(".appendpricing").on("click",".pricingPackge",function() {
				$(".appendpricing .pricingPackge").not(this).removeClass("active"); 
				$(".appendpricing .pricingPackge").not(this).find("input").prop("checked",false);
				$(this).addClass("active");
				$(this).find("input").prop("checked",true); 
				//console.log($(this).find("input[name=channelPackge]:checked").val()); 
				var formData = new FormData();
				formData.append("selectPackge", $(this).find("input[name=channelPackge]:checked").val()); 
				$(".groupPackge #preloaderInBox").css("display", "block"); 
				$.ajax({
					url: "'.route("channel.select.packge",$channel["domainPrimary"]).'",
					type: "POST",
					cache: false,
					contentType: false,
					processData: false,
					dataType:"json",
					data:formData,
					headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
					success: function (result) {
						if(result.success==true){
							$(".groupPackge #preloaderInBox").css("display", "none"); 
							jQuery.gritter.add({
								title: "Thông báo!",
								text: result.message, 
								class_name: "growl-success",
								sticky: false,
								time: ""
							});
							window.location.href = "'.route("channel.home",$channel["domainPrimary"]).'";
						}else{
							$(".groupPackge #preloaderInBox").css("display", "none");  
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
			$(".appendPackge").on("click",".packgeCheck",function() {
				$(".appendPackge .packgeCheck").not(this).removeClass("active"); 
				$(".appendPackge .packgeCheck").not(this).find("input").prop("checked",false);
				$(this).addClass("active");
				$(this).find("input").prop("checked",true);
			}); 
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
			
			jQuery(document).ready(function(){
				"use strict";
				// With Form Validation Wizard
				var $validator = jQuery("#form1").validate({
					highlight: function(element) {
					  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
					},
					success: function(element) {
					  jQuery(element).closest(".form-group").removeClass("has-error");
					}
				});
				jQuery("#validationWizard").bootstrapWizard({
					withVisible:      false,
					tabClass:         "stepwizard",
					onNext: function(tab, navigation, index) {
						var $valid = jQuery("#form1").valid();
						if(!$valid) {
							$validator.focusInvalid();
							return false;
						}else{
							var move = false; 
							if(index==1){
								$(".previous").removeClass("hidden"); 
								$(".textNext").text("Tiếp tục"); 
								var channelPackge=$("input[name=channelPackge]:checked").val(); 
								var formData = new FormData();
								formData.append("channelPackge", channelPackge); 
								$("#validationWizard #preloaderInBox").css("display", "block"); 
								$.ajax({
									url: "'.route("channel.add.step1",$channel["domainPrimary"]).'",
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
											move= true; 
											$("#validationWizard #preloaderInBox").css("display", "none"); 
											$("#validationWizard").bootstrapWizard("show",index); 
											$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow");
										}else{
											if(result.type=="login"){
												window.location.href = "'.route("channel.login",$channel["domainPrimary"]).'?urlRedirect='.urlencode(route('channel.add',$channel["domainPrimary"])).'";
											}else{
												$("#validationWizard #preloaderInBox").css("display", "none");  
												move=false; 
												jQuery.gritter.add({
													title: "Thông báo!",
													text: result.message, 
													class_name: "growl-danger",
													sticky: false,
													time: ""
												});
											}
										}
									}
								});
							}else if(index==2){
								$(".textNext").text("Tiếp tục"); 
								
								var channelDomain=$("input[name=channelDomain]").val(); 
								var formData = new FormData();
								formData.append("channelDomain", channelDomain); 
								$("#validationWizard #preloaderInBox").css("display", "block"); 
								$.ajax({
									url: "'.route("channel.add.step2",$channel["domainPrimary"]).'",
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
											move= true; 
											$("#validationWizard #preloaderInBox").css("display", "none"); 
											$("#validationWizard").bootstrapWizard("show",index); 
											$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow");
										}else{
											$("#validationWizard #preloaderInBox").css("display", "none");  
											move=false; 
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
							}else if(index==3){
								$(".textNext").text("Tiếp tục"); 
								
								var channelName=$("input[name=channelName]").val(); 
								var channelDescription=$("input[name=channelDescription]").val(); 
								var formData = new FormData();
								formData.append("channelName", channelName); 
								formData.append("channelDescription", channelDescription); 
								$("#validationWizard #preloaderInBox").css("display", "block"); 
								$.ajax({
									url: "'.route("channel.add.step3",$channel["domainPrimary"]).'",
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
											move= true; 
											$("#validationWizard #preloaderInBox").css("display", "none"); 
											$("#validationWizard").bootstrapWizard("show",index); 
											$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow"); 
										}else{
											$("#validationWizard #preloaderInBox").css("display", "none");  
											move=false; 
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
							}else if(index==4){
								$(".textNext").text("Tiếp tục"); 
								
								var channelAddress=$("input[name=channelAddress]").val(); 
								var channelFields=$("select[name=channelFields]").select2("val"); 
								var channelRegion=$("select[name=channelRegion]").select2("val");
								var channelSubRegion=$("select[name=channelSubRegion]").select2("val"); 
								var channelDistrict=$("select[name=channelDistrict]").select2("val");
								var channelWard=$("select[name=channelWard]").select2("val");
								var formData = new FormData();
								formData.append("channelAddress", channelAddress); 
								formData.append("channelFields", channelFields); 
								formData.append("channelRegion", channelRegion); 
								formData.append("channelSubRegion", channelSubRegion); 
								formData.append("channelDistrict", channelDistrict); 
								formData.append("channelWard", channelWard); 
								$("#validationWizard #preloaderInBox").css("display", "block"); 
								$.ajax({
									url: "'.route("channel.add.step4",$channel["domainPrimary"]).'",
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
											if(result.type=="free"){
												$("#validationWizard #preloaderInBox").css("display", "none"); 
												$("#validationWizard").bootstrapWizard("show",index); 
												$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow"); 
												move= true; 
											}else{
												$("#validationWizard #preloaderInBox").css("display", "none"); 
												$("#validationWizard").bootstrapWizard("show",index); 
												$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow"); 
											}
										}else{
											$("#validationWizard #preloaderInBox").css("display", "none");  
											move=false; 
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
							}else if(index==5){
								$(".textNext").text("Tiếp tục"); 
								
								var channelPhone=$("input[name=channelPhone]").val(); 
								var channelEmail=$("input[name=channelEmail]").val(); 
								var channelPassword=$("input[name=password]").val(); 
								var channelRePassword=$("input[name=password_confirmation]").val(); 
								var formData = new FormData();
								formData.append("channelPhone", channelPhone); 
								formData.append("channelEmail", channelEmail); 
								formData.append("password", channelPassword); 
								formData.append("password_confirmation", channelRePassword); 
								$("#validationWizard #preloaderInBox").css("display", "block"); 
								$.ajax({
									url: "'.route("channel.add.step5",$channel["domainPrimary"]).'",
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
											if(result.type=="free"){
												$("#validationWizard #preloaderInBox").css("display", "none"); 
												$("#validationWizard").bootstrapWizard("show",index); 
												$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow"); 
												window.location.href = "'.route("channel.me",$channel["domainPrimary"]).'";
											}else{
												$("#validationWizard #preloaderInBox").css("display", "none"); 
												$("#validationWizard").bootstrapWizard("show",index); 
												$("html, body").animate({scrollTop: $("#validationWizard").offset().top}, "slow"); 
												window.location.href = "'.route("pay.cart",$channel["domainPrimary"]).'";
											}
										}else{
											$("#validationWizard #preloaderInBox").css("display", "none");  
											move=false; 
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
							}
							return move;
						}
					}
				});
			});
			', $dependencies);
		?>
		<div class="form-group">
			<div class="channelAbout">Thanh toán trực tuyến và theo tháng </div>
			
		</div>
		<div class="row row-pad-5">
			<div class="col-md-12">
				<h3 class="text-center">Tính năng nổi bật</h3>
				<div class="row-pad-5">
					<div class="col-md-12"> 
					<li class="list-group-item"><i class="glyphicon glyphicon-time text-success"></i> Kích hoạt và sử dụng được ngay</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-globe text-success"></i> Miễn phí tên miền dạng .cungcap.net và có thể sử dụng tên miền riêng dạng .com/ .net/ .com.vn/ .vn...</li> 
						<li class="list-group-item"><i class="glyphicon glyphicon-tint text-success"></i> Không tốn phí thiết kế</li> 
						<li class="list-group-item"><i class="glyphicon glyphicon-hdd text-success"></i> Không tốn phí hosting</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-download-alt text-success"></i> Backup, Bảo mật hàng ngày</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-heart text-success"></i> Hỗ trợ 24/7</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-search text-success"></i> Giao diện chuẩn SEO, Responsive</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-phone text-success"></i> Hỗ trợ trên Điện thoại, máy tính bảng, Desktop</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-user text-success"></i> Không giới hạn tài khoản quản lý</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-cog text-success"></i> Cài đặt thay đổi màu sắc, logo, ảnh đại diện, thông tin công ty</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-list text-success"></i> Quản lý danh mục, Tạo Menu</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-edit text-success"></i> Đăng bài, quản lý, sửa xóa</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-tags text-success"></i> Tạo, quản lý và dễ dàng lấy ý tưởng từ khóa cho bài viết</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-shopping-cart text-success"></i> Đăng sản phẩm, giá bán, đặt hàng</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-envelope text-success"></i> Nhận thông báo đơn hàng qua Email</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-thumbs-up text-success"></i> Thích, bình luận, chia sẻ thông tin lên các trang mạng Xã Hội</li>
						<!--<li class="list-group-item"><i class="glyphicon glyphicon-share text-success"></i> Tự động đăng lên Cung Cấp, trên các mạng xã hội Facebook, Google, Twitter, Linkedin, Wordpress, Blogspot...</li>-->
						<li class="list-group-item"><i class="glyphicon glyphicon-stats text-success"></i> Thống kê lượt xem</li>
					</div>
				</div>
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
	', $dependencies);
?>