<?
	$channel['theme']->setTitle('Cung Cấp Hosting');
	$channel['theme']->setKeywords('Cung cấp email theo tên miền, email server, email hosting, email tên miền ');
	$channel['theme']->setDescription('Cung cấp hosting tốc độ cao sử dụng ổ cứng SSD siêu tốc '); 
	$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap-hosting.jpg'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
?>

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
			$getService=\App\Model\Services::find(9); 
			
		?>
		@foreach($getService->attributeAll as $attribute)
			<?
				$attributeJson=json_decode($attribute->attribute_value); 
				if(Session::has('hostingPackge') && Session::get('hostingPackge')==$attribute->id){
					$active='active'; 
					$checked='checked'; 
				}else if(!Session::has('hostingPackge') && $attribute->id==29){
					$active='active'; 
					$checked='checked'; 
				}else{
					$active=''; 
					$checked=''; 
				}
			?>
			<div class="col-xs-12 col-sm-6 col-md-4 appendpricing">
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
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> @if($attributeJson->cloud==0) Không giới hạn @else{{$attributeJson->cloud}} GB @endif <strong>Dung lượng SSD </strong></li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i>@if($attributeJson->banwidth==0) Băng thông không giới hạn @else {{$attributeJson->banwidth}} GB băng thông @endif</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>@if($attributeJson->website==0)Không giới hạn @else{{$attributeJson->website}}@endif</strong> Website</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>@if($attributeJson->mysql==0)Không giới hạn @else{{$attributeJson->mysql}}@endif</strong> Database MySQL</li>
							
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>Miễn phí chứng chỉ SSL</strong> </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> <strong>Đã bao gồm</strong> VAT</li>
						</ul>
					</div>
					<div class="rdio rdio-primary">
						<input type="radio" name="hostingPackge" value="{{$attribute->id}}" id="radioPrimary{{$attribute->id}}" {{$checked}}>
						<label for="radioPrimary{{$attribute->id}}">Chọn</label>
					</div>
				</div>
			</div>
			@endforeach
		</div>
		<div class='form-group'><a href="tel:0903706288"><img class="img-responsive" src="{{'//'.$channel["domainPrimary"].Theme::asset()->url('img/banner-contact.jpg')}}"></a></div> 
		<div class="">
			<div class="">
				<h2>Điểm nổi bật của Hosting</h2>
				<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>cPanel - Bảng điều khiển đa chức năng</strong> Rất nhiều chức năng hữu ích được tích hợp như File Manager, FTP, Access Log Analyzer giúp các thao tác trở nên đơn giản và dễ dàng.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Ổ cứng SSD siêu tốc</strong> sử dụng ổ cứng SSD siêu tốc với tốc độ nhanh hơn gấp 4 lần so với ổ HDD thông thường.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> <strong>Cài đặt Application dễ dàng</strong> Không cần những câu lệnh phức tạp, chỉ cần lựa chọn và cài đặt ứng dụng cần thiết dễ dàng và nhanh chóng trong cPanel.</li>
				<h2>Tại sao bạn nên dùng Hosting</h2>
				<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Hệ thống an toàn - tốc độ - ổn định, sử dụng 100% ổ cứng SSD cùng với hệ điều hành Cloud Linux cho chất lượng dịch vụ cao nhất.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Hỗ trợ 24/7/365 với đội ngũ kỹ thuật giàu kinh nghiệm, chuyên nghiệp, nhiệt tình và chu đáo</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Hỗ trợ dịch vụ từ A - Z với đội ngũ kỹ thuật giàu kinh nghiệm, nhiệt tình và chu đáo</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Khi đăng ký Hosting, bạn sẽ được hỗ trợ chuyển dữ liệu miễn phí từ nhà cung cấp khác về mà không cần phải lo lắng về vấn đề kỹ thuật và việc hoạt động của website</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Bạn không cần phải hiểu biết hay kiến thức liên quan đến vận hành máy chủ. Thay vào đó, chúng tôi cung cấp môi trường lý trưởng nhất để bạn có thể sử dụng và vận hành website của mình một cách ổn định - bảo mật - nhanh chóng</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Nếu bạn đang chạy nhiều website trên một gói hay website có lượng truy cập lớn, hãy chọn gói Hosting lớn hơn để phù hợp với mục đích của bạn. Để tốt cho việc kinh doanh, chúng tôi khuyên dùng bạn nên sử dụng Hosting gói B trở lên để đảm bảo cho website được hoạt động tốt nhất</li>
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
			$("#preloaderInBox").css("display", "block"); 
			var formData = new FormData();
			formData.append("hostingPackge", $(this).find("input[name=hostingPackge]:checked").val()); 
			formData.append("cartType", "hostingAdd"); 
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