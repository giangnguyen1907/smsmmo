<!DOCTYPE html>
<html lang="vi">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

	@php
	$page_title = $detail->title;
	$title = $detail->title;
	$image = $detail->image != '' ? $detail->image : null;
	$seo_title = $title;
	$seo_keyword = $title;
	$seo_description = $detail->brief;
	$seo_image = $image ?? null;

	$url_category = '/'.$detail->taxonomy.'/'.$detail->taxonomy_url_part.'.html';

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

	<link rel="stylesheet" href="{{ asset('themes/frontend/phongkham/bacsi_chitiet.css') }}" />

</head>
<body class="post-template-default single single-post single-format-standard cbp-spmenu-push cbp-spmenu-widget-push">	@include('frontend.panels.scriptfb')

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
	            		<span class="breadcrumb_last" aria-current="page">{{ $taxonomy->title }}</span> »
	            		<span class="breadcrumb_last" aria-current="page">{{ $detail->title }}</span>
	            	</span>
	            </p>
	         </div>
	         <div id="primary" class="sec32_col1">
	         	<div class="entry-doctor d_flex">
	         		@if($detail->image)
				    <div class="thumb">
					   	<span>
					   		<img data-lazyloaded="1" src="{{ $detail->image }}" width="350" height="370" data-src="{{ $detail->image }}" class="attachment-full size-full wp-post-image entered litespeed-loaded" alt="{{ $detail->title }}" decoding="async" data-ll-status="loaded">
					   	</span>
				   	</div>
				   	@endif
				   <div class="text">

				      @if($detail->trinhdo)<div class="text__h4">{{ $detail->trinhdo }}</div>@endif
				      @if($detail->title)<h1 class="text__h1">{{ $detail->title }}</h1>@endif
				      @if($detail->chucvu)<div class="text__h5">{{ $detail->chucvu }}</div>@endif
				      @if($taxonomy->title)<div class="text__tax"><span>{{ $taxonomy->title }}</span></div>@endif
				      <div class="text__phone d_flex">
				      	 @if($detail->email)
				         	<div class="text__phone__1"><a href="mailto:{{ $detail->email }}"><small>Email:</small> <br class="d_mb"> <small>{{ $detail->email }}</small></a></div>
				         @endif
				         @if($detail->sdt)
				         	<div class="text__phone__2"><a href="tel:{{ $detail->sdt }}"><small>Liên hệ:</small> <br class="d_mb"> <small>{{ $detail->sdt }}</small></a></div>
				         @endif
				      </div>
				   </div>
				</div>
				@if($detail->content)
				<div class="entry-content">
					{!! $detail->content !!}
				</div>
	            @endif

	            <div class="fb-comments" data-href="{{ url()->full() }}" data-width="100%" data-numposts="5"></div>

				@if(count($posts) > 0)
				<div class="entry-related">
				    <div class="entry-related__title h_4"><span>Xem thêm bác sĩ</span></div>

				    <div class="owl-carousel owl-theme owl__v1">
				   		@foreach($posts as $item)
						<div class="item">
						   <div class="thumb">
						   	<a href="/chi-tiet-bac-si/{{ $item->url_part }}.html" title="{{ $item->title }}">
						   		<img data-lazyloaded="1" src="{{ $item->image }}" width="350" height="370" data-src="{{ $item->image }}" class="attachment-full size-full wp-post-image entered litespeed-loaded" alt="{{ $item->title }}" decoding="async" data-ll-status="loaded" />
						   	</a>
						   </div>
						   <div class="text">
						   		<div class="text__h4">{{ $item->trinhdo }}</div>
						   		<h3 class="text__title"><a href="/chi-tiet-bac-si/{{ $item->url_part }}.html">{{ $item->title }}</a></h3>
						   		<div class="text__h5"></div>
						   </div>
						</div>
						@endforeach
					</div>

				</div>
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
