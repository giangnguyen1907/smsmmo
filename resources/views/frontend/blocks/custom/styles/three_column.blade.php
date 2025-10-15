@if ($block)
  @php
    // Filter all blocks by parent_id
    $block_childs = $blocks->filter(function ($item, $key) use ($block) {
        return $item->parent_id == $block->id;
    });
  @endphp
  @if ($block_childs)
    <style>
      #opening .slider-bottom-box {
        display: block;
        padding: 40px;
        background: #FFF;
        box-shadow: 0px 10px 40px 0px rgba(47, 47, 47, 0.1);
        border-radius: 3px;
      }

      #opening .slider-box-wrap {
        position: relative;
        top: -50px;
        margin-bottom: -50px;
        z-index: 2;
      }

      #opening .iconlist li {
        position: relative;
        display: inherit;
        border-bottom: 1px dashed;
        margin-bottom: 10px;
        padding-bottom: 10px;
      }

    </style>
    <section id="opening">
      <div class="container clearfix">
        <div class="row justify-content-center slider-box-wrap clearfix">
          <div class="col-12">
            <div class="slider-bottom-box">
              <div class="row clearfix">
                @foreach ($block_childs as $item)
                  @php
                    $title = $item->json_params->title->{$locale} ?? $item->title;
                    $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                    $image = $item->image != '' ? $item->image : url('assets/img/behavioral.jpg');
                    $url_link = $item->url_link != '' ? $item->url_link : '';
                    $url_link_title = $item->json_params->url_link_title->{$locale} ?? $item->url_link_title;
                    $icon = $item->icon != '' ? $item->icon : '';
                    $style = $item->json_params->style ?? '';
                  @endphp
                  <div class="col-lg-4 col-sm-6">
                    <div class="card-body">
                      <h4>{!! $title !!}</h4>
                      <p>
                        {!! nl2br($brief) !!}
                      </p>
                      @if ($item->sub > 0)
                        <ul class="iconlist mb-0">
                          @foreach ($blocks as $item_sub)
                            @if ($item_sub->parent_id == $item->id)
                              @php
                                $title = $item_sub->json_params->title->{$locale} ?? $item_sub->title;
                                $brief = $item_sub->json_params->brief->{$locale} ?? $item_sub->brief;
                                $icon = $item_sub->icon != '' ? $item_sub->icon : '';
                                $style = $item_sub->json_params->style ?? '';
                              @endphp
                              <li>
                                {!! $icon !!}
                                <strong>{{ $title }}</strong>
                                <span class="float-end">{{ $brief }}</span>
                              </li>
                            @endif
                          @endforeach
                        </ul>
                      @endif
                      @if ($url_link != '')
                        <a href="{{ $url_link }}"
                          class="button button-border button-border-thin {{ $style }} m-0">
                          {!! $icon !!}
                          {{ $url_link_title }}
                        </a>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endif
@endif
