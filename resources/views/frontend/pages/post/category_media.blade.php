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
<body class="">

	<div class="page">

	@include('frontend.element.header')

		<div class="page__content">
			<div class="container">
				<nav class="main-nav">
					<div class="main-nav__list">
						<a class="main-nav__item active" href="/{{ $taxonomy->taxonomy }}/{{ $taxonomy->url_part }}.html">{{ $taxonomy->title }}</a>
						@foreach($taxonomylist as $taxolist)
							@if($taxolist->parent_id == $taxonomy->id)
								<a class="main-nav__item" href="/{{ $taxolist->taxonomy }}/{{ $taxolist->url_part }}.html">{{ $taxolist->title }}</a>
							@endif
						@endforeach
					</div>
					<div class="main-nav__dropdown"></div>
					<div class="main-nav__toggle"><span>...</span><i class="fa fa-angle-down ml-2"></i></div>
				</nav>	
				@if(count($posts) > 0)
				<section class="section mb-20">
					<div class="row gutter-16">
						@foreach($posts as $item)					
						<div class="col-6 col-md-4 col-lg-3 mb-3">
							<div class="news-5">
								<a class="news-5__frame" href="/chi-tiet/{{ $item->url_part }}.html" title="{{ $item->title }}">
								<img src="{{ $item->image }}" onerror="this.src='/public/themes/frontend/phongkham/img/macdinh_tinbai_360_200.png'" alt="{{ $item->title }}" />
								</a>
								<h3 class="news-5__title text-dark mb-0">
									<a href="/chi-tiet/{{ $item->url_part }}.html" title="{{ $item->title }}">{{ $item->title }}</a>
								</h3>
								<div class="news-5__info">
									{{ date('d/m/Y', strtotime($item->created_at)) }}
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</section>
				{{ $posts->withQueryString()->links('frontend.pagination.default') }}
				@endif
			</div>
		</div>	

		@include('frontend.element.footer')	

	</div>

@include('frontend.element.scrolltop')	

</body>

</html>
