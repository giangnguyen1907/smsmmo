@if ($block)
  @php
    $title = $block->json_params->title->{$locale} ?? $block->title;
    $brief = $block->json_params->brief->{$locale} ?? $block->brief;
    $background = $block->image_background != '' ? $block->image_background : url('assets/img/banner.jpg');
    $url_link = $block->url_link != '' ? $block->url_link : '';
    $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
    
    $params['status'] = App\Consts::POST_STATUS['active'];
    $params['is_featured'] = true;
    $params['is_type'] = App\Consts::POST_TYPE['doctor'];
    
    $rows = App\Http\Services\ContentService::getCmsPost($params)->get();
  @endphp
  <section id="teams" class="section bg-white m-0">
    <div class="container clearfix">
      <div class="heading-block">
        <h2>{!! $title !!}</h2>
      </div>
      @isset($rows)
        <div class="portfolio row grid-container g-0">
          @foreach ($rows as $item)
            @php
              $title = $item->json_params->title->{$locale} ?? $item->title;
              $brief = $item->json_params->brief->{$locale} ?? $item->brief;
              $image = $item->image_thumb != '' ? $item->image_thumb : ($item->image != '' ? $item->image : null);
              $date = date('H:i d/m/Y', strtotime($item->created_at));
              // Viet ham xu ly lay slug
              $alias = Str::slug($title);
              $url_link = route('frontend.cms.doctor', ['alias' => $alias]) . '.html?id=' . $item->id;
            @endphp
            <article class="portfolio-item col-12 col-sm-6 col-md-4 col-lg-3 pf-media pf-icons"
              title="{{ $title }}">
              <div class="grid-inner">
                <div class="portfolio-image">
                  <img src="{{ $image }}" alt="">
                  <div class="bg-overlay">
                    <div class="bg-overlay-content dark" data-hover-animate="fadeIn">
                      <a href="{{ $url_link }}" class="overlay-trigger-icon bg-light text-dark"><i
                          class="icon-line-ellipsis"></i></a>
                    </div>
                    <div class="bg-overlay-bg dark" data-hover-animate="fadeIn"></div>
                  </div>
                </div>
                <div class="portfolio-desc">
                  <h4 class="mb-3">
                    <a href="{{ $url_link }}">{{ $title }}</a>
                  </h4>
                  <ul class="iconlist mb-0">
                    @isset($item->json_params->phone)
                      <li>
                        <i class="icon-phone"></i> {{ $item->json_params->phone }}
                      </li>
                    @endisset
                    @isset($item->json_params->email)
                      <li>
                        <i class="icon-email3"></i> {{ $item->json_params->email }}
                      </li>
                    @endisset
                  </ul>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      @endisset
    </div>
  </section>
@endif
