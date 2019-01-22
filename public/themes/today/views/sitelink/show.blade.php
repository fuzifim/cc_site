<?
	
	if(!empty($site->title->attribute_value)){
		Theme::setTitle($site->domain.': '.str_limit($site->title->attribute_value, 100));
	}else{
		Theme::setTitle($site->domain);
	}
	if(!empty($site->keywords->attribute_value)){
		Theme::setKeywords($site->keywords->attribute_value);
	}
	if(!empty($site->description->attribute_value)){
		Theme::setDescription(strip_tags($site->description->attribute_value,"")); 
	}
	if(!empty($site->image->attribute_value)){
		Theme::setImage($site->image->attribute_value); 
	}
?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
  <a class="navbar-brand" href="http://cungcap.net" title="Cung Cấp"><img class="" id="logoChannel" src="http://cungcap.net/assets/img/logo-small.png" alt="Cung Cấp" title="Cung Cấp"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
	<ul class="navbar-nav mr-auto">
	  <li class="nav-item active">
		<a class="nav-link" href="http://{{$site->domain}}.{{config('app.url')}}">{{$site->domain}}</a>
	  </li>
	</ul>
	<form class="form-inline mt-2 mt-md-0">
	  <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
	  <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
	</form>
  </div>
</nav>
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<main role="main" class="container" itemscope="" itemtype="http://schema.org/Article">
	<div class="form-group">
		<!-- Ad News -->
		<ins class="adsbygoogle"
			 style="display:block"
			 data-ad-client="ca-pub-6739685874678212"
			 data-ad-slot="7536384219"
			 data-ad-format="auto"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	</div>
  <div class="jumbotron table-responsive">
	<h1 class="headTitle" itemprop="name">{{$site->domain}}</h1>
	<meta itemprop="description" content="{!! str_limit(Theme::get('description'), 200) !!}">
	<meta itemprop="mainEntityOfPage" content="http://{{$site->domain}}.{{config('app.url')}}">
	<meta itemprop="headline" content="{!!Theme::get('title')!!}"> 
	<small>{{$site->view}} views <span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($site->updated_at,'en')!!}</span></small>
	@if(!empty($site->rank))
		<p>Rank <span class="label label-primary"><i class="glyphicon glyphicon-globe"></i> {{Site::price($site->rank)}}</span> 
			@if(!empty($site->country_code)&&!empty($site->rank_country))
				<?
					$getRegion=\App\Model\Regions::where('iso','=',$site->country_code)->first(); 
				?>
				@if(!empty($getRegion->id))
					<span class="label label-primary"><i class="flag flag-{{mb_strtolower($getRegion->iso)}}"></i> {{$getRegion->country}} {{Site::price($site->rank_country)}}</span>
				@endif
			@endif 
		</p>
	@endif
	@if(!empty($site->title->attribute_value))<h2>{!!str_limit($site->title->attribute_value, 100)!!}</h2>@endif
	<p class="lead">@if(!empty($site->image->attribute_value))<img class="" width="150" align="left" src="{{$site->image->attribute_value}}">@endif {!! str_limit(Theme::get('description'), 200) !!}</p>
	@if(!empty($site->keywords->attribute_value))<p>{{$site->keywords->attribute_value}}</p>@endif
	<div class="form-group">
		<!-- Ad News -->
		<ins class="adsbygoogle"
			 style="display:block"
			 data-ad-client="ca-pub-6739685874678212"
			 data-ad-slot="7536384219"
			 data-ad-format="auto"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
	</div>
	<button class="btn btn-lg btn-primary btn-block clickLink" type="button" data-src="http://{{$site->domain}}" role="button">Visit site » click here</button>
  </div>
  <div class="row">
			<div class="col-md-9">
				<div class="keySearch articleBody" itemprop="articleBody">
					@if(count($keySearch)>0)
						<h4 class="card-title">Search related to {{$site->domain}}</h4>
						<p><small>Created at {{WebService::time_request($site->postSearch->attribute_created_at,'en')}}</small></p>
						<ul class="list-group">
						<?
							$i=0;
						?>
						@foreach($keySearch as $key)
							@if($i==2 && $i<3 && !empty($site->videoSearch->attribute_value))
								<?
									$videoDecode=json_decode($site->videoSearch->attribute_value); 
								?>
								@if($site->videoSearch->search_by=='youtube')
									<div id="videoYoutube" class="carousel slide" data-ride="carousel">
										<div class="carousel-inner">
											<? $y=0; ?>
											@foreach($videoDecode as $video)
											<? $y++;?>
											<div class="carousel-item @if($y==1) active @endif">
												<h4>{{$video->title}}</h4>
												<div class="groupThumb pointer playVideoYoutube" style="position:relative;" data-id="{{$video->videoId}}" data-title="{{$video->title}}">
													<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
													<img itemprop="image" class="img-responsive lazy" src="https://i.ytimg.com/vi/{{$video->videoId}}/hqdefault.jpg" alt="{{$video->title}}">
												</div>
											</div>
											@endforeach
											
										</div>
										@if(count($videoDecode)>=2)
										<?
											$dependencies = array(); 
											Theme::asset()->writeScript('groupCarouselControl', '
												jQuery(document).ready(function(){
												"use strict"; 
												$(".groupCarouselControl").show(); 
											});
											', $dependencies);
										?>
										@else
											<?
												$dependencies = array(); 
												Theme::asset()->writeScript('groupCarouselControl', '
													jQuery(document).ready(function(){
													"use strict"; 
													$(".groupCarouselControl").hide(); 
												});
												', $dependencies);
											?>
										@endif
										<div class="groupCarouselControl">
											<a class="carousel-control-prev" href="#videoYoutube" data-slide="prev">
											<span class="carousel-control-prev-icon"></span>
										  </a>
										  <a class="carousel-control-next" href="#videoYoutube" data-slide="next">
											<span class="carousel-control-next-icon"></span>
										  </a>
										</div>
									</div>
								@endif
							@endif
							@if($i==3 && $i<4)
								<div class="list-group-item">
									<!-- Ad News -->
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
							<li class="list-group-item">
							<h4 class="blog-title headTitle"><span class="text-info clickLinkSearch pointer nopadding"  role="button" data-src="@if(!empty($key['linkFull'])){{$key['linkFull']}}@endif" id="{{Str::slug($key['title'])}}">@if(!empty($key['domainRegister']))<img src="https://www.google.com/s2/favicons?domain={{$key['domainRegister']}}" alt="{{$key['domainRegister']}}" title="{{$key['domainRegister']}}">@endif <strong>{!!$key['title']!!}</strong></span></h4><p>@if(!empty($key['image']))<img src="{{$key['image']}}">@endif   @if(!empty($key['description'])){!!$key['description']!!}@endif @if(!empty($key['domainRegister']))<br><span class=""><a class="text-danger clickPrimaryLink" target="_blank" data-src="@if(!empty($key['linkFull'])){{$key['linkFull']}}@endif" href="http://{{$key['domainRegister']}}.{{config('app.url')}}"><small>{{$key['domainRegister']}}</small></a></span>@endif</p>
							</li>
							<? $i++; ?>
						@endforeach
						</ul>
					@endif
				</div>
			</div>
			<div class="col-md-3">
			@if(!empty($site->h1tag->attribute_value) || !empty($site->h2tag->attribute_value) || !empty($site->h3tag->attribute_value) || !empty($site->h4tag->attribute_value))
				<div class="card form-group">
					<div class="card-body table-responsive">
						<h5 class="card-title">H TAGS ({{$site->domain}})</h5>
						@if(!empty($site->h1tag->attribute_value))
							<?
								$getH1=json_decode($site->h1tag->attribute_value); 
								$ih1=0;
							?>
							@if(count($getH1)>0)
							<span class="badge badge-primary">H1</span> 
							<p>@foreach($getH1 as $h1)@if($ih1<=10 && strlen($h1)>4  && strlen($h1)<=100 && !WebService::is_valid_url($h1))<span><a class="badge badge-secondary" target="_blank" href="{{route('keyword.show',array(config('app.url'),urlencode(str_replace(' ', '+', $h1))))}}">{{$h1}}</a></span>@endif <?$ih1++?> @endforeach</p>
							@endif
						@endif
						@if(!empty($site->h2tag->attribute_value))
							<?
								$getH2=json_decode($site->h2tag->attribute_value); 
								$ih2=0;
							?>
							@if(count($getH2)>0)
							<span class="badge badge-primary">H2</span> 
							<p>@foreach($getH2 as $h2)@if($ih2<=10 && strlen($h2)>4  && strlen($h2)<=100 && !WebService::is_valid_url($h2))<span><a class="badge badge-secondary" target="_blank" href="{{route('keyword.show',array(config('app.url'),urlencode(str_replace(' ', '+', $h2))))}}">{{$h2}}</a></span>@endif <?$ih2++?> @endforeach</p>
							@endif
						@endif
						@if(!empty($site->h3tag->attribute_value))
							<?
								$getH3=json_decode($site->h3tag->attribute_value); 
								$ih3=0;
							?>
							@if(count($getH3)>0)
							<span class="badge badge-primary">H3</span> 
							<p>@foreach($getH3 as $h3)@if($ih3<=10 && strlen($h3)>4  && strlen($h3)<=100 && !WebService::is_valid_url($h3))<span><a class="badge badge-secondary" target="_blank" href="{{route('keyword.show',array(config('app.url'),urlencode(str_replace(' ', '+', $h3))))}}">{{$h3}}</a></span>@endif <?$ih3++?> @endforeach</p>
							@endif
						@endif
						@if(!empty($site->h4tag->attribute_value))
							<?
								$getH4=json_decode($site->h4tag->attribute_value); 
								$ih4=0;
							?>
							@if(count($getH4)>0)
							<span class="badge badge-primary">H4</span> 
							<p>@foreach($getH4 as $h4)@if($ih4<=10 && strlen($h4)>4  && strlen($h4)<=100 && !WebService::is_valid_url($h4))<span><a class="badge badge-secondary" target="_blank" href="{{route('keyword.show',array(config('app.url'),urlencode(str_replace(' ', '+', $h4))))}}">{{$h4}}</a></span>@endif <?$ih4++?> @endforeach</p>
							@endif
						@endif
					</div>
				</div>
				@endif
				@if(!empty($site->atag->attribute_value))
				<?
					$getALink=json_decode($site->atag->attribute_value); 
					$i=0; 
				?>
				@if(count($getALink)>0)
				<div class="card form-group">
					<div class="card-body table-responsive">
						<h5 class="card-title">KEYWORDS ANALYSIS</h5>
						@foreach($getALink as $linkATag)
							@if(strlen($linkATag->text)>5 && strlen($linkATag->link)>10 && strlen($linkATag->text)<=100)
								<?
									$to_replace = array('.html', '.php','/','index.php','mailto:','-','_');
									$replace_with = array(' ', ' ',' ',' ',' mailto ',', ',' '); 
									$i++; 
								?>
							@if($i<=10 && !WebService::is_valid_url($linkATag->text))
							<a class="badge badge-secondary" target="_blank" href="{{route('keyword.show',array(config('app.url'),urlencode(str_replace(' ', '+', $linkATag->text))))}}">{!!$linkATag->text!!}</a> 
							@endif
							@endif
						@endforeach
					</div>
				</div>
				@endif
				@endif
				@if(!empty($site->ip->attribute_value))
				<div class="card form-group">
					<div class="card-body">
						<h5 class="card-title">IP INFO</h5>
						@if(!empty($site->ip->attribute_value))
							<?
								$opts = array(  
									'http'=>array(  
										'method'=>"GET",  
										'timeout'=>1, 
										'ignore_errors'=> true

									)  
								);
								$context = stream_context_create($opts);
								$getInfoIp=@file_get_contents('http://ip-api.com/json/'.$site->ip->attribute_value, false, $context); 
								$jsonDecodeIp=json_decode($getInfoIp); 
							?>
							@if(!empty($jsonDecodeIp->as))<p><span class="badge badge-secondary">as: </span> {!!$jsonDecodeIp->as!!}</p>@endif
							@if(!empty($jsonDecodeIp->city))<p><span class="badge badge-secondary">City: </span> {!!$jsonDecodeIp->city!!}</p>@endif
							@if($jsonDecodeIp->country)<p><span class="badge badge-secondary">Country: </span> {!!$jsonDecodeIp->country!!}</p>@endif
							@if(!empty($jsonDecodeIp->isp))<p><span class="badge badge-secondary">Isp: </span> {!!$jsonDecodeIp->isp!!}</p>@endif
							@if($jsonDecodeIp->regionName)<p><span class="badge badge-secondary">Region Name: </span> {!!$jsonDecodeIp->regionName!!}</p>@endif
						@endif
					</div>
				</div>
				@endif
				<h4 class="card-title">Site new updated</h4>
				<div class="panel panel-default">
					@foreach($getSite as $siteList)
						<li class="list-group-item"><img src="https://www.google.com/s2/favicons?domain={{$siteList->domain}}" alt="{{$siteList->domain}}"> <a href="http://{{$siteList->domain}}.{{config('app.url')}}">{{$siteList->domain}}</a> <small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($siteList->updated_at,'en')!!}</span> - {{$siteList->view}} views</small>@if(!empty($siteList->title->attribute_value))<p>{{str_limit($siteList->title->attribute_value, 100)}}</p>@endif</li>
					@endforeach
				</div>
			</div>
		</div>
</main>
<div id="modalYoutube" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
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
		$(".clickLink").click(function(){
			//console.log($(this).attr("data-src")); 
			window.open($(this).attr("data-src"),"_blank")
			return false; 
		}); 
		$(".keySearch .clickLinkSearch").click(function(){
			//console.log($(this).attr()); 
			window.open($(this).attr("data-src"),"_blank")
			return false; 
		});
		$(".keySearch .clickPrimaryLink").click(function(){
			//console.log($(this).attr()); 
			window.open($(this).attr("data-src"),"_blank")
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