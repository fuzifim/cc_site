<?
	$getDomain=App\Model\Domain::where('site_id','=',$template->id)->where('status','=','active')->where('domain_primary','=','default')->first(); 
	if(isset($getDomain->domain)){
		$domainPrimary=$getDomain->domain; 
	}else{
		$domainPrimary=$template->domain; 
	}
?>
<!--content list website-->
<div id="panel_change_{{$template->id}}" class="panel panel-program">
	@if(count($ads)<4)
		<script>
			$( "#panel_change_{{$template->id}}" ).hide();
		</script>
	@endif
	<div class="panel-heading heading-program">
		<h4 class="company-name">
			<a href="//{{$domainPrimary}}">
				<div class="company-avatar">
					@if(strlen($template->logo)>0)
						<img src="{{$template->logo}}" alt="" height="30" width="30"/> 
					@else 
						<span class="glyphicon glyphicon-picture" style="font-size: 20px;"></span>
					@endif
				</div>
				 <span>{{$template->title}}</span>
			</a>
			<div class="pull-right dropdown">
				<a class="" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-option-vertical"></i> </a> 
				<ul class="dropdown-menu">
					<li> 
						@if(WebService::check_user_Like($template->id))
							<a class="" onclick="setLikeWebsite({{$template->id}},'unlike')" href="javascript:void(0)"><i class="glyphicon glyphicon-thumbs-up"></i>Đã Thích</a>
					   @else
							<a class="" onclick="setLikeWebsite({{$template->id}},'like')" href="javascript:void(0)"><i class="glyphicon glyphicon-thumbs-up"></i>Thích</a>
					   @endif
					</li>
					<li>
						 @if(Auth::check())
							<a href="javascript:void(0)" onclick="notCareAds({{$template->id}})" class="click_hiden" title="Không quan tâm"><i class="glyphicon glyphicon-remove"></i></a>
						 @else
							<a href="#!login" data-toggle="modal" data-target="#myModal" data-backdrop="true" title="Không quan tâm"><i class="glyphicon glyphicon-remove"></i></a>
						 @endif
					</li>
				</ul>
			</div>
		</h4>
	</div>
   <div class="panel-body">
			<div class="row" role="listbox">
				<?php $i=0; ?>
				@foreach($ads as $listProducts)
				@if(strlen($listProducts->ads_thumbnail))
						<div class="col-xs-12 col-sm-6 col-md-3">
							<div class="portfolio_utube_item_image">
								<a href="{{route('front.ads.detail',$listProducts->ads_slug)}}" class="coverImagePostThumbnail">
									<img src="{{$listProducts->ads_thumbnail}}" class="img-fluid lazy-load" alt="{{$listProducts->ads_title}}"/>
								</a>
							</div>
							<div class="item_caption">
							@if(strlen($template->regionsID)>0 && strlen($template->subRegionsID)>0 )
							   <h4><a href="{{route('front.ads.detail',$listProducts->ads_slug)}}">{{$listProducts->ads_title}}</a></h4>
							@endif   
							   @if(!empty($listProducts->ads_price))
									<div class="product__price clear">
										<span class="price">
											 <span itemprop="price" class="price__value">{!!Site::price($listProducts->ads_price)!!}</span>
											 <span class="price__symbol">đ</span>
											 @if(!empty($listProducts->discount))
													<span class="price__discount">-{!!Site::percentPrice($listProducts->ads_price,$listProducts->discount)!!}</span>
											 @endif
										</span>
									</div>
									@if(!empty($listProducts->discount))
									  <div class="product__price product__price--list-price clear">
										  <span class="price price--list-price">
											   <span class="price__value">{!!Site::price($listProducts->discount)!!}</span><span class="price__symbol">đ</span>
										  </span>
									   </div>
									@endif
							   @endif
								<div class="item_caption_author">
									<span>bởi</span>
									  <a href="//{{$domainPrimary}}"> 
										 <span>{{$template->title}}</span>
									  </a>
								</div>
								<ul>
									<li>{{$listProducts->ads_view}} lượt xem</li>
									<li><span>.</span></li>
									<li>{!!WebService::time_request($listProducts->updated_at)!!}</li>
								</ul>
						   </div>
						</div>
				 @endif
				   <?php $i=$i+1; ?>
				@endforeach
			</div>
   </div><!--panel-body-->
</div><!--panel-default panel-chanel-->