@extends('inc.master')
@section('seo')
<?php $data_seo = array(
         'title' => 'Doanh nghiệp tại '.$regions->country.config('app.title_seo'),
         'keywords' => '',
         'description' => 'Thông tin các doanh nghiệp tại '.$regions->country,
         'og_title' => $regions->country.config('app.title_seo'),
          'og_description' => 'Thông tin các doanh nghiệp tại '.$regions->country,
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
					<div class="row no-gutter">
						<div class="col-md-12">
							<form name="frm_search" id="frm_search" <?php if(isset($txt) && strlen($txt)>0){?> action="http://cungcap.net/search/<?php echo addslashes($txt); ?>" <?php }else{?> action="" <?php } ?> class="" role="search">
								<div class="input-group" id="adv-search">
									<div class="input-group-btn">
										<div class="btn-group" role="group">
											<div class="dropdown dropdown-lg">
												<ol class="breadcrumb" itemscope="" itemtype="http://schema.org/BreadcrumbList">
													<li class="dropdown" itemprop="itemListElement">
														<a class="btn btn-default dropdown-toggle btn-menu" itemprop="item" href="{{url('/')."/".$regions->iso}}/company" data-toggle="dropdown" aria-expanded="false"><span><i class="flag-icon flag-icon-{{mb_strtolower($regions->iso)}}"></i> <span class="hidden-xs" itemprop="name">{{$regions->country}}</span> <span class="glyphicon glyphicon-menu-down"></span></a>
													   @if(count($regions_alls)>0)
														<ul class="dropdown-menu">
															  @foreach($regions_alls as $regions_all)
															  <li><a href="{{url('/')."/".$regions_all->iso}}/company">
																<i class="fa fa-folder fa-fw"></i> {{$regions_all->country}}</a>
															  </li>
															  @endforeach
														</ul>
														@endif
													</li>
													<li class="dropdown hidden-xs" itemprop="itemListElement">
														<a class="btn btn-default dropdown-toggle btn-menu" itemprop="item" data-toggle="dropdown" href="javascript:void(0)"><span itemprop="name">Khu vực</span> <span class="glyphicon glyphicon-menu-down"></span></a>
														@if(count($subregion_cs)>0)
															<ul class="dropdown-menu">
															   @foreach($subregion_cs as $subregion_c)
															   <li><a href="{{url('/').$subregion_c->SolrID}}/company"">
																 <i class="fa fa-folder fa-fw"></i> {{$subregion_c->subregions_name}}</a>
															   </li>
															   @endforeach
															</ul>
														@endif
													</li>
												</ol>
											</div>
											<input id="txt_search" type="text" class="form-control" placeholder="Doanh nghiệp: {{$regions->country}}" value="<?php if(isset($txt) && strlen($txt)>0){ echo $txt;}?>"/>
											<button id="search_btn" type="submit" class="btn btn-primary btn-search"><span class="glyphicon glyphicon-search"></span></button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row no-gutter content_list_cs">
				<div id="group_content_page_generate" class="col-lg-12 col-md-12 col-xs-12">
					@foreach($customers as $data_customer)
						<div class="panel panel-default">
							<div class="panel-body panel-company">
								<h3>
								
								@if(!empty($data_customer->customers_images) && $data_customer->customers_images!="null")
									<img width="32" src="{{addslashes($data_customer->customers_images)}}"/>
								@else
									<span class="glyphicon glyphicon-picture" style="font-size: 20px; color:#de1a42; "></span>
								@endif
								
								<a href="@if(strlen($data_customer->customers_company)>0)  {{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_company)))}} @else {{route('front.customer.details',array($data_customer->id_auto,Str::slug($data_customer->customers_name)))}} @endif">@if(strlen($data_customer->customers_company)>0)  {{$data_customer->customers_company}} @else {{$data_customer->customers_name}} @endif</a>
								</h3>
								<span class="the-article-publish">
									{!!WebService::time_request($data_customer->customers_op_at)!!}
								</span>
								@if(strlen($data_customer->address))
								<p class="address">
									<i class="fa fa-map-marker"></i>
									{{$data_customer->address}}
								</p>
								@endif
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