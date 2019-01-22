<?php  

	// Home

	Breadcrumbs::register('home', function($breadcrumbs)
	{
	    $breadcrumbs->push('Trang chủ', route('home'));
	});

	// Home > Register
	Breadcrumbs::register('register', function($breadcrumbs)
	{
	    $breadcrumbs->parent('home');
	    $breadcrumbs->push('Đăng ký', route('register'));
	});

    // Home > Đổi mật khẩu
    Breadcrumbs::register('requestchangepass', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Quên mật khẩu', route('front.user.emailpassword'));
    });

    //Home -> User change password
    Breadcrumbs::register('user.change.password', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Đổi mật khẩu', route('post.user.changepassword'));
    });
	// Home > Đăng nhập
	Breadcrumbs::register('login', function($breadcrumbs)
	{
	    $breadcrumbs->parent('home');
	    $breadcrumbs->push('Đăng nhập', route('front.user.emailpassword'));
	});
    //Home -> User change profile
    Breadcrumbs::register('user.profile', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Hồ sơ', route('user.profile'));
    });
    // Home > Post Ads
    Breadcrumbs::register('postads', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Đăng bài');
    });

    //Home -> manager ads
    Breadcrumbs::register('user.manage.ads', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Quản lý bài đăng', route('user.ads.manager'));
    });

    //Home -> manager theme setting
    Breadcrumbs::register('user.template.setting', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Cài đặt thông tin kênh', route('user.template.setting'));
    });

    //Home -> manager create website
    Breadcrumbs::register('front.channel.add', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Tạo kênh', route('front.channel.add'));
    });
    //Home category
    Breadcrumbs::register('category', function($breadcrumbs,$category)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Danh Mục');
        $breadcrumbs->push($category->name, route('front.categories.ads',array($category->slug,$category->id)));
    });
    //Home -> show ads
    Breadcrumbs::register('ads.detail', function($breadcrumbs,$cat,$ads)
    {
        $breadcrumbs->parent('home');
		//$breadcrumbs->push('Danh Mục');
		//dd($cat);
        //$breadcrumbs->push($cat->cat_name, route('front.categories.ads',array($cat->slug,$cat->SolrID)));
        $breadcrumbs->push($ads->ads_title);
    });

    Breadcrumbs::register('front.customer.details.category', function($breadcrumbs,$customies)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Công ty');
        $breadcrumbs->push($customies->customers_company);
    });
    Breadcrumbs::register('front.customer.list.category', function($breadcrumbs,$categories)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Category');
        $breadcrumbs->push($categories->name);
    });

    Breadcrumbs::register('front.regions.contry', function($breadcrumbs,$regions)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Quốc gia');
        $breadcrumbs->push($regions->country);
    });
    Breadcrumbs::register('front.location.contry', function($breadcrumbs,$regions)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Quốc gia');
        $breadcrumbs->push($regions->country);
    });
    Breadcrumbs::register('front.subregions.break', function($breadcrumbs,$subregions,$regions)
    {
        $breadcrumbs->parent('home');

        $breadcrumbs->push($regions->country);
        $breadcrumbs->push($subregions->subregions_name);
    });
    Breadcrumbs::register('front.location.subregions.break', function($breadcrumbs,$subregions,$regions)
    {
        $breadcrumbs->parent('home');

        $breadcrumbs->push($regions->country);
        $breadcrumbs->push($subregions->subregions_name);
    });
    Breadcrumbs::register('front.distric.break', function($breadcrumbs,$regions,$subregions,$districts)
    {
        $breadcrumbs->parent('home');

        $breadcrumbs->push($regions->country);
        $breadcrumbs->push($subregions->subregions_name);
        $breadcrumbs->push($districts->district_name);
    });
    Breadcrumbs::register('front.location.distric', function($breadcrumbs,$regions,$subregions,$districts)
    {
        $breadcrumbs->parent('home');

        $breadcrumbs->push($regions->country);
        $breadcrumbs->push($subregions->subregions_name);
        $breadcrumbs->push($districts->district_name);
    });
    Breadcrumbs::register('front.ward.break', function($breadcrumbs,$regions,$subregions,$districts,$wards)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push($regions->country);
        $breadcrumbs->push($subregions->subregions_name);
        $breadcrumbs->push($districts->district_name);
        $breadcrumbs->push($wards->ward_name);
    });
    Breadcrumbs::register('front.location.ward', function($breadcrumbs,$regions,$subregions,$districts,$wards)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push($regions->country);
        $breadcrumbs->push($subregions->subregions_name);
        $breadcrumbs->push($districts->district_name);
        $breadcrumbs->push($wards->ward_name);
    });
    //Home -> Message
    Breadcrumbs::register('user.message', function($breadcrumbs)
    {
        $breadcrumbs->parent('home');
        $breadcrumbs->push('Tin nhắn');
    });
?>