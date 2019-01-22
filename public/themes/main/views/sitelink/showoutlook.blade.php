<?
	//$domain=json_decode($site->content); 
	$domainName=$site->domain; 
	if(!empty($site->title)){
		Theme::setTitle($domainName.': '.str_limit(strip_tags($site->title,''), 100));
	}else{
		Theme::setTitle($domainName);
	}
	if(!empty($site->keywords)){
		Theme::setKeywords(strip_tags($site->keywords,''));
	}
	if(!empty($site->description)){
		Theme::setDescription(strip_tags($site->description,"")); 
	}
	if(!empty($site->domain_image)){
		Theme::setImage($site->domain_image); 
	}
	Theme::setSearch($domainName);
?>
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('swiper.min', 'library/swiper/js/swiper.min.js', array('core-script'))!!}
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
{!!Theme::partial('formSearch', array('region' => $region))!!}

	<div class="pageheader">
		<meta itemprop="mainEntityOfPage" content="http://{{$domainName}}.{{config('app.url')}}">
		<meta itemprop="headline" content="{!!Theme::get('title')!!}"> 
		<h1>@if(!empty($site->domain_image))<img class="lazy" width="50" src="{{$site->domain_image}}">@else<img class="lazy" src="https://www.google.com/s2/favicons?domain={!!$domainName!!}" alt="{!!$domainName!!}" title="{!!$domainName!!}"> @endif {!!$domainName!!}</h1>
		<p><small>{{$site->view}} views <span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($site->updated_at,'en')!!}</span></small></p>
		@if(!empty($site->attribute['rank']))
			<p>Rank <span class="label label-primary"><i class="glyphicon glyphicon-globe"></i> {{Site::price($site->attribute['rank'])}}</span> 
				@if(!empty($site->attribute['country_code'])&&!empty($site->attribute['rank_country']))
					<?
						$getRegion=\App\Model\Regions::where('iso','=',$site->attribute['country_code'])->first(); 
					?>
					@if(!empty($getRegion->id))
						<span class="label label-primary"><i class="flag flag-{{mb_strtolower($getRegion->iso)}}"></i> {{$getRegion->country}} {{Site::price($site->attribute['rank_country'])}}</span>
					@endif
				@endif
			</p>
		@endif
		@if(!empty($site->title))<h2 class="subtitle mb5"><strong>{!!str_limit($site->title, 100)!!}</strong></h2>@endif
		<span>{!! str_limit(Theme::get('description'), 200) !!}</span>
	</div>
	<div class="contentpanel section-content">
		<div class="row row-pad-5">
			<div class="col-md-8">
				@if($site->attribute['ads']=='active' || $site->attribute['ads']=='pending')
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
				<div class="form-group">
					<a class="btn btn-lg btn-primary btn-block siteLink" href="http://{{$domainName}}.{{config('app.url')}}" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode("http://".$domainName))))}}' target="_blank">Visit to site » click here</a>
				</div>
				<div class="keySearch articleBody" itemprop="articleBody">
					@if(!empty($domainContent->basic_info))
					<ul class="nav nav-tabs">
						<li class="active"><a href="#basicInfo" data-toggle="tab"><strong>Basic</strong></a></li>
						<li><a href="#info" data-toggle="tab"><strong>Website</strong></a></li>
						<li class=""><a href="#SemRush" data-toggle="tab"><strong>SemRush Metrics</strong></a></li>
						<li class=""><a href="#dns" data-toggle="tab"><strong>DNS Report</strong></a></li>
						<li class=""><a href="#ipAddress" data-toggle="tab"><strong>IP</strong></a></li>
						<li class=""><a href="#whois" data-toggle="tab"><strong>Whois</strong></a></li>
					</ul>
					<div class="tab-content mb10">
						@if(!empty($domainContent->basic_info))
						<div class="panel tab-pane active" id="basicInfo">
							@if($site->attribute['ads']=='active' || $site->attribute['ads']=='pending')
								<div class="row">
									<div class="col-md-6">
										<ins class="adsbygoogle"
											 style="display:block"
											 data-ad-client="ca-pub-6739685874678212"
											 data-ad-slot="7536384219"
											 data-ad-format="auto"></ins>
										<script>
										(adsbygoogle = window.adsbygoogle || []).push({});
										</script>
									</div>
									<div class="col-md-6">
										{!!$domainContent->basic_info!!}
									</div>
								</div>
							@else 
								{!!$domainContent->basic_info!!}
							@endif
						</div>
						@endif
						@if(!empty($domainContent->website_info))
						<div class="panel tab-pane" id="info">
							{!!$domainContent->website_info!!}
						</div>
						@endif
						@if(!empty($domainContent->semrush_metrics))
						<div class="panel tab-pane" id="SemRush">
							{!!$domainContent->semrush_metrics!!}
						</div>
						@endif
						@if(!empty($domainContent->dns_report))
						<div class="panel tab-pane" id="dns">
							{!!$domainContent->dns_report!!}
						</div>
						@endif
						@if(!empty($domainContent->ip_address_info))
						<div class="panel tab-pane" id="ipAddress">
							{!!$domainContent->ip_address_info!!}
						</div>
						@endif
						@if(!empty($domainContent->whois_record))
						<div class="panel tab-pane" id="whois">
							{!!$domainContent->whois_record!!}
						</div>
						@endif
					</div>
					@endif
					@if(count($domainSearch)>0)
						@if($site->attribute['ads']!='disable')
						{!!Theme::partial('search.search_4', array('domainSearch'=>$domainSearch,'ads'=>'true'))!!} 
						@else 
							{!!Theme::partial('search.search_4', array('domainSearch'=>$domainSearch,'ads'=>'false'))!!} 
						@endif
					@endif
					@if(count($affiliate)>0)
						<div class="form-group">
						{!!Theme::partial('affiliate.affiliate_2', array('affiliateSearch' => $affiliate))!!}
						</div>
					@endif
					@if(count($postsSearch)>0)
						<div class="mt5">
							<?
								$idPost=array(); 
								$i=0; 
							?>
							@foreach($postsSearch as $item)
								<?
									array_push($idPost,$item->id); 
								?>
							@endforeach 
							<?
								$getPost = Cache::store('file')->remember('domain_getPost_search_'.$site->id, 500, function() use($idPost)
								{
									return \App\Model\Posts::where('posts_status','=','active')->whereIn('id',$idPost)->limit(6)->get(); 
								});
							?> 
							@if(count($getPost)>0) 
								@foreach($getPost->chunk(3) as $chunk)
								{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!}
								@endforeach
							@endif
						</div>
					@endif
				</div>
				<div class="panel panel-primary form-group">
					<div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> News</div>
					<div class="list-group">
						@foreach($news as $postRelate)
							@if(!empty($postRelate->image))
								<div class="list-group-item form-group">
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
										<a class="image" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">
											<img src="{{$postRelate->image}}" class="img-responsive img-thumbnail lazy" alt="{!!$postRelate->title!!}" title="" >
										</a>
									</div>
									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
										<h5 class="postTitle nomargin"><a class="title" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
										<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> <small><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($postRelate->joinField->SolrID)))}}"><span itemprop="name">{!!$postRelate->joinField->name!!}</span></a></small>
									</div>
								</div>
							@else 
								<div class="list-group-item form-group">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h5 class="postTitle"><a class="title" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
										<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> <small><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($postRelate->joinField->SolrID)))}}"><span itemprop="name">{!!$postRelate->joinField->name!!}</span></a></small>
									</div>
								</div>
							@endif
						@endforeach
					</div>
				</div> 
			</div>
			<div class="col-md-4">
				@if($site->attribute['ads']=='active' || $site->attribute['ads']=='pending')
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
				@if(count($newsSearch)>0)
					<div class="list-group">
						@foreach($newsSearch as $postRelate)
							@if(!empty($postRelate->image))
								<div class="list-group-item form-group">
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
										<a class="image" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">
											<img src="{{$postRelate->image}}" class="img-responsive img-thumbnail lazy" alt="{!!$postRelate->title!!}" title="" >
										</a>
									</div>
									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
										<h5 class="postTitle nomargin"><a class="title" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
										<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> <small><span class="text-danger">{{$postRelate->view}} lượt xem</span></small>  <small><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($postRelate->joinField->SolrID)))}}"><span itemprop="name">{!!$postRelate->joinField->name!!}</span></a></small>
									</div>
								</div>
							@else 
								<div class="list-group-item form-group">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<h5 class="postTitle"><a class="title" href="https://news-{{$postRelate->id}}.{{config('app.url')}}">{!!$postRelate->title!!}</a></h5>
										<small><span><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($postRelate->updated_at)!!}</span></small> <small><span class="text-danger">{{$postRelate->view}} lượt xem</span></small>  <small><a itemscope itemtype="http://schema.org/Thing"  itemprop="item" href="{{route('channel.slug',array($channel['domainPrimary'],Str::slug($postRelate->joinField->SolrID)))}}"><span itemprop="name">{!!$postRelate->joinField->name!!}</span></a></small>
									</div>
								</div>
							@endif
						@endforeach
					</div>
				@endif
				<div class="panel form-group">
					<div class="panel-heading"><h4 class="panel-title">Recently Analyzed websites</h4></div>
					<ul class="list-group">
						
						@foreach($getNewDomain as $siteList)
							<li class="list-group-item"><img src="https://www.google.com/s2/favicons?domain={{$siteList->domain}}" alt="{{$siteList->domain}}"> <a class="siteLink" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode("http://".$siteList->domain))))}}' href="http://{{$siteList->domain}}.{{config('app.url')}}">{{$siteList->domain}}</a> <small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($siteList->updated_at,'en')!!}</span> - {{$siteList->view}} views</small>@if(!empty($siteList->domain_title))<p>{{str_limit($siteList->domain_title, 100)}}</p>@endif
							@if(!empty($siteList->rank))<p>Rank <span class="label label-primary"><i class="glyphicon glyphicon-globe"></i> {{Site::price($siteList->rank)}}</span> @if(!empty($siteList->country_code)&&!empty($siteList->rank_country))
								<?
									$getRegion=\App\Model\Regions::where('iso','=',$siteList->country_code)->first(); 
								?>
								@if(!empty($getRegion->id))
									<span class="label label-primary"><i class="flag flag-{{mb_strtolower($getRegion->iso)}}"></i> {{$getRegion->country}} {{Site::price($siteList->rank_country)}}</span>
								@endif
							@endif</p>@endif
							</li>
						@endforeach
					</ul>
				</div>
				<div class="panel form-group">
					<div class="panel-heading"><h4 class="panel-title">Recently Viewed</h4></div>
					<ul class="list-group">
						
						@foreach($getNewDomainCreated as $siteList)
							<li class="list-group-item"><img src="https://www.google.com/s2/favicons?domain={{$siteList->domain}}" alt="{{$siteList->domain}}"> <a class="siteLink" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode("http://".$siteList->domain))))}}' href="http://{{$siteList->domain}}.{{config('app.url')}}">{{$siteList->domain}}</a> <small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($siteList->updated_at,'en')!!}</span> - {{$siteList->view}} views</small>@if(!empty($siteList->title))<p>{{str_limit($siteList->title, 100)}}</p>@endif
							@if(!empty($siteList->rank))<p>Rank <span class="label label-primary"><i class="glyphicon glyphicon-globe"></i> {{Site::price($siteList->rank)}}</span> @if(!empty($siteList->country_code)&&!empty($siteList->rank_country))
								<?
									$getRegion=\App\Model\Regions::where('iso','=',$siteList->country_code)->first(); 
								?>
								@if(!empty($getRegion->id))
									<span class="label label-primary"><i class="flag flag-{{mb_strtolower($getRegion->iso)}}"></i> {{$getRegion->country}} {{Site::price($siteList->rank_country)}}</span>
								@endif
							@endif</p>@endif
							</li>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</div>
