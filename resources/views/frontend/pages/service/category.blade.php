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


$chiadoi = ceil(count($posts)/2);


@endphp

@section('content')

<div data-elementor-type="wp-page" data-elementor-id="218" class="elementor elementor-218">
<link rel='stylesheet' href="{{ asset('themes/frontend/css/post-218.css') }}" media='all' />

<section class="elementor-section elementor-top-section elementor-element elementor-element-5527ae1c elementor-section-boxed elementor-section-height-default elementor-section-height-default"
data-id="5527ae1c" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
  <div class="elementor-background-overlay" style="{{$image_background}}">
  </div>
  <div class="elementor-container elementor-column-gap-default">
    <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-3afe8b24"
    data-id="3afe8b24" data-element_type="column">
      <div class="elementor-widget-wrap elementor-element-populated">
        <div class="elementor-element elementor-element-4912f113 elementor-widget elementor-widget-heading animated fadeInUp"
        data-id="4912f113" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
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
        <div class="elementor-element elementor-element-35748cab elementor-widget elementor-widget-heading animated fadeInDown"
        data-id="35748cab" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}"
        data-widget_type="heading.default">
          <div class="elementor-widget-container">
            <h3 class="elementor-heading-title elementor-size-default">
              {{ $page_brief }}
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<style>
  .elementor-widget-price-list
  .elementor-price-list{list-style:none;padding:0;margin:0}.elementor-widget-price-list
  .elementor-price-list li{margin:0}.elementor-price-list li:not(:last-child){margin-bottom:20px}.elementor-price-list
  .elementor-price-list-image{max-width:50%;flex-shrink:0;padding-right:25px}.elementor-price-list
  .elementor-price-list-image img{width:100%}.elementor-price-list .elementor-price-list-header,.elementor-price-list
  .elementor-price-list-item,.elementor-price-list .elementor-price-list-text{display:flex}.elementor-price-list
  .elementor-price-list-item{align-items:flex-start}.elementor-price-list
  .elementor-price-list-item .elementor-price-list-text{align-items:flex-start;flex-wrap:wrap;flex-grow:1}.elementor-price-list
  .elementor-price-list-item .elementor-price-list-header{align-items:center;flex-basis:100%;font-size:19px;font-weight:600;margin-bottom:10px;justify-content:space-between}.elementor-price-list
  .elementor-price-list-item .elementor-price-list-title{max-width:80%}.elementor-price-list
  .elementor-price-list-item .elementor-price-list-price{font-weight:600}.elementor-price-list
  .elementor-price-list-item p.elementor-price-list-description{flex-basis:100%;font-size:14px;margin:0}.elementor-price-list
  .elementor-price-list-item .elementor-price-list-separator{flex-grow:1;margin-left:10px;margin-right:10px;border-bottom-style:dotted;border-bottom-width:2px;height:0}.elementor-price-table{text-align:center}.elementor-price-table
  .elementor-price-table__header{background:var(--e-price-table-header-background-color,#555);padding:20px
  0}.elementor-price-table .elementor-price-table__heading{margin:0;padding:0;line-height:1.2;font-size:24px;font-weight:600;color:#fff}.elementor-price-table
  .elementor-price-table__subheading{font-size:13px;font-weight:400;color:#fff}.elementor-price-table
  .elementor-price-table__original-price{margin-right:15px;text-decoration:line-through;font-size:.5em;line-height:1;font-weight:400;align-self:center}.elementor-price-table
  .elementor-price-table__original-price .elementor-price-table__currency{font-size:1em;margin:0}.elementor-price-table
  .elementor-price-table__price{display:flex;justify-content:center;align-items:center;flex-wrap:wrap;flex-direction:row;color:#555;font-weight:800;font-size:65px;padding:40px
  0}.elementor-price-table .elementor-price-table__price .elementor-typo-excluded{line-height:normal;letter-spacing:normal;text-transform:none;font-weight:400;font-size:medium;font-style:normal}.elementor-price-table
  .elementor-price-table__after-price{display:flex;flex-wrap:wrap;text-align:left;align-self:stretch;align-items:flex-start;flex-direction:column}.elementor-price-table
  .elementor-price-table__integer-part{line-height:.8}.elementor-price-table
  .elementor-price-table__currency,.elementor-price-table .elementor-price-table__fractional-part{line-height:1;font-size:.3em}.elementor-price-table
  .elementor-price-table__currency{margin-right:3px}.elementor-price-table
  .elementor-price-table__period{width:100%;font-size:13px;font-weight:400}.elementor-price-table
  .elementor-price-table__features-list{list-style-type:none;margin:0;padding:0;line-height:1;color:var(--e-price-table-features-list-color)}.elementor-price-table
  .elementor-price-table__features-list li{font-size:14px;line-height:1;margin:0;padding:0}.elementor-price-table
  .elementor-price-table__features-list li .elementor-price-table__feature-inner{margin-left:15px;margin-right:15px}.elementor-price-table
  .elementor-price-table__features-list li:not(:first-child):before{content:"";display:block;border:0
  solid hsla(0,0%,47.8%,.3);margin:10px 12.5%}.elementor-price-table .elementor-price-table__features-list
  i{margin-right:10px;font-size:1.3em}.elementor-price-table .elementor-price-table__features-list
  svg{margin-right:10px;fill:var(--e-price-table-features-list-color);height:1.3em;width:1.3em}.elementor-price-table
  .elementor-price-table__features-list svg~*{vertical-align:text-top}.elementor-price-table
  .elementor-price-table__footer{padding:30px 0}.elementor-price-table .elementor-price-table__additional_info{margin:0;font-size:13px;line-height:1.4}.elementor-price-table__ribbon{position:absolute;top:0;left:auto;right:0;transform:rotate(90deg);width:150px;overflow:hidden;height:150px}.elementor-price-table__ribbon-inner{text-align:center;left:0;width:200%;transform:translateY(-50%)
  translateX(-50%) translateX(35px) rotate(-45deg);margin-top:35px;font-size:13px;line-height:2;font-weight:800;text-transform:uppercase;background:#000}.elementor-price-table__ribbon.elementor-ribbon-left{transform:rotate(0);left:0;right:auto}.elementor-price-table__ribbon.elementor-ribbon-right{transform:rotate(90deg);left:auto;right:0}.elementor-widget-price-table
  .elementor-widget-container{overflow:hidden;background-color:#f7f7f7}.e-con-inner>.elementor-widget-price-list,.e-con>.elementor-widget-price-list{width:var(--container-widget-width);--flex-grow:var(--container-widget-flex-grow)}
</style>

<section class="elementor-section elementor-top-section elementor-element elementor-element-75b17a01 elementor-section-boxed elementor-section-height-default elementor-section-height-default"
data-id="75b17a01" data-element_type="section">
  <div class="elementor-container elementor-column-gap-no">
    
	<div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-34683842 animated fadeInLeft"
    data-id="34683842" data-element_type="column" data-settings="{&quot;animation&quot;:&quot;fadeInLeft&quot;}">
      <div class="elementor-widget-wrap elementor-element-populated">
        <div class="elementor-element elementor-element-67574857 elementor-widget elementor-widget-price-list"
        data-id="67574857" data-element_type="widget" data-widget_type="price-list.default">
          <div class="elementor-widget-container">
            
            <ul class="elementor-price-list">
              
			  <?php
			  $index = 0;
			  foreach ($posts as $key=> $item){
				  $index++;
			  if ($index <= $chiadoi ){
			  ?>
			  <li class="elementor-price-list-item">
                <div class="elementor-price-list-text">
                  <div class="elementor-price-list-header">
                    <span class="elementor-price-list-title">
                      {{ $item->title }}
                    </span>
                    <span class="elementor-price-list-separator">
                    </span>
                    <span class="elementor-price-list-price">
                      {{ $item->price }}
                    </span>
                  </div>
                  <p class="elementor-price-list-description">
                    {{ $item->brief }}
                  </p>
                </div>
              </li>
			  <?php unset($posts[$key]); }} ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
	
    <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-179da4ba animated fadeInRight"
    data-id="179da4ba" data-element_type="column" data-settings="{&quot;animation&quot;:&quot;fadeInRight&quot;}">
      <div class="elementor-widget-wrap elementor-element-populated">
        <div class="elementor-element elementor-element-35c4774a elementor-widget elementor-widget-price-list"
        data-id="35c4774a" data-element_type="widget" data-widget_type="price-list.default">
          <div class="elementor-widget-container">
            <ul class="elementor-price-list">
              <?php foreach ($posts as $key=> $item){ ?>
			  <li class="elementor-price-list-item">
                <div class="elementor-price-list-text">
                  <div class="elementor-price-list-header">
                    <span class="elementor-price-list-title">
                      {{ $item->title }}
                    </span>
                    <span class="elementor-price-list-separator">
                    </span>
                    <span class="elementor-price-list-price">
                      {{ $item->price }}
                    </span>
                  </div>
                  <p class="elementor-price-list-description">
                    {{ $item->brief }}
                  </p>
                </div>
              </li>
              <?php } ?>
			  
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="elementor-element elementor-element-e1c8334 e-flex e-con-boxed e-con e-parent"
data-id="e1c8334" data-element_type="container" data-settings="{&quot;content_width&quot;:&quot;boxed&quot;}"
data-core-v316-plus="true">
  <div class="e-con-inner">
    <div class="elementor-element elementor-element-2e84b68 elementor-align-center elementor-widget elementor-widget-button"
    data-id="2e84b68" data-element_type="widget" data-widget_type="button.default">
      <div class="elementor-widget-container">
        <div class="elementor-button-wrapper">
          <a class="elementor-button elementor-button-link elementor-size-md" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6Ijc0NyIsInRvZ2dsZSI6dHJ1ZX0%3D">
            <span class="elementor-button-content-wrapper">
              <span class="elementor-button-icon elementor-align-icon-right">
                <i aria-hidden="true" class="mdi mdi-call-made">
                </i>
              </span>
              <span class="elementor-button-text">
                {{ $array_translate[strtolower('Book now')]->$locale ?? "Book now" }}
              </span>
            </span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@include('frontend.element.promo')
@include('frontend.element.booking')

</div>
@endsection
