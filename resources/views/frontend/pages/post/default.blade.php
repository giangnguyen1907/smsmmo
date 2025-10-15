<!DOCTYPE html>
<html lang="vi">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

    @php
        $page_title = $keyword;
    @endphp

    <title>Tìm kiếm {{ $page_title }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="robots" content="index, follow" />
    <meta http-equiv="Content-Language" content="vi" />
    <meta name="copyright" content="Copyright" />
    <meta property="og:sitename" content="{{ Request::fullUrl() }}" />
    <link rel="canonical" href="{{ Request::fullUrl() }}" />
    <link rel="shortcut icon" type="image/png" href="{{ $web_information->image->favicon ?? '' }}" />

    @include('frontend.panels.styles')

</head>
<body class="archive category category-24 cbp-spmenu-push cbp-spmenu-widget-push">

    @include('frontend.element.menu')

    @include('frontend.element.header')

    <section class="container12 sec32">
       <div class="wrap">
          <div class="d_flex">
             <div class="sec32_row0">
                <p class="entry-breadcrumb yoast_breadcrumb">
                    <span>
                        <span>
                            <a href="/">Trang chủ</a>
                        </span> » 
                        <span class="breadcrumb_last" aria-current="page">Bạn đã tìm "{{ $keyword }}"</span>
                    </span>
                </p>
             </div>
             <div id="primary" class="sec32_col1">
                @if(count($posts) > 0)
                <div class="entry-loop">
                   <ul class="d_flex">
                        @foreach($posts as $item)
                          <li class="d_flex">
                             <div class="thumb">
                                <a href="/chi-tiet/{{ $item->url_part }}.html" title="{{ $item->title }}">
                                    <img data-lazyloaded="1" width="480" height="270" src="{{ $item->image }}" class="attachment-thumb-480x270 size-thumb-480x270 wp-post-image entered litespeed-loaded" alt="{{ $item->title }}" decoding="async" data-ll-status="loaded">
                                </a>
                             </div>
                             <div class="text">
                                <h2 class="text__title">
                                    <a href="/chi-tiet/{{ $item->url_part }}.html">{{ $item->title }}</a>
                                </h2>
                                <div class="text__info d_flex">
                                    <span class="">
                                        <span>
                                            <i class="svg__fa-calendar-alt"></i> {{ date('d/m/Y', strtotime($item->created_at)) }}
                                        </span>
                                    </span>
                                </div>
                                <p><span>{{ $item->brief }}</span></p>
                             </div>
                          </li>
                        @endforeach
                   </ul>
                </div>
                {{ $posts->withQueryString()->links('frontend.pagination.newdefault') }}
                @endif
             </div>
             <div id="secondary" class="sec32_col2">

                @include('frontend.element.cauhoi_left')

                @include('frontend.element.tintucmoi_left')

             </div>
          </div>
       </div>
    </section>

    @include('frontend.element.footer')

    @include('frontend.panels.scrolltop')

    @include('frontend.panels.scripts')

</body>
</html>
