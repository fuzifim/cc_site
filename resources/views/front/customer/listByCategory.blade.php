@extends('inc.master')
@section('seo')
<?php $data_seo = array(
         'title' => $categories->name.config('app.title_seo'),
         'keywords' => '',
         'description' => '',
         'og_title' => '',
          'og_description' => '',
          'og_url' => '',
          'og_img' => asset('img/logo.png'),
           'current_url' =>Request::url()
        );
$seo = WebService::getSEO($data_seo);
?>
@include('partials.seo')
@endsection
@section('content')
<div id="wrapper">
		<div id="sidebar-wrapper" class="menu_home clear">
			{!!WebService::leftMenuRender()!!}
		</div><!--sidebar-wrapper-->
	<div id="page-content-wrapper" class="page_content_primary clear">
		<div class="container-fluid entry_container xyz">
			 <div class="row no-gutter mrb-5 country_option_cs">
				 <div class="col-lg-12">
					<div class="breadcrumbs_container pd-body clear">
						<ol itemtype="http://schema.org/BreadcrumbList" itemscope="" class="breadcrumb">
							 <li itemprop="itemListElement">Lĩnh vực <i class="fa fa-angle-right"></i> {{$categories->name}}</li>
						</ol>
					</div>
				</div>
			</div>
			<div class="row no-gutter content_list_cs">
				<div id="group_content_page_generate" class="col-lg-12 col-md-12 col-xs-12">
					@foreach($customers as $data_customer)
						<div class="panel panel-default">
							<div class="panel-body panel-company">
								<h3>
								@if(!empty($data_customer->customers_images))
									<img width="32" src="{{$data_customer->customers_images}}">
								@else
									<span class="glyphicon glyphicon-picture" style="font-size: 20px; color:#de1a42;"></span>
								@endif
								<a href="@if(strlen($data_customer->customers_company)>0)  {{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_company)))}} @else {{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_name)))}} @endif">@if(strlen($data_customer->customers_company)>0)  {{$data_customer->customers_company}} @else {{$data_customer->customers_name}} @endif</a>
								</h3>
								<span class="the-article-publish">
									{!!WebService::time_request($data_customer->customers_op_at)!!}
								</span>
								<p class="address">
								<i class="fa fa-map-marker"></i>
								{{$data_customer->address}}
								</p>
							</div>
							<div class="panel-footer">
								<span class="share-social"><i class="glyphicon glyphicon-thumbs-up"></i> <a class="likePost" data-user="{userId}" title="Thích" href="#">Thích</a></span>
								<span class="share-social"><i class="glyphicon glyphicon-comment"></i> <span data-href="{{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_name)))}}" class="fb-comments-count fb_comments_count_zero" fb-xfbml-state="rendered"><span class="fb_comments_count">0</span></span> <a title="Bình luận" href="#post-1213832">Bình luận</a></span>
								<span class="share-social"><a href="javascript: void(0)" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_name)))}}&amp;t={{$data_customer->customers_name}}', 'mywindow', 'toolbar=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=580,height=325');
							return false;" type="icon_link" id="fb-share"><i class="fa fa-facebook"></i> Facebook</a>
								<a rel="nofollow" title="{{$data_customer->customers_name}}" href="https://twitter.com/share?url={{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_name)))}}&amp;text={{$data_customer->customers_name}}&amp;via=[via]&amp;hashtags=[hashtags]"><i class="fa fa-twitter"></i> Twitter</a>
								<a rel="nofollow" title="{{$data_customer->customers_name}}" href="https://plus.google.com/share?url={{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_name)))}}"><i class="fa fa-google-plus"></i> Google+</a>
								<a rel="nofollow" title="{{$data_customer->customers_name}}" href="https://pinterest.com/pin/create/bookmarklet/?media=[post-img]&amp;url={{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_name)))}}&amp;is_video=[is_video]&amp;description={{$data_customer->customers_name}}
								"><i class="fa fa-pinterest"></i> Pinterest</a></span>
							</div>
						</div>
					@endforeach
				</div>
                <div id="load_item_page" class="text-center">
					<input id="token_load_company" type="hidden" name="_token" value="{{ csrf_token() }}">
					<input id="check_click_company" class="check_click_company" type="hidden" value="{{$customers->currentPage()}}" autocomplete="off"/>
					<input id="total_company" class="total_company" type="hidden" value="{{$customers->total()}}" autocomplete="off"/>
					<input id="url_request_current" class="url_request_get" type="hidden" value="{{Request::url()}}" autocomplete="off"/>
					<input id="url_next_get" class="url_next_get" type="hidden" value="{{$customers->nextPageUrl()}}" autocomplete="off"/>
					<input id="load_item_get" class="load_item_get" type="hidden" value="{{$customers->perPage()}}" autocomplete="off"/>
					<input id="lastPage" class="lastPage" type="hidden" value="{{$customers->lastPage()}}" autocomplete="off"/>
					@if(strlen($customers->nextPageUrl())>0)
						<div class="click-more">
							<button class="btn btn-success btn-xs" id="loading-page"><i class="fa fa-spinner fa-spin"></i> Loading</button> 
							<a href="{{$customers->nextPageUrl()}}" class="view-more-ads-home"><i class="glyphicon glyphicon-chevron-down"></i> Tiếp</a>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection