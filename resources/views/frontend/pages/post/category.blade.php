@extends('frontend.layouts.default')
@php
$page_title = $taxonomy->title->$locale ?? '';
$image_background = $taxonomy->json_params->image_background ? $taxonomy->json_params->image_background : '';

$image = $taxonomy->image ?? null;
$seo_title = $taxonomy->meta_title ?? $page_title;
$seo_keyword = $taxonomy->meta_keyword ?? null;
$seo_description = $taxonomy->meta_description ?? null;
$seo_image = $image ?? null;

@endphp

@section('content')

<div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600" data-parallax="scroll" data-image-src="{{ $image_background }}">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="tg-innerbannercontent">
					<h1 class="p-4 hidden">{{ $page_title }}</h1>
					<ol class="tg-breadcrumb">
						<li ><a href="/">{{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}</a></li>
						<li class="tg-active">{{ $page_title }}</li>
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
						<div id="tg-content" class="tg-content" style="display: flex; justify-content: space-between;">
							<div class="tg-newsgrid">
								<div class="tg-sectionhead">
									<h2>{{ $page_title }}</h2>
								</div>
								<div class="row">
									@foreach($posts as $item)
									@php
									  $title = $item->title->$locale;
									  $brief = $item->brief->$locale;
									  $image = $item->image != '' ? $item->image : '';
									  $date = date('H:i d/m/Y', strtotime($item->created_at));
									  $url = '/detail/'.$item->url_part.'.html';
									@endphp
									<div class="col-xs-6 col-sm-12 col-md-6 col-lg-4">
										<article class="tg-post">
											<figure><a href="{{ $url }}"><img src="{{ $image }}" alt="image description" class="img-tintuc"></a></figure>
											<div class="tg-postcontent">
												<div class="tg-posttitle">
													<h3 style="font-size: 14px;"><a href="{{ $url }}" class="line-1">{{ $title }}</a></h3>
												</div>
												<span class="fa fa-user-o">
													<a href="javascript:void(0);" style="font-family: 'Open Sans';"> {{ $item->fullname }}</a>
												</span>
												<ul class="tg-postmetadata">
													<li><a href="javascript:void(0);"><i class="fa fa-comment-o"></i><i>{{ $item->number_comment }}</i></a></li>
													<li><a href="javascript:void(0);"><i class="fa fa-eye"></i><i>{{ $item->number_view }}</i></a></li>
												</ul>
											</div>
										</article>
									</div>
									@endforeach
								</div>
								{{ $posts->withQueryString()->links('frontend.pagination.default') }}
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
	.line-1 {
		line-height: 18px;
		overflow: hidden;
		text-overflow: ellipsis;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
		display: -webkit-box;
		height: auto;
	}
</style>

@endsection
