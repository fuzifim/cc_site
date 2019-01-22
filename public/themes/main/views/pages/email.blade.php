<?
	$channel['theme']->setTitle('Cung Cấp Mail Server');
	$channel['theme']->setKeywords('Cung cấp email theo tên miền, email server, email hosting, email tên miền ');
	$channel['theme']->setDescription('Giải pháp Email chuyên nghiệp theo tên miền riêng cho doanh nghiệp của bạn '); 
	$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap-email.jpg')); 
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
			$getService=\App\Model\Services::find(11); 
			
		?>
		@foreach($getService->attributeAll as $attribute)
			<?
				$attributeJson=json_decode($attribute->attribute_value); 
				if(Session::has('emailPackge') && Session::get('emailPackge')==$attribute->id){
					$active='active'; 
					$checked='checked'; 
				}else if(!Session::has('emailPackge') && $attribute->id==41){
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
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> {{$attributeJson->account}} <strong> Email</strong></li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>{{$attributeJson->ssd}}</strong> GB SSD</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>Đã bao gồm</strong> VAT</li>
						</ul>
					</div>
					<div class="rdio rdio-primary">
						<input type="radio" name="emailPackge" value="{{$attribute->id}}" id="radioPrimary{{$attribute->id}}" {{$checked}}>
						<label for="radioPrimary{{$attribute->id}}">Chọn</label>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<div class='form-group'><a href="tel:0903706288"><img class="img-responsive" src="{{'//'.$channel["domainPrimary"].Theme::asset()->url('img/banner-contact.jpg')}}"></a></div> 
		<div class="">
			<div class="">
				<h2>Mail Server là gì? </h2>
				<p>“Email Server là một dịch vụ email được cung cấp trên một máy chủ chuyên dụng với các tính năng bảo mật cao. Email Server được thiết lập trên Cloud Server cùng ổ đĩa full SSD. Chúng tôi tích hợp nhiều tính năng đặc biệt để đảm bảo an toàn và tiện ích cho hệ thống email của bạn.”</p>
				<h2>Điểm nổi bật của Email Server</h2>
				<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Cloud Server</strong> Đảm bảo 100% không mất kết nối. Môi trường máy chủ ảo nhanh chóng và dễ dàng sử dụng sẽ đáp ứng nhu cầu của bạn một cách tốt nhất.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>SSD Enterprise</strong> Email của bạn sẽ được lưu trữ trong ổ cứng SSD Enterprise với tốc độ nhanh hơn 40 lần so với ổ cứng thông thường.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>CLOUDMARK</strong> Cloudmark Authority là phần mềm chống spam mail và virus được tin dùng, phổ biến trên toàn thế giới hiện nay.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>E-mail Status</strong> Bạn có thể tự kiểm tra tình trạng của email trong ControlPanel. Hệ thống sẽ có thông báo nếu email của bạn không được gửi đi.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Control Panel</strong> Bạn có thể quản lý số lượng lớn tài khoản email trong ControlPanel. ControlPanel được thiết kế thân thiện và phù hợp cho tất cả người dùng.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Webmail, SSL</strong> Hệ thống máy chủ email của chúng tôi hỗ trợ Webmail, IMAP, SMTP và chứng thư SSL.</li>
				</ul>
				<h2>Tại sao bạn nên dùng Email Server</h2>
				<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Nâng cao uy tín, khẳng định thương hiệu trên internet cho cá nhân và doanh nghiệp của bạn</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Quản lý công việc của nhân viên hiệu quả, kiểm tra và điều chỉnh kịp thời khi cần thiết</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Email theo tên miền trên hosting là dịch vụ miễn phí kèm theo khi sử dụng hosting vì vậy sẽ không được cam kết đảm bảo hoạt động gửi/nhận mail tốt nhất</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Bạn có thể sử dụng email doanh nghiệp để gửi thư quảng cáo đến khách hàng của mình với số lượng được ghi rõ trong thông số kỹ thuật.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Luôn tuân thủ nghiêm ngặt theo quy định của các tổ chức chống SPAM quốc tế để đảm bảo tên miền cũng như từ khóa gửi mail không bị liệt kê vào backlist của các tổ chức đó.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Trong trường hợp có các email cố tình gửi spam, hệ thống sẽ tự động suspend email đó và thông báo cho bạn để kiểm tra, xử lý.</li>
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
			formData.append("emailPackge", $(this).find("input[name=emailPackge]:checked").val()); 
			formData.append("cartType", "emailAdd"); 
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