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

</head>
<body class="post-template-default single single-post single-format-standard cbp-spmenu-push cbp-spmenu-widget-push">

	@include('frontend.panels.scriptfb')

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
	            		</span> »
	            		<span class="breadcrumb_last" aria-current="page">{{ $detail->title }}</span>
	            	</span>
	            </p>
	         </div>
	         <div id="primary" class="sec32_col1">
	            <article>
				    <div class="entry-title">
				      <h1 class="entry-title__h1">{{ $detail->title }}</h1>
				    </div>
				    <div class="entry-post-info">
				   		<span><i class="svg__fa-calendar-alt"></i> Ngày cập nhật: {{ date('d/m/Y', strtotime($detail->created_at)) }}</span>
				   		<span><i class="svg__fa-user"></i> Tác giả: {{ $detail->fullname }}</span>
				   		<span><i class="svg__fa-eye"></i> {{ $detail->number_view }} lượt xem</span>
				   	</div>
				    <div class="entry-content">
				     	{!! $detail->content !!}
				    </div>
				    <div class="fb-comments" data-href="{{ url()->full() }}" data-width="100%" data-numposts="5"></div>
				</article>
				@if(count($posts) > 0)
				<div class="entry-related__dich_vu">
				   <div class="entry-related__dich_vu__title h_4"><span>Dịch vụ liên quan</span></div>
				   <div id="metaslider-id-344604" style="max-width: 198px; margin: 0 auto;" class="ml-slider-3-29-1 metaslider metaslider-flex metaslider-344604 ml-slider single-footer-slider">
				      <div id="metaslider_container_344604">
				         <div id="metaslider_344604" class="flexslider">
				            <ul aria-live="polite" class="slides">
				            	@foreach($posts as $item)
				                <li style="display: block; width: 100%;" class="slide-538432 ms-image">
				                   <a href="/chi-tiet-dich-vu/{{ $item->url_part }}.html" target="_self" aria-label="navigation">
				                   	<img data-lazyloaded="1" style="height: 220px;width: 198px;object-fit: cover;" src="{{ $item->image }}" data-src="{{ $item->image }}" height="220" width="198" alt="{{ $item->title }}" class="slider-344604 slide-538432 entered litespeed-loaded" title="{{ $item->title }}" data-ll-status="loaded">
				                   </a>
				                    <div class="caption-wrap">
				                       <div class="caption">{{ $item->title }}</div>
				                    </div>
				                </li>
				                @endforeach
				            </ul>
				         </div>
				      </div>
				   </div>
				</div>
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
