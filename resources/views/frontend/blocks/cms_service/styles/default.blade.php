@if ($block)
  @php
    $title = $block->json_params->title->{$locale} ?? $block->title;
    $brief = $block->json_params->brief->{$locale} ?? $block->brief;
    $background = $block->image_background != '' ? $block->image_background : url('assets/img/banner.jpg');
    $url_link = $block->url_link != '' ? $block->url_link : '';
    $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
    
    $params['status'] = App\Consts::POST_STATUS['active'];
    $params['is_featured'] = true;
    $params['is_type'] = App\Consts::POST_TYPE['service'];
    
    $rows = App\Http\Services\ContentService::getCmsPost($params)->get();
  @endphp
  <section id="services" class="section bg-white m-0">
    <div class="container clearfix">
      <div class="heading-block">
        <h2>{!! $title !!}</h2>
      </div>
      @isset($rows)
        <div class="row col-mb-50 mb-0">
          @foreach ($rows as $item)
            @php
              $title = $item->json_params->title->{$locale} ?? $item->title;
              $brief = $item->json_params->brief->{$locale} ?? $item->brief;
              $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
              $date = date('H:i d/m/Y', strtotime($item->created_at));
              // Viet ham xu ly lay slug
              $alias_category = Str::slug($item->taxonomy_title);
              $alias_detail = Str::slug($title);
              $url_link = route('frontend.cms.service', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
            @endphp
            <div class="col-sm-6 col-md-4" title="{{ $title }}">
              <div class="feature-box fbox-plain">
                <div class="fbox-icon" data-animate="bounceIn" data-delay="{{ $loop->index * 200 }}">
                  <a href="{{ $url_link }}">
                    <img class="img-responsive" src="{{ $image }}" width="120" height="120" alt="" />
                  </a>
                </div>
                <div class="fbox-content">
                  <a href="{{ $url_link }}">
                    <h3>{{ Str::limit($title, 30) }}</h3>
                  </a>
                  <p>{{ Str::limit($brief, 100) }}</p>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endisset
  </section>
@endif
