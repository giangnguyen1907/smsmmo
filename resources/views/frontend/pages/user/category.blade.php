@extends('frontend.layouts.default')

@php
$page_title = $taxonomy->title ?? ($page->title ?? $page->name);
$image_background = $taxonomy->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? '');

$title = $taxonomy->json_params->title->{$locale} ?? ($taxonomy->title ?? null);
$image = $taxonomy->json_params->image ?? null;
$seo_title = $taxonomy->json_params->seo_title ?? $title;
$seo_keyword = $taxonomy->json_params->seo_keyword ?? null;
$seo_description = $taxonomy->json_params->seo_description ?? null;
$seo_image = $image ?? null;
@endphp

@section('content')
  {{-- Print all content by [module - route - page] without blocks content at here --}}

  <section id="page-title" class="page-title-pattern" style="background-image: url({{ $image_background }});">
    <div class="container clearfix">
      <h1>{{ $page_title }}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">@lang('Home')</a></li>
        <li class="breadcrumb-item"><a href="{{ route('frontend.cms.resource_category') }}">{{ $page->name ?? '' }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $page_title }}</li>
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
                $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
                $date = date('H:i d/m/Y', strtotime($item->created_at));
                // Viet ham xu ly lay alias bai viet
                $alias_category = Str::slug($item->taxonomy_title);
                $alias_detail = Str::slug($title);
                $url_link = route('frontend.cms.resource', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
                $taxonomy_url_link = route('frontend.cms.resource_category', ['alias' => $alias_category]) . '.html?id=' . $item->taxonomy_id;
              @endphp
              <div class="entry col-md-4 col-sm-6 col-12">
                <div class="grid-inner">
                  <div class="entry-image">
                    <div class="portfolio-image">
                      <a href="{{ $url_link }}">
                        <img src="{{ $image }}" alt="">
                      </a>
                      <div class="bg-overlay">
                        <div class="bg-overlay-content">
                          <a href="{{ $url_link }}"
                            class="overlay-trigger-icon size-lg op-ts op-07 bg-light text-dark" data-hover-animate="op-1"
                            data-hover-animate-out="op-07">
                            <i class="icon-film"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="entry-title">
                    <h2>
                      <a href="{{ $url_link }}">
                        {{ $title }}
                      </a>
                    </h2>
                  </div>
                  <div class="entry-meta">
                    <ul>
                      <li><i class="icon-calendar3"></i> {!! $date !!}</li>
                      <li>
                        <a href="{{ $taxonomy_url_link }}">
                          <i class="icon-line-folder"></i>
                          {{ $item->taxonomy_title }}
                        </a>
                      </li>
                    </ul>
                  </div>
                  <div class="entry-content">
                    <p>
                      {{ Str::limit($brief, 100) }}
                    </p>
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
