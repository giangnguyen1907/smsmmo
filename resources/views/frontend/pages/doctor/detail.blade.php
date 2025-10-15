@extends('frontend.layouts.default')

@php
$title = $detail->json_params->title->{$locale} ?? $detail->title;
$brief = $detail->json_params->brief->{$locale} ?? null;
$content = $detail->json_params->content->{$locale} ?? null;
$image = $detail->image != '' ? $detail->image : null;
$image_thumb = $detail->image_thumb != '' ? $detail->image_thumb : null;
$date = date('H:i d/m/Y', strtotime($detail->created_at));

// For taxonomy
$taxonomy_json_params = json_decode($detail->taxonomy_json_params);
$taxonomy_title = $taxonomy_json_params->title->{$locale} ?? $detail->taxonomy_title;
$image_background = $taxonomy_json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? null);
$taxonomy_alias = Str::slug($taxonomy_title);
$taxonomy_url_link = route('frontend.cms.department', ['alias' => $taxonomy_alias]) . '.html?id=' . $detail->taxonomy_id;

$seo_title = $detail->json_params->seo_title ?? $title;
$seo_keyword = $detail->json_params->seo_keyword ?? null;
$seo_description = $detail->json_params->seo_description ?? $brief;
$seo_image = $image ?? ($image_thumb ?? null);

@endphp

@push('style')
  <style>
    #content-detail h2 {
      font-size: 30px;
    }

    #content-detail h3 {
      font-size: 24px;
    }

    #content-detail h4 {
      font-size: 18px;
    }

    #content-detail h5,
    #content-detail h6 {
      font-size: 16px;
    }

    #content-detail p {
      margin-top: 0;
      margin-bottom: 0;
    }

    #content-detail h1,
    #content-detail h2,
    #content-detail h3,
    #content-detail h4,
    #content-detail h5,
    #content-detail h6 {
      margin-top: 0;
      margin-bottom: .5rem;
    }

    #content-detail p+h2,
    #content-detail p+.h2 {
      margin-top: 1rem;
    }

    #content-detail h2+p,
    #content-detail .h2+p {
      margin-top: 1rem;
    }

    #content-detail p+h3,
    #content-detail p+.h3 {
      margin-top: 0.5rem;
    }

    #content-detail h3+p,
    #content-detail .h3+p {
      margin-top: 0.5rem;
    }

    #content-detail ul,
    #content-detail ol {
      list-style: inherit;
      padding: 0 0 0 50px;

    }

    #content-detail ul li,
    #content-detail ol li {
      display: list-item;
    }

    .posts-sm .entry-image {
      width: 75px;
    }
  </style>
@endpush

@section('content')
  {{-- Print all content by [module - route - page] without blocks content at here --}}

  <section id="page-title" class="page-title-pattern" style="background-image: url({{ $image_background }});">
    <div class="container clearfix">
      <h1>{{ $title }}</h1>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">@lang('Home')</a></li>
        <li class="breadcrumb-item"><a href="{{ $taxonomy_url_link }}">{{ $taxonomy_title }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
      </ol>
    </div>
  </section>

  <section id="content">
    <div class="content-wrap">
      <div class="container clearfix">
        @if ($detail)
          <div class="row gutter-30 ">

            <div class="postcontent col-lg-12">
              <div class="single-post mb-0">
                <div class="entry clearfix">
                  <div class="entry-content mt-0">
                    <div class="content-block">
                      <div class="news-detail">
                        <div class="clear"></div>

                        <h5 class="font-weight-medium text-dark text-justify">
                          {{ $brief }}
                        </h5>
                        <div class="offset-top-20 text-justify detail" id="content-detail">
                          {!! $content !!}
                        </div>
                        <div class="clear"></div>
                        <div class="pt-5">
                          <div class="fancy-title title-border">
                            <h3 class="text-uppercase">
                              @lang('Booking this doctor')
                            </h3>
                          </div>
                          <div class="form-result"></div>
                          <form class="mb-0" method="post" action="{{ route('frontend.booking.store') }}"
                            id="form-booking">
                            @csrf
                            <div class="form-process">
                              <div class="css3-spinner">
                                <div class="css3-spinner-scaler"></div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6 form-group">
                                <label for="name">
                                  @lang('Fullname')
                                  <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control form-control-lg"
                                  value="" required autocomplete="off">
                              </div>
                              <div class="col-md-6 form-group">
                                <label for="phone">
                                  @lang('Phone')
                                  <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="phone" name="phone" class="form-control form-control-lg"
                                  value="" required autocomplete="off">
                              </div>
                              <div class="col-6 form-group">
                                <label for="booking_date">
                                  @lang('Appointment Date')
                                  <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="booking_date" name="booking_date"
                                  class="form-control form-control-lg text-start component-datepicker today"
                                  placeholder="dd-mm-yyyy" required>
                              </div>
                              <div class="col-6 form-group">
                                <label for="booking_time">@lang('Appointment Hour'):</label>
                                <input type="text"
                                  class="form-control form-control-lg  datetimepicker-input datetimepicker text-start"
                                  data-toggle="datetimepicker" data-target=".datetimepicker" placeholder="00:00 AM/PM"
                                  name="booking_time" id="booking_time" />
                              </div>

                              <div class="w-100"></div>
                              <div class="col-12 form-group">
                                <label for="customer_note">
                                  @lang('Content note')
                                </label>
                                <textarea class="form-control form-control-lg" id="customer_note" name="customer_note" rows="5" cols="30"
                                  autocomplete="off"></textarea>
                              </div>
                              <div class="col-12 form-group">
                                <button type="submit" class="button button-3d m-0">
                                  @lang('Submit booking')
                                </button>
                              </div>
                            </div>
                            <input type="hidden" name="doctor_id" value="{{ $detail->id }}">
                            <input type="hidden" name="department_id" value="{{ $detail->taxonomy_id }}">
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            @if (count($posts) > 0)
              <div class="col-lg-12">
                <div class="offset-top-30">
                  <h3 class="text-uppercase">@lang('Other doctor')</h3>
                  <hr class="text-subline">
                </div>
                <div class="portfolio row grid-container g-0">
                  @foreach ($posts as $item)
                    @php
                      $title = $item->json_params->title->{$locale} ?? $item->title;
                      $brief = $item->json_params->brief->{$locale} ?? '';
                      $image = $item->image != '' ? $item->image : ($item->image_thumb != '' ? $item->image_thumb : null);
                      $date = date('H:i d/m/Y', strtotime($item->created_at));
                      // Viet ham xu ly lay alias bai viet
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
              </div>
            @endif

          </div>
        @else
          <div class="row">
            <div class="col-12">
              <p>@lang('not_found')</p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </section>

  {{-- End content --}}
@endsection
