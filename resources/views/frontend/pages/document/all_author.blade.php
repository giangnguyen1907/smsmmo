@extends('frontend.layouts.default')
@php
	$perPage = 12; // Số lượng tác giả mỗi trang
    $totalAuthors = $cmsAuthors->count(); // Tổng số tác giả
    $totalPages = ceil($totalAuthors / $perPage); // Tổng số trang

    $currentPage = request()->get('page', 1); // Trang hiện tại, mặc định là trang 1 nếu không có trang được chỉ định
    $start = ($currentPage - 1) * $perPage; // Vị trí bắt đầu của phần tác giả trên trang hiện tại
    $slicedAuthors = $cmsAuthors->slice($start, $perPage); 
@endphp

@section('content')

<div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600" data-parallax="scroll" data-image-src="{{ $web_information->image->bread_crumb }}">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="tg-innerbannercontent">
					<ol class="tg-breadcrumb">
						<li ><a href="/">{{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}</a></li>
						<li class="tg-active">{{ $array_translate[strtolower('Author list')]->$locale ?? 'Author list' }}</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
</div>

<main id="tg-main" class="tg-main tg-haslayout">
	<div class="tg-sectionspace tg-haslayout">
		<div class="container">
			<div class="row">
				<div id="tg-twocolumns" class="tg-twocolumns">
					
					<div class="col-xs-12 col-sm-8 col-md-8 col-lg-9 pull-right">
						<div id="tg-content" class="tg-content">
							<div class="tg-newslist">
								<div class="tg-sectionhead" style="display: flex; justify-content: space-between; padding: 0 0px 0px 0;">
									<h2>{{ $array_translate[strtolower('Author list')]->$locale ?? 'Author list' }}</h2>
								</div>

								{{-- Dạng lưới --}}
								<div class="row view-doc-container grid-view-doc">
									@foreach($slicedAuthors as $item)
										@php
											$title = $item['title'];
											$image = $item['image'] != '' ? $item['image'] : asset('themes/frontend/images/user-vector.jpg');
											$url = route('frontend.cms.authors-documents', ['id' => $item['id']]);
										@endphp

										<div class="col-md-3 col-xs-6">
											<article class="tg-post books-grid">
												<figure class="new">
													<a href="{{ $url }}">
														<img src="{{ $image }}" alt="image description">
													</a>
												</figure>
												<div class="tg-postcontent" >
													<div class="tg-posttitle">
														<h3><a href="{{ $url }}" class="line-2" >{{ $title }}</a></h3>
														<span>{{ $item['description'] }}</span>
													</div>
												</div>
											</article>
										</div>
									@endforeach
								</div>

								{{-- phân trang --}}
								
								@if ($totalPages > 1)
									<ul class="pagination">
										@if($currentPage > 1)
											<li>
												<a href="{{ route('frontend.cms.all-author', ['page' => $currentPage - 1]) }}" aria-label="Previous">
													<span aria-hidden="true"><i class="fa fa-angle-left"></i></span>
												</a>
											</li>
										@endif

										@for ($i = 1; $i <= $totalPages; $i++)
											@if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2))
												<li class="{{ $i == $currentPage ? 'active' : '' }}">
													<a href="{{ route('frontend.cms.all-author', ['page' => $i]) }}">{{ $i }}</a>
												</li>
											@elseif ($i == $currentPage - 3 || $i == $currentPage + 3)
												<li class="disabled">
													<span>...</span>
												</li>
											@endif
										@endfor

										@if($currentPage < $totalPages)
											<li>
												<a href="{{ route('frontend.cms.all-author', ['page' => $currentPage + 1]) }}" aria-label="Next">
													<span aria-hidden="true"><i class="fa fa-angle-right"></i></span>
												</a>
											</li>
										@endif
									</ul>
								@endif
								
							</div>
						</div>
					</div>
					
					@include('frontend.element.left')
					
				</div>
			</div>
		</div>
	</div>
</main>

<style>
	.grid-view-doc {
		display: flex;
		flex-wrap: wrap;
	}

	.grid-view-doc .tg-post:hover {
		box-shadow: 1px 1px 5px 1px #ccc;
	}

	.grid-view-doc .tg-post:hover figure {
		border-color: #f4f4f4;
	}

	.grid-view-doc .col-md-3{
		padding: 5px;
	}

	.grid-view-doc .tg-post{
		flex-direction: column; 
		background-color: #f4f4f4; 
		margin: 0; 
		padding-bottom: 12px;
	}

	.grid-view-doc .new {
		width: 100% !important; 
		-webkit-box-shadow: unset !important; 
		box-shadow: unset !important;
	}

	.grid-view-doc .new a{
		padding: 10px 0;
	}

	.grid-view-doc .new a img{
		object-fit: contain;
	}

	.grid-view-doc .tg-postcontent {
		padding: 0 15px !important;
	}

	.grid-view-doc .tg-postcontent .line-2{
		font-size: 14px;
	}

	.btn-view {
		padding: 6px 10px;
		background-color: #ccc;
	}

	.btn-view.active {
		background-color: var(--primary-color);
		border-color: #fff;
		color: #fff;
	}

	.btn-view:hover {
		opacity: 0.8;
	}
</style>
@endsection

