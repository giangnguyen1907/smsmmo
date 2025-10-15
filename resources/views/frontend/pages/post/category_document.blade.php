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
						@if(count($postsnoibat) > 0)
						<section class="section section-5">
							<h2 class="section-5__title">Tài liệu nổi bật</h2>
							<div class="section-5__body">
								<div class="list-3">
									@foreach($postsnoibat as $items)
									<div class="list-3__item">
										<div class="doc media">
											<div class="doc__icon">
												<i class="fa fa-fw fa-file-pdf-o fa-3x"></i>
											</div>
											<div class="media-body">
												<h3 class="doc__title">
													<a href="/chi-tiet/{{ $items->url_part }}.html" title="{{ $items->title }}">{{ $items->title }}</a>
												</h3>
												<div class="doc__info"><i class="fa fa-eye mr-1"></i> {{ $items->number_view }}</div>
											</div>
										</div>
									</div>
									@endforeach						
								</div>
							</div>
						</section>
						@endif

						@if(count($posts) > 0)
						@php
							$params['status'] = 'active';
				            $params['taxonomy'] = App\Consts::CATEGORY['tai-lieu'];
				            $params['order_by'] = ['id'=>'asc'];
						  	$taxolist = App\Http\Services\ContentService::getCmsTaxonomy($params)
						  	->get();
						@endphp
						<section class="section">
							<form class="bg-primary px-2 pt-2 mb-3">
								<div class="row gutter-8">
									<div class="col-lg-4 mb-2">
										<select class="custom-select border-0" id="taxolist" onchange="search_document()">
											{{-- <option value="">Chọn danh mục</option> --}}
												@foreach($taxolist as $taxo)
													<option value="{{ $taxo->id }}" <?php if($taxo->id == $taxonomy->id) { echo 'selected'; } ?> >{{ $taxo->title }}</option>
												@endforeach	
										</select>
									</div>
									<div class="col-lg-4 mb-2">
										<select class="custom-select border-0" id="orderby" onchange="search_document()">
											<option value="">Sắp xếp theo</option>									
											<option value="created_at">Mới nhất</option>
											<option value="number_download">Tải nhiều nhất</option>
											<option value="number_view">Xem nhiều nhất</option>
										</select>
									</div>
									<div class="col-lg-4 mb-2">
										<div class="input-group">
											<input class="form-control border-0" type="text" id="keyword" placeholder="Từ khoá tìm kiếm" onchange="search_document()">
											{{-- <div class="input-group-append">
												<button class="input-group-text border-0 bg-white">
													<i class="fa fa-search"></i>
												</button>
											</div> --}}
										</div>
									</div>
								</div>
							</form>
							<script type="text/javascript">

								function search_document() {
								    
									var keyword = $("#keyword").val();
									var orderby = $("#orderby").val();
									var taxolist = $("#taxolist").val();

								    var f = "?keyword=" + keyword.trim() + "&orderby=" + orderby + "&taxolist=" + taxolist;
								    
								    var _url = "/search_document" + f;

								    $.ajax({
								      type: "GET",
								      url: _url,
								      data: f,
								      cache: false,
								      context: document.body,
								      success: function(data) {
								        $("#list-document").html(data);
								      }
								    });
								}
							</script>
							<div class="list-2" id="list-document">
								@foreach($posts as $item)
								<div class="list-2__item">
									<div class="doc-2">
										<h3 class="doc-2__title">
											<a href="/chi-tiet/{{ $item->url_part }}.html" title="{{ $item->title }}">{{ $item->title }}</a>
										</h3>
										<div class="media">
											<a class="doc-2__frame" href="/chi-tiet/{{ $item->url_part }}.html" title="{{ $item->title }}">
											<img src="{{ $item->image }}" onerror="this.src='/public/themes/frontend/yenbinh/themes/chuyennt/images/nopic.jpg'" alt="{{ $item->title }}" /></a>
											<div class="media-body">
												<div class="doc-2__desc">{{ $item->title }}</div>
												<div class="doc-2__footer">
													<div class="doc-2__info">
														<!-- <span class="d-inline-block mr-3"><i class="fa fa-file-pdf-o mr-1"></i><span>70tr</span></span><span
															class="d-inline-block mr-3"><i class="fa fa-share-square-o mr-1"></i><span>ntt</span></span> -->
														<span
														class="d-inline-block mr-3"><i class="fa fa-calendar mr-1"></i><span>{{ date('d/m/Y', strtotime($item->created_at)) }}</span></span>
														<span
														class="d-inline-block mr-3"><i class="fa fa-eye mr-1"></i><span>{{ $item->number_view }}</span></span>
														<span
														class="d-inline-block"><i class="fa fa-cloud-download mr-1"></i><span>{{ $item->number_download }}</span></span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
							{{-- phân trang --}}
							{{ $posts->withQueryString()->links('frontend.pagination.default') }}
						</section>
						@endif

					</div>

					<div class="col-lg-3 mb-20">

						@include('frontend.element.nightmode')

						@include('frontend.element.thuvienanh')

						@include('frontend.element.thuvienvideo')

						{{-- @include('frontend.element.thuvientailieu') --}}

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
