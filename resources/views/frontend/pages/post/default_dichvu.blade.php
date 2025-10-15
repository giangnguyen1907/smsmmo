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
	            			<a href="/dich-vu">Dịch vụ khám</a>
	            		</span>»
	            		<span class="breadcrumb_last" aria-current="page">{{ $taxonomy->title }}</span>
	            	</span>
	            </p>
	         </div>
	         <div id="primary" class="sec32_col1">
	            <div class="entry-title entry-title--archive">
	               <h1 class="entry-title__h1"><span>{{ $taxonomy->title }}</span></h1>
	            </div>
	            @if(count($posts) > 0)
	            <div class="entry-loop">
	               <ul class="d_flex">
	               		@foreach($posts as $item)												
						@php							
						$image = $item->image;
						@endphp
		                  <li class="d_flex">
		                     <div class="thumb">
		                     	<a href="/chi-tiet-dich-vu/{{ $item->url_part }}.html" title="{{ $item->title }}">
		                     		<img data-lazyloaded="1" width="480" height="270" src="{{ $image }}" onerror="this.src='/public/themes/frontend/phongkham/img/macdinh_tinbai_360_200.png'" class="attachment-thumb-480x270 size-thumb-480x270 wp-post-image entered litespeed-loaded" alt="{{ $item->title }}" decoding="async" data-ll-status="loaded">
		                     	</a>
		                     </div>
		                     <div class="text">
		                        <h2 class="text__title">
		                        	<a href="/chi-tiet-dich-vu/{{ $item->url_part }}.html">{{ $item->title }}</a>
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

	         	<div class="widget_question">
				   <div class="widget__title_question">
				      <span>Dịch vụ</span>
				      <hr>
				   </div>
				   <div class="widget__container">
				      <ul>
				      	@foreach($dichvu as $dv)
				        <li>
				        	<h3><a href="/dich-vu/{{ $dv->url_part }}.html" title="{{ $dv->title }}">{{ $dv->title }}</a></h3>
				        </li>
				        @endforeach
				      </ul>
				   </div>
				</div>

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
