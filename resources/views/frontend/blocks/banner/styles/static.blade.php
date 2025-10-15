@if ($block)
  @php
    $title = $block->json_params->title->{$locale} ?? $block->title;
    $brief = $block->json_params->brief->{$locale} ?? $block->brief;
    $content = $block->json_params->content->{$locale} ?? $block->content;
    $image_background = $block->image_background != '' ? $block->image_background : '';
    $url_link = $block->url_link != '' ? $block->url_link : '';
    $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
    $style = isset($block->json_params->style) && $block->json_params->style == 'slider-caption-right' ? 'float-end' : '';
  @endphp
  <section id="adv" class="slider-element swiper_wrapper min-vh-md-75 min-vh-50">
    <div class="slider-inner">
      <div class="swiper-container swiper-parent">
        <div class="swiper-wrapper">
          <div class="swiper-slide">
            <div class="container">
              <div class="slider-caption {{ $style }}" style="max-width: 700px;">
                <div>
                  <h2>{!! $title !!}</h2>
                  <p>{!! nl2br($brief) !!}</p>
                  <p class="d-none d-sm-block mb-5">{!! nl2br($content) !!}</p>
                  @if ($url_link)
                    <a href="{{ $url_link }}" class="button button-rounded button-large m-0">
                      {{ $url_link_title }}
                    </a>
                  @endif
                </div>
              </div>
            </div>
            <div class="swiper-slide-bg" style="background-image: url('{{ $image_background }}');"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
