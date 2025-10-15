@extends('frontend.layouts.default')


@section('content')

<link rel="stylesheet" id="elementor-post-1637-css" href="{{ asset('themes/frontend/css/post-1637.css') }}" media="all">

<link rel="stylesheet" href="https://themesflat.co/kitmellis/wp-content/plugins/elementor/assets/css/widget-icon-list.min.css">

<div data-elementor-type="wp-post" data-elementor-id="1637" class="elementor elementor-1637">
  <section class="elementor-section elementor-top-section elementor-element elementor-element-f053d1b elementor-section-boxed elementor-section-height-default elementor-section-height-default tf-sticky-section tf-sticky-no"
  data-id="f053d1b" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;tf_sticky&quot;:&quot;no&quot;}">
    <div class="elementor-background-overlay">
    </div>
    <div class="elementor-container elementor-column-gap-default">
      <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-0344994"
      data-id="0344994" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-8b7808f elementor-widget__width-auto elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list"
          data-id="8b7808f" data-element_type="widget" data-widget_type="icon-list.default">
            <div class="elementor-widget-container">
              
              <ul class="elementor-icon-list-items">
                <li class="elementor-icon-list-item">
                  <a href="#">
                    <span class="elementor-icon-list-text">
                      Home
                    </span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="elementor-element elementor-element-5c23cda elementor-widget__width-auto elementor-widget elementor-widget-heading"
          data-id="5c23cda" data-element_type="widget" data-widget_type="heading.default">
            <div class="elementor-widget-container">
              <style>
                /*! elementor - v3.17.0 - 25-10-2023 */ .elementor-heading-title{padding:0;margin:0;line-height:1}.elementor-widget-heading
                .elementor-heading-title[class*=elementor-size-]>a{color:inherit;font-size:inherit;line-height:inherit}.elementor-widget-heading
                .elementor-heading-title.elementor-size-small{font-size:15px}.elementor-widget-heading
                .elementor-heading-title.elementor-size-medium{font-size:19px}.elementor-widget-heading
                .elementor-heading-title.elementor-size-large{font-size:29px}.elementor-widget-heading
                .elementor-heading-title.elementor-size-xl{font-size:39px}.elementor-widget-heading
                .elementor-heading-title.elementor-size-xxl{font-size:59px}
              </style>
              <h2 class="elementor-heading-title elementor-size-default">
                /
              </h2>
            </div>
          </div>
          <div class="elementor-element elementor-element-efda0e7 elementor-widget__width-auto elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list"
          data-id="efda0e7" data-element_type="widget" data-widget_type="icon-list.default">
            <div class="elementor-widget-container">
              <ul class="elementor-icon-list-items">
                <li class="elementor-icon-list-item">
                  <span class="elementor-icon-list-text">
                    
                  </span>
                </li>
              </ul>
            </div>
          </div>
          <div class="elementor-element elementor-element-c204a4e elementor-widget elementor-widget-heading"
          data-id="c204a4e" data-element_type="widget" data-widget_type="heading.default">
            <div class="elementor-widget-container">
              <h2 class="elementor-heading-title elementor-size-default">
                Albums
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="elementor-section elementor-top-section elementor-element elementor-element-4b27f2b elementor-section-boxed elementor-section-height-default elementor-section-height-default tf-sticky-section tf-sticky-no"
  data-id="4b27f2b" data-element_type="section" data-settings="{&quot;tf_sticky&quot;:&quot;no&quot;}">
    <div class="elementor-container elementor-column-gap-default">
      <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-1aceaf0"
      data-id="1aceaf0" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-dec83f1 elementor-widget elementor-widget-tfposts"
          data-id="dec83f1" data-element_type="widget" data-widget_type="tfposts.default">
            <div class="elementor-widget-container">
              <div id="tf-posts-dec83f1" class="tf-posts-dec83f1 tf-posts-wrap column-3 tablet-column-3 mobile-column-1  no-arrow no-bullets grid-styles-default featured-post-top  "
              data-tabid="dec83f1" data-class_id="tf-posts-dec83f1" data-loop="" data-auto=""
              data-column="" data-column2="" data-column3="" data-spacer="" data-prev_icon=""
              data-next_icon="">
                <div class="tf-posts  grid">
                  @foreach($posts as $item)
                  <div class="column">
                    <div class="entry blog-post">
                      <div class="featured-post">
                        <img decoding="async" src="{{ $item->image }}"
                        alt="image">
                        <a href="#"
                        class="overlay">
                          <i class="fas fa-plus" aria-hidden="true">
                          </i>
                        </a>
                      </div>
                      <div class="content">
                        
                        <h2 class="title">
                          <a href="/detail/{{ $item->url_part }}.html"
                          title="Best place of true splander">
                            {{ $item->title }}
                          </a>
                        </h2>
                       
                       
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>

                {{ $posts->withQueryString()->links('frontend.pagination.newdefault') }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

@endsection
