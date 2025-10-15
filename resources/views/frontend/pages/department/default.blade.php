@extends('frontend.layouts.default')

@php
$page_title = $taxonomy->title ?? ($page->title ?? $page->name);
$image_background = $taxonomy->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? '');
@endphp

@section('content')
  {{-- Print all content by [module - route - page] without blocks content at here --}}
  <section id="page-title" class="page-title-pattern" style="background-image: url({{ $image_background }});">
    <div class="container clearfix">
      <h1>{{ $page_title }}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">@lang('Home')</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $page->name ?? '' }}</li>
      </ol>
    </div>
  </section>

  <section id="content">
    <div class="content-wrap">
      <div class="container clearfix">

        @if ($posts)
          <div id="posts" class="post-grid row grid-container gutter-40 clearfix" data-layout="fitRows">
            @foreach ($posts as $item)
              @php
                $title = $item->json_params->title->{$locale} ?? $item->title;
                $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                $image = $item->json_params->image != '' ? $item->json_params->image : ($item->json_params->image_background != '' ? $item->json_params->image_background : null);
                $date = date('H:i d/m/Y', strtotime($item->created_at));
                // Viet ham xu ly lay alias bai viet
                $alias = Str::slug($title);
                $url_link = route('frontend.cms.department', ['alias' => $alias]) . '.html?id=' . $item->id;
              @endphp
              <div class="col-lg-3 col-sm-6">
                <div class="fbox-effect flex-column center border p-4 rounded-3">
                  <div class="mb-4">
                    <a href="{{ $url_link }}">
                      <img src="{{ $image }}" alt="" class="bg-transparent rounded-0">
                    </a>
                  </div>
                  <div class="fbox-content">
                    <h3>
                      <a href="{{ $url_link }}">
                        {{ Str::limit($title, 30) }}
                      </a>
                    </h3>
                    <p>{{ Str::limit($brief, 100) }}</p>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{ $posts->withQueryString()->links('frontend.pagination.default') }}
        @else
          <div class="row">
            <div class="col-12">
              <p>@lang('not_found')</p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  {{-- End content --}}
@endsection
