<?
	$channel['theme']->setTitle($data['category']->category_name);
	$channel['theme']->setKeywords($data['category']->category_name);
	$channel['theme']->setDescription($data['category']->category_description); 
	if(!empty($channel['info']->channelAttributeBanner[0]->media->media_url)){$channel['theme']->setImage($channel['info']->channelAttributeBanner[0]->media->media_url);}
	Theme::asset()->usePath()->add('custom', 'css/style.default.css', array('core-style'));
	Theme::asset()->usePath()->add('quick-alo', 'css/quick-alo.css', array('core-style'));
	Theme::asset()->usePath()->add('flags', 'flags/flags.min.css', array('core-style'));
	Theme::asset()->usePath()->add('jquery.gritter', 'css/jquery.gritter.css', array('core-style'));
	Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.sparkline', 'js/jquery.sparkline.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('retina', 'js/retina.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('jquery.gritter.min', 'js/jquery.gritter.min.js', array('core-script'));
	Theme::asset()->container('footer')->usePath()->add('custom', 'js/custom.js', array('core-script'));
?>
@if($channel['security']==true)
<?php
Theme::asset()->container('footer')->usePath()->add('select2.min', 'js/select2.min.js', array('core-script'));
?>
@endif
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>{!! Theme::get('keywords') !!}</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel">
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body text-center">
					<strong>{{$data['category']->category_name}}</strong> Không có bài viết nào <a href="{{route('channel.home',$channel['domain']->domain)}}">Trở về {{$channel['info']->channel_name}}</a>
				</div>
			</div>
		</div>
	</div>
</div><!-- mainpanel -->
{!!Theme::partial('rightpanel', array('title' => 'Header'))!!}
</section>
@if(count($channel['info']->joinPhone) && $channel['info']->channel_parent_id!=0)
<div class="quick-alo-phone quick-alo-green quick-alo-show" id="quick-alo-phoneIcon">
	<a href="tel:{{$channel['info']->joinPhone[0]->phone->phone_number}}">
		<div class="quick-alo-ph-circle-fill"></div>
		<div class="quick-alo-ph-img-circle"></div>
		<div class="quick-alo-number">{{$channel['info']->joinPhone[0]->phone->phone_number}}</div> 
	</a>    
</div>
@endif