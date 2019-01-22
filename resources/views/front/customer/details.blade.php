@extends('inc.master')
@section('seo')
<?php $data_seo = array(
        'title' => (!empty($customies->customers_company) ? $customies->customers_company : $customies->customers_name),
        'keywords' => $customies->customers_company,
        'description' => $customies->customers_company.' - '.$customies->address,
        'og_title' => $customies->customers_company,
        'og_description' => $customies->customers_company.' - '.$customies->address,
        'og_url' => Request::url(),
        'og_img' => url('/').'/image/'.(empty($customies->customers_company) ? urlencode($customies->customers_name): urlencode($customies->customers_company)),
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
													@if(isset($region))
													<li class="dropdown" itemprop="itemListElement">
														<a class="btn btn-default btn-menu" itemprop="item" href="{{url('/')."/".$region->iso}}/company"><span><i class="flag-icon flag-icon-{{mb_strtolower($region->iso)}}"></i> <span class="hidden-xs" itemprop="name">{{$region->country}}</span></a>
													</li>
													@endif
													@if(isset($subregion))
													<li class="dropdown hidden-xs" itemprop="itemListElement">
														<a class="btn btn-default btn-menu" itemprop="item" href="{{ url('/').$subregion->SolrID.'/company'}}"><span itemprop="name">{{$subregion->subregions_name}}</span></a>
													</li>
													@endif
												</ol>
											</div>
											<input id="txt_search" type="text" class="form-control" placeholder="@if(!empty($customies->customers_company)){{$customies->customers_company}}@else {{$customies->customers_name}} @endif" value="<?php if(isset($txt) && strlen($txt)>0){ echo $txt;}?>"/>
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
					@if(isset($customies))
						<div class="panel panel-default">
							<div class="panel-heading">
								@if(!empty($customies->customers_company))<h1 class="text-center title-company">{{$customies->customers_company}}</h1> @else <h1 class="text-center title-company">{{$customies->customers_name}}</h1> @endif
							</div>
							<div class="panel-body  panel-company">	
								<div class="align-center" style="text-align:-webkit-center;"><img itemprop="image" title="@if(!empty($customies->customers_company)){{$customies->customers_company}}@else {{$customies->customers_name}} @endif" class="photo-company img-responsive" src="{{url('/')}}/image/@if(!empty($customies->customers_company)){{urlencode($customies->customers_company)}}@else {{urlencode($customies->customers_name)}} @endif"></div>
								<div class="group_details_company_view clear">
									@if(!empty($customies->customers_company))
										<h3>
										<i class="fa fa-info"></i>
										{{$customies->customers_name}}
										</h3>
									@endif
									<p itemprop="description">
									<strong>{{$customies->customers_company}}</strong> được thành lập vào ngày <?php echo date("d/m/Y", strtotime($customies->ngay_cap)); ?>, mã số thuế {{$customies->tax_code}}, quản lý bởi {{$customies->admin_name}}. @if(isset($subregion))Giấy phép kinh doanh được đăng ký tại {{$subregion->subregions_name}}@endif, địa chỉ công ty: {{$customies->address}}. 
									</p>
									<ul class="list-group">
										<li class="list-group-item"><i class="fa fa-barcode"></i> Mã số thuế:<strong>@if(!empty($customies->tax_code)) {{$customies->tax_code}} @endif</strong></li>
										<li class="list-group-item"><i class="fa fa-user"></i> Người đại diện: <strong itemprop="name">@if(!empty($customies->admin_name)) {{$customies->admin_name}} @endif</strong></li>
										<li class="list-group-item">
											@if(empty($customies->admin_email))
												<div class="alert alert-warning"><i class="fa fa-envelope"></i> Chưa cập nhật địa chỉ Email - <a href="javascript: void(0)" rel="nofollow"><i class="glyphicon glyphicon-edit"></i> cập nhật email</a></div>
											@else
												<i class="fa fa-envelope"></i> Email: <strong>{{$customies->admin_email}}</strong>
											@endif
										</li>
										<li class="list-group-item">
											@if(!empty($customies->address))
												<i class="fa fa-map-marker"></i>
												Địa chỉ: <strong><span class="street-address">{{$customies->address}}</span>, <span class="region"> Việt Nam</span></strong>
											 @else
												<div class="alert alert-warning"><i class="fa fa-envelope"></i> Chưa cập nhật địa chỉ - <a href="javascript: void(0)" rel="nofollow"><i class="glyphicon glyphicon-edit"></i> cập nhật địa chỉ</a></div>
											 @endif
										</li>
										<li class="list-group-item">
											<abbr title="voice" class="type"></abbr>
											@if(!empty($customies->admin_phone))
												<i class="fa fa-phone"></i> Điện thoại: <strong>{{$customies->admin_phone}}</strong>
											 @else
												<div class="alert alert-warning"><i class="fa fa-phone"></i> Chưa cập nhật số điện thoại - <a href="javascript: void(0)" rel="nofollow"><i class="glyphicon glyphicon-edit"></i> cập nhật điện thoại</a></div>
											@endif
										</li>
										<li class="list-group-item">
											@if(empty($customies->website))
												<div class="alert alert-warning">
													<i class="fa fa-globe"></i> Website {{$customies->customers_company}} chưa được cập nhật
												</div>
											@else
												<i class="fa fa-globe"></i> Website: {{$customies->website}}
											@endif
										</li>
									</ul>
									<div class="footer-info region">
										@if(!empty($ward->ward_name))<span><a style="font-weight:bold;" title="{{$ward->ward_name}}" href="{{ url('/').$ward->SolrID.'/company'}}">{{$ward->ward_name}}</a></span>
										@endif - @if(!empty($district->district_name))<span><a style="font-weight:bold;" title="{{$district->district_name}}" href="{{ url('/').$district->SolrID.'/company'}}">{{$district->district_name}}</a></span>
										@endif - @if(!empty($subregion->subregions_name))<span><a style="font-weight:bold;" title="{{$subregion->subregions_name}}" href="{{ url('/').$subregion->SolrID.'/company'}}">{{$subregion->subregions_name}}</a></span>
										@endif - @if(!empty($region->country))<span><a style="font-weight:bold;" title="{{$region->country}}" href="{{ url('/')."/".$region->iso.'/company'}}">{{$region->country}}</a></span>
										@endif
									</div>
									<div class="datetime pd-bt5 clear">
										<em>Lần cập nhật mới nhất:</em>
										<strong><time class="rev" datetime="{!!Site::Date($customies->customers_op_at,'d-m-Y H:s')!!}">{!!WebService::time_request($customies->customers_op_at)!!}</time></strong>
									</div>
								</div>
								<script type="application/ld+json">
								{
								  "@context": "http://schema.org/",
								  "@type": "Review",
								  "itemReviewed": {
									"@type": "Thing",
									"name": "{{$customies->customers_company}}"
								  },
								  "author": {
									"@type": "Person",
									"name": "{{$customies->admin_name}}"
								  },
								  "reviewRating": {
									"@type": "Rating",
									"ratingValue": "10", 
									"bestRating": "10"
								  },
								  "publisher": {
									"@type": "Organization",
									"name": "{{$customies->customers_company}}"
								  }
								}
								</script>
							</div>
							<div class="panel-footer">
								<span class="first-share-sociall share-social"><i class="fa fa-eye"></i> Lượt xem: {{$customies->customers_views}}</span>
								
								<a class="btn btn-xs btn-primary" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u={{Request::url()}}&amp;t={{$customies->customers_company}}" id="fb-share"><i class="fa fa-facebook"></i> Face<span class="hidden-xs">book</span></a> 
								<a class="btn btn-xs btn-primary" rel="nofollow" title="{{$customies->customers_company}}" href="https://twitter.com/share?url={{Request::url()}}&amp;text={{$customies->customers_company}}&amp;via=[via]&amp;hashtags=[hashtags]"><i class="fa fa-twitter"></i> Twit<span class="hidden-xs">ter</span></a> 
								<a class="btn btn-xs btn-primary" rel="nofollow" title="{{$customies->customers_company}}" href="https://plus.google.com/share?url={{Request::url()}}"><i class="fa fa-google-plus"></i> Goo<span class="hidden-xs">gle+</span></a> 
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-body">
								<div class="fb-comments" data-width="100%" data-href="{!!Site::urlCurrent()!!}" data-numposts="5" data-colorscheme="light"></div>
							</div>
						</div>
						<div class="panel panel-default">
							<div class="panel-heading">
								<strong>Lĩnh vực hoạt động</strong>
							</div>
							<div class="panel-body panel-company">
								<?php  for($j=0;$j<count($Data_Categorys);$j++):?>
									<span class="category_item"><a href="{{route('front.customer.list',array($Data_Categorys[$j]->id,Str::slug($Data_Categorys[$j]->name)))}}"><i class="fa fa-folder-o"></i> <?php echo $Data_Categorys[$j]->name;?></a></span>
								<?php endfor;?>
							</div>
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection