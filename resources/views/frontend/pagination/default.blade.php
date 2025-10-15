              
@if ($paginator->hasPages())
<ul class="pagination" style="float: inline-end">
	@if ($paginator->onFirstPage())
	<li class="disabled"><a href="javascript:;"><i class="fa fa-chevron-left" aria-hidden="true"></i></a></li>
	@else
	<li><a href="{{ $paginator->previousPageUrl() }}"><i class="fa fa-chevron-right" aria-hidden="true"></i></a></li>
	@endif
	@foreach ($elements as $element)
		
		@if (is_string($element))
			<li><a href="javascript:;">{{ $element }}</a></li>
		@endif
		@if (is_array($element))
			@foreach ($element as $page => $url)
			  @if ($page == $paginator->currentPage())
				<li class="active"><a href="javascript:;">{{ $page }}</a></li>
			  @else
				<li><a href="{{ $url }}">{{ $page }}</a></li>
			  @endif
			@endforeach
		@endif
    @endforeach
</ul>
@endif
