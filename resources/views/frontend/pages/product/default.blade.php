@extends('frontend.layouts.default')

@php
$page_title = $taxonomy->title ?? ($page->title ?? ($page->name ?? null));
$image_background = $taxonomy->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? '');
@endphp

@section('content')
  {{-- Print all content by [module - route - page] without blocks content at here --}}
  <section id="page-title" class="page-title-pattern" style="background-image: url({{ $image_background }});">
    <div class="container clearfix">
      <h1>{{ $page_title }}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">@lang('Home')</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $page->name ?? '' }}</li>
      </ol>
    </div>
  </section>

  <section id="content">
    <div class="content-wrap">
      <div class="container clearfix">

        <div class="row gutter-40 col-mb-80">
          <div class="postcontent col-lg-9">
            @if ($posts)
              <div id="shop" class="shop row grid-container gutter-20" data-layout="fitRows">
                @foreach ($posts as $item)
                  @php
                    $title = $item->json_params->title->{$locale} ?? $item->title;
                    $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                    $image = $item->image_thumb ?? ($item->image ?? null);
                    $date = date('H:i d/m/Y', strtotime($item->created_at));
                    // Viet ham xu ly lay alias bai viet
                    $alias_category = Str::slug($item->taxonomy_title);
                    $alias_detail = Str::slug($title);
                    $url_link = route('frontend.cms.product', ['alias_category' => $alias_category, 'alias_detail' => $alias_detail]) . '.html?id=' . $item->id;
                    $taxonomy_url_link = route('frontend.cms.product_category', ['alias' => $alias_category]) . '.html?id=' . $item->taxonomy_id;
                  @endphp

                  <div class="product col-md-4 col-sm-6 col-12">
                    <div class="grid-inner">
                      <div class="product-image">
                        <a href="{{ $url_link }}"><img src="{{ $image }}" alt=""></a>
                        <div class="bg-overlay">
                          <div class="bg-overlay-content align-items-end justify-content-between"
                            data-hover-animate="fadeIn" data-hover-speed="400">
                            <a href="javascript:void(0);" class="btn btn-dark me-2 add-to-cart"
                              data-id="{{ $item->id }}" data-quantity="1"  title="@lang('Add to cart')">
                              <i class="icon-shopping-cart"></i>
                            </a>
                            <a href="{{ $url_link }}" class="btn btn-dark" title="@lang('View detail')">
                              <i class="icon-line-expand"></i>
                            </a>
                          </div>
                          <div class="bg-overlay-bg bg-transparent"></div>
                        </div>
                      </div>
                      <div class="product-desc">
                        <div class="product-title">
                          <h3><a href="{{ $url_link }}">{{ Str::limit($title, 30) }}</a></h3>
                        </div>
                        <div class="product-price text-danger">
                          @lang('Price'):
                          {{ isset($item->json_params->price) && $item->json_params->price > 0 ? number_format($item->json_params->price) . ' ' . $item->json_params->price_currency : __('Contact') }}
                        </div>
                        <p>
                          {{ Str::limit($brief, 100) }}
                        </p>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              {{ $posts->withQueryString()->links('frontend.pagination.default') }}
            @else
              <p>@lang('not_found')</p>
            @endif
          </div>

          <!-- Sidebar ============================================= -->
          <div class="sidebar col-lg-3">
            @include('frontend.components.sidebar.product')
          </div>
          <!-- .sidebar end -->
        </div>

      </div>
    </div>
  </section>


  {{-- End content --}}
@endsection
