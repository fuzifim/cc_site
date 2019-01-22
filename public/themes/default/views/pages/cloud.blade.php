<?
	$channel['theme']->setTitle('Cung Cấp Cloud Server');
	$channel['theme']->setKeywords('Cung cấp cloud server, Cloud ');
	$channel['theme']->setDescription('Trung tâm dữ liệu đa quốc gia và sử dụng ổ cứng SSD siêu tốc'); 
	$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap-cloud.jpg')); 
?>
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span>{!! Theme::get('description') !!}</span>
	</div>
	<div class="contentpanel">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		<div class="row row-pad-5 pricingGroup">
		<?
			$getService=\App\Model\Services::find(10); 
			
		?>
		@foreach($getService->attributeAll as $attribute)
			<?
				$attributeJson=json_decode($attribute->attribute_value); 
				if(Session::has('cloudPackge') && Session::get('cloudPackge')==$attribute->id){
					$active='active'; 
					$checked='checked'; 
				}else if(!Session::has('cloudPackge') && $attribute->id==33){
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
						<h1><strong>{{Site::price(($attribute->price_re_order))}} <sup>đ</sup></strong></h1>
						/ tháng
					</div>
					<div class="price-features">
						<ul class="list-group">
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> CPU {{$attributeJson->core}} <strong> Core</strong></li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> {{$attributeJson->ram}} RAM</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>{{$attributeJson->ssd}}</strong> GB SSD</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>Đã bao gồm</strong> VAT</li>
						</ul>
					</div>
					<div class="rdio rdio-primary">
						<input type="radio" name="cloudPackge" value="{{$attribute->id}}" id="radioPrimary{{$attribute->id}}" {{$checked}}>
						<label for="radioPrimary{{$attribute->id}}">Chọn</label>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<div class='form-group'><a href="tel:0903706288"><img class="img-responsive" src="{{'//'.$channel["domainPrimary"].Theme::asset()->url('img/banner-contact.jpg')}}"></a></div> 
		<div class="">
			<div class="">
				<h2>Điểm nổi bật của Cloud Server</h2>
				<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Cài đặt nhanh chóng</strong> Sever của Quý khách sẽ sẵn sàng hoạt động trong vòng 30 phút sau khi đăng ký</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Ổ cứng SSD siêu tốc</strong> sử dụng ổ cứng SSD siêu tốc với tốc độ nhanh hơn gấp 4 lần so với ổ HDD thông thường.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Open Stack</strong> Chúng tôi sử dụng nền tảng Cloud Openstack IaaS tiêu chuẩn với nhiều tiện ích và chức năng.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Scale Up/Scale down</strong> Quý khách có thể dễ dàng chủ động điều chỉnh thông số của server phù hợp với nhu cầu sử dụng.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Tự động Back-up</strong> Chức năng tự động back-up 1 lần / tuần giúp Quý khách không còn phải lo lắng việc mất dữ liệu khi sự cố xảy ra.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Trang quản trị dễ dàng</strong> Trang quản trị của chúng tôi được thiết kế đơn giản và thân thiện với người sử dụng.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Trung tâm dữ liệu đa quốc gia</strong> Bạn có thể tùy chọn trung tâm phù hợp tại Việt Nam, Singapore, Nhật Bản, Hoa Kỳ.</li>
				</ul>
				<h2>Cung cấp Cloud Server</h2>
				<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Với Shared Hosting, Website của bạn được đặt trên máy chủ cùng với khoảng vài trăm đến vài nghìn Website khác và chia sẻ chung tài nguyên từ máy chủ đó như bộ nhớ RAM và CPU. Còn VPS là một hệ thống riêng biệt được tạo ra bằng cách phân chia máy chủ vật lý với CPU riêng, dung lượng RAM riêng…và người dùng có toàn quyền quản trị cao nhất.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Bạn chỉ cần cung cấp các thông tin từ nhà cung cấp cũ, chúng tôi sẽ kiểm tra phân tích và chuyển dữ liệu cho bạn hoàn toàn miễn phí về.</li>
				</ul>
			</div>
		</div>
	</div>
</div>
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom',' 
		$(".appendpricing").on("click",".pricingPackge",function() {
			$(".appendpricing .pricingPackge").not(this).removeClass("active"); 
			$(".appendpricing .pricingPackge").not(this).find("input").prop("checked",false);
			$(this).addClass("active");
			$(this).find("input").prop("checked",true); 
			$(".groupPackge #preloaderInBox").css("display", "block"); 
			var formData = new FormData();
			formData.append("cloudPackge", $(this).find("input[name=cloudPackge]:checked").val()); 
			formData.append("cartType", "cloudAdd"); 
			$("#preloaderInBox").css("display", "block"); 
			$.ajax({
				url: "'.route("create.cart",$channel["domainPrimary"]).'",
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
		}); 
	', $dependencies);
?>