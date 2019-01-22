@extends('inc.master')
@section('seo')
<?php
   $data_seo = array(
		'title' => $post->post_title,
		'keywords' => Illuminate\Support\Str::slug($post->post_title, $separator = ' '),
		'description' => str_limit(strip_tags(htmlspecialchars_decode($post->post_description)), $limit = 120, $end = ''),
		'og_title' => $post->post_title,
		'og_description' => str_limit(strip_tags(htmlspecialchars_decode($post->post_description)), $limit = 120, $end = ''),
		'og_url' => Request::url(),
		'og_sitename' => config('app.appname'),
		'og_img' => url($post->post_thumbnail),
		'current_url' => Request::url()
	    );
	$seo = WebService::getSEO($data_seo);   
?>
@include('partials.seo')
@endsection

@section('content')
	<div class="section">
		<form name="frm_search" id="frm_search" <?php if(isset($txt) && strlen($txt)>0){?> action="{{url('/')}}/search/<?php echo addslashes($txt); ?>" <?php }else{?> action="" <?php } ?> class="panel" role="search">
			<div class="input-group" id="adv-search">
				<div class="input-group-btn">
					<div class="btn-group" role="group">
						<div class="dropdown dropdown-lg">
						</div>
						<input id="txt_search" type="text" class="form-control" placeholder="{{ $post->post_title }}" value="<?php if(isset($txt) && strlen($txt)>0){ echo $txt;}?>"/>
						<button id="search_btn" type="submit" class="btn btn-primary btn-search"><span class="glyphicon glyphicon-search"></span></button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="section">
		<div class="row no-gutter">
			<div class="col-md-8">
				@if($post->category_option_post_id==5)
					@if(strlen($post->link_youtube)>0)
						<div class="embed-responsive embed-responsive-4by3"><iframe class="embed-responsive-item" frameborder="0" src="{{$post->link_youtube}}" allowfullscreen="true"></iframe></div>
					@endif
				@else
					@if(count($medias)>0)
						<div class="panel carousel slide article-slide single_content_entry" id="article-photo-carousel">
							<!-- Wrapper for slides -->
							  <div class="carousel-inner cont-slider">
								<?php
									$i=0;
									foreach ($medias as $image) {
								?>
								<div class="item coverImageGalleryPostDetail @if($i==0) active @endif">
								  <img itemprop="image" class="img-responsive img-fluid lazy-load" alt="{{$post->post_title}}" src="{{$image}}"/>
								</div>
								<?php
									$i=$i+1; 
									}
								?>
							  </div>
							  <a class="left carousel-control carousel_control_left" href="#article-photo-carousel" role="button" data-slide="prev">
								<span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							  </a>
							  <a class="right carousel-control carousel_control_right" href="#article-photo-carousel" role="button" data-slide="next">
								<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							  </a>
						</div>
					@endif
				@endif
				<div class="panel panel-default">
					<div class="panel-body">
						<h1>{{ $post->post_title }}</h1> 
						<div class="company-chanel-post">
							<div class="chanel-text-view">
								<strong>{{ $post->post_view }} lượt xem</strong>
							</div>
							<div class="chanel-action pull-right">
								<div class="row no-gutter">
									<div class="col-xs-4 col-sm-4 col-lg-4 text-center">
										<input class="url_current_product_{{$post->id}}" value="{{route('home')}}" type="hidden" />
										<input class="url_current_like_product_{{$post->id}}" value="{!!WebService::getLike_post($post->id)!!}" type="hidden" />
										<input class="url_current_unlike_product_{{$post->id}}" value="{!!WebService::getUnLike_post($post->id)!!}" type="hidden" />
										@if(WebService::check_user_post_like($post->id))
											<button type="button" class="btn btn-xs btn-primary" onclick="setLikePost({{$post->id}},'unlike')"><i class="glyphicon glyphicon-thumbs-up"></i> {!!WebService::getLike_post($post->id)!!}</button>
										@else
											<button type="button" class="btn btn-xs btn-primary" onclick="setLikePost({{$post->id}},'like')"><i class="glyphicon glyphicon-thumbs-up"></i> {!!WebService::getLike_post($post->id)!!}</button>
										@endif
										@if(WebService::check_user_post_unlike($post->id))
											<button type="button" class="btn btn-xs btn-danger" onclick="setUnLikePost({{$post->id}},'unlike')"><i class="glyphicon glyphicon-thumbs-down"></i> {!!WebService::getUnLike_post($post->id)!!}</button>
										@else
											<button type="button" class="btn btn-xs btn-danger" onclick="setUnLikePost({{$post->id}},'like')"><i class="glyphicon glyphicon-thumbs-down"></i> {!!WebService::getUnLike_post($post->id)!!}</button>
										@endif 
									</div>
									<div class="col-xs-4 col-sm-4 col-lg-4 text-center">
										<button class="btn btn-xs btn-default"><i class="glyphicon glyphicon-comment"></i> Bình luận</button> 
									</div>
									<div class="col-xs-4 col-sm-4 col-lg-4 text-center">
										<button class="btn btn-xs btn-default" data-toggle="modal" data-target="#modalShare" data-id="{{$post->id}}" data-url="{{Request::url()}}" data-title="{{$post->post_title}}" data-image="@if(!empty($post->post_thumbnail)) {{$post->post_thumbnail}} @endif" id="clickShare"><i class="glyphicon glyphicon-share-alt"></i> Chia sẻ</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="post-description">
							@if(!empty(strip_tags(htmlspecialchars_decode($post->post_description))))
							{!! str_limit(strip_tags(htmlspecialchars_decode($post->post_description),"<p><br><b><i><font>"), $limit = 1000, $end='...') !!} <a href="{{route('member.details-post',array($domainPrimary,$post->id,$post->post_slug))}}"> Xem thêm</a>
							@else
							<small class="no-content"><i>Không có nội dung mô tả</i></small>
							@endif
						</div>
						<div class="form-group">
							@if(!empty($post->discount) || !empty($post->post_price) )
							   @if(!empty($post->post_price))
								 <div class="product__price">
									  <span class="price">
										<span class="price__value" itemprop="price">Giá: {!!Site::price($post->post_price)!!}</span>
										<span class="price__symbol" itemprop="priceCurrency" content="VND">đ</span>
										@if(!empty($post->discount))
										<span class="price__discount">-{!!Site::percentPrice($post->post_price,$post->discount)!!}</span>
										@endif
									  </span>
								 </div>
								<button class="btn btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Đặt mua</button>
								@endif
								  @if(!empty($post->discount))
									 <div class="product__price product__price--list-price" style="display: inline-block;">
									  <span class="price price--list-price">
										   <span class="price__value">{!!Site::price($post->discount)!!}</span><span class="price__symbol">đ</span>
									   </span>
									  </div>
								  @endif
							@endif
						</div>
						<script type="application/ld+json">
							{
							  "@context": "http://schema.org/",
							  "@type": "Review",
							  "itemReviewed": {
								"@type": "Thing",
								"name": "{{ $post->post_title }}"
							  },
							  "author": {
								"@type": "Person",
								"name": "{{$post->title}}"
							  },
							  "reviewRating": {
								"@type": "Rating",
								"ratingValue": "10", 
								"bestRating": "10"
							  },
							  "publisher": {
								"@type": "Organization",
								"name": "{{ $post->post_title }}"
							  }
							}
						</script>
					</div>
				</div>
				<div class="post-description">
					<ul class="list-group">
						@if (Auth::check())
						   @if(Auth::user()->id == $post->user_id)
							<li class="list-group-item"><a href="{{route('front.post.edit',$post->post_id)}}" style="color:red; "><i class="glyphicon glyphicon-edit"></i> Sửa bài</a></li>
							@endif
						@endif
						<li class="list-group-item"><h3 class="chanel-name-post"><a href="//{{$domainPrimary}}" title="">
						@if(!empty($post->logo))
							<img class="avata-chanel" src="{{$post->logo}}">
						@else
							<span class="glyphicon glyphicon-picture" style="font-size: 20px;"></span>
						@endif
						 {{$post->title}}</a></h3></li> 
						<li class="list-group-item"><strong>Website:</strong> <a href="//{{$domainPrimary}}">{{$domainPrimary}}</a></li> 
						@if(isset($company))<li class="list-group-item"><strong>Doanh nghiệp:</strong> <a href="{{route('front.customer.details',array($company->id_auto,Str::slug($company->customers_company)))}}">@if(!empty($post->title_shop)){{$post->title_shop}}@else{{$company->customers_company}}@endif</a></li>@endif
						<li class="list-group-item"><strong>Địa chỉ: </strong> <small><cite title="@if ($post->address_shop != ''){{$post->address_shop}} @else Đang cập nhật địa chỉ... @endif"><i class="glyphicon glyphicon-map-marker"></i> @if ($post->address_shop != ''){{$post->address_shop}} @else Chưa cập nhật địa chỉ... @endif</cite></small></li>
						@if(!empty($subregions) || !empty($regions))
						<li class="list-group-item"><strong>Khu vực: </strong>
						@if(!empty($subregions))
						<a href="{{route('front.post_by_location.city',array($regions->iso,$subregions->subregions_name_slug))}}">{{$subregions->subregions_name}}</a>,
						@endif
						@if(!empty($regions))
						<a href="{{route('front.post_by_location.contry',$regions->iso)}}">{{$regions->country}}</a>
						@endif</li> @endif
						<li class="list-group-item"><strong>Ngày đăng: </strong><time class="rev" datetime="{!!Site::Date($post->updated_at)!!}">{!!WebService::time_request($post->updated_at)!!}</time></li>
						<li class="list-group-item"><strong>Người đăng: </strong>
							 <a href="{{route('username.profile',$post->user_id)}}">{{$post->name}}</a>
						</li>
						<li class="list-group-item"><strong>Số điện thoại: </strong><a href="tel:{{ $post->phone_contact }}"> {{ $post->phone_contact }}</a></li>
						@if(!empty($post->user_face))<li><strong>Facebook: </strong><a href="{{ $post->user_face }}" target="_blank"> {{str_replace(array('facebook.com','https://','//','/',"www."),array(''), $post->user_face)  }}</a></li>@endif
					</ul>
				</div>
			   <div class="panel panel-default">
					<div class="panel-body">
						 @include('include.comment_post',array('id'=>$post->id))
					</div>
			   </div>
				<!--Comment-->
			</div>
			<div class="col-md-4">
				@include('front.post.partials.RelatedPosts')
			</div>
		</div><!--row-->
	</div>
