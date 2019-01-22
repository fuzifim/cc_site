 <ul>
	@foreach($menus as $menu)
		@if($menu->children->count())
			<li>
				<a href="javascript:void(0);"><span class="menu-item-parent">{!!$menu->name!!}</span></a>
				{!! Theme::partial('childItems',array('menus'=>$menu->children,'menuParent'=>$menu,'location'=>$location)) !!}
				
			</li>
		@else 
			<li class="">
				<a href="{{route('channel.slug',array($channel['domainPrimary'],$location.Str::slug($menu->SolrID)))}}" title=""><span class="menu-item-parent">{!!$menu->name!!}</span></a>
			</li>
		@endif
	@endforeach
</ul>