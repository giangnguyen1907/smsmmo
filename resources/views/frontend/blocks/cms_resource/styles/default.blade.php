@if ($block)
  @php
    $title = $block->json_params->title->{$locale} ?? $block->title;
    $brief = $block->json_params->brief->{$locale} ?? $block->brief;
    $background = $block->image_background != '' ? $block->image_background : url('assets/img/banner.jpg');
    $url_link = $block->url_link != '' ? $block->url_link : '';
    $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
    
    $params['status'] = App\Consts::POST_STATUS['active'];
    $params['is_featured'] = true;
    $params['is_type'] = App\Consts::POST_TYPE['resource'];
    
    $rows = App\Http\Services\ContentService::getCmsPost($params)
        ->limit(6)
        ->get();
  @endphp

  <section id="videos" class="section m-0">
    <div class="container">
      <div class="heading-block center">
        <h3>{!! $title !!}</h3>
      </div>
      @isset($rows)
        <div class="row col-mb-50 mb-0">
          <div class="col-lg-7">
            @php
              $title_head = $rows[0]->json_params->title->{$locale} ?? $rows[0]->title;
              $brief_head = $rows[0]->json_params->brief->{$locale} ?? $rows[0]->brief;
              $image_head = $rows[0]->image != '' ? $rows[0]->image : ($rows[0]->_thumb != '' ? $rows[0]->_thumb : null);
              // Viet ham xu ly lay slug
              $alias_category_head = Str::slug($rows[0]->taxonomy_title);
              $alias_detail_head = Str::slug($title_head);
              $url_link_head = route('frontend.cms.resource', ['alias_category' => $alias_category_head, 'alias_detail' => $alias_detail_head]) . '.html?id=' . $rows[0]->id;
            @endphp
            <div class="posts-md">
              <div class="entry">
                <div class="entry-image">
                  <div class="portfolio-image">
                    <a href="{{ $url_link_head }}">
                      <img src="{{ $image_head }}" alt="">
                    </a>
                    <div class="bg-overlay">
                      <div class="bg-overlay-content">
                        <a href="{{ $url_link_head }}"
                          class="overlay-trigger-icon size-lg op-ts op-07 bg-light text-dark" data-hover-animate="op-1"
                          data-hover-animate-out="op-07">
                          <i class="icon-film"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="entry-title title-sm">
                  <h3>
                    <a href="{{ $url_link_head }}">{{ $title_head }}</a>
                  </h3>
                  <p>{{ Str::limit($brief_head, 100) }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="posts-sm row">
              @foreach ($rows as $item)
                @if ($loop->index > 0)
                  @php
                    $title = $item->json_params->title->{$locale} ?? $item->title;
                    $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                    $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
                    $date = date('H:i d/m/Y', strtotime($item->created_at));
                    // Viet ham xu ly lay slug
                    $alias_category = Str::slug($item->taxonomy_title);
                    $alias_detail = Str::slug($title);
                    $url_link = route('frontend.cms.resource', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
                  @endphp
                  <div class="entry col-12">
                    <div class="grid-inner row g-0">
                      <div class="col-auto">
                        <div class="entry-image">
                          <div class="portfolio-image">
                            <a href="{{ $url_link }}">
                              <img src="{{ $image }}" alt="">
                            </a>
                            <div class="bg-overlay">
                              <div class="bg-overlay-content">
                                <a href="{{ $url_link }}"
                                  class="overlay-trigger-icon size-sm op-ts op-07 bg-light text-dark" data-hover-animate="op-1"
                                  data-hover-animate-out="op-07">
                                  <i class="icon-film"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="col ps-3">
                        <div class="entry-title">
                          <h4>
                            <a href="{{ $url_link }}">
                              {{ $title }}
                            </a>
                          </h4>
                          <p>{{ Str::limit($brief, 75) }}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        </div>
      @endisset
    </div>
  </section>
@endif
