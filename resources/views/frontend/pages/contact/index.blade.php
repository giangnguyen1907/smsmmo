@extends('frontend.layouts.default')

@php

    $seo_title = $taxonomy->title->$locale ?? ($page->title ?? ($page->name ?? ''));
    $seo_brief = $taxonomy->brief->$locale ?? "";
    $seo_keyword = $taxonomy->meta_keyword ?? null;
    $seo_description = $taxonomy->meta_description ?? null;
    $image_background = $taxonomy->json_params->image_background ? 'background-image: url('.$taxonomy->json_params->image_background.')' : '';
	$image = $taxonomy->json_params->image ??  '';

@endphp

@section('content')

<div data-elementor-type="wp-page" data-elementor-id="113" class="elementor elementor-113">

<link rel='stylesheet' href="{{ asset('themes/frontend/css/post-113.css') }}" media='all' />

<section class="elementor-section elementor-top-section elementor-element elementor-element-1acef51a elementor-section-boxed elementor-section-height-default elementor-section-height-default"
data-id="1acef51a" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
  <div class="elementor-background-overlay" style="{{ $image_background }}">
  </div>
  <div class="elementor-container elementor-column-gap-default">
    <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-2db3febc"
    data-id="2db3febc" data-element_type="column">
      <div class="elementor-widget-wrap elementor-element-populated">
        <div class="elementor-element elementor-element-32f73c5b elementor-widget elementor-widget-heading animated fadeInUp"
        data-id="32f73c5b" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;}"
        data-widget_type="heading.default">
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
            <h1 class="elementor-heading-title elementor-size-default">
			{{ $seo_title }}
            </h1>
          </div>
        </div>
        <div class="elementor-element elementor-element-51bd466e elementor-widget elementor-widget-heading animated fadeInDown"
        data-id="51bd466e" data-element_type="widget" data-settings="{&quot;_animation&quot;:&quot;fadeInDown&quot;}"
        data-widget_type="heading.default">
          <div class="elementor-widget-container">
            <h3 class="elementor-heading-title elementor-size-default">
              {{ $seo_brief }}
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="elementor-section elementor-top-section elementor-element elementor-element-2f341679 elementor-reverse-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default"
data-id="2f341679" data-element_type="section">
  <div class="elementor-container elementor-column-gap-default">
    <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-1bd8a294 animated fadeInLeft"
    data-id="1bd8a294" data-element_type="column" data-settings="{&quot;animation&quot;:&quot;fadeInLeft&quot;}">
      <div class="elementor-widget-wrap elementor-element-populated">
        <div class="elementor-element elementor-element-7b626364 elementor-widget elementor-widget-image"
        data-id="7b626364" data-element_type="widget" data-widget_type="image.default">
          <div class="elementor-widget-container">
            <style>
              .elementor-widget-image{text-align:center}.elementor-widget-image
              a{display:inline-block}.elementor-widget-image a img[src$=".svg"]{width:48px}.elementor-widget-image
              img{vertical-align:middle;display:inline-block}
            </style>
            <img fetchpriority="high" decoding="async" width="1080" height="720" src="{{ $image }}"
            class="attachment-full size-full wp-image-119" alt="" 
            sizes="(max-width: 1080px) 100vw, 1080px">
          </div>
        </div>
      </div>
    </div>
    <div class="elementor-column elementor-col-50 elementor-top-column elementor-element elementor-element-72a3b492 animated fadeInRight"
    data-id="72a3b492" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInRight&quot;}">
      <div class="elementor-widget-wrap elementor-element-populated">
        <div class="elementor-element elementor-element-3895a81b elementor-widget elementor-widget-heading"
        data-id="3895a81b" data-element_type="widget" data-widget_type="heading.default">
          <div class="elementor-widget-container">
            <h6 class="elementor-heading-title elementor-size-default">
              {{ $array_translate[strtolower('Get in touch')]->$locale ?? 'Get in touch' }}
            </h6>
          </div>
        </div>
        <div class="elementor-element elementor-element-5246dee7 elementor-widget elementor-widget-heading"
        data-id="5246dee7" data-element_type="widget" data-widget_type="heading.default">
          <div class="elementor-widget-container">
            <h2 class="elementor-heading-title elementor-size-default">
              {{ $array_translate[strtolower('Send us a message.')]->$locale ?? 'Send us a message.' }}
            </h2>
          </div>
        </div>
        <div class="elementor-element elementor-element-34163ccf elementor-widget elementor-widget-text-editor"
        data-id="34163ccf" data-element_type="widget" data-widget_type="text-editor.default">
          <div class="elementor-widget-container">
            <style>
              .elementor-widget-text-editor.elementor-drop-cap-view-stacked
              .elementor-drop-cap{background-color:#69727d;color:#fff}.elementor-widget-text-editor.elementor-drop-cap-view-framed
              .elementor-drop-cap{color:#69727d;border:3px solid;background-color:transparent}.elementor-widget-text-editor:not(.elementor-drop-cap-view-default)
              .elementor-drop-cap{margin-top:8px}.elementor-widget-text-editor:not(.elementor-drop-cap-view-default)
              .elementor-drop-cap-letter{width:1em;height:1em}.elementor-widget-text-editor
              .elementor-drop-cap{float:left;text-align:center;line-height:1;font-size:50px}.elementor-widget-text-editor
              .elementor-drop-cap-letter{display:inline-block}
            </style>
            <p>
              {{ $array_translate[strtolower('We are delighted to hear from you')]->$locale ?? 'We are delighted to hear from you' }}
            </p>
          </div>
        </div>
        <div class="elementor-element elementor-element-53b1314e elementor-button-align-stretch elementor-widget elementor-widget-form"
        data-id="53b1314e" data-element_type="widget" data-settings="{&quot;step_next_label&quot;:&quot;Next&quot;,&quot;step_previous_label&quot;:&quot;Previous&quot;,&quot;button_width&quot;:&quot;100&quot;,&quot;step_type&quot;:&quot;number_text&quot;,&quot;step_icon_shape&quot;:&quot;circle&quot;}"
        data-widget_type="form.default">
          <div class="elementor-widget-container">
            <style>
               .elementor-button.elementor-hidden,.elementor-hidden{display:none}.e-form__step{width:100%}.e-form__step:not(.elementor-hidden){display:flex;flex-wrap:wrap}.e-form__buttons{flex-wrap:wrap}.e-form__buttons,.e-form__buttons__wrapper{display:flex}.e-form__indicators{display:flex;justify-content:space-between;align-items:center;flex-wrap:nowrap;font-size:13px;margin-bottom:var(--e-form-steps-indicators-spacing)}.e-form__indicators__indicator{display:flex;flex-direction:column;align-items:center;justify-content:center;flex-basis:0;padding:0
              var(--e-form-steps-divider-gap)}.e-form__indicators__indicator__progress{width:100%;position:relative;background-color:var(--e-form-steps-indicator-progress-background-color);border-radius:var(--e-form-steps-indicator-progress-border-radius);overflow:hidden}.e-form__indicators__indicator__progress__meter{width:var(--e-form-steps-indicator-progress-meter-width,0);height:var(--e-form-steps-indicator-progress-height);line-height:var(--e-form-steps-indicator-progress-height);padding-right:15px;border-radius:var(--e-form-steps-indicator-progress-border-radius);background-color:var(--e-form-steps-indicator-progress-color);color:var(--e-form-steps-indicator-progress-meter-color);text-align:right;transition:width
              .1s linear}.e-form__indicators__indicator:first-child{padding-left:0}.e-form__indicators__indicator:last-child{padding-right:0}.e-form__indicators__indicator--state-inactive{color:var(--e-form-steps-indicator-inactive-primary-color,#c2cbd2)}.e-form__indicators__indicator--state-inactive
              [class*=indicator--shape-]:not(.e-form__indicators__indicator--shape-none){background-color:var(--e-form-steps-indicator-inactive-secondary-color,#fff)}.e-form__indicators__indicator--state-inactive
              object,.e-form__indicators__indicator--state-inactive svg{fill:var(--e-form-steps-indicator-inactive-primary-color,#c2cbd2)}.e-form__indicators__indicator--state-active{color:var(--e-form-steps-indicator-active-primary-color,#39b54a);border-color:var(--e-form-steps-indicator-active-secondary-color,#fff)}.e-form__indicators__indicator--state-active
              [class*=indicator--shape-]:not(.e-form__indicators__indicator--shape-none){background-color:var(--e-form-steps-indicator-active-secondary-color,#fff)}.e-form__indicators__indicator--state-active
              object,.e-form__indicators__indicator--state-active svg{fill:var(--e-form-steps-indicator-active-primary-color,#39b54a)}.e-form__indicators__indicator--state-completed{color:var(--e-form-steps-indicator-completed-secondary-color,#fff)}.e-form__indicators__indicator--state-completed
              [class*=indicator--shape-]:not(.e-form__indicators__indicator--shape-none){background-color:var(--e-form-steps-indicator-completed-primary-color,#39b54a)}.e-form__indicators__indicator--state-completed
              .e-form__indicators__indicator__label{color:var(--e-form-steps-indicator-completed-primary-color,#39b54a)}.e-form__indicators__indicator--state-completed
              .e-form__indicators__indicator--shape-none{color:var(--e-form-steps-indicator-completed-primary-color,#39b54a);background-color:initial}.e-form__indicators__indicator--state-completed
              object,.e-form__indicators__indicator--state-completed svg{fill:var(--e-form-steps-indicator-completed-secondary-color,#fff)}.e-form__indicators__indicator__icon{width:var(--e-form-steps-indicator-padding,30px);height:var(--e-form-steps-indicator-padding,30px);font-size:var(--e-form-steps-indicator-icon-size);border-width:1px;border-style:solid;display:flex;justify-content:center;align-items:center;overflow:hidden;margin-bottom:10px}.e-form__indicators__indicator__icon
              img,.e-form__indicators__indicator__icon object,.e-form__indicators__indicator__icon
              svg{width:var(--e-form-steps-indicator-icon-size);height:auto}.e-form__indicators__indicator__icon
              .e-font-icon-svg{height:1em}.e-form__indicators__indicator__number{width:var(--e-form-steps-indicator-padding,30px);height:var(--e-form-steps-indicator-padding,30px);border-width:1px;border-style:solid;display:flex;justify-content:center;align-items:center;margin-bottom:10px}.e-form__indicators__indicator--shape-circle{border-radius:50%}.e-form__indicators__indicator--shape-square{border-radius:0}.e-form__indicators__indicator--shape-rounded{border-radius:5px}.e-form__indicators__indicator--shape-none{border:0}.e-form__indicators__indicator__label{text-align:center}.e-form__indicators__indicator__separator{width:100%;height:var(--e-form-steps-divider-width);background-color:#c2cbd2}.e-form__indicators--type-icon,.e-form__indicators--type-icon_text,.e-form__indicators--type-number,.e-form__indicators--type-number_text{align-items:flex-start}.e-form__indicators--type-icon
              .e-form__indicators__indicator__separator,.e-form__indicators--type-icon_text
              .e-form__indicators__indicator__separator,.e-form__indicators--type-number
              .e-form__indicators__indicator__separator,.e-form__indicators--type-number_text
              .e-form__indicators__indicator__separator{margin-top:calc(var(--e-form-steps-indicator-padding,
              30px) / 2 - var(--e-form-steps-divider-width, 1px) / 2)}.elementor-field-type-hidden{display:none}.elementor-field-type-html{display:inline-block}.elementor-login
              .elementor-lost-password,.elementor-login .elementor-remember-me{font-size:.85em}.elementor-field-type-recaptcha_v3
              .elementor-field-label{display:none}.elementor-field-type-recaptcha_v3
              .grecaptcha-badge{z-index:1}.elementor-button .elementor-form-spinner{order:3}.elementor-form
              .elementor-button>span{display:flex;justify-content:center;align-items:center}.elementor-form
              .elementor-button .elementor-button-text{white-space:normal;flex-grow:0}.elementor-form
              .elementor-button svg{height:auto}.elementor-form .elementor-button .e-font-icon-svg{height:1em}.elementor-select-wrapper
              .select-caret-down-wrapper{position:absolute;top:50%;transform:translateY(-50%);inset-inline-end:10px;pointer-events:none;font-size:11px}.elementor-select-wrapper
              .select-caret-down-wrapper svg{display:unset;width:1em;aspect-ratio:unset;fill:currentColor}.elementor-select-wrapper
              .select-caret-down-wrapper i{font-size:19px;line-height:2}.elementor-select-wrapper.remove-before:before{content:""!important}
            </style>
            <form class="elementor-form" method="post" name="New Form" action="{{ route('frontend.contact.store') }}" >
			@csrf
              <div class="elementor-form-fields-wrapper elementor-labels-above">
                <div class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50">
                  <label for="form-field-name" class="elementor-field-label">
                    {{ $array_translate[strtolower('Name')]->$locale ?? 'Name' }}
                  </label>
                  <input size="1" type="text" name="name" id="form-field-name"
                  class="elementor-field elementor-size-md  elementor-field-textual" placeholder="{{ $array_translate[strtolower('Name')]->$locale ?? 'Name' }}">
                </div>
                <div class="elementor-field-type-tel elementor-field-group elementor-column elementor-field-group-field_b5deeaa elementor-col-50">
                  <label for="form-field-field_b5deeaa" class="elementor-field-label">
                    {{ $array_translate[strtolower('Phone')]->$locale ?? 'Phone' }}
                  </label>
                  <input size="1" type="tel" name="phone" id="form-field-field_b5deeaa"
                  class="elementor-field elementor-size-md  elementor-field-textual" placeholder="{{ $array_translate[strtolower('Phone')]->$locale ?? 'Phone' }}"
                  pattern="[0-9()#&amp;+*-=.]+" title="Only numbers and phone characters (#, -, *, etc) are accepted.">
                </div>
                <div class="elementor-field-type-email elementor-field-group elementor-column elementor-field-group-email elementor-col-100 elementor-field-required">
                  <label for="form-field-email" class="elementor-field-label">
                    {{ $array_translate[strtolower('Email')]->$locale ?? 'Email' }}
                  </label>
                  <input size="1" type="email" name="email" id="form-field-email"
                  class="elementor-field elementor-size-md  elementor-field-textual" placeholder="{{ $array_translate[strtolower('Email')]->$locale ?? 'Email' }}"
                  required="required" aria-required="true">
                </div>
                <div class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-field_0bd83ff elementor-col-100 elementor-field-required">
                  <label for="form-field-field_0bd83ff" class="elementor-field-label">
                    {{ $array_translate[strtolower('Subject')]->$locale ?? 'Subject' }}
                  </label>
                  <input size="1" type="text" name="subject" id="form-field-field_0bd83ff"
                  class="elementor-field elementor-size-md  elementor-field-textual" placeholder="{{ $array_translate[strtolower('Subject')]->$locale ?? 'Subject' }}"
                  required="required" aria-required="true">
                </div>
                <div class="elementor-field-type-textarea elementor-field-group elementor-column elementor-field-group-message elementor-col-100">
                  <label for="form-field-message" class="elementor-field-label">
                    {{ $array_translate[strtolower('Message')]->$locale ?? 'Message' }}
                  </label>
                  <textarea class="elementor-field-textual elementor-field  elementor-size-md"
                  name="content" id="form-field-message" rows="4" placeholder="{{ $array_translate[strtolower('Message')]->$locale ?? 'Message' }}">
                  </textarea>
                </div>
                <div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
                  <button type="submit" class="elementor-button elementor-size-md">
                    <span>
                      <span class="elementor-align-icon-left elementor-button-icon">
                        <i aria-hidden="true" class="lnr lnr-envelope">
                        </i>
                      </span>
                      <span class="elementor-button-text">
                        {{ $array_translate[strtolower('Send Message')]->$locale ?? 'Send Message' }}
                      </span>
                    </span>
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

</div>
@endsection
