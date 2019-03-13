<?
	$setKeyword=[]; 
	if(!empty($getKeyword->keyword)){
		Theme::setCanonical(route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($getKeyword->keyword)))); 
		Theme::setTitle(str_limit(str_replace('????','', $getKeyword->keyword), 100));
		Theme::setSearch($getKeyword->keyword); 
		array_push($setKeyword,$getKeyword->keyword);
	}
	if(!empty($getKeyword->description->content)){
		Theme::setKeywords($getKeyword->description->content);
	}
	if(!empty($getKeyword->image->content)){
		Theme::setImage($getKeyword->image->content); 
	}else{
		if(!empty($channel['info']->channelAttributeBanner[0]->media->media_name)){Theme::setImage(config('app.link_media').$channel['info']->channelAttributeBanner[0]->media->media_path.'thumb/'.$channel['info']->channelAttributeBanner[0]->media->media_name);} 
	} 
	//Theme::setNoindex('noindex');
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
?>

{!!Theme::asset()->container('footer')->usePath()->add('swiper.min', 'library/swiper/js/swiper.min.js', array('core-script'))!!}
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<section>
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
{!!Theme::partial('formSearch', array('region' => $channel['region']))!!}
	<div class="pageheader hidden-xs" itemscope itemtype="http://schema.org/NewsArticle">
		<h1 itemprop="name">{{str_replace('????','', $getKeyword->keyword)}}</h1>
		<p><small>{{$getKeyword->view}} views <span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($getKeyword->created_at,'en')!!}</span></small></p>
		<span>{!! str_limit(Theme::get('description'), 200) !!}</span>
	</div>
	<div class="contentpanel section-content">
		<div class="row row-pad-5">
			<div class="col-md-9">
				@if(count($affiliate)>0)
					<div id="proTop" class="swiper-container list-group-item">
						<div class="panel-heading"><div class="panel-title"><i class="glyphicon glyphicon-facetime-video"></i> Top Product</div></div>
						<div class="swiper-wrapper">
							<? $y=0; ?>
							@foreach($affiliate as $listItem)
							<? $y++;?>
							<div class="swiper-slide @if($y==1) active @endif listItem">
								<a class="image img-thumbnail siteLink" data-url='@if(!empty($listItem->deeplink)){{json_encode($listItem->deeplink)}}@endif' target="_blank" href="{{route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($listItem->title)))}}">
								<?
									if($listItem->campaign=='vuivui.com'){
										$linkImage=str_replace('https:///Products/Images/', 'https://cdn.tgdd.vn/Products/Images/', $listItem->image); 
									}else{
										$linkImage=$listItem->image; 
									}
								?>
								<img src="{{$linkImage}}" class="img-responsive imgThumb lazy" alt="{!!$listItem->title!!}" title="" >
								</a>
								<h2 class="blog-title mb10"><a class="title siteLink" data-url='@if(!empty($listItem->deeplink)){{json_encode($listItem->deeplink)}}@endif' target="_blank" href="{{route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($listItem->title)))}}"><span class="text-primary">{!!$listItem->title!!}</span></a></h2>
							</div>
							@endforeach
							
						</div>
						@if(count($affiliate)>=2)
						<?
							$dependencies = array(); 
							$channel['theme']->asset()->writeScript('groupCarouselControlPro', '
								jQuery(document).ready(function(){
								"use strict"; 
								$(".groupCarouselControlPro").show(); 
							});
							', $dependencies);
						?>
						@else
							<?
								$dependencies = array(); 
								$channel['theme']->asset()->writeScript('groupCarouselControlPro', '
									jQuery(document).ready(function(){
									"use strict"; 
									$(".groupCarouselControlPro").hide(); 
								});
								', $dependencies);
							?>
						@endif
						<div class="groupCarouselControlPro">
							<a class="left carousel-control carousel_control_left" href="#proTop" role="button" data-slide="prev">
								<span class="fa fa-chevron-left" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="right carousel-control carousel_control_right" href="#proTop" role="button" data-slide="next">
								<span class="fa fa-chevron-right" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>
					</div>
				@endif
				<div class="keySearch" itemprop="articleBody">
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="panel-title">Search related</div>
						</div>
						@if(count($getKeyword->joinPosts)>0)
							<ul class="list-group">
							@foreach($getKeyword->joinPosts()->paginate(5) as $joinPost)
								<?
								if($joinPost->post->postsJoinChannel->channel->domainJoinPrimary->domain->domain_primary!='default'){
									if(count($joinPost->post->postsJoinChannel->channel->domainAll)>0){
										foreach($joinPost->post->postsJoinChannel->channel->domainAll as $domain){
											if($domain->domain->domain_primary=='default'){
												$domainPrimary=$domain->domain->domain; 
											}
										}
									}else{
										$domainPrimary=$joinPost->post->postsJoinChannel->channel->domainJoinPrimary->domain->domain; 
									}
								}else{
									$domainPrimary=$joinPost->post->postsJoinChannel->channel->domainJoinPrimary->domain->domain; 
								}
								array_push($setKeyword,$joinPost->post->posts_title);
							?>
							<li class="row list-group-item">
								<h4 class="blog-title headTitle"><strong><a class="title" href="{{route('channel.slug',array($domainPrimary,$joinPost->post->getSlug->slug_value))}}" rel="nofollow" target="_blank"><span class="text-primary">{!!$joinPost->post->posts_title!!}</span></a></strong></h4>
								<span class=""><img src="https://www.google.com/s2/favicons?domain={{$domainPrimary}}" alt="{{$domainPrimary}}" title="{{$domainPrimary}}"> <a class="text-danger" target="_blank" href="{{route('channel.slug',array($domainPrimary,$joinPost->post->getSlug->slug_value))}}" rel="nofollow" target="_blank">{{$domainPrimary}}</a></span>
								<p>
								<a href="{{route('channel.slug',array($domainPrimary,$joinPost->post->getSlug->slug_value))}}" rel="nofollow" target="_blank">
									@if($joinPost->post->gallery[0]->media->media_storage=='youtube')
										<img src="//img.youtube.com/vi/{{$joinPost->post->gallery[0]->media->media_name}}/hqdefault.jpg" class="lazy padding5" alt="" title="" align="left"  width="100">
									@elseif($joinPost->post->gallery[0]->media->media_storage=='video')
										<div class="groupThumb" style="position:relative;">
											<span class="btnPlayVideoClick"><i class="glyphicon glyphicon-play"></i></span>
											<img itemprop="image" class="lazy padding5" alt="{{$joinPost->post->posts_title}}" src="{{config('app.link_media').$joinPost->post->gallery[0]->media->media_path.'xs/'.$joinPost->post->gallery[0]->media->media_id_random.'.png'}}" align="left"  width="100"/>
										</div>
									@elseif($joinPost->post->gallery[0]->media->media_storage=='files')
										<img src="{!!asset('assets/img/file.jpg')!!}" class="lazy padding5" alt="" align="left"  title=""  width="100">
									@else
										<img src="{{config('app.link_media').$joinPost->post->gallery[0]->media->media_path.'xs/'.$joinPost->post->gallery[0]->media->media_name}}" class="lazy padding5" alt="" align="left" title=""  width="100">
									@endif
								</a> 
								@if(strlen($joinPost->post->posts_description)>5){{WebService::limit_string(strip_tags(html_entity_decode($joinPost->post->posts_description),""), $limit = 200)}}@endif
								</p>
							</li>
							@endforeach
							</ul>
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
										<?
											array_push($setKeyword,$key['title']);
										?>
										<a class="text-info siteLink" data-url='@if(!empty($key["linkFull"])){{json_encode(route("go.to.url",array(config("app.url"),urlencode($key["linkFull"]))))}}@endif' target="_blank" href="{{route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($key['title'])))}}">{!!$key['title']!!}</a>
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
								@if($i==1 || $i==3)
									<div class="form-group">
										<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
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
								@if($i==1 && !empty($getKeyword->videoSearch->content))
									<?
										$videoDecode=json_decode($videoSearch); 
									?>
									@if(is_array($videoDecode) && count($videoDecode)>0)
										<div id="videoYoutube" class="swiper-container list-group-item">
											<div class="panel-heading"><div class="panel-title"><i class="glyphicon glyphicon-facetime-video"></i> Videos related</div></div>
											<div class="swiper-wrapper">
												<? $y=0; ?>
												@foreach($videoDecode as $video)
												<?
													array_push($setKeyword,$video->title);
												?>
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
											@if(count($videoDecode)>=2)
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
						@else
							<div class="alert alert-warning"><strong>{!!$getKeyword->keyword!!} not found!</strong> Please try again</div>
						@endif
					</div>
				</div>
			</div>
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading"><div class="panel-title">New keywords</div></div>
					<div class="list-group">
						@foreach($Keywords as $keywordRelate)
							<li class="list-group-item"><a href="{{route('keyword.show',array(config('app.url'),WebService::characterReplaceUrl($keywordRelate->keyword)))}}">{{str_replace('????','', $keywordRelate->keyword)}}</a> <small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($keywordRelate->created_at,'en')!!}</span> - views {{$keywordRelate->view}}</small></li>
						@endforeach
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="panel-heading"><div class="panel-title">Site new updated</div></div>
					<div class="list-group">
						@foreach($getSite as $site)
							<li class="list-group-item"><a href="http://{{$site->domain}}.{{config('app.url')}}">{{$site->domain}}</a> <small><span class="time-update"><i class="glyphicon glyphicon-time"></i> {!!WebService::time_request($site->updated_at,'en')!!}</span> - {{$site->view}} views</small>@if(!empty($site->title->attribute_value))<p>{{str_limit($site->title->attribute_value, 100)}}</p>@if(!empty($site->rank))<p>Rank <span class="label label-primary"><i class="glyphicon glyphicon-globe"></i> {{Site::price($site->rank)}}</span> @if(!empty($site->country_code)&&!empty($site->rank_country))
								<?
									$getRegion=\App\Model\Regions::where('iso','=',$site->country_code)->first(); 
								?>
								@if(!empty($getRegion->id))
									<span class="label label-primary"><i class="flag flag-{{mb_strtolower($getRegion->iso)}}"></i> {{$getRegion->country}} {{Site::price($site->rank_country)}}</span>
								@endif
							@endif</p>@endif @endif</li>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
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
	if(count($affiliate)<=0){
		/*$listAffiliate=[]; 
		$textRank=''; 
		foreach(array_unique($setKeyword) as $keyVal){
			$rake = \DonatelloZa\RakePlus\RakePlus::create($keyVal, 'en_US');
			$phrase_scores = $rake->sortByScore('desc')->scores(); 
			if(count($phrase_scores)>0){
				$keyValRe=array_slice($phrase_scores, 0);
				$textRank.=key($keyValRe).', ';
			}
		}
		$rake = \DonatelloZa\RakePlus\RakePlus::create($textRank, 'en_US');
		$phrase_scores = $rake->sortByScore('asc')->scores(); 
		$i=0; 
		foreach($phrase_scores as $key=>$phrase){
			$getSearch=\App\Model\Affiliate_feed::search($key)->chunk(1)->toArray(); 
			foreach($getSearch as $search){
				array_push($listAffiliate,$search);
			}
			$i++; 
			if($i==1){
				break; 
			}
		}
		$keywordData = \App\Model\Keyword_data::firstOrNew(array('parent_id'=>$getKeyword->id));
		$keywordData->affiliate=json_encode($listAffiliate);
		$keywordData->save();*/
	}
	$dependencies = array(); 
	Theme::asset()->writeScript('moreChannel', '
		jQuery(document).ready(function(){
		"use strict"; 
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
		
	});
	', $dependencies);
?>
<?
	$dependencies = array(); 
	Theme::asset()->writeScript('loadLazy','
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
		});
		var swiper = new Swiper("#carousel-2", {
			navigation: {
				nextEl: ".carousel_control_right_2",
				prevEl: ".carousel_control_left_2",
			},
		});
	', $dependencies);
?>