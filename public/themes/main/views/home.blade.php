<?
	$channel['theme']->setTitle('Cung Cấp');
	$channel['theme']->setKeywords('Cung cấp, cung cap, provide, provision, supply. ');
	$channel['theme']->setDescription('Cung cấp sản phẩm, dịch vụ kinh doanh đến mọi người ');
	$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap-website.jpg'));
?>
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap-wizard', 'js/bootstrap-wizard.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="container panelRegisterSite">
		<div class="row row-pad-5">
			<div class="col-md-6">
				<div class="formRegisterChannel mb10">
					<h1><strong>Tạo website</strong></h1>
					<p>Tạo website để cung cấp sản phẩm, dịch vụ kinh doanh đến mọi người. </p>
					<div id="validationWizard" class="basic-wizard" style="position:relative;">
						<div id="preloaderInBox" style="display:none;">
							<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
						</div>
						<ul class="stepwizard-row">
							<li class="stepwizard-step">
								<a class="btn btn-default btn-circle disabled" href="#vtab1" data-toggle="tab">1</a>
							</li>
							<li class="stepwizard-step">
								<a class="btn btn-default btn-circle disabled"href="#vtab2" data-toggle="tab">2</a>
							</li>
							<li class="stepwizard-step">
								<a class="btn btn-default btn-circle disabled"  href="#vtab3" data-toggle="tab">3</a>
							</li>
						</ul>
						<form class="form" id="form1" method="post" action="{{route('channel.create.request',$channel['domain']->domain)}}">
							<div class="tab-content">
								<div class="tab-pane" id="vtab1">
									<?
										if(Session::has('channelDomain')){
											$channelDomain=Session::get('channelDomain');
										}
									?>
									<div class="form-group">
										<div class="input-group">
										  <input type="text" name="channelDomain" id="channelDomain" class="form-control" value="@if(!empty($channelDomain)){!!$channelDomain!!}@endif" maxlength="30" placeholder="Nhập địa chỉ tên miền" required >
										  <span class="input-group-addon">.{!! $channel['domainPrimary'] !!}</span>
										</div>
										<code id="changeDomain">@if(!empty($channelDomain))http://{!!$channelDomain!!}.{!! $channel['domainPrimary'] !!} @endif</code>
										<label class="error" for="channelDomain"></label>
									</div>
									<?
										if(Session::has('channelInfo')){
											$channelInfo=Session::get('channelInfo');
										}
									?>
									<div class="form-group">
										<input type="text" id="channelName" name="channelName" value="@if(!empty($channelInfo['channelName'])){!!$channelInfo['channelName']!!}@endif" class="form-control" placeholder="Tên website Vd: Cung cấp đậu phộng..." required />
									</div>
									<div class="form-group">
										<input type="text" name="channelDescription" value="@if(!empty($channelInfo['channelDescription'])){!!$channelInfo['channelDescription']!!}@endif" class="form-control" placeholder="Mô tả website, cửa hàng..." required />
									</div>
								</div>
								<div class="tab-pane" id="vtab2">
									<?
										if(Session::has('channelRegion')){
											$channelRegion=Session::get('channelRegion');
										}
									?>
									<div class="form-group">
										<div class="addFields"></div>
									</div>
									<div class="row mb5">
										<div class="col-sm-12">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
												<input type="text" name="channelAddress" value="@if(!empty($channelRegion['channelAddress'])){!!$channelRegion['channelAddress']!!}@endif" class="form-control" placeholder="Địa chỉ đường, số nhà công ty, cửa hàng..." required />
											</div>
											<label class="error" for="channelAddress"></label>
										</div>
									</div>
									<div class="row mb5">
										<div class="col-sm-6">
											<input type="hidden" name="idRegion" value="@if(!empty($channelRegion['channelRegion'])){!!$channelRegion['channelRegion']!!}@else{{$channel['info']->channelJoinRegion->region->id}}@endif">
											<input type="hidden" name="regionIso" value="{{mb_strtolower($channel['info']->channelJoinRegion->region->iso)}}">
											<div class="addSelectRegion"></div>
											<div class="mb10"></div>
										</div>
										<div class="col-sm-6">
											<input type="hidden" name="idSubRegion" value="@if(!empty($channelRegion['channelSubRegion'])){!!$channelRegion['channelSubRegion']!!}@else{{$channel['info']->channelJoinSubRegion->subregion->id}}@endif">
											<div class="addSelectSubRegion"></div>
											<div class="mb10"></div>
										</div>
									</div>
									<div class="row mb5">
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
								</div>
								<div class="tab-pane" id="vtab3">
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
											<input placeholder="Tên của bạn" id="fullName" name="fullName" value="@if(!empty($sessionChannel['channelFullName'])){!!$sessionChannel['channelFullName']!!}@elseif(Auth::check()){{Auth::user()->name}}@endif" type="text" class="form-control" required>
										</div>
										<label class="error" for="fullName"></label>
									</div>
									<div class="row mb5">
										<div class="col-sm-6">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-phone"></i></span>
												<input type="phone" class="form-control" name="channelPhone" value="@if(!empty($sessionChannel['channelPhone'])){!!$sessionChannel['channelPhone']!!}@elseif(Auth::check()){{Auth::user()->phone}}@endif" placeholder="Số điện thoại..." required>
											</div>
											<label class="error" for="channelPhone"></label>
											<div class="mb10"></div>
										</div>
										<div class="col-sm-6">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
												<input type="email" class="form-control" name="channelEmail" value="@if(!empty($sessionChannel['channelEmail'])){!!$sessionChannel['channelEmail']!!}@elseif(Auth::check()){{Auth::user()->email}}@endif" placeholder="Địa chỉ email..." required>
											</div>
											<label class="error" for="channelEmail"></label>
											<div class="mb10"></div>
										</div>
										@if(!Auth::check())
										<div class="col-sm-6">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
													<input placeholder="Mật khẩu" id="password" name="password" type="password" class="form-control" required>
												</div>
												<label class="error" for="password"></label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
													<input placeholder="Nhập lại mật khẩu" id="password_confirmation" name="password_confirmation" type="password"  class="form-control" required>
												</div>
												<label class="error" for="password_confirmation"></label>
											</div>
										</div>
										@endif
									</div>
									<div class="form-group text-right">
										<input type="checkbox" class="filled-in" name="accept_term" id="accept-term"/>
										<label for="filled-in-box">
											Đồng ý <a href="http://cungcap.net/dieu-khoan-su-dung">Điều khoản của chúng tôi</a>
										</label>
									</div>
								</div>
							</div>
							<div class="text-right">
								<ul class="pager wizard">
									<li class="previous hidden"><a href="javascript:void(0)"><i class="fa fa-chevron-left"></i> Quay lại</a></li>
									<li class="next"><button type="button" class="btn btn-primary btn-next pull-right"><span class="textNext">Tiếp tục tạo website</span> <i class="fa fa-chevron-right"></i></button></li>
								</ul>
							</div>
						</form>
					</div>
				</div><!-- panel -->
			</div>
			<div class="col-md-6">
				<div class="channelPrice">Tạo website miễn phí</div>
				<ul class="list-group">
					<li class="list-group-item"><i class="glyphicon glyphicon-list-alt text-success"></i> 10 bài viết</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-cloud text-success"></i> 20MB dung lượng SSD</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-globe text-success"></i> Miễn phí tên miền dạng .{!! $channel['domainPrimary'] !!}</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-tint text-success"></i> Không tốn phí thiết kế</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-hdd text-success"></i> Không tốn phí hosting</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-search text-success"></i> Giao diện chuẩn SEO, Responsive</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-phone text-success"></i> Hỗ trợ trên mọi thiết bị</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-edit text-success"></i> Đăng bài, quản lý, sửa xóa</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-shopping-cart text-success"></i> Đăng sản phẩm, giá bán, đặt hàng</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-envelope text-success"></i> Nhận thông báo đơn hàng qua Email</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-thumbs-up text-success"></i> Thích, bình luận, chia sẻ</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-stats text-success"></i> Thống kê lượt xem</li>
					<li class="list-group-item"><i class="glyphicon glyphicon-check text-success"></i> Và còn nữa...</li>
				</ul>
			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<div class="panelOptimation mb5">
	<div class="container">
		<div class="row">
			<div class="col-sm-6">
			<h3>Quản lý dễ dàng</h3>

				<p>Không giới hạn tài khoản quản lý, dễ đăng bài, tạo chuyên mục menu, theo dõi thống kê lượt xem, thích, bình luận và nhận thông báo ngay vào email...</p>
			</div>
			<div class="col-sm-6">

				<h3>Tiện lợi</h3>
				<p>Sử dụng dễ dàng trên mọi thiết bị, điện thoại, máy tính bảng, desktop... truy cập và quản lý mọi lúc, mọi nơi.   </p>
			</div>
			<div class="col-sm-6">
				<h3>Tốc độ truy cập cao</h3>
				<p>Trung tâm dữ liệu tại nhiều quốc gia đảm bảo tốc độ truy cập cao ở mọi nơi, backup và bảo mật hàng ngày. </p>
			</div>
			<div class="col-sm-6">
				<h3>Đa quốc gia</h3>
				<p>Tiếp cận khách hàng ở khắp các khu vực và liên tục 24/24 thông qua các công cụ tìm kiếm và qua các trang mạng xã hội. </p>
			</div>
		</div>
	</div>
