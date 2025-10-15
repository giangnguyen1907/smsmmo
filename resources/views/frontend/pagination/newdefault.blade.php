              
@if ($paginator->hasPages())
<div class="entry-pagination">
  <div class="pagenavi">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    {{-- <a class="prev page-numbers">«</a> --}}
    @else
    <a class="prev page-numbers" href="{{ $paginator->previousPageUrl() }}">«</a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
      {{-- "Three Dots" Separator --}}
      @if (is_string($element))
        <span class="page-numbers dots">{{ $element }}</span>
      @endif

      {{-- Array Of Links --}}
      @if (is_array($element))
        @foreach ($element as $page => $url)
          @if ($page == $paginator->currentPage())
            <span aria-current="page" class="page-numbers current">{{ $page }}</span>
          @else
          <a class="page-numbers" href="{{ $url }}">{{ $page }}</a>
            </li>
          @endif
        @endforeach
      @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
      <a class="next page-numbers" href="{{ $paginator->nextPageUrl() }}">»</a>
    @else
      {{-- <a class="next page-numbers">»</a> --}}
    @endif
  </div>
</div> 
@endif
