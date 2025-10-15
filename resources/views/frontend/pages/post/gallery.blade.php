@extends('frontend.layouts.default')
@php
$page_title = $taxonomy->title;
$title = $taxonomy->title;
$image = $taxonomy->image;
$seo_title = $title;
$seo_keyword = $taxonomy->seo_keyword;
$seo_description = $taxonomy->seo_description;
$seo_image = $image ?? null;

$image_background = $taxonomy->image ? 'background-image: url('.$taxonomy->image.')' : '';

@endphp

@section('content')

<link rel="stylesheet" id="elementor-post-1540-css" href="{{ asset('themes/frontend/css/post-1540.css') }}" media="all">

<div data-elementor-type="wp-post" data-elementor-id="1540" class="elementor elementor-1540">
  <section class="elementor-section elementor-top-section elementor-element elementor-element-d068fdc elementor-section-boxed elementor-section-height-default elementor-section-height-default tf-sticky-section tf-sticky-no"
  data-id="d068fdc" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;tf_sticky&quot;:&quot;no&quot;}" style="{{ $image_background }}">
    <div class="elementor-background-overlay">
    </div>
    <div class="elementor-container elementor-column-gap-default">
      <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-ea0d3ee"
      data-id="ea0d3ee" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-7507409 elementor-widget__width-auto elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list"
          data-id="7507409" data-element_type="widget" data-widget_type="icon-list.default">
            <div class="elementor-widget-container">
              <ul class="elementor-icon-list-items">
                <li class="elementor-icon-list-item">
                  <a href="/">
                    <span class="elementor-icon-list-text">
                      Home
                    </span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="elementor-element elementor-element-f76358d elementor-widget__width-auto elementor-widget elementor-widget-heading"
          data-id="f76358d" data-element_type="widget" data-widget_type="heading.default">
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
          <div class="elementor-element elementor-element-52f555a elementor-widget__width-auto elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list"
          data-id="52f555a" data-element_type="widget" data-widget_type="icon-list.default">
            <div class="elementor-widget-container">
              <ul class="elementor-icon-list-items">
                <li class="elementor-icon-list-item">
                  <span class="elementor-icon-list-text">
                  </span>
                </li>
              </ul>
            </div>
          </div>
          <div class="elementor-element elementor-element-08233b5 elementor-widget elementor-widget-heading"
          data-id="08233b5" data-element_type="widget" data-widget_type="heading.default">
            <div class="elementor-widget-container">
              <h2 class="elementor-heading-title elementor-size-default">
			  {{ $title }}
              </h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  
  <section class="elementor-section elementor-top-section elementor-element elementor-element-400e683 elementor-section-boxed elementor-section-height-default elementor-section-height-default tf-sticky-section tf-sticky-no"
  data-id="400e683" data-element_type="section" data-settings="{&quot;tf_sticky&quot;:&quot;no&quot;}">
    <div class="elementor-container elementor-column-gap-default">
		 @foreach($posts as $item)
		<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-74cdabe"
      data-id="74cdabe" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-fd97f58 elementor-widget elementor-widget-tfimagebox"
          data-id="fd97f58" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img loading="lazy" decoding="async" width="378" height="407" src="{{ $item->image }}"
                  class="attachment-full size-full wp-image-1552" alt="" srcset="{{ $item->image }}"
                  sizes="(max-width: 378px) 100vw, 378px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
		</div>
		@endforeach
		
		<!--
		<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-8030a3c"
      data-id="8030a3c" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-c4cc811 elementor-widget elementor-widget-tfimagebox"
          data-id="c4cc811" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img fetchpriority="high" decoding="async" width="378" height="407" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/gallery-1.jpg"
                  class="attachment-full size-full wp-image-1543" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/gallery-1.jpg 378w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/gallery-1-279x300.jpg 279w"
                  sizes="(max-width: 378px) 100vw, 378px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="elementor-element elementor-element-8ae18c8 elementor-widget elementor-widget-tfimagebox"
          data-id="8ae18c8" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img decoding="async" width="756" height="814" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/imaage-gallery-LWJQAAC.jpg"
                  class="attachment-full size-full wp-image-2136" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/imaage-gallery-LWJQAAC.jpg 756w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/imaage-gallery-LWJQAAC-279x300.jpg 279w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/imaage-gallery-LWJQAAC-600x646.jpg 600w"
                  sizes="(max-width: 756px) 100vw, 756px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-34367cc"
      data-id="34367cc" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-3b87092 elementor-widget elementor-widget-tfimagebox"
          data-id="3b87092" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img decoding="async" width="754" height="1644" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-promotions-AVVSC9Q-1.jpg"
                  class="attachment-full size-full wp-image-2135" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-promotions-AVVSC9Q-1.jpg 754w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-promotions-AVVSC9Q-1-138x300.jpg 138w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-promotions-AVVSC9Q-1-470x1024.jpg 470w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-promotions-AVVSC9Q-1-704x1536.jpg 704w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-promotions-AVVSC9Q-1-600x1308.jpg 600w"
                  sizes="(max-width: 754px) 100vw, 754px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-0569fd6"
      data-id="0569fd6" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-cddf7ba elementor-widget elementor-widget-tfimagebox"
          data-id="cddf7ba" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img loading="lazy" decoding="async" width="756" height="814" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-6JERQNX.jpg"
                  class="attachment-full size-full wp-image-2137" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-6JERQNX.jpg 756w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-6JERQNX-279x300.jpg 279w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-6JERQNX-600x646.jpg 600w"
                  sizes="(max-width: 756px) 100vw, 756px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="elementor-element elementor-element-cfcd10f elementor-widget elementor-widget-tfimagebox"
          data-id="cfcd10f" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img loading="lazy" decoding="async" width="756" height="814" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-H2GADQG.jpg"
                  class="attachment-full size-full wp-image-2138" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-H2GADQG.jpg 756w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-H2GADQG-279x300.jpg 279w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-H2GADQG-600x646.jpg 600w"
                  sizes="(max-width: 756px) 100vw, 756px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-1ecf96c"
      data-id="1ecf96c" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-afb70c8 elementor-widget elementor-widget-tfimagebox"
          data-id="afb70c8" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img loading="lazy" decoding="async" width="378" height="407" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/gallery-6.jpg"
                  class="attachment-full size-full wp-image-1548" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/gallery-6.jpg 378w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/gallery-6-279x300.jpg 279w"
                  sizes="(max-width: 378px) 100vw, 378px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="elementor-element elementor-element-62beabd elementor-widget elementor-widget-tfimagebox"
          data-id="62beabd" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img loading="lazy" decoding="async" width="756" height="814" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-galley-K4QQAM9.jpg"
                  class="attachment-full size-full wp-image-2140" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-galley-K4QQAM9.jpg 756w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-galley-K4QQAM9-279x300.jpg 279w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-galley-K4QQAM9-600x646.jpg 600w"
                  sizes="(max-width: 756px) 100vw, 756px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
	   -->
    </div>
  </section>
 
  <section class="elementor-section elementor-top-section elementor-element elementor-element-a2f0e46 elementor-section-boxed elementor-section-height-default elementor-section-height-default tf-sticky-section tf-sticky-no"
  data-id="a2f0e46" data-element_type="section" data-settings="{&quot;tf_sticky&quot;:&quot;no&quot;}">
    <div class="elementor-container elementor-column-gap-default">
      
	  <!--
	  <div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-348997a"
      data-id="348997a" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-dceea97 elementor-widget elementor-widget-tfimagebox"
          data-id="dceea97" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img loading="lazy" decoding="async" width="756" height="814" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-NAHJPRF.jpg"
                  class="attachment-full size-full wp-image-2139" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-NAHJPRF.jpg 756w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-NAHJPRF-279x300.jpg 279w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-gallery-NAHJPRF-600x646.jpg 600w"
                  sizes="(max-width: 756px) 100vw, 756px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-a7476fd elementor-hidden-tablet"
      data-id="a7476fd" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
          <div class="elementor-element elementor-element-7712b87 elementor-widget elementor-widget-tfimagebox"
          data-id="7712b87" data-element_type="widget" data-widget_type="tfimagebox.default">
            <div class="elementor-widget-container">
              <div class="tf-imagebox style-2">
                <div class="image">
                  <img loading="lazy" decoding="async" width="1532" height="814" src="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-home02-BBXZPVJ-1.jpg"
                  class="attachment-full size-full wp-image-2134" alt="" srcset="https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-home02-BBXZPVJ-1.jpg 1532w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-home02-BBXZPVJ-1-300x159.jpg 300w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-home02-BBXZPVJ-1-1024x544.jpg 1024w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-home02-BBXZPVJ-1-768x408.jpg 768w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-home02-BBXZPVJ-1-800x425.jpg 800w, https://themesflat.co/kitmellis/wp-content/uploads/2023/04/image-home02-BBXZPVJ-1-600x319.jpg 600w"
                  sizes="(max-width: 1532px) 100vw, 1532px">
                  <div class="image-overlay fade-in">
                  </div>
                </div>
                <div class="content-only ">
                </div>
                <div class="content fade-in">
                  <div class="tf-button-container ">
                    <a class="tf-button bt_icon_after hover-default" href="">
                      <i class="icon icon-plus">
                      </i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
	  -->
      
	  
    </div>
  </section>
</div>

@endsection