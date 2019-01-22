<?
	$channel['theme']->setTitle('Tạo kênh Cung cấp');
	$channel['theme']->setKeywords('Tạo kênh, tạo website');
	$channel['theme']->setDescription('Đăng ký tạo kênh cung cấp thông tin sản phẩm, dịch vụ trực tuyến cho doanh nghiệp của bạn. '); 
	$channel['theme']->setImage('http://'.config('app.url').'/themes/control/assets/img/cungcap.jpg');
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
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('title') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		@if($channel['info']->channel_parent_id==0)
				
				<div class="row-pad-5 pricingGroup">
					<?
						$getService=\App\Model\Services::find(2); 
						
					?>
					@foreach($getService->attributeAll as $attribute)
					<?
						$attributeJson=json_decode($attribute->attribute_value); 
					?>
					<div class="col-xs-12 col-sm-6 col-md-4">
						<div class="panel panel-success pricingPackge">
							<div class="panel-heading text-center">
								<h3 class="panel-title">{{$attribute->name}}</h3>
							</div>
							<div class="text-center">
								<h1><strong>{{Site::price($attribute->price_re_order+$attribute->price_order)}} <sup>đ</sup></strong></h1>
							</div>
							<div class="price-features">
								<ul class="list-group">
									<li class="list-group-item list-group-item-success"><i class="fa fa-check text-success"></i> <strong>Phí duy trì: </strong> {{Site::price($attribute->price_re_order)}}<sup>đ</sup>/ năm</li>
									<li class="list-group-item list-group-item-success"><i class="fa fa-check text-success"></i> <strong>Dung lượng: </strong> {{$attributeJson->limit_cloud}}MB</li>
									<li class="list-group-item list-group-item-success"><i class="fa fa-check text-success"></i> <strong>{{Site::price($attributeJson->limit_post)}}</strong> Bài viết</li>
								</ul>
							</div>
							<div class="panel-footer text-center">
								<button type="button" class="btn btn-primary btn-block btnSelectPackge" data-id="{{$attribute->id}}"><i class="glyphicon glyphicon-ok"></i> Chọn</button>
							</div>
						</div>
					</div>
					@endforeach
				</div>
				<div class="form-group">
					<h3 class="text-center">Các tính năng chính</h3>
				</div>
				<div class="row-pad-5">
					<div class="col-md-12">
						<li class="list-group-item"><i class="glyphicon glyphicon-globe text-success"></i> Có 1 website và có thể sử dụng tên miền riêng dạng .com/ .net/ .com.vn/ .vn...</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-download-alt text-success"></i> Backup hàng ngày</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-heart text-success"></i> Hỗ trợ 24/7</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-search text-success"></i> Giao diện chuẩn SEO, Responsive</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-phone text-success"></i> Hỗ trợ trên Điện thoại, máy tính bảng, Desktop</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-user text-success"></i> Không giới hạn tài khoản quản lý</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-cog text-success"></i> Cài đặt thay đổi màu sắc, logo, ảnh đại diện, thông tin công ty</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-list text-success"></i> Quản lý danh mục, Tạo Menu</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-edit text-success"></i> Đăng bài, quản lý, sửa xóa</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-tags text-success"></i> Tạo, quản lý và dễ dàng lấy ý tưởng từ khóa cho bài viết</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-shopping-cart text-success"></i> Đăng sản phẩm, giá bán, đặt hàng</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-thumbs-up text-success"></i> Thích, bình luận, chia sẻ thông tin lên các trang mạng Xã Hội</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-share text-success"></i> Được quảng cáo thông tin lên Cung Cấp. Net và trên các fanpage</li>
						<li class="list-group-item"><i class="glyphicon glyphicon-stats text-success"></i> Thống kê lượt xem</li>
					</div>
				</div>
				<?
				$dependencies = array(); 
				$channel['theme']->asset()->writeScript('custom',' 
				$(".pricingPackge").on("click",".btnSelectPackge",function() {
					$(".pricingGroup").append("<div id=\"preloaderInBox\"><div id=\"status\"><i class=\"fa fa-spinner fa-spin\"></i></div></div>"); 
					var formData = new FormData(); 
					formData.append("packgeSelected", $(this).attr("data-id")); 
					$.ajax({
						url: "'.route("channel.select.packge.request",$channel["domainPrimary"]).'",
						type: "POST",
						cache: false,
						contentType: false,
						processData: false,
						dataType:"json",
						data:formData,
						headers: {"X-CSRF-TOKEN": $("meta[name=_token]").attr("content")},
						success: function (result) {
							//console.log(result); 
							$(".pricingGroup #preloaderInBox").remove(); 
							window.location.href = "'.route("channel.add.info",$channel["domainPrimary"]).'";
						}
					});
				}); 
				', $dependencies);
			?>
		@else
		@endif
	</div>

</div><!-- mainpanel -->
</section>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('onload',' 
		var docHeight = jQuery(document).height();
		if(docHeight > jQuery(".mainpanel").height())
		jQuery(".mainpanel").height(docHeight);
	', $dependencies);
?>