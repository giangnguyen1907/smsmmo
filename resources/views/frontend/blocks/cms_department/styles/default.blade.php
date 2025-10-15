@if ($block)
  @php
    $title = $block->json_params->title->{$locale} ?? $block->title;
    $brief = $block->json_params->brief->{$locale} ?? $block->brief;
    $background = $block->image_background != '' ? $block->image_background : url('assets/img/banner.jpg');
    $url_link = $block->url_link != '' ? $block->url_link : '';
    $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
    
    $params['status'] = App\Consts::TAXONOMY_STATUS['active'];
    $params['is_featured'] = true;
    $params['taxonomy'] = App\Consts::TAXONOMY['department'];
    
    $rows = App\Http\Services\ContentService::getCmsTaxonomy($params)->get();
  @endphp
  <section id="departments" class="section bg-white m-0">
    <div class="container clearfix">
      <div class="heading-block">
        <h2>{!! $title !!}</h2>
      </div>
      @isset($rows)
        <div class="row mb-0 gutter-30 justify-content-center">
          @foreach ($rows as $item)
            @php
              $title = $item->json_params->title->{$locale} ?? $item->title;
              $brief = $item->json_params->brief->{$locale} ?? $item->brief;
              $image = $item->json_params->image_thumb ?? ($item->json_params->image ?? null);
              $date = date('H:i d/m/Y', strtotime($item->created_at));
              // Viet ham xu ly lay slug
              $alias = Str::slug($title);
              $url_link = route('frontend.cms.department', ['alias' => $alias]) . '.html?id=' . $item->id;
            @endphp
            <div class="col-lg-3 col-sm-6">
              <div class="fbox-effect flex-column center border p-4 rounded-3">
                <div class="mb-4">
                  <a href="{{ $url_link }}">
                    <img src="{{ $image }}" alt="" class="bg-transparent rounded-0">
                  </a>
                </div>
                <div class="fbox-content">
                  <h3>
                    <a href="{{ $url_link }}">
                      {{ Str::limit($title, 30) }}
                    </a>
                  </h3>
                  <p>{{ Str::limit($brief, 100) }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endisset
    </div>
  </section>
@endif
