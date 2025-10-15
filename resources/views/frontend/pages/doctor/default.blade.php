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
          <div class="portfolio row grid-container g-0">
            @foreach ($posts as $item)
              @php
                $title = $item->json_params->title->{$locale} ?? $item->title;
                $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
                $date = date('H:i d/m/Y', strtotime($item->created_at));
                // Viet ham xu ly lay alias bai viet
                $alias = Str::slug($title);
                $url_link = route('frontend.cms.doctor', ['alias' => $alias]) . '.html?id=' . $item->id;
              @endphp
              <article class="portfolio-item col-12 col-sm-6 col-md-4 col-lg-3 pf-media pf-icons"
                title="{{ $title }}">
                <div class="grid-inner">
                  <div class="portfolio-image">
                    <img src="{{ $image }}" alt="">
                    <div class="bg-overlay">
                      <div class="bg-overlay-content dark" data-hover-animate="fadeIn">
                        <a href="{{ $url_link }}" class="overlay-trigger-icon bg-light text-dark"><i
                            class="icon-line-ellipsis"></i></a>
                      </div>
                      <div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
                    </div>
                  </div>
                  <div class="portfolio-desc">
                    <h4 class="mb-3">
                      <a href="{{ $url_link }}">{{ $title }}</a>
                    </h4>
                    <ul class="iconlist mb-0">
                      @isset($item->json_params->phone)
                        <li>
                          <i class="icon-phone"></i> {{ $item->json_params->phone }}
                        </li>
                      @endisset
                      @isset($item->json_params->email)
                        <li>
                          <i class="icon-email3"></i> {{ $item->json_params->email }}
                        </li>
                      @endisset
                    </ul>
                  </div>
                </div>
              </article>
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
