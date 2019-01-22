<ul class="children menuItem-{{$menuParent->id}}" style="{{(\Request::url()==route('channel.slug',array($channel['domain']->domain,$menuParent->getSlug->slug_value))) ? 'display:block;' : ''}}">
	<li class="{{(\Request::url()==route('channel.slug',array($channel['domain']->domain,$menuParent->getSlug->slug_value))) ? 'active' : ''}}"><a href="{{route('channel.slug',array($channel['domain']->domain,$menuParent->getSlug->slug_value))}}"><i class="fa fa-caret-right"></i> Xem tất cả</a></li>
    @foreach($menus as $menu)
		@if($menu->children->count())
		   <li class="nav-parent-child {{(\Request::url()==route('channel.slug',array($channel['domain']->domain,$menu->getSlug->slug_value))) ? 'nav-active' : ''}}  menuItem-{{$menu->id}}">
				<a href="{{route('channel.slug',array($channel['domain']->domain,$menu->getSlug->slug_value))}}"><i class="fa fa-plus"></i> <span>{!! html_entity_decode($menu->category_name) !!}</span></a>
				{!! Theme::partial('childItems',array('menus'=>$menu->children,'menuParent'=>$menu)) !!}
			</li>
		@else
			<li class="{{(\Request::url()==route('channel.slug',array($channel['domain']->domain,$menu->getSlug->slug_value))) ? 'active' : ''}} menuItem-{{$menu->id}}">
				<a href="{{route('channel.slug',array($channel['domain']->domain,$menu->getSlug->slug_value))}}"><i class="fa fa-caret-right"></i> <span>{!! html_entity_decode($menu->category_name) !!}</span></a>
			</li>
		@endif
    @endforeach
 </ul>