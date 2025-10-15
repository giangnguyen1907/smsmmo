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

	{{-- @include('frontend.element.modal') --}}

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
				<div class="row gutter-20">
					<div class="col-lg-9 mb-20">
						@if(count($posts) > 0)
						<section class="section pt-0">
							<div class="list-2">
								@foreach($posts as $item)
									<div class="list-2__item">
										<div class="news-4 media">
											<span class="news-4__frame"><img src="{{ $item->image }}" onerror="this.src='/public/themes/frontend/yenbinh/themes/chuyennt/images/pngwing.com.png'" alt="{{ $item->title }}" /></span>
											<div class="media-body">
												<h2 class="news-4__title big_name">
													{{ $item->title }}
												</h2>									
												<div class="news-3__desc big_desc"> 
													@if($item->chucvu != '')
														<p>Chức vụ: {{ $item->chucvu }}</p>
													@endif
													@if($item->namsinh != '')
													<p>Năm sinh: {{ $item->namsinh }}</p>
													@endif
													@if($item->trinhdo != '')
													<p>Trình độ: {{ $item->trinhdo }}</p>
													@endif
													@if($item->sdt != '')
													<p>Số điện thoại: {{ $item->sdt }}</p>
													@endif
													@if($item->email != '')
													<p>Email: <em><strong>{{ $item->email }}</strong></em></p>
													@endif
												</div>
											</div>
										</div>
									</div>
								@endforeach
							</div>
						</section>	

						{{ $posts->withQueryString()->links('frontend.pagination.default') }}
						@endif
					</div>

					<div class="col-lg-3 mb-20">

						@include('frontend.element.nightmode')

						@include('frontend.element.tintucdocnhieu')

						@include('frontend.element.truycap')

						@include('frontend.element.facebook')
						
					</div>
				</div>
		</div>	

		@include('frontend.element.footer')	

	</div>

@include('frontend.element.scrolltop')	

</body>

</html>
