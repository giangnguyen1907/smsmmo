<!DOCTYPE html>
<html lang="vi">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

	@php
	$page_title = $taxonomy->title;
	$title = $taxonomy->title;
	$image = $taxonomy->image;
	$seo_title = $title;
	$seo_keyword = $title;
	$seo_description = $taxonomy->brief;
	$seo_image = $image ?? null;

	$url_category = '/'.$taxonomy->taxonomy.'/'.$taxonomy->url_part.'.html';

	@endphp

	<title>{{ $page_title }}</title>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<meta http-equiv="Content-Language" content="vi" />
	<meta name="copyright" content="Copyright" />
	<meta name="description" content="{{ $seo_description }}" />
	<meta name="keywords" content="{{ $seo_keyword }}" />
	<meta name="DC.title" content="{{ $seo_title }}" />
	<meta property="og:type" name="ogtype" content="Website" />
	<meta property="og:title" name="ogtitle" content="{{ $seo_title }}" />
	<meta property="og:description" name="ogdescription" content="{{ $seo_description }}" />
	<meta property="og:image" name="ogimage" content="{{ $seo_image }}" />
	<meta property="og:sitename" content="{{ Request::fullUrl() }}" />
	<link rel="canonical" href="{{ Request::fullUrl() }}" />
	<link rel="shortcut icon" type="image/png" href="{{ $web_information->image->favicon ?? '' }}" />
	
	@include('frontend.panels.styles')

	<link rel="stylesheet" href="{{ asset('themes/frontend/phongkham/thuvien.css') }}" />
	
	<style>
	.entry-loop ul li .thumb {
		width: 100%;
	}
	.entry-loop ul li .text{
		width: 100%;
	}
	</style>

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
	            		<span>
	            			<a href="/{{ $taxonomy->taxonomy }}/{{ $taxonomy->url_part }}.html">{{ $taxonomy->title }}</a>
	            		</span>
	            	</span>
	            </p>
	         </div>
            <div class="entry-title entry-title--archive">
               <h1 class="entry-title__h1"><span>{{ $taxonomy->title }}</span></h1>
            </div>
            @if(count($posts) > 0)
            <div class="entry-loop">
            	<div class="entry-loop__row">
	            	<ul class="d_flex">
	            		@foreach($posts as $item)												
						@php							
							$image = $item->image;
						@endphp
	            		<li class="d_flex">
	            			<div class="thumb">
	            				<a href="/chi-tiet-thu-vien/{{ $item->url_part }}.html" title="{{ $item->title }}">
	            					<img data-lazyloaded="1" src="{{ $image }}" onerror="this.src='/public/themes/frontend/phongkham/img/macdinh_tinbai_360_200.png'" width="480" height="270" data-src="{{ $image }}" class="attachment-thumb-480x270 size-thumb-480x270 wp-post-image entered litespeed-loaded" alt="{{ $item->title }}" decoding="async" data-ll-status="loaded">
	            				</a>
	            			</div>
	            			<div class="text">
	            				<h2 class="text__title">
	            					<a href="/chi-tiet-thu-vien/{{ $item->url_part }}.html">{{ $item->title }}</a>
	            				</h2>
	            			</div>
	            		</li>
	            		@endforeach
	            	</ul>
            	</div>
            </div>
            {{ $posts->withQueryString()->links('frontend.pagination.newdefault') }}
            @endif
	      </div>
	   </div>
	</section>

	@include('frontend.element.footer')

	@include('frontend.panels.scrolltop')

	@include('frontend.panels.scripts')

</body>
</html>
