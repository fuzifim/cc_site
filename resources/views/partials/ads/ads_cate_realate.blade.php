<?php 
			$category_user = WebService::getAllCategoryBytemplate($ads->template_setting_id);
			if(!empty($category_user)):
		?>
<div class="panel panel-default item-member list_category_details item-relate">
	<div class="panel-heading"><i class="fa fa-tags" aria-hidden="true"></i> Lĩnh vực hoạt động</div>
	<div class="panel-body">
		<ul class="list-group">
			@foreach ($category_user as $cate)
				<li class="list-group-item">
					<a href="{{ route('front.categories.ads.regions',$cate->category_id) }}">@if(empty($cate->icon)) <i class="fa-hand-o-right fa"></i> @else <img src="{{$cate->icon}}" width="20" height="20"/> @endif {{ $cate->name }}</a>
				</li>
			@endforeach
		</ul>
	</div>
</div>
<?php endif; ?>
