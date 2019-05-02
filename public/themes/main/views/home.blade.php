<?
	$channel['theme']->setTitle('Cung Cấp');
	$channel['theme']->setKeywords('Cung cấp, cung cap, provide, provision, supply. ');
	$channel['theme']->setDescription('Cung cấp sản phẩm, dịch vụ kinh doanh đến mọi người ');
	$channel['theme']->setAds('true');
	$channel['theme']->setImage('http://'.$channel["domainPrimary"].Theme::asset()->url('img/cungcap-website.jpg'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('bootstrap-wizard', 'js/bootstrap-wizard.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery.validate.min', 'js/jquery.validate.min.js', array('core-script'));
Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
?>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				@foreach($getCateNews as $cate)
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="panel-title">{!! $cate['keyword'] !!}</div>
						</div>
						<div class="panel-body">
							<?php
								$getNews=Cache::store('memcached')->remember('news_cate_'.(string)$cate['_id'], 1, function() use($cate)
								{
									return DB::connection('mongodb')->collection('mongo_news')
											->where('parent_id',(string)$cate['_id'])
											->orderBy('updated_at','desc')
											->limit(3)
											->get();
								});
							?>
							<div class="row-pad-5 filemanager">
								@foreach($getNews as $news)
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 content-list-post">
										<div class="thmb">
											<a class="thmb-prev" href="{!! route('news.detail',array($channel['domainPrimary'],(string)$news['_id'],str_slug(mb_substr($news['title'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">
												@if(count($news['image']))
													<img src="{!! $news['image'][0]['thumb'] !!}" class="img-responsive" alt="{!! $news['title'] !!}" title="{!! $news['title'] !!}">
												@endif
											</a>
											<h5 class="fm-title"><a class="title" href="{!! route('news.detail',array($channel['domainPrimary'],(string)$news['_id'],str_slug(mb_substr($news['title'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">{!! $news['title'] !!}</a></h5>
											<div class="attribute-2">
												<?php
												if ($news['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
													$updated_at= $news['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
												}else{
													$updated_at= $news['updated_at'];
												}
												?>
												<small>
													<span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($updated_at)!!}</span>
													@if(!empty($news->views))<span class="post-view">{{$news->views}} lượt xem</span>@endif
												</small>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				@endforeach
			</div>
			<div class="col-md-4">

			</div>
		</div>
	</div>

</div><!-- mainpanel -->
</section>
<div class="panelOptimation mb5">

</div>