<?
	$setKeyword=[]; 
	if(!empty($keyword['keyword'])){
		Theme::setCanonical(route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($keyword['keyword']))));
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
?>
<section>
	<div class="mainpanel">
		{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
		<div class="container">
			<div class="row row-pad-5">
				<div class="col-md-12">
					<h1><strong>{!! $keyword['keyword'] !!}</strong></h1>
					<?php
					if ($keyword['updated_at'] instanceof \MongoDB\BSON\UTCDateTime) {
						$updated_at= $keyword['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
					}else{
						$updated_at= $keyword['updated_at'];
					}
					?>
					<small>Updated at {!! $updated_at !!}</small> @if(!empty($keyword['view']))<small><strong>Views: {!! $keyword['view'] !!}</strong></small>@endif
					@if(!empty($keyword['parent']))
					<p>Parent <a href="{{route('keyword.show',array($channel['domainPrimary'],WebService::characterReplaceUrl($keyword['parent'])))}}">{!! $keyword['parent'] !!}</a></p>
					@endif
					@if(!empty($keyword['site_relate']) && count($keyword['site_relate'])>0)
					<div class="panel panel-primary">
						<div class="panel-heading">
							Site relate for {!! $keyword['keyword'] !!}
						</div>
						<ul class="list-group">
							@foreach($keyword['site_relate'] as $siteRelate)
								<?php
								$site=DB::connection('mongodb')->collection('mongo_site')
										->where('_id', (string)$siteRelate)->first();
								?>
								@if(!empty($site['title']))
									<li class="list-group-item">
										<h4><a class="siteLink" id="linkContinue" href="{!! route('go.to.url',array(config('app.url'),$site['link'])) !!}" rel="nofollow" target="blank">{!! $site['title'] !!}</a></h4>
										<span>{!! $site['updated_at']->toDateTime()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s') !!}</span><br>
										<span>{!! $site['description'] !!}</span><br>
										<span>{!! $site['link'] !!}</span><br>
										<i class="glyphicon glyphicon-globe"></i> <a href="http://{!! $site['domain'] !!}.d.{!! config('app.url') !!}" target="blank">{!! WebService::renameBlacklistWord($site['domain']) !!}</a>
									</li>
								@endif
							@endforeach
						</ul>

					</div>
					@endif
					@if(!empty($keyword['keyword_relate']) && count($keyword['keyword_relate'])>0)
						<div class="form-group">
							<p><strong>Keyword relate for {!! $keyword['keyword'] !!}</strong></p>
							@foreach($keyword['keyword_relate'] as $keywordRelate)
								<?php
								$keywordRe=DB::connection('mongodb')->collection('mongo_keyword')
										->where('_id', (string)$keywordRelate)->first();
								?>
								<span><a class="badge" href="{{route('keyword.show',array($channel['domainPrimary'],WebService::characterReplaceUrl($keywordRe['keyword'])))}}">{!! $keywordRe['keyword'] !!}</a></span>
							@endforeach
						</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</section>