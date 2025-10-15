@extends('frontend.layouts.default')

@php
$title = $detail->json_params->title->{$locale} ?? $detail->title;
$brief = $detail->json_params->brief->{$locale} ?? null;
$content = $detail->json_params->content->{$locale} ?? null;
$image = $detail->json_params->image ?? null;
$image_background = $detail->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? null);

$seo_title = $detail->json_params->seo_title ?? $title;
$seo_keyword = $detail->json_params->seo_keyword ?? null;
$seo_description = $detail->json_params->seo_description ?? null;
$seo_image = $image ?? null;

@endphp

@push('style')
  <style>
    #content-detail h2 {
      font-size: 30px;
    }

    #content-detail h3 {
      font-size: 24px;
    }

    #content-detail h4 {
      font-size: 18px;
    }

    #content-detail h5,
    #content-detail h6 {
      font-size: 16px;
    }

    #content-detail p {
      margin-top: 0;
      margin-bottom: 0;
    }

    #content-detail h1,
    #content-detail h2,
    #content-detail h3,
    #content-detail h4,
    #content-detail h5,
    #content-detail h6 {
      margin-top: 0;
      margin-bottom: .5rem;
    }

    #content-detail p+h2,
    #content-detail p+.h2 {
      margin-top: 1rem;
    }

    #content-detail h2+p,
    #content-detail .h2+p {
      margin-top: 1rem;
    }

    #content-detail p+h3,
    #content-detail p+.h3 {
      margin-top: 0.5rem;
    }

    #content-detail h3+p,
    #content-detail .h3+p {
      margin-top: 0.5rem;
    }

    #content-detail ul,
    #content-detail ol {
      list-style: inherit;
      padding: 0 0 0 50px;

    }

    #content-detail ul li,
    #content-detail ol li {
      display: list-item;
    }

    .posts-sm .entry-image {
      width: 75px;
    }
  </style>
@endpush

@section('content')
  {{-- Print all content by [module - route - page] without blocks content at here --}}

  <section id="page-title" class="page-title-pattern" style="background-image: url({{ $image_background }});">
    <div class="container clearfix">
      <h1>{{ $title }}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">@lang('Home')</a></li>
        <li class="breadcrumb-item"><a href="{{ route('frontend.cms.department') }}">{{ $page->name ?? '' }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
      </ol>
    </div>
  </section>

  <section id="content">
    <div class="content-wrap">
      <div class="container clearfix">
        @if ($detail)
          <div class="row gutter-30 ">

            <div class="postcontent col-lg-12">
              <div class="single-post mb-0">
                <div class="entry clearfix">
                  <div class="entry-content mt-0">
                    <div class="content-block">
                      <div class="news-detail">
                        <div class="clear"></div>

                        <h5 class="font-weight-medium text-dark text-justify">
                          {{ $brief }}
                        </h5>
                        <div class="offset-top-20 text-justify detail" id="content-detail">
                          {!! $content !!}
                        </div>
                        <div class="clear"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            @if (count($posts) > 0)
              <div class="col-lg-12">
                <div class="offset-top-30">
                  <h3 class="text-uppercase">@lang('Other department')</h3>
                  <hr class="text-subline">
                </div>
                <div id="posts" class="row mb-0 gutter-30 justify-content-center " data-layout="fitRows">
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
              </div>
            @endif
          </div>
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
