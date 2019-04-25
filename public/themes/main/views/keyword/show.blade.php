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
	Theme::setAmp(route('keyword.show.id',array($channel['domainPrimary'],$keyword['_id'],str_slug(mb_substr($keyword['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))).'?amp=true');
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	$imageShow=false;
	$siteShow=false;
	$videoShow=false;
	$showEmpty=false;
	if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0){
		$siteShow=true;
		$count=count($keyword['site_relate']);
		if($count>=5){
			$skipImage=3;
			$skipVideo=5;
		}else if($count==4){
			$skipImage=2;
			$skipVideo=4;
		}else if($count==3){
			$skipImage=1;
			$skipVideo=2;
		}else if($count==2){
			$skipImage=1;
			$skipVideo=2;
		}else if($count==1){
			$skipImage=1;
			$skipVideo=2;
		}
	}
	if(!empty($keyword['image_relate']) && count($keyword['image_relate'])>0){
		$imageShow=true;
	}
	if(!empty($keyword['video_relate']) && count($keyword['video_relate'])>0){
		$videoShow=true;
	}
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
				<ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('keyword.show.id',array($channel['domainPrimary'],$parentKey['_id'],str_slug(mb_substr($parentKey['keyword'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}"><span itemprop="name">{!! $keyword['parent'] !!}</span></a></li>
				</ol>
			@else
				<ol class="breadcrumb mb5" itemscope itemtype="http://schema.org/BreadcrumbList">
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing" itemprop="item" href="{{route('channel.home',$channel['domainPrimary'])}}"><i class="fa fa-home"></i> <span class="hidden-xs" itemprop="name">Cung Cấp</span></a></li>
					<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{!! route('keyword.show.id',array($channel['domainPrimary'],$keyword['parent_id'],str_slug(mb_substr($keyword['parent'], 0, \App\Model\Mongo_keyword::MAX_LENGTH_SLUG),'-'))) !!}"><span itemprop="name">{!! $keyword['parent'] !!}</span></a></li>
				</ol>
			@endif
		@endif
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
		</div>
		<div class="container">
			<div class="row row-pad-5">
				<div class="col-md-12">
					@if($showEmpty==true)
						Từ khóa {!! $keyword['keyword'] !!} chưa có bất kỳ thông tin trang web, hình ảnh, video nào!
					@endif
					@if(count($postSearch))
						<div class="PostlistItem">
							{!!Theme::partial('listPostChannelSlider', array('postSearch' => $postSearch,'keyword'=>$keyword))!!}
						</div>
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
					@if($siteShow==true)
						{!!Theme::partial('keyword.listSite', array('keyword' => $keyword,'ads'=>$ads,'skipImage'=>$skipImage,'skipVideo'=>$skipVideo,'imageShow'=>$imageShow,'videoShow'=>$videoShow))!!}
					@else
						@if($imageShow==true)
							{!!Theme::partial('keyword.listImage', array('keyword' => $keyword))!!}
						@endif
						@if($videoShow==true)
							{!!Theme::partial('keyword.listVideo_slider', array('keyword' => $keyword,'from'=>0,'to'=>8))!!}
						@endif
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