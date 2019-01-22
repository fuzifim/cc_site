<?
	
	if(!empty($site->domain_title)){
		Theme::setTitle($domainName.': '.str_limit(strip_tags($site->domain_title,''), 100));
	}else{
		Theme::setTitle($domainName);
	}
	if(!empty($site->domain_keywords)){
		Theme::setKeywords(strip_tags($site->domain_keywords,''));
	}
	if(!empty($site->domain_description)){
		Theme::setDescription(strip_tags($site->domain_description,"")); 
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
		@if(!empty($site->rank))
			<p>Rank <span class="label label-primary"><i class="glyphicon glyphicon-globe"></i> {{Site::price($site->rank)}}</span> 
				@if(!empty($site->country_code) && !empty($site->rank_country))
					<?
						$getRegion=\App\Model\Regions::where('iso','=',$site->country_code)->first(); 
					?>
					@if(!empty($getRegion->id))
						<span class="label label-primary"><i class="flag flag-{{mb_strtolower($getRegion->iso)}}"></i> {{$getRegion->country}} {{Site::price($site->rank_country)}}</span>
					@endif
				@endif 
			</p>
		@endif
		@if(!empty($site->domain_title))<h2 class="subtitle mb5"><strong>{!!str_limit($site->domain_title, 100)!!}</strong></h2>@endif
		<span>{!! str_limit(Theme::get('description'), 200) !!}</span>
	</div>
	<div class="contentpanel section-content">
		<div class="row row-pad-5">
			<div class="col-md-9">
				<div class="form-group">
					<a class="btn btn-lg btn-primary btn-block siteLink" href="http://{{$domainName}}.{{config('app.url')}}" data-url='{{json_encode(route("go.to.url",array(config("app.url"),urlencode("http://".$domainName))))}}' target="_blank">Visit to site » click here</a>
				</div>
				<div class="keySearch articleBody" itemprop="articleBody">
					@if(!empty($domainWhois))
						<?
						$decodeWhois=json_decode($domainWhois); 
						?>
						@if(count($decodeWhois)>0)
						<p class="alert alert-info"><strong>{{$domainName}}</strong>@if(!empty($decodeWhois->creationDate)) created at {{$decodeWhois->creationDate}}  @endif @if(!empty($decodeWhois->expirationDate)) and expiration date {{$decodeWhois->expirationDate}}. @endif @if(!empty($decodeWhois->registrar)) Registrar <strong><a class="" target="_blank" href="{{route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($decodeWhois->registrar)))}}">{!!$decodeWhois->registrar!!}</a></strong>.@endif @if(!empty($decodeWhois->nameServer)) Name server: @if(!empty($decodeWhois->nameServer[0])){{$decodeWhois->nameServer[0]}}@endif @if(!empty($decodeWhois->nameServer[1]))and {{$decodeWhois->nameServer[1]}}@endif @endif. @if(!empty($site->rank)) It has a global traffic rank of {{Site::price($site->rank)}} in the world @if(!empty($site->country_code) && !empty($site->rank_country)) and rank at <strong>{{$getRegion->country}}</strong> of {{Site::price($site->rank_country)}}@endif @endif @if(!empty($domain_ip)), ip address {{$domain_ip}}@endif</p>
						@endif
					@endif
					@if($site->ads!='disable')
					<div class="form-group mt5">
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
					@if(count($affiliate)>0)
						{!!Theme::partial('affiliate.affiliate_2', array('affiliateSearch' => $affiliate))!!}
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
								$getPost=\App\Model\Posts::where('posts_status','=','active')->whereIn('id',$idPost)->limit(6)->get(); 
							?> 
							@if(count($getPost)>0) 
								@foreach($getPost->chunk(3) as $chunk)
								{!!Theme::partial('listPostChannel', array('chunk' => $chunk))!!}
								@endforeach
							@endif
						</div>
					@endif
					@if(count($newsSearch)>0)
						<div class="row">
							<div class="col-md-4">
								<div class="panel panel-default form-group">
									<div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> News Relate</div>
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
									<div class="panel-footer text-center">
										<a href="#" class="view-more-ads"><i class="glyphicon glyphicon-chevron-down"></i> Hiển thị thêm</a>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								@if(count($domainSearch)>0)
									<div class="form-group mt5">
										@if($site->ads!='disable')
										{!!Theme::partial('search.search_4', array('domainSearch'=>$domainSearch,'ads'=>'true'))!!} 
										@else 
											{!!Theme::partial('search.search_4', array('domainSearch'=>$domainSearch,'ads'=>'false'))!!} 
										@endif
									</div>
								@endif
								@if(is_array($postSearch) && count($postSearch)>0)
									<ul class="list-group">
										<?
											$i=0;
										?>
										@foreach($postSearch as $key)
											@if(!empty($key['title']))
											<li class="list-group-item">
												<h4 class="blog-title headTitle"><strong>
												@if(strlen($key['title'])>4 && !WebService::is_valid_url($key['title']) && !empty($key['domainRegister']) && $key['domainRegister']!=config('app.url'))
													<a class="text-info siteLink" data-url='@if(!empty($key["linkFull"])){{json_encode(route("go.to.url",array(config("app.url"),urlencode($key["linkFull"]))))}}@endif' target="_blank" href="http://{{$key['domainRegister']}}.{{config('app.url')}}">{!!$key['title']!!}</a>
												@else
													{!!$key['title']!!}
												@endif
												</strong></h4>
												@if(!empty($key['domainRegister']))
													<span class=""><img src="https://www.google.com/s2/favicons?domain={{$key['domainRegister']}}" alt="{{$key['domainRegister']}}" title="{{$key['domainRegister']}}"> <a class="text-danger siteLink" target="_blank" data-url='@if(!empty($key["linkFull"])){{json_encode(route("go.to.url",array(config("app.url"),urlencode($key["linkFull"]))))}}@endif' href="http://{{$key['domainRegister']}}.{{config('app.url')}}">{{$key['domainRegister']}}</a></span>
												@endif 
												<p>@if(!empty($key['image']))<img src="{{$key['image']}}">@endif   @if(!empty($key['description'])){!!$key['description']!!}@endif</p>
												</li>
											@endif
											@if($i==1)
												@if(is_array($videoSearch) && count($videoSearch)>0)
													<div id="videoYoutube" class="swiper-container list-group-item">
														<div class="panel-heading"><div class="panel-title"><i class="glyphicon glyphicon-facetime-video"></i> Videos related</div></div>
														<div class="swiper-wrapper">
															<? $y=0; ?>
															@foreach($videoSearch as $video)
															<? $y++;?>
															<div class="swiper-slide @if($y==1) active @endif">
																<div class="groupThumb pointer playVideoYoutube" style="position:relative;" data-id="{{$video->videoId}}" data-title="{{$video->title}}">
																	<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
																	<img itemprop="image" class="img-responsive lazy" src="https://i.ytimg.com/vi/{{$video->videoId}}/hqdefault.jpg" alt="{{$video->title}}">
																</div>
																<p class="text-center">{!!$video->title!!}</p>
															</div>
															@endforeach
															
														</div>
														@if(count($videoSearch)>=2)
														<?
															$dependencies = array(); 
															$channel['theme']->asset()->writeScript('groupCarouselControl', '
																jQuery(document).ready(function(){
																"use strict"; 
																$(".groupCarouselControl").show(); 
															});
															', $dependencies);
														?>
														@else
															<?
																$dependencies = array(); 
																$channel['theme']->asset()->writeScript('groupCarouselControl', '
																	jQuery(document).ready(function(){
																	"use strict"; 
																	$(".groupCarouselControl").hide(); 
																});
																', $dependencies);
															?>
														@endif
														<div class="groupCarouselControl">
															<a class="left carousel-control carousel_control_left" href="#videoYoutube" role="button" data-slide="prev">
																<span class="fa fa-chevron-left" aria-hidden="true"></span>
																<span class="sr-only">Previous</span>
															</a>
															<a class="right carousel-control carousel_control_right" href="#videoYoutube" role="button" data-slide="next">
																<span class="fa fa-chevron-right" aria-hidden="true"></span>
																<span class="sr-only">Next</span>
															</a>
														</div>
													</div>
												@endif
											@endif
											<? $i++; ?>
										@endforeach
									</ul>
								@endif
							</div>
						</div>
					@else
						@if(count($domainSearch)>0)
							<div class="form-group mt5">
								@if($site->ads!='disable')
								{!!Theme::partial('search.search_4', array('domainSearch'=>$domainSearch,'ads'=>'true'))!!} 
								@else 
									{!!Theme::partial('search.search_4', array('domainSearch'=>$domainSearch,'ads'=>'false'))!!} 
								@endif
							</div>
						@endif
						@if(is_array($postSearch) && count($postSearch)>0)
							<ul class="list-group">
								<?
									$i=0;
								?>
								@foreach($postSearch as $key)
									@if(!empty($key['title']))
									<li class="list-group-item">
										<h4 class="blog-title headTitle"><strong>
										@if(strlen($key['title'])>4 && !WebService::is_valid_url($key['title']) && !empty($key['domainRegister']) && $key['domainRegister']!=config('app.url'))
											<a class="text-info siteLink" data-url='@if(!empty($key["linkFull"])){{json_encode(route("go.to.url",array(config("app.url"),urlencode($key["linkFull"]))))}}@endif' target="_blank" href="http://{{$key['domainRegister']}}.{{config('app.url')}}">{!!$key['title']!!}</a>
										@else
											{!!$key['title']!!}
										@endif
										</strong></h4>
										@if(!empty($key['domainRegister']))
											<span class=""><img src="https://www.google.com/s2/favicons?domain={{$key['domainRegister']}}" alt="{{$key['domainRegister']}}" title="{{$key['domainRegister']}}"> <a class="text-danger siteLink" target="_blank" data-url='@if(!empty($key["linkFull"])){{json_encode(route("go.to.url",array(config("app.url"),urlencode($key["linkFull"]))))}}@endif' href="http://{{$key['domainRegister']}}.{{config('app.url')}}">{{$key['domainRegister']}}</a></span>
										@endif 
										<p>@if(!empty($key['image']))<img src="{{$key['image']}}">@endif   @if(!empty($key['description'])){!!$key['description']!!}@endif</p>
										</li>
									@endif
									@if($i==1)
										@if(is_array($videoSearch) && count($videoSearch)>0)
											<div id="videoYoutube" class="swiper-container list-group-item">
												<div class="panel-heading"><div class="panel-title"><i class="glyphicon glyphicon-facetime-video"></i> Videos related</div></div>
												<div class="swiper-wrapper">
													<? $y=0; ?>
													@foreach($videoSearch as $video)
													<? $y++;?>
													<div class="swiper-slide @if($y==1) active @endif">
														<div class="groupThumb pointer playVideoYoutube" style="position:relative;" data-id="{{$video->videoId}}" data-title="{{$video->title}}">
															<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
															<img itemprop="image" class="img-responsive lazy" src="https://i.ytimg.com/vi/{{$video->videoId}}/hqdefault.jpg" alt="{{$video->title}}">
														</div>
														<p class="text-center">{!!$video->title!!}</p>
													</div>
													@endforeach
													
												</div>
												@if(count($videoSearch)>=2)
												<?
													$dependencies = array(); 
													$channel['theme']->asset()->writeScript('groupCarouselControl', '
														jQuery(document).ready(function(){
														"use strict"; 
														$(".groupCarouselControl").show(); 
													});
													', $dependencies);
												?>
												@else
													<?
														$dependencies = array(); 
														$channel['theme']->asset()->writeScript('groupCarouselControl', '
															jQuery(document).ready(function(){
															"use strict"; 
															$(".groupCarouselControl").hide(); 
														});
														', $dependencies);
													?>
												@endif
												<div class="groupCarouselControl">
													<a class="left carousel-control carousel_control_left" href="#videoYoutube" role="button" data-slide="prev">
														<span class="fa fa-chevron-left" aria-hidden="true"></span>
														<span class="sr-only">Previous</span>
													</a>
													<a class="right carousel-control carousel_control_right" href="#videoYoutube" role="button" data-slide="next">
														<span class="fa fa-chevron-right" aria-hidden="true"></span>
														<span class="sr-only">Next</span>
													</a>
												</div>
											</div>
										@endif
									@endif
									<? $i++; ?>
								@endforeach
							</ul>
						@endif
					@endif
				</div>
			</div>
			<div class="col-md-3">
				@if(!empty($domain_h1) && count($domain_h1)>0 || !empty($domain_h2) && count($domain_h2)>0 || !empty($domain_h3) && count($domain_h3)>0 || !empty($domain_h4) && count($domain_h4))
				<div class="panel panel-default form-group">
					<div class="panel-heading"><h5 class="panel-title">H TAGS ({{$domainName}})</h5></div>
					<div class="panel-body table-responsive wordWrap">
						@if(!empty($domain_h1) && count($domain_h1)>0)
							<?
								$ih1=0;
							?>
							<span class="badge badge-primary">H1</span> 
							<p>@foreach($domain_h1 as $h1)@if(!empty($h1) && $ih1<=10 && strlen($h1)>4  && strlen($h1)<=100 && !WebService::is_valid_url($h1))<span class="htag">{{strip_tags($h1,'')}}</span>@endif <?$ih1++?> @endforeach</p>
						@endif
						@if(!empty($domain_h2) && count($domain_h2)>0)
							<?
								$ih2=0;
							?>
							<span class="badge badge-primary">H2</span> 
							<p>@foreach($domain_h1 as $h2)@if(!empty($h2) && $ih2<=10 && strlen($h2)>4  && strlen($h2)<=100 && !WebService::is_valid_url($h2))<span class="htag">{{strip_tags($h2,'')}}</span>@endif <?$ih2++?> @endforeach</p>
						@endif
						@if(!empty($domain_h3) && count($domain_h3)>0)
							<?
								$ih3=0;
							?>
							<span class="badge badge-primary">H3</span> 
							<p>@foreach($domain_h3 as $h3)@if(!empty($h3) && $ih3<=10 && strlen($h3)>4  && strlen($h3)<=100 && !WebService::is_valid_url($h3))<span class="htag">{{strip_tags($h3,'')}}</span>@endif <?$ih3++?> @endforeach</p>
						@endif
						@if(!empty($domain_h4) && count($domain_h4)>0)
							<?
								$ih4=0;
							?>
							<span class="badge badge-primary">H4</span> 
							<p>@foreach($domain_h4 as $h4)@if(!empty($h4) && $ih4<=10 && strlen($h4)>4  && strlen($h4)<=100 && !WebService::is_valid_url($h4))<span class="htag">{{strip_tags($h4,'')}}</span>@endif <?$ih4++?> @endforeach</p>
						@endif
					</div>
				</div>
				@endif
				@if(!empty($domain_a) && count($domain_a)>0)
				<?
					$i=0; 
				?>
				<div class="panel form-group">
					<div class="panel-heading"><h5 class="panel-title">KEYWORDS ANALYSIS</h5></div>
					<div class="panel-body table-responsive">
						@foreach($domain_a as $linkATag)
							@if(!empty($linkATag->text) && strlen($linkATag->text)>5 && strlen($linkATag->link)>10 && strlen($linkATag->text)<=100)
								<?
									$to_replace = array('.html', '.php','/','index.php','mailto:','-','_');
									$replace_with = array(' ', ' ',' ',' ',' mailto ',', ',' '); 
									$i++; 
								?>
								@if($i<=10 && !WebService::is_valid_url($linkATag->text))
									<span class="htag">{!!strip_tags($linkATag->text,'')!!}</span>
								@endif
							@endif
						@endforeach
					</div>
				</div>
				@endif
				@if(!empty($domain_ip))
				<div class="panel form-group">
					<div class="panel-heading"><h5 class="panel-title">IP Address Information</h5></div>
					<div class="panel-body">
						@if(count($ip_detail)>0)
							<p><span class="badge badge-secondary">Server ip: </span> {!!$domain_ip!!}</p>
							@if(!empty($ip_detail->as))<p><span class="badge badge-secondary">as: </span> {!!$ip_detail->as!!}</p>@endif
							@if(!empty($ip_detail->city))<p><span class="badge badge-secondary">City: </span> {!!$ip_detail->city!!}</p>@endif
							@if($ip_detail->country)<p><span class="badge badge-secondary">Country: </span> {!!$ip_detail->country!!}</p>@endif
							@if(!empty($ip_detail->isp))<p><span class="badge badge-secondary">Isp: </span> {!!$ip_detail->isp!!}</p>@endif
							@if($ip_detail->regionName)<p><span class="badge badge-secondary">Region Name: </span> {!!$ip_detail->regionName!!}</p>@endif
						@endif
					</div>
				</div>
				@endif
				<div class="panel form-group">
					<div class="panel-heading"><h4 class="panel-title">Site new updated</h4></div>
					<ul class="list-group">
						
						@foreach($getSite as $siteList)
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