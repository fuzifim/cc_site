@if(count($PostRelate)>0)
<div class="panel panel-primary item-relate">
	<div class="panel-heading">Bài liên quan</div>
	<div class="panel-body">
            @foreach($PostRelate as $postRelate)
				@if(strlen($postRelate->post_thumbnail))
					<div id="product-{{$postRelate->id}}-wrapper" class="product_releated_item product-wrapper_releated_details _tracking">
						<div id="product-{{$postRelate->post_id}}-item" class="form-group">
							<div class="row">
								<div class="col-xs-12 col-sm-4 col-md-4">
									<a class="img-post-relate" href="{{route('front.post.show',$postRelate->post_slug)}}">
										 <img class="img-responsive" src="{{$postRelate->post_thumbnail}}" alt="{{$postRelate->post_title}}"/>
									</a>
								</div>
								<div class="col-xs-12 col-sm-8 col-md-8">
									 <h3 class="product__title">
										<a href="{{route('front.post.show',$postRelate->post_slug)}}">{{ str_limit(strip_tags($postRelate->post_title), $limit = 45, $end = '...') }}</a>
									 </h3>
									 <span class="PostRelate-view">{{ $postRelate->post_view }} lượt xem</span>
									@if(!empty($postRelate->post_price))
									   <div class="product__price">
											<span class="price">
												<span itemprop="price" class="price__value">{!!Site::price($postRelate->post_price)!!}</span>
												<span class="price__symbol">đ</span>
												@if(!empty($postRelate->discount))
													<span class="price__discount">-{!!Site::percentPrice($postRelate->post_price,$postRelate->discount)!!}</span>
												@endif
											</span>
										</div>
									@endif
									@if(!empty($postRelate->discount))
										<div style="display: inline-block;" class="product__price product__price--list-price">
											<span class="price price--list-price">
												<span class="price__value">{!!Site::price($postRelate->discount)!!}</span><span class="price__symbol">đ</span>
											</span>
										</div>
									@endif
									<?
										$getDomain=App\Model\Domain::where('site_id','=',$postRelate->template_setting_id)->where('status','=','active')->where('domain_primary','=','default')->first(); 
										if(isset($getDomain->domain)){
											$domainPrimary=$getDomain->domain; 
										}else{
											$domainPrimary=$postRelate->domain; 
										}
									?>
									@if(!empty($postRelate->logo))
										<img class="img-thumbnail img-thumbnail-small" width="16" src="{{$postRelate->logo}}">
									@else
										<span class="glyphicon glyphicon-globe" style="font-size: 16px;"></span>
									@endif
									<a href="{{route('member.home',$domainPrimary)}}"><small>{{$postRelate->title}}</small></a> 
								</div>
							</div>
						</div>
					</div>
				@endif
            @endforeach
        
	</div>
    <div class="panel-footer">
        <a href="#" class="view-more-post"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</a>
    </div>
</div>
@endif