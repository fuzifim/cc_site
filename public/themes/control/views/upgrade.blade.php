<?
	$channel['theme']->setTitle('Nâng cấp');
	$channel['theme']->setKeywords('Nâng cấp website, nâng cấp gói');
	$channel['theme']->setDescription('Nâng cấp gói và dung lượng sử dụng website'); 
?>
{!!Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'))!!}
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
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
		<div class="row row-pad-5 pricingGroup">
		<?
			$getService=\App\Model\Services::find(2); 
			
		?>
		@foreach($getService->attributeAll as $attribute)
			<?
				$attributeJson=json_decode($attribute->attribute_value); 
			?>
			<div class="col-xs-12 col-sm-6 col-md-3 appendpricing">
				<div class="list-group-item btn @if($channel['totalSize']>=$attributeJson->limit_cloud || $channel['limitPosts']>=$attributeJson->limit_post) disabled @endif @if($channel['info']->service_attribute_id==$attribute->id) active @endif pricingPackge">
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
							<li class="list-group-item list-group-item-info">@if($attributeJson->ssl=='ok')<i class="fa fa-check text-success"></i>@else<i class="fa fa-check text-danger"></i>@endif Chứng chỉ bảo mật SSL</li>
						</ul>
					</div>
					@if($channel['info']->service_attribute_id==$attribute->id)
						<div class="text-center">
							<h5><strong>Đang sử dụng</strong></h5>
						</div>
					@elseif($channel['totalSize']>=$attributeJson->limit_cloud || $channel['limitPosts']>=$attributeJson->limit_post)
						<div class="text-center">
							<h5><strong>Không được chọn gói này</strong></h5>
						</div>
					@else
					<div class="rdio rdio-primary">
						<input type="radio" name="channelPackge" value="{{$attribute->id}}" id="radioPrimary{{$attribute->id}}" >
						<label for="radioPrimary{{$attribute->id}}">Chọn</label>
					</div>
					@endif
				</div>
			</div>
			@endforeach
		</div>
		<div class="text-right">
			<button type="button" class="btn btn-primary btnPayment"><i class="glyphicon glyphicon-ok"></i> Nâng cấp</button>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('custom','
		$(".contentpanel").on("click",".btnPayment",function() {
			var packgeId=$("input[name=channelPackge]:checked").val(); 
			var formData = new FormData();
			formData.append("cartType", "channelUpgrade"); 
			formData.append("packgeId", packgeId); 
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
						window.location.href = "'.route("pay.cart",$channel["domainPrimary"]).'";
					}else{
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
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
	$(".appendpricing").on("click",".pricingPackge",function() {
		$(".appendpricing .pricingPackge").not(this).removeClass("active"); 
		$(".appendpricing .pricingPackge").not(this).find("input").prop("checked",false);
		$(this).addClass("active");
		$(this).find("input").prop("checked",true);
	}); 
	', $dependencies);
?>