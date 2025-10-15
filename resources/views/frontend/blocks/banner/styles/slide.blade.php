@if ($block)
  @php
    // Filter all blocks by parent_id
    $block_childs = $blocks->filter(function ($item, $key) use ($block) {
        return $item->parent_id == $block->id;
    });
  @endphp
  <section id="slider" class="slider-element swiper_wrapper min-vh-md-75 min-vh-50 include-header" data-nav="true"
    data-loop="true" data-autoplay="5000">
    <div class="slider-inner">
      <div class="swiper-container swiper-parent">
        <div class="swiper-wrapper">
          @if ($block_childs)
            @foreach ($block_childs as $item)
              @php
                $title = $item->json_params->title->{$locale} ?? $item->title;
                $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                $image = $item->image != '' ? $item->image : url('assets/images/slide-01.jpg');
                $url_link = $item->url_link != '' ? $item->url_link : '';
                $url_link_title = $item->json_params->url_link_title->{$locale} ?? $item->url_link_title;
                $icon = $item->icon != '' ? $item->icon : '';
                $style = $item->json_params->style ?? '';
              @endphp
              <div class="swiper-slide">
                <div class="container">
                  <div class="slider-caption {{ $style }}" style="max-width: 700px;">
                    <div>
                      <h2 data-animate="flipInX">
                        {!! $title !!}
                      </h2>
                      <p class="d-none d-sm-block" data-animate="flipInX" data-delay="500">
                        {!! $brief !!}
                      </p>
                      @if ($url_link != '')
                        <a href="{{ $url_link }}" class="button button-rounded m-0" data-animate="flipInX"
                          data-delay="1000">
                          {{ $url_link_title }}
                        </a>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="swiper-slide-bg" style="background-image: url('{{ $image }}');"></div>
              </div>
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </section>
@endif
