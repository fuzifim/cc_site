@if ($paginator->lastPage() > 1)
<ul class="pagination nomargin pull-right">
    <li class="{{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a href="{{ $paginator->url(1) }}">Previous</a>
    </li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
            <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
        </li>
    @endfor
	@if($paginator->currentPage() < $paginator->lastPage())
    <li class="">
			<a href="{{ $paginator->url($paginator->currentPage()+1) }}" >Next</a>
	</li>
	@else
		
	@endif
</ul>
@endif