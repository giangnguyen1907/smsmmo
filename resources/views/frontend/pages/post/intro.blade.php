@extends('frontend.layouts.default')

@php

if($detail){

  $title_detail = $detail->title->$locale ?? $detail->title->$locale;
  $brief_detail = $detail->brief->$locale ?? null;
  $content = $detail->content->$locale ?? null;
  $image = $detail->image != '' ? $detail->image : null;
  $image_thumb = $detail->image_thumb != '' ? $detail->image_thumb : null;

  $taxonomy_title = $taxonomy->title->$locale;
  $taxonomy_id = $taxonomy->id;
  $image_background = $taxonomy->json_params->image_background ?? null;
	//dd($image_background);
  $seo_title = $detail->meta_title ?? $title_detail;
  $seo_keyword = $detail->meta_keyword ?? null;
  $seo_description = $detail->meta_description ?? $brief_detail;
  $seo_image = $image ?? ($image_thumb ?? null);
}else{
	
	$title_detail = $taxonomy->title->$locale;
	$brief_detail = $taxonomy->brief->$locale ?? null;
	$content = null;
	$image = $taxonomy->json_params->image != '' ?? null;
	$image_background = $taxonomy->json_params->image_background != '' ?? null;
	
	$taxonomy_title = $taxonomy->title->$locale;
	$taxonomy_id = $taxonomy->id;
	
}


@endphp

@section('content')

<div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner" data-z-index="-100" data-appear-top-offset="600"
        data-parallax="scroll" data-image-src="{{ $image_background }}">
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="tg-innerbannercontent">
					<h1 class="p-4 hidden">{{ $title_detail }}</h1>
					<ol class="tg-breadcrumb">
						<li><a
								href="javascript:void(0);">{{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}</a>
						</li>
						<li><a href="javascript:void(0);">{{ $taxonomy_title }}</a></li>
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
							<div class="tg-newsdetail">
								<div class="tg-sectionhead">
									<h2>{{ $taxonomy_title }}</h2>
								</div>
								<div class="tg-posttitle">
									<h3><a href="javascript:void(0);">{{ $title_detail }}</a></h3>
								</div>
								
								@if ($detail->image)
									<img src="{{ $detail->image }}" alt="" class="image-post">
								@endif
								@if ($content)
									<div class="tg-description"
										style="border-bottom: 2px solid #dbdbdb; padding-bottom: 30px;">
										{!! $content !!}
									</div>
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

@endsection
