@if ($block)
  @php
    // Filter all blocks by parent_id
    $block_childs = $blocks->filter(function ($item, $key) use ($block) {
        return $item->parent_id == $block->id;
    });
  @endphp
  <section id="clients" class="section m-0">
    <div class="container">
      <div class="owl-carousel image-carousel carousel-widget" data-margin="20" data-nav="false" data-pagi="true"
        data-items-xs="2" data-items-sm="3" data-items-md="4" data-items-lg="5" data-items-xl="6">

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
            <div class="oc-item">
              <a href="{{ $url_link }}"><img src="{{ $image }}" alt="{{ $title }}"></a>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </section>
@endif
