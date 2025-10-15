@extends('frontend.layouts.default')

@php

$page_title = $taxonomy->title->$locale ?? '';
$page_brief = $taxonomy->brief->$locale ?? '';
$image_background = $taxonomy->json_params->image_background ? 'background-image: url('.$taxonomy->json_params->image_background.')' : '';
$taxonomy_id = $taxonomy->id;
$image = $taxonomy->image ?? null;
$seo_title = $taxonomy->meta_title ?? $page_title;
$seo_keyword = $taxonomy->meta_keyword ?? null;
$seo_description = $taxonomy->meta_description ?? null;
$seo_image = $image ?? null;

// For taxonomy

$taxonomy_title = $taxonomy->title->$locale;
$taxonomy_id = $taxonomy->id;

$image_background = '';

$seo_title = $detail->json_params->seo_title ?? $page_title;
$seo_keyword = $detail->json_params->seo_keyword ?? null;
$seo_description = $detail->json_params->seo_description ?? null;
$seo_image = $image ?? ($image_thumb ?? null);

@endphp

<?php

$dem_soluong = count($list_taxonomy);
/*
if($dem_soluong <= 4){
	$soluongvonglap = 1;
	$soluong = $dem_soluong;
}else{
	$soluongvonglap = ceil($dem_soluong/3);
	$soluong = 3;
}
*/
$soluongvonglap = ceil($dem_soluong/3);
$soluong = 3;

 ?>

@section('content')
<div data-elementor-type="wp-page" data-elementor-id="72" class="elementor elementor-72">
<link rel='stylesheet' href="{{ asset('themes/frontend/css/post-72.css') }}" media='all' />

<section class="elementor-section elementor-top-section elementor-element elementor-element-2ae0cd2a elementor-section-boxed elementor-section-height-default elementor-section-height-default"
data-id="2ae0cd2a" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}" >
  <div class="elementor-background-overlay" style="{{ $image_background }}">
  </div>
  <div class="elementor-container elementor-column-gap-default">
    <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-131a8720"
    data-id="131a8720" data-element_type="column">
      <div class="elementor-widget-wrap elementor-element-populated">
        <div class="elementor-element elementor-element-dab8d4b elementor-widget elementor-widget-heading animated fadeInUp"
        data-id="dab8d4b" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
        data-widget_type="heading.default">
          <div class="elementor-widget-container">
            <style>
              .elementor-heading-title{padding:0;margin:0;line-height:1}.elementor-widget-heading
              .elementor-heading-title[class*=elementor-size-]>a{color:inherit;font-size:inherit;line-height:inherit}.elementor-widget-heading
              .elementor-heading-title.elementor-size-small{font-size:15px}.elementor-widget-heading
              .elementor-heading-title.elementor-size-medium{font-size:19px}.elementor-widget-heading
              .elementor-heading-title.elementor-size-large{font-size:29px}.elementor-widget-heading
              .elementor-heading-title.elementor-size-xl{font-size:39px}.elementor-widget-heading
              .elementor-heading-title.elementor-size-xxl{font-size:59px}
            </style>
            <h1 class="elementor-heading-title elementor-size-default">
              {{ $page_title }}
            </h1>
          </div>
        </div>
        <div class="elementor-element elementor-element-5dbcbb7f elementor-widget elementor-widget-heading animated fadeInDown"
        data-id="5dbcbb7f" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}"
        data-widget_type="heading.default">
          <div class="elementor-widget-container">
            <h3 class="elementor-heading-title elementor-size-default">
			{{ $page_brief }}
            </h3>
          </div>
        </div>
        <div class="elementor-element elementor-element-4b341a43 elementor-align-center elementor-widget elementor-widget-button"
        data-id="4b341a43" data-element_type="widget" data-widget_type="button.default">
          <div class="elementor-widget-container">
            <div class="elementor-button-wrapper">
              <a class="elementor-button elementor-button-link elementor-size-sm" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc2OSIsInRvZ2dsZSI6dHJ1ZX0%3D">
                <span class="elementor-button-content-wrapper">
                  <span class="elementor-button-text">
                    {{ $array_translate[strtolower('Book now')]->$locale ?? 'Book now' }}
                  </span>
                </span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="elementor-section elementor-top-section elementor-element elementor-element-7ef4a47c elementor-section-boxed elementor-section-height-default elementor-section-height-default"
data-id="7ef4a47c" data-element_type="section" style="padding-top: 100px;">
  <div class="elementor-container elementor-column-gap-default">
    <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-b6b1669"
    data-id="b6b1669" data-element_type="column">
      <div class="elementor-widget-wrap elementor-element-populated">
	  
		<?php for($lap = 1; $lap <= $soluongvonglap; $lap++){ ?>
			<section class="elementor-section elementor-inner-section elementor-element elementor-element-46430554 elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-invisible"
		  data-id="46430554" data-element_type="section" data-settings="{&quot;animation&quot;:&quot;fadeInUp&quot;}">
				<div class="elementor-container elementor-column-gap-default">
				  <?php 
				  $chiso = 0;
				  foreach($list_taxonomy as $key2=> $dichvu){ 
					if($chiso >= $soluong){ break; }
					$chiso++;
				  ?>
				  <div class="elementor-column elementor-col-33 elementor-inner-column elementor-element elementor-element-71da4d39"
				  data-id="71da4d39" data-element_type="column">
					<div class="elementor-widget-wrap elementor-element-populated">
					  <div class="elementor-element elementor-element-33142992 elementor-cta--valign-bottom elementor-cta--skin-classic elementor-animated-content elementor-widget elementor-widget-call-to-action"
					  data-id="33142992" data-element_type="widget" data-widget_type="call-to-action.default">
						<div class="elementor-widget-container">
						  <div class="elementor-cta">
							<div class="elementor-cta__bg-wrapper">
							  <div class="elementor-cta__bg elementor-bg" style="background-image: url({{ $dichvu->json_params->image }});">
							  </div>
							  <div class="elementor-cta__bg-overlay">
							  </div>
							</div>
							<div class="elementor-cta__content">
							  <div class="elementor-cta__title elementor-cta__content-item elementor-content-item">
								{{ $dichvu->brief->$locale ?? "" }}
							  </div>
							  <div class="elementor-cta__description elementor-cta__content-item elementor-content-item">
								{{ $dichvu->title->$locale }}
							  </div>
							  <div class="elementor-cta__button-wrapper elementor-cta__content-item elementor-content-item ">
								<a class="elementor-cta__button elementor-button elementor-size-sm" href="/service/{{ $dichvu->url_part }}.html">
								  {{ $array_translate[strtolower('View more')]->$locale ?? "View more" }}
								</a>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				  <?php unset($list_taxonomy[$key2]); } ?>
				</div>
			</section>
		  <?php } ?>
      </div>
    </div>
  </div>
</section>

@include('frontend.element.popular')

</div>
@endsection
