<?
	$channel['theme']->setTitle('Từ khóa cho '.$post->posts_title);
	$channel['theme']->setKeywords('Từ khóa cho '.$post->posts_title);
	$channel['theme']->setDescription('Kết quả Từ khóa cho '.$post->posts_title.' trên '.$channel['info']->channel_name); 
?>
{!!Theme::asset()->container('footer')->usePath()->add('jquery', 'js/jquery-1.11.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery-migrate', 'js/jquery-migrate-1.2.1.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('bootstrap', 'js/bootstrap.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('modernizr', 'js/modernizr.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('toggles', 'js/toggles.min.js', array('core-script'))!!}
{!!Theme::asset()->container('footer')->usePath()->add('jquery.cookies', 'js/jquery.cookies.js', array('core-script'))!!}
<section>
{!!Theme::partial('leftpanel', array('title' => 'Header'))!!}
<div class="mainpanel">
{!!Theme::partial('headerbar', array('title' => 'Header'))!!}
	<div class="pageheader">
		<h1>Từ khóa "{!!$post->posts_title!!}"</h1>
		<span><small>{!! Theme::get('description') !!}</small></span>
	</div>
	<div class="contentpanel" style="position:relative; ">
		<div id="preloaderInBox" style="display:none;">
			<div id="status"><i class="fa fa-spinner fa-spin"></i></div>
		</div>
		@if(count($keywords)>0)
			<div class="panel panel-default">
                <div class="panel-heading">
					{!!Theme::partial('pagination', array('paginator' => $keywords))!!}
                    <p>Khoảng {{$keywords->total()}} kết quả</p>
                </div><!-- panel-heading -->
                <div class="panel-body results-list">
					@foreach($keywords as $keyword)
						<a href="{{route('search.slug',array($channel['domainPrimary'],$keyword->slug))}}" class="btn btn-default btn-xs mb5"><i class="fa fa-tag"></i> {!!$keyword->keyword!!}</a>
					@endforeach
				</div><!-- results-list -->
            </div>
		@else 
			<div class="alert alert-warning">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<strong>Thông báo!</strong> Không tìm thấy kết quả tìm kiếm nào.
			</div>
		@endif
	</div>
</div><!-- mainpanel -->
</section>