<script type="text/javascript">
	jQuery(document).ready(function($){ 
		$(document).delegate('#clickShare', 'click', function() { 
			$('.appendShare').empty(); 
			var id = $(this).attr('data-id'); 
			var url = $(this).attr('data-url'); 
			var title = $(this).attr('data-title'); 
			var image = $(this).attr('data-image'); 
			$('.titleShare').html(title);
			$('.appendShare').append('<div class="row no-gutter">'+ 
				'<div class="col-xs-6 col-sm-4 col-lg-3 text-center"><a class="btn btn-primary btn-block" rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u='+url+'&amp;t='+title+'" id="fb-share"><i class="fa fa-facebook"></i> Facebook</a></div>'+
				'<div class="col-xs-6 col-sm-4 col-lg-3 text-center"><a class="btn btn-primary btn-block" rel="nofollow" href="https://twitter.com/share?url='+url+'&amp;text='+title+'&amp;via=[via]&amp;hashtags=[hashtags]"><i class="fa fa-twitter"></i> Twitter</a></div>'+
				'<div class="col-xs-6 col-sm-4 col-lg-3 text-center"><a class="btn btn-primary btn-block" rel="nofollow" href="https://plus.google.com/share?url='+url+'"><i class="fa fa-google-plus"></i> Google+</a></div>'+
				'<div class="col-xs-6 col-sm-4 col-lg-3 text-center"><a class="btn btn-primary btn-block" rel="nofollow" href="https://pinterest.com/pin/create/bookmarklet/?media='+image+'&amp;url='+url+'&amp;is_video=[is_video]&amp;description='+title+'"><i class="fa fa-pinterest"></i> Pinterest</a></div>'+
				'</div>');	
		}); 
	});
</script>
<!-- Modal -->
	<div id="modalShare" class="modal fade" role="dialog">
	  <div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">Chia sẻ</h4>
		  </div>
		  <div class="modal-body">
			<p><span class="titleShare"></span></p>
			<div class="appendShare"></div>
		  </div>
		</div>

	  </div>
	</div>
@endsection