</div>

<div class="panelCountSite mb5">
	<div class="container">
		<h1 class="text-center">{{Site::price(count($getChannelAll))}} website</h1>
		<h4 class="text-center">{{Site::price(count($getUserAll))}} thành viên đăng ký</h4>
	</div>
</div>
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
			var appendDomain="cungcap.net";
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
	});
	function getPackge(){
		$.ajax({
			url: "'.route('channel.packge.json',$channel['domainPrimary']).'",
			type: "GET",
			dataType: "json",
			success: function (result) {
				$.each(JSON.parse(result.data), function(i, item) {
					if(item.id==26){
						$(".appendPackge").append("<div class=\"col-lg-4 col-md-4 col-sm-6 col-xs-12\">"
						+"<div class=\"form-group\">"
							+"<div class=\"list-group-item text-center active btn packgeCheck\">"
								+"<h4 class=\"list-group-item-heading\">"+item.name+"</h4>"
								+"<div class=\"list-group-item-text\">"
									+"<p>Phí Đăng ký: <strong>"+(parseInt(item.price_re_order)+parseInt(item.price_order)).toLocaleString()+"<sup>đ</sup></strong>/ năm</p>"
									+"<p>Phí Duy trì: <strong>"+parseInt(item.price_re_order).toLocaleString()+"<sup>đ</sup></strong>/ năm</p>"
									+"<p><i class=\"glyphicon glyphicon-cloud\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_cloud).toLocaleString()+" MB</p>"
									+"<p><i class=\"glyphicon glyphicon-check\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_post).toLocaleString()+" Bài viết</p>"
								+"</div>"
								+"<input type=\"radio\" class=\"hidden\" value=\""+item.id+"\" name=\"channelPackge\" checked>"
							+"</div>"
						+"</div>"
					+"</div>");
					}else{
						$(".appendPackge").append("<div class=\"col-lg-4 col-md-4 col-sm-6 col-xs-12\">"
						+"<div class=\"form-group\">"
							+"<div class=\"list-group-item text-center btn packgeCheck\">"
								+"<h4 class=\"list-group-item-heading\">"+item.name+"</h4>"
								+"<div class=\"list-group-item-text\">"
									+"<p>Phí Đăng ký: <strong>"+(parseInt(item.price_re_order)+parseInt(item.price_order)).toLocaleString()+"<sup>đ</sup></strong>/ năm</p>"
									+"<p>Phí Duy trì: <strong>"+parseInt(item.price_re_order).toLocaleString()+"<sup>đ</sup></strong>/ năm</p>"
									+"<p><i class=\"glyphicon glyphicon-cloud\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_cloud).toLocaleString()+" MB</p>"
									+"<p><i class=\"glyphicon glyphicon-check\"></i> "+parseInt($.parseJSON(item.attribute_value).limit_post).toLocaleString()+" Bài viết</p>"
								+"</div>"
								+"<input type=\"radio\" class=\"hidden\" value=\""+item.id+"\" name=\"channelPackge\">"
							+"</div>"
						+"</div>"
					+"</div>");
					}
				});
			}
		});
	}
	getPackge();
	$(".appendPackge").on("click",".packgeCheck",function() {
		$(".appendPackge .packgeCheck").not(this).removeClass("active");
		$(".appendPackge .packgeCheck").not(this).find("input").prop("checked",false);
		$(this).addClass("active");
		$(this).find("input").prop("checked",true);
	});
	getFields();
	getRegions();
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
	function getFields(){
		$(".addFields").append("<div class=\"loading\"><small><i class=\"fa fa-spinner fa-spin\"></i> đang tải lĩnh vực, vui lòng chờ...</small></div>");
		$.ajax({
			url: "'.route('channel.json.fields',$channel['domainPrimary']).'",
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
			url: "'.route("district.json.list.post",$channel["domainPrimary"]).'",
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
			url: "'.route("ward.json.list.post",$channel["domainPrimary"]).'",
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
						var channelDomain=$("input[name=channelDomain]").val();
						var channelName=$("input[name=channelName]").val();
						var channelDescription=$("input[name=channelDescription]").val();
						var formData = new FormData();
						formData.append("channelDomain", channelDomain);
						formData.append("channelName", channelName);
						formData.append("channelDescription", channelDescription);
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
					}else if(index==2){
						$(".textNext").text("Đăng ký");

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
					}else if(index==3){

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
<?
	$getService=\App\Model\Services::find(4);
	$listLtd='';
	foreach($getService->attributeAll as $serviceDomain){
	$listLtd.='
	+"<div class=\"col-lg-3 col-md-3 col-sm-6 col-xs-6\">"
		+"<div class=\"form-group text-left\">"
			+"<div class=\"ckbox ckbox-success\">"
				+"<input type=\"checkbox\" class=\"filled-in\" name=\"ltdDomain\" data-type=\"register\" value=\"'.$serviceDomain->attribute_type.'\" id=\"'.$serviceDomain->attribute_type.'\" checked=\"\" >"
				+"<label for=\"'.$serviceDomain->attribute_type.'\"> .'.$serviceDomain->attribute_type.'</label>"
			+"</div>"
		+"</div>"
	+"</div>"';
	}
	$dependencies = array();
	$channel['theme']->asset()->writeScript('loadLazy','
		$(function() {
			$(".lazy").lazy();
		});
		function convertToSlug(title)
			{
			  //Đổi chữ hoa thành chữ thường
				var slug = title.toLowerCase();

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
		getCheckDomain();
		function getCheckDomain(){
			$(".panelNewRegisterDomain .checkDomain").append("<div class=\"form-group\"><div class=\"input-group\">"
				+"<input type=\"text\" class=\"form-control\" name=\"domainNewRegister\" placeholder=\"Nhập tên miền cần kiểm tra\" required>"
				+"<span class=\"input-group-btn\">"
					+"<button class=\"btn btn-primary\" type=\"submit\" id=\"btnCheckDomain\"><span class=\"glyphicon glyphicon-retweet\"></span> Kiểm tra</button>"
				+"</span>"
			+"</div>"
			+"<label class=\"error\" for=\"domainNewRegister\"></label>"
			+"</div>"
			+"<div class=\"row\">"
				'.$listLtd.'
			+"</div>");
		}
		var $validator = jQuery("#formCheckDomain").validate({
			highlight: function(element) {
			  jQuery(element).closest(".form-group").removeClass("has-success").addClass("has-error");
			},
			success: function(element) {
			  jQuery(element).closest(".form-group").removeClass("has-error");
			}
		});
		$(".panelNewRegisterDomain").on("click","#btnCheckDomain",function() {
			$(".panelNewRegisterDomain input[name=ltdDomain]:checked").each(function () {
				$(".domainResult").empty();
				var ltdDomain=$(this).val();
				var Domain=convertToSlug($(".panelNewRegisterDomain input[name=domainNewRegister]").val());
				var formData = new FormData();
				formData.append("domain", Domain);
				formData.append("ltdDomain", ltdDomain);
				formData.append("domainType", $(this).attr("data-type"));
				formData.append("checkType", "status");
				$.ajax({
					url: "'.route("channel.domain.check",$channel["domain"]->domain).'",
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
							if(result.domainInfo.code==1){
								if(result.serviceAttribute.price_re_order==0){
									$(".domainResult").append("<li class=\"list-group-item list-group-item-info\">"
										+"<div class=\"pull-right\">"
											+"<div class=\"form-group\"><strong class=\"text-info\">Miễn phí</strong> <button type=\"button\" class=\"btn btn-xs btn-success selectDomain\" domain-data=\""+result.domain+"\" domain-type=\""+result.domainType+"\" data-check=\"\"><i class=\"glyphicon glyphicon-unchecked\"></i> chọn</button></div>"
										+"</div> "
										+"<strong>"+result.domainInfo.domainName+"</strong> <small class=\"hidden-xs\">(tên miền này chưa được đăng ký)</small>"
									+"</li>");
								}else{
									$(".domainResult").append("<li class=\"list-group-item list-group-item-success\">"
										+"<div class=\"pull-right\">"
											+"<div class=\"form-group\"><strong class=\"text-danger\">"+(parseInt(result.serviceAttribute.price_re_order)+parseInt(result.serviceAttribute.price_order)).toLocaleString()+"<sup>VND</sup></strong> <button type=\"button\" class=\"btn btn-xs btn-success selectDomain\" domain-data=\""+result.domain+"\" domain-type=\""+result.domainType+"\" data-check=\"\"><i class=\"glyphicon glyphicon-unchecked\"></i> chọn</button></div>"
										+"</div> "
										+"<strong>"+result.domainInfo.domainName+"</strong> <small class=\"hidden-xs\">(tên miền này chưa được đăng ký)</small>"
									+"</li>");
								}
							}else if(result.domainInfo.code==0){
								$(".domainResult").append("<li class=\"list-group-item list-group-item-warning disabled\"><button type=\"button\" class=\"btn btn-xs btn-default pull-right btnDetail\" data-domainName=\""+result.domainInfo.domainName+"\" data-registrar=\""+result.domainInfo.registrar+"\" data-nameServer=\""+result.domainInfo.nameServer+"\" data-creationDate=\""+result.domainInfo.creationDate+"\" data-expirationDate=\""+result.domainInfo.expirationDate+"\" data-registrantName=\""+result.domainInfo.registrantName+"\"><i class=\"glyphicon glyphicon-eye-open\"></i> Xem</button> <strong>"+result.domainInfo.domainName+"</strong> <small class=\"text-danger hidden-xs\">(tên miền này đã được đăng ký)</small> </li>");
							}else{
								$(".domainResult").append("<li class=\"list-group-item list-group-item-warning disabled\"><strong>"+result.domainInfo.domainName+"</strong> </li>");
							}
						}else{
							$(".domainResult").empty();
							$(".domainResult").append("<div class=\"alert alert-danger alert-dismissable\" id=\"alertError\"><a href=\"#\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\">&times;</a>"+result.message+"</div>");
						}
					}
				});
			});
			return false;
		});
		$(".panelNewRegisterDomain").on("click",".btnDetail",function() {
			var domainName=$(this).attr("data-domainName");
			var dateCreated=$(this).attr("data-creationDate");
			var dateExpiration=$(this).attr("data-expirationDate");
			var domainRegistar=$(this).attr("data-registrar");
			var nameServer=$(this).attr("data-nameServer");
			var registrantName=$(this).attr("data-registrantName");
			$("#modalViewDomain .modal-title").empty();
			$("#modalViewDomain .modal-body").empty();
			$("#modalViewDomain .modal-title").text(domainName);
			$("#modalViewDomain .modal-body").html(""
			+"<strong>Ngày đăng ký: </strong>"+dateCreated+"<br>"
			+"<strong>Ngày hết hạn: </strong>"+dateExpiration+"<br>"
			+"<strong>Chủ sở hữu: </strong>"+registrantName+"<br>"
			+"<strong>Name Server: </strong>"+nameServer+"<br>"
			+"<strong>Nhà đăng ký: </strong>"+domainRegistar+"<br>"
			+"");
			$("#modalViewDomain").modal("show");

		});
	', $dependencies);
?>