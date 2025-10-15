<!DOCTYPE html>
<html lang="vi">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

	@php
	$page_title = 'Dịch vụ khám';
	$title = 'Dịch vụ khám';
	$image = '';
	$seo_title = $title;
	$seo_keyword = $title;
	$seo_description = 'Dịch vụ khám';
	$seo_image = $image ?? null;

	@endphp

	<title>Dịch vụ khám</title>
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

	<link rel="stylesheet" href="{{ asset('themes/frontend/phongkham/dichvu.css') }}" />

</head>
<body class="archive category category-dich-vu-kham category-2137 cbp-spmenu-push cbp-spmenu-widget-push">

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
	            			<a href="/dich-vu">Dịch vụ khám</a>
	            		</span>
	            	</span>
	            </p>
	         </div>
            <div class="entry-title entry-title--archive">
               <h1 class="entry-title__h1"><span>Dịch vụ khám</span></h1>
            </div>
            @if(count($posts) > 0)
	            <div class="entry-loop__dich-vu-y-te">
	            	<ul class="d_flex">
	            		@foreach($posts as $item)												
						@php							
						$image = $item->json_params->image;						
						@endphp
		            		<li class="d_flex">
		            			<div class="thumb">
		            				<a href="/dich-vu/{{ $item->url_part }}.html">
		            					<img data-lazyloaded="1" src="{{ $image }}" onerror="this.src='/public/themes/frontend/phongkham/img/macdinh_tinbai_360_200.png'" width="400" height="480" data-src="{{ $image }}" alt="{{ $item->title }}" data-ll-status="loaded" class="entered litespeed-loaded">
		            				</a>
		            			</div>
		            			<div class="text">
		            				<h2 class="text__title">
		            					<a href="/dich-vu/{{ $item->url_part }}.html">{{ $item->title }}</a>
		            				</h2>
		            			</div>
		            		</li>
	            		@endforeach
	            	</ul>
	            </div>
            @endif
	      </div>
	   </div>
	</section>

	@include('frontend.element.footer')

	@include('frontend.panels.scrolltop')

	@include('frontend.panels.scripts')

</body>
</html>