</div><!-- mainpanel -->
</section>
<div id="modalYoutube" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?

	$dependencies = array(); 
	Theme::asset()->writeScript('custom',' 
		/*$(".htag").each(function() {
			$(this).text($(this).text());
		});*/
		$(".siteLink").click(function(){
			window.open(jQuery.parseJSON($(this).attr("data-url")),"_blank")
			return false; 
		});
		$(".playVideoYoutube").click(function(){
			var idVideo=$(this).attr("data-id"); 
			$("#modalYoutube .modal-title").empty(); 
			$("#modalYoutube .modal-body").empty(); 
			$("#modalYoutube .modal-title").html($(this).attr("data-title")); 
			$("#modalYoutube .modal-body").append("<div class=\"embed-responsive embed-responsive-16by9\"><iframe class=\"embed-responsive-item\" src=\"//www.youtube.com/embed/"+idVideo+"\"></iframe></div>"); 
			$("#modalYoutube").modal("show"); 
			return false; 
		});
	', $dependencies);
?>
<?
	$dependencies = array(); 
	$channel['theme']->asset()->writeScript('loadLazy','
		$(function() {
			$(".lazy").lazy();
		}); 
		var swiper = new Swiper(".swiper-container", {
			slidesPerView: 2,
			slidesPerColumn: 1,
			spaceBetween: 20,
			navigation: {
				nextEl: ".carousel_control_right",
				prevEl: ".carousel_control_left",
			},
			autoplay: {
                        delay: 5000,
                      },
		});
	', $dependencies);
?>