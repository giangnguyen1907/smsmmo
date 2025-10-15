@extends('frontend.layouts.default')
@php
$page_title = $array_translate[strtolower('Search news')]->$locale ?? "Search news";
$title = $array_translate[strtolower('Search news')]->$locale ?? "Search news";
$image = '';
$seo_title = $title;
$seo_keyword = $array_translate[strtolower('Search news')]->$locale ?? "Search news";
$seo_description = $array_translate[strtolower('Search news')]->$locale ?? "Search news";
$seo_image = $image ?? null;
$image_background = '';


@endphp

@section('content')


<style>
#pxl-wapper #pxl-main {
  padding-top: 100px;
  padding-bottom: 100px;
}

</style>
 
  <div id="pxl-page-title-elementor">
    <div data-elementor-type="wp-post" data-elementor-id="126" class="elementor elementor-126">
      <section class="elementor-section elementor-top-section elementor-element elementor-element-2b679ef elementor-section-height-min-height elementor-section-boxed elementor-section-height-default elementor-section-items-middle pxl-row-scroll-none pxl-section-overflow-visible pxl-bg-color-none pxl-section-overlay-none" style="<?php echo $image_background ?>"
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
                        {{ $title }}
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
                          {{$array_translate[strtolower('Home')]->$locale ?? "Home" }}
                        </a>
                      </li>
                      <li>
                        <span class="breadcrumb-entry">
                         {{ $title }}
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
  <div class="elementor-container">
    <div class="row pxl-content-wrap no-sidebar">
      <div id="pxl-content-area" class="pxl-content-area pxl-content-page col-12">
        <main id="pxl-content-main">
          <article id="pxl-post-245" class="post-245 page type-page status-publish hentry">
            <div class="pxl-entry-content clearfix">
              <div data-elementor-type="wp-page" data-elementor-id="245" class="elementor elementor-245">
                <section class="elementor-section elementor-top-section elementor-element elementor-element-6f6d39a elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default pxl-row-scroll-none pxl-section-overflow-visible pxl-bg-color-none pxl-section-overlay-none"
                data-id="6f6d39a" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}"
                style="width: 1519px; left: 0px;">
                  <div class="elementor-container elementor-column-gap-extended ">
                    <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-6f68e24 pxl-column-none"
                    data-id="6f68e24" data-element_type="column">
                      <div class="elementor-widget-wrap elementor-element-populated">
                        <div class="elementor-element elementor-element-edce183 pxl-post-layout-post-2 elementor-widget elementor-widget-pxl_post_grid"
                        data-id="edce183" data-element_type="widget" data-widget_type="pxl_post_grid.default">
                          <div class="elementor-widget-container">
                            <div id="pxl_post_grid-edce183-9503" class="pxl-grid pxl-blog-grid pxl-blog-grid-layout2 pxl-blog-style2 style-default"
                            data-layout="masonry" data-start-page="1" data-max-pages="2" data-total="12"
                            data-perpage="6" data-next-link="#">
                              <div class="pxl-grid-inner pxl-grid-masonry row" data-gutter="15" style="height: 1059px;">
                                
                                @foreach($posts as $item)
                                @php
                                  $title = $item->title->$locale ?? "";
                                  $brief = $item->brief->$locale ?? "";
                                  $image = $item->image != '' ? $item->image : '';
                                  $date = date('H:i d/m/Y', strtotime($item->created_at));
                                  $url = '/detail/'.$item->url_part.'.html';
                                @endphp
                                <div class="pxl-grid-item col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 campeign"
                                style="position: absolute; left: 0%; top: 0px;">
                                  <div class="pxl-item--inner " data-wow-duration="1.2s">
                                    <div class="pxl-item--featured">
                                      <a href="{{ $url }}"
                                      style="background-image: url({{ $image }});">
                                        <img decoding="async" class="no-lazyload " src="{{ $image }}"
                                        width="740" height="474" alt="{{ $title }}"
                                        title="{{ $title }}">
                                      </a>
                                    </div>
                                    <div class="pxl-item--holder">
                                      <h5 class="pxl-item--title">
                                        <a href="{{ $url }}">
                                          {{ $title }}
                                        </a>
                                      </h5>
                                      <div class="pxl-item--content pxl-sz-content pxl-excerpt-line" style="-webkit-line-clamp: 2;">
                                        {{ $brief }}
                                      </div>
                                      <div class="pxl-item--button">
                                        <a class="btn--readmore" href="{{ $url }}">
                                          <span class="btn-readmore--text">
                                            {{$array_translate[strtolower('ViewMore')]->$locale ?? "View more" }}
                                          </span>
                                          <span class="btn-readmore--icon pxl-ml-10">
                                            <i class="flaticon-right-arrow-2 rtl-reverse">
                                            </i>
                                          </span>
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                @endforeach
                                <div class="grid-sizer col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                                </div>
                              </div>
                              
                              {{ $posts->withQueryString()->links('frontend.pagination.default') }}

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
            </div>
          </article>
        </main>
      </div>
    </div>
  </div>
</div>
@endsection
