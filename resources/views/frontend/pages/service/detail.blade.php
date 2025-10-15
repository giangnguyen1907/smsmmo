@extends('frontend.layouts.default')

@php

$title = $detail->title->$locale ?? null;
$brief = $detail->brief->$locale ?? null;
$content = $detail->content->$locale ?? null;
$image = $detail->image != '' ? $detail->image : null;

$image_thumb = $detail->image_thumb != '' ? $detail->image_thumb : null;
$date = date('H:i d/m/Y', strtotime($detail->created_at));

// For taxonomy

$taxonomy_title = $taxonomy->title->$locale;
$taxonomy_id = $taxonomy->id;

$image_background = $taxonomy_json_params->image ?? ($web_information->image->background_breadcrumbs ?? null);

$seo_title = $detail->json_params->seo_title ?? $title;
$seo_keyword = $detail->json_params->seo_keyword ?? null;
$seo_description = $detail->json_params->seo_description ?? $brief;
$seo_image = $image ?? ($image_thumb ?? null);

@endphp

@section('content')



{{--
  <div id="pxl-page-title-elementor">
    <div data-elementor-type="wp-post" data-elementor-id="126" class="elementor elementor-126">
      <section class="elementor-section elementor-top-section elementor-element elementor-element-2b679ef elementor-section-height-min-height elementor-section-boxed elementor-section-height-default elementor-section-items-middle pxl-row-scroll-none pxl-section-overflow-visible pxl-bg-color-none pxl-section-overlay-none" style="<?php if($image_background!=''){ ?> background-image: url({{ $image_background }}); <?php } ?>"
      data-id="2b679ef" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
        <div class="elementor-container elementor-column-gap-default ">
          <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-890e5c8 pxl-column-none"
          data-id="890e5c8" data-element_type="column">
            <div class="elementor-widget-wrap elementor-element-populated">
              <div class="elementor-element elementor-element-436ce90 elementor-widget elementor-widget-pxl_heading"
              data-id="436ce90" data-element_type="widget" data-widget_type="pxl_heading.default">
                <div class="elementor-widget-container">
                  <div id="pxl-pxl_heading-436ce90-2472" class="pxl-heading px-sub-title-default-style">
                    <div class="pxl-heading--inner">
                      <h1 class="pxl-item--title " data-wow-delay="ms">
                        {{ $taxonomy_title }}
                      </h1>
                    </div>
                  </div>
                </div>
              </div>
              <div class="elementor-element elementor-element-134f3ed elementor-widget elementor-widget-pxl_breadcrumb"
              data-id="134f3ed" data-element_type="widget" data-widget_type="pxl_breadcrumb.default">
                <div class="elementor-widget-container">
                  <div class="pxl-breadcrumb-wrap">
                    <ul class="pxl-breadcrumb">
                      <li>
                        <a class="breadcrumb-entry" href="/">
                          {{$array_translate[strtolower('Home')]->$locale }}
                        </a>
                      </li>
                      <li>
                        <span class="breadcrumb-entry">
                          {{ $taxonomy_title }}
                        </span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

	<div id="pxl-main">
  <div class="container">
    <div class="row">
      <div id="pxl-content-area" class="col-12">
        <main id="pxl-content-main">
          <article id="pxl-post-1527" class="post-1527 service type-service status-publish has-post-thumbnail hentry service-category-pte">
            <div data-elementor-type="wp-post" data-elementor-id="1527" class="elementor elementor-1527">
              <section class="elementor-section elementor-top-section elementor-element elementor-element-c203942 elementor-section-stretched elementor-reverse-tablet elementor-reverse-mobile_extra elementor-reverse-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default pxl-row-scroll-none pxl-section-overflow-visible pxl-bg-color-none pxl-section-overlay-none"
              data-id="c203942" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}"
              style="width: 1519px; left: -109.6px;">
                <div class="elementor-container elementor-column-gap-extended ">
                  <div class="elementor-column elementor-col-30 elementor-top-column elementor-element elementor-element-671abc0 pxl-column-none"
                  data-id="671abc0" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <div class="elementor-element elementor-element-3291125 elementor-widget-tablet__width-initial elementor-widget elementor-widget-pxl_link"
                      data-id="3291125" data-element_type="widget" data-widget_type="pxl_link.default">
                        <div class="elementor-widget-container">
                          <ul id="pxl-link-pxl_link-3291125-2864" class="pxl-link pxl-link-l1  style-box type-vertical"
                          data-wow-delay="ms">
                            
							<?php
								foreach($taxonomy_all as $taxo){
								$hienthi = trim($taxo->hienthi,';');
								$vitrihienthi = explode(';',$hienthi); // chuyển về mảng
								if(in_array('1',$vitrihienthi)){
							  ?>
                            <li>
                              <a href="/{{ $taxo->taxonomy.'/'.$taxo->url_part.'.html' }}"
                              class="<?php if($taxo->id == $taxonomy->id) echo "active"; ?>">
                                <i aria-hidden="true" class="flaticon flaticon-student">
                                </i>
                                <span>
                                  {{$taxo->title->$locale}}
                                </span>
                              </a>
                            </li>
								<?php }} ?>
                          </ul>
                        </div>
                      </div>
					  
                    </div>
                  </div>
                  <div class="elementor-column elementor-col-70 elementor-top-column elementor-element elementor-element-9cbcf9b pxl-column-none"
                  data-id="9cbcf9b" data-element_type="column">
                    <div class="elementor-widget-wrap elementor-element-populated">
                      <div class="elementor-element elementor-element-b78aae4 elementor-widget elementor-widget-pxl_heading"
                      data-id="b78aae4" data-element_type="widget" data-widget_type="pxl_heading.default">
                        <div class="elementor-widget-container">
                          <div id="pxl-pxl_heading-b78aae4-3779" class="pxl-heading px-sub-title-default-style">
                            <div class="pxl-heading--inner">
                              <h1 class="pxl-item--title " data-wow-delay="ms">
                                {{ $title }}
                              </h1>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="elementor-element elementor-element-5391928 elementor-widget elementor-widget-pxl_text_editor"
                      data-id="5391928" data-element_type="widget" data-widget_type="pxl_text_editor.default">
                        <div class="elementor-widget-container">
                          <div class="pxl-text-editor">
                            <div class="pxl-item--inner " data-wow-delay="ms">
                              {!! $content !!}
                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="elementor-element elementor-element-412e287 elementor-widget elementor-widget-pxl_accordion"
                      data-id="412e287" data-element_type="widget" data-widget_type="pxl_accordion.default" style="margin-top: 80px;">
                        <div class="elementor-widget-container">
                          <div class="pxl-accordion-wrap ">
                            <div class="pxl-accordion pxl-accordion1 style1 " data-wow-delay="ms">
                              <?php $i=0;
							  foreach($blocksContent as $banner){
								if($banner->block_code == 'faq' and in_array($taxonomy_id,explode(',',$banner->taxonomy)) ){ ?>
							  <div class="pxl--item <?php if($i==0) echo "active"; ?>">
                                <h5 class="pxl-item--title" data-target="#pxl_accordion-412e287-9786-{{ $banner->id }}">
                                  <span class="pxl-title--text">
								  {{ $banner->title->$locale }}
                                  </span>
                                  <i class="pxl-icon--plus">
                                  </i>
                                </h5>
                                <div id="pxl_accordion-412e287-9786-{{ $banner->id }}" class="pxl-item--content"
                                style="<?php if($i==0) echo "display: block;"; ?>">
                                  {!! $banner->content->$locale !!}
                                </div>
                              </div>
							  <?php $i++; }} ?>
                            </div>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </article>
          <!-- #post -->
        </main>
      </div>
    </div>
  </div>
</div>
--}}

	<header class="lte-page-header lte-parallax-yes">
        <div class="container">
            <div class="lte-header-h1-wrapper">
                <h1 class="header">
                    {{ $taxonomy_title }}
                </h1>
            </div>
            <ul class="breadcrumbs" typeof="BreadcrumbList" vocab="https://schema.org/">
                <!-- Breadcrumb NavXT 7.0.0 -->
                <li class="home">
                    <span property="itemListElement" typeof="ListItem">
                        <a property="item" typeof="WebPage" title="Go to Home." href="/" class="home">
                            <span property="name">
                                {{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}
                            </span>
                        </a>
                        <meta property="position" content="1">
                    </span>
                </li>
                <li class="post post-page current-item">
                    <span property="itemListElement" typeof="ListItem">
                        <span property="name">
                            {{ $taxonomy_title }}
                        </span>
                        <meta property="position" content="2">
                    </span>
                </li>
            </ul>
        </div>
    </header>
	
	
    <div class="lte-content-wrapper  lte-layout-default section-service">
        <div class="container main-wrapper">
            <div class="row">
                @foreach ($serviceChild as $item)
                    @if ($item->id % 2 != 0)
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="content-wrapper">
                                <div class="content">{{ $item->title }}</div>
                                <div class="line"></div>
                                <div class="price">{{ $item->price }}</div>
                            </div>
                            <p>{{ $item->brief ? "$item->brief" : '' }}</p>
                        </div>
                    @else
                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                            <div class="content-wrapper">
                                <div class="content">{{ $item->title }}</div>
                                <div class="line"></div>
                                <div class="price">{{ $item->price }}</div>
                            </div>
                            <p>{{ $item->brief ? "$item->brief" : '' }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="container main-wrapper" style="text-align: center;">
            <a href="" class="lte-btn btn-black ">
                <span class="lte-btn-inner">
                    <span class="lte-btn-before">
                    </span>
                    Book now
                    <span class="lte-btn-after">
                    </span>
                </span>
            </a>
        </div>
    </div>


@endsection
