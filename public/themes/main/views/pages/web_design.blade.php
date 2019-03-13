<?
	$channel['theme']->setTitle('Thiết kế website');
	$channel['theme']->setKeywords('Thiết kế web, thiết kế website doanh nghiệp, thiết kế website theo yêu cầu, web design ');
	$channel['theme']->setDescription('Thiết kế website chuyên nghiệp theo yêu cầu của khách hàng! '); 
	$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap-website.jpg'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
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
   itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li> 
			<li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('pages.web.design',$channel['domainPrimary'])}}"><span itemprop="name">Thiết kế website</span></a></li> 
		</ol> 
		<div class='form-group'><a href="http://web.cungcap.net" target="_blank"><img class="img-responsive" src="{{'http://'.$channel["domainPrimary"].Theme::asset()->url('img/banner-view-theme.jpg')}}"></a></div> 
		<div class="row-pad-5 pricingGroup">
			<div class="col-xs-12 col-sm-6 col-md-4 appendpricing">
				<div class="list-group-item btn pricingPackge  active ">
					<div class="text-center">
						<h3 class="">Gói thiết kế cơ bản</h3>
					</div>
					<div class="text-center">
						<h1><strong>10.000.000 <sup>đ</sup></strong></h1>
					</div>
					<div class="price-features">
						<ul class="list-group">
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Mẫu website có bản quyền</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Hosting chất lượng cao 20GB</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tên miền </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế 1 banner </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế danh mục menu </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế 5 trang nội dung</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tính năng bán hàng</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> 20 bài viết/ sản phẩm đầu tiên </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tối ưu SEO</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tối ưu tốc độ</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tự động backup dữ liệu</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Bảo hành 1 năm</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Đào tạo hướng dẫn sử dụng</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-danger"></i> Chứng chỉ bảo mật ssl </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-danger"></i> Đăng ký website bộ công thương </li>
						</ul>
					</div>
					<div class="rdio rdio-primary">
						<input type="radio" name="channelPackge" value="18" id="radioPrimary18" checked="checked">
						<label for="radioPrimary18">Chọn</label>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 appendpricing">
				<div class="list-group-item btn pricingPackge ">
					<div class="text-center">
						<h3 class="">Gói thiết kế nâng cao</h3>
					</div>
					<div class="text-center">
						<h1><strong>20.000.000 <sup>đ</sup></strong></h1>
					</div>
					<div class="price-features">
						<ul class="list-group">
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Mẫu website có bản quyền</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Hosting chất lượng cao 20GB</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tên miền </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế 3 banner </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế danh mục menu </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế 7 trang nội dung</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tính năng bán hàng</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> 30 bài viết/ sản phẩm đầu tiên </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tối ưu SEO</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tối ưu tốc độ</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tự động backup dữ liệu</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Bảo hành 1 năm</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Đào tạo hướng dẫn sử dụng</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Chứng chỉ bảo mật ssl </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-danger"></i> Đăng ký website bộ công thương </li>
						</ul>
					</div>
					<div class="rdio rdio-primary">
						<input type="radio" name="channelPackge" value="19" id="radioPrimary19">
						<label for="radioPrimary19">Chọn</label>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 appendpricing">
				<div class="list-group-item btn pricingPackge ">
					<div class="text-center">
						<h3 class="">Gói thiết kế cao cấp</h3>
					</div>
					<div class="text-center">
						<h1><strong>50.000.000 <sup>đ</sup></strong></h1>
					</div>
					<div class="price-features">
						<ul class="list-group">
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Mẫu website có bản quyền</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Hosting chất lượng cao 20GB</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tên miền </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế 3 banner </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế danh mục menu </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Thiết kế 15 trang nội dung</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tính năng bán hàng</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> 40 bài viết/ sản phẩm đầu tiên </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tối ưu SEO</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tối ưu tốc độ</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Tự động backup dữ liệu</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Bảo hành 1 năm</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Đào tạo hướng dẫn sử dụng</li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Chứng chỉ bảo mật ssl </li>
							<li class="list-group-item list-group-item-info"><i class="fa fa-check text-success"></i> Đăng ký website bộ công thương </li>
						</ul>
					</div>
					<div class="rdio rdio-primary">
						<input type="radio" name="channelPackge" value="20" id="radioPrimary20">
						<label for="radioPrimary20">Chọn</label>
					</div>
				</div>
			</div>
		</div>
		<div class='form-group'><a href="tel:0903706288"><img class="img-responsive" src="{{'http://'.$channel["domainPrimary"].Theme::asset()->url('img/banner-contact.jpg')}}"></a></div> 
		<div class="panel panel-default">
			<div class="panel-body">
				<h2>Điểm nổi bật của thiết kế website chuyên nghiệp</h2>
				<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Có mã bản quyền phát hành từ nhà cung cấp.</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Được nâng cấp miễn phí theo giấy phép bản quyền đã mua</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Có khả năng hiển thị trên mọi thiết bị, máy tính, máy tính bảng, điện thoại có hỗ trợ trình duyệt</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Thiết kế ấn tượng, bắt mắt</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Linh hoạt trong việc tùy biến giao diện, giúp bạn dễ dàng quản lý hoạt động bán hàng, có thể thay đổi giao diện website để tạo sự kiện khuyến mãi theo các sự kiện xã hội cũng như sự kiện cá nhân</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Quản lý bán hàng và giao hàng tốt hơn</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i>Tương tác tốt với mạng xã hội, tăng thêm cơ hội tiếp cận khách hàng</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Tương tác tốt với người truy cập qua phần mềm chat, hỗ trợ trực tuyến, gọi nhanh</li>
				</ul>
				<h2>Vì sao bạn chọn thiết kế website</h2>
				<ul class="list-group list-group-flush">
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Bạn cần 1 website bắt mắt với thiết kế ấn tượng</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Bạn muốn khách truy cập tin cậy năng lực bán hàng và chăm sóc dịch vụ của bạn</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Bạn muốn duy trì website ổn định lâu dài trong nhiều năm</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Bạn cần mở rộng hoạt động của website trong tương lai</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Sẽ dễ dàng hơn rất nhiều nếu bạn PR cho một sản phẩm tốt</li>
				<li class="list-group-item"><i class="fa fa-check text-success"></i> Tuổi đời càng tăng lên, website càng tăng giá trị. Bởi website là thương hiệu, là tích lũy thông tin marketting từ khi khởi đầu đến hiện tại, đã và đang rất nhiều khách hàng biết tới website của bạn nên khi bạn ngưng hoạt động website đồng nghĩa với việc bạn bỏ đi một số lượng lớn khách hàng.</li>

				<li class="list-group-item"><i class="fa fa-check text-success"></i> Sai lầm thường mắc phải khi ngay từ đầu nếu bạn không có sự quan tâm đúng mức vào thương hiệu mà bạn đặt hết tâm huyết để phát triển và xây dựng nó. Sự ổn định và khả năng tồn tại lâu dài của 1 website quyết định rằng bạn đầu tư hiệu quả hay thất bại.</li>
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
		}); 
	', $dependencies);
?>