<?
	$setKeyword=[];
	if(!empty($keyword['keyword'])){
		Theme::setCanonical(route('keyword.show.id',array($channel['domainPrimary'],$keyword['_id'],str_slug(mb_substr($keyword['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))));
		Theme::setTitle($keyword['keyword']);
	}
	if(!empty($keyword['description'])){
		Theme::setDescription($keyword['description']);
	}
	if(!empty($keyword['image'])){
		Theme::setImage($keyword['image']);
	}
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	$showListImage=0;
	if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0 && !empty($keyword['image_relate']) && count($keyword['image_relate'])>0){
		$showListImage=1;
	}else if(!empty($keyword['image_relate']) && count($keyword['image_relate'])>0){
		$showListImage=2;
	}
	$showListVideo=0;
	if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0 && !empty($keyword['video_relate']) && count($keyword['video_relate'])>0){
		$showListVideo=1;
	}else if(!empty($keyword['video_relate']) && count($keyword['video_relate'])>0){
		$showListVideo=2;
	}
	$showEmpty=false;
	if(empty($keyword['site_relate']) && empty($keyword['image_relate']) && empty($keyword['video_relate'])){
		$showEmpty=true;
	}
	$ads='true';
?>
@if($ads=='true' && config('app.env')!='local')
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<script>
		(adsbygoogle = window.adsbygoogle || []).push({
			google_ad_client: "ca-pub-6739685874678212",
			enable_page_level_ads: true
		});
	</script>
@endif
<section>
	<div class="mainpanel">
		{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
		<div class="pageheader form-group">
			<h1><strong>{!! $keyword['keyword'] !!}</strong></h1>
			<?php
			if ($keyword['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
				$updated_at= $keyword['updated_at']->toDateTime()->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s');
			}else{
				$updated_at= $keyword['updated_at'];
			}
			?>
			<small>Updated at {!! $updated_at !!}</small> @if(!empty($keyword['view']))<small><strong>Views: {!! $keyword['view'] !!}</strong></small>@endif
			@if(!empty($keyword['parent']))
				@if(empty($keyword['parent_id']))
					<?php
					$parentKey = DB::connection('mongodb')->collection('mongo_keyword')
							->where('base_64', base64_encode($keyword['parent']))->first();
					DB::connection('mongodb')->collection('mongo_keyword')
						->where('_id',(string)$keyword['_id'])
						->update(
							[
								'parent_id'=>(string)$parentKey['_id']
							]
						);

					?>
					<p>Parent <a href="{!! route('keyword.show.id',array($channel['domainPrimary'],$parentKey['_id'],str_slug(mb_substr($parentKey['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">{!! $keyword['parent'] !!}</a></p>
				@else
					<p>Parent <a href="{!! route('keyword.show.id',array($channel['domainPrimary'],$keyword['parent_id'],str_slug(mb_substr($keyword['parent'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">{!! $keyword['parent'] !!}</a></p>
				@endif
			@endif
		</div>
		<div class="container">
			<div class="row row-pad-5">
				<div class="col-md-12">
					@if($showEmpty==true)
						Từ khóa {!! $keyword['keyword'] !!} chưa có bất kỳ thông tin trang web, hình ảnh, video nào!
					@endif
					@if($ads=='true' && config('app.env')!='local')
						<div class="form-group">
							<ins class="adsbygoogle"
								 style="display:block"
								 data-ad-client="ca-pub-6739685874678212"
								 data-ad-slot="7536384219"
								 data-ad-format="auto"></ins>
							<script>
								(adsbygoogle = window.adsbygoogle || []).push({});
							</script>
						</div>
					@endif
					@if($showListImage==1)
						{!!Theme::partial('keyword.listImage', array('keyword' => $keyword))!!}
					@endif
					@if($showListVideo==0)
						@if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0)
							{!!Theme::partial('keyword.listSite', array('keyword' => $keyword,'ads'=>$ads))!!}
						@endif
					@endif
					@if($showListVideo==1)
						@if($ads=='true' && config('app.env')!='local')
							<div class="form-group">
								<ins class="adsbygoogle"
									 style="display:block"
									 data-ad-client="ca-pub-6739685874678212"
									 data-ad-slot="7536384219"
									 data-ad-format="auto"></ins>
								<script>
									(adsbygoogle = window.adsbygoogle || []).push({});
								</script>
							</div>
						@endif
						<div class="row row-pad-5">
							<div class="col-md-9">
								{!!Theme::partial('keyword.listSite', array('keyword' => $keyword,'ads'=>$ads))!!}
								{!!Theme::partial('keyword.listVideo_1', array('keyword' => $keyword,'from'=>0,'to'=>4))!!}
								@if($ads=='true' && config('app.env')!='local')
									<div class="form-group">
										<ins class="adsbygoogle"
											 style="display:block"
											 data-ad-client="ca-pub-6739685874678212"
											 data-ad-slot="7536384219"
											 data-ad-format="auto"></ins>
										<script>
											(adsbygoogle = window.adsbygoogle || []).push({});
										</script>
									</div>
								@endif
								{!!Theme::partial('keyword.listVideo_2', array('keyword' => $keyword,'from'=>4,'to'=>4))!!}
							</div>
							<div class="col-md-3">
								{!!Theme::partial('keyword.listVideo_3', array('keyword' => $keyword,'from'=>8,'to'=>12))!!}
							</div>
						</div>
					@elseif($showListVideo==2)
						{!!Theme::partial('keyword.listVideo_4', array('keyword' => $keyword))!!}
						@if($ads=='true' && config('app.env')!='local')
							<div class="form-group">
								<ins class="adsbygoogle"
									 style="display:block"
									 data-ad-client="ca-pub-6739685874678212"
									 data-ad-slot="7536384219"
									 data-ad-format="auto"></ins>
								<script>
									(adsbygoogle = window.adsbygoogle || []).push({});
								</script>
							</div>
						@endif
					@endif
					@if($showListImage==2)
						{!!Theme::partial('keyword.listImage', array('keyword' => $keyword))!!}
					@endif
					@if(!empty($keyword['keyword_relate']) && count($keyword['keyword_relate'])>0)
						<div class="form-group">
							<p><strong>Keyword relate for {!! $keyword['keyword'] !!}</strong></p>
							@foreach($keyword['keyword_relate'] as $keywordRelate)
								<?php
								$keywordRe=DB::connection('mongodb')->collection('mongo_keyword')
										->where('_id', (string)$keywordRelate)->first();
								?>
							@if(empty($keywordRe['craw_next']))
								<span class="badge">{!! $keywordRe['keyword'] !!}</span>
							@else
								<span><a class="badge progress-bar-success" href="{!! route('keyword.show.id',array($channel['domainPrimary'],$keywordRe['_id'],str_slug(mb_substr($keywordRe['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}">{!! $keywordRe['keyword'] !!}</a></span>
							@endif

							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>
<?php
$dependencies = array();
Theme::asset()->writeScript('loadLazy','
		$(".showImageLink").click(function(){
			$("#showImageLarge").attr("src",$(this).attr("data-image"));
			$("#showImageLarge").attr("title",$(this).attr("data-title"));
			$("#showImageLarge").attr("alt",$(this).attr("data-title"));
			$("#showImageLargeLink").attr("href",$(this).attr("data-url"));
			$("#showImageLargeLink").text($(this).attr("data-title"));
			return false;
		});
	', $dependencies);
?>