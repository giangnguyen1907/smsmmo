<header id="header" class="transparent-header" data-sticky-class="not-dark" data-responsive-class="not-dark">
  <div id="top-bar">
    <div class="container clearfix">
      <div class="row justify-content-between">
        <div class="col-12 col-md-auto d-none d-md-flex">
          <div class="top-links">
            <ul class="top-links-container">
              @if ($web_information->information->hotline)
                <li class="top-links-item">
                  <a href="tel:{{ $web_information->information->hotline }}">
                    <i class="icon-phone3"></i>
                    {{ $web_information->information->hotline }}
                  </a>
                </li>
              @endif
              @if ($web_information->information->email)
                <li class="top-links-item">
                  <a class="nott" href="mailto:{{ $web_information->information->email }}">
                    <i class="icon-envelope2"></i>
                    {{ $web_information->information->email }}
                  </a>
                </li>
              @endif
            </ul>
          </div>
        </div>
        <div class="col-12 col-md-auto">
          <div class="top-links">
            <ul id="top-social">
              @isset($web_information->social->facebook)
                <li>
                  <a href="{{ $web_information->social->facebook }}" class="si-facebook" target="_blank">
                    <span class="ts-icon">
                      <i class="icon-facebook"></i>
                    </span>
                    <span class="ts-text">Facebook</span>
                  </a>
                </li>
              @endisset
              @isset($web_information->social->twitter)
                <li>
                  <a href="{{ $web_information->social->twitter }}" class="si-twitter" target="_blank">
                    <span class="ts-icon"><i class="icon-twitter"></i></span>
                    <span class="ts-text">Twitter</span>
                  </a>
                </li>
              @endisset
              @isset($web_information->social->instagram)
                <li>
                  <a href="{{ $web_information->social->instagram }}" class="si-instagram" target="_blank">
                    <span class="ts-icon"><i class="icon-instagram2"></i></span>
                    <span class="ts-text">Instagram</span>
                  </a>
                </li>
              @endisset
              @isset($web_information->social->youtube)
                <li>
                  <a href="{{ $web_information->social->youtube }}" class="si-youtube" target="_blank">
                    <span class="ts-icon"><i class="icon-youtube"></i></span>
                    <span class="ts-text">Youtube</span>
                  </a>
                </li>
              @endisset
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="header-wrap">
    <div class="container">
      <div class="header-row">

        <!-- Logo
        ============================================= -->
        <div id="logo" class="d-none d-sm-block">
          <a href="{{ route('frontend.home') }}" class="standard-logo"
            data-dark-logo="{{ $web_information->image->logo_header_light ?? '' }}"
            style="height: 60px; padding: 5px 0;">
            <img src="{{ $web_information->image->logo_header_light ?? '' }}" alt="logo">
          </a>
          <a href="{{ route('frontend.home') }}" class="retina-logo"
            data-dark-logo="{{ $web_information->image->logo_header_light ?? '' }}"
            style="height: 60px; padding: 5px 0;">
            <img src="{{ $web_information->image->logo_header_light ?? '' }}" alt="logo">
          </a>
        </div>
        <!-- #logo end -->

        <div class="header-misc">
          <div id="top-search" class="header-misc-icon">
            <a href="#" id="top-search-trigger"><i class="icon-line-search"></i><i class="icon-line-cross"></i></a>
          </div>
          <div id="cart" class="header-misc-icon">
            <a href="{{ route('frontend.order.cart') }}">
              <i class="icon-line-bag"></i>
              <span class="top-cart-number">{{ count((array) session('cart')) }}</span>
            </a>
          </div>
          <a href="#" class="button btn-success button-small">
            @lang('BOOK AN APPOINTMENT')
          </a>
        </div>

        <div id="primary-menu-trigger">
          <svg class="svg-trigger" viewBox="0 0 100 100">
            <path
              d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20">
            </path>
            <path d="m 30,50 h 40"></path>
            <path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20">
            </path>
          </svg>
        </div>

        <!-- Primary Navigation
        ============================================= -->
        <nav class="primary-menu not-dark with-arrows">

          <ul class="menu-container not-dark">
            @isset($menu)
              @php
                $main_menu = $menu->first(function ($item, $key) {
                    return $item->menu_type == 'header' && ($item->parent_id == null || $item->parent_id == 0);
                });
                
                $content = '';
                foreach ($menu as $item) {
                    $url = $title = '';
                    if ($item->parent_id == $main_menu->id) {
                        $title = isset($item->json_params->title->{$locale}) && $item->json_params->title->{$locale} != '' ? $item->json_params->title->{$locale} : $item->name;
                        $url = $item->url_link;
                        $active = $url == url()->current() ? 'current' : '';
                
                        $content .= '<li class="menu-item ' . $active . '"><a class="menu-link" href="' . $url . '"><div>' . $title . '</div></a>';
                
                        if ($item->sub > 0) {
                            $content .= '<ul class="sub-menu-container">';
                            foreach ($menu as $item_sub) {
                                $url = $title = '';
                                if ($item_sub->parent_id == $item->id) {
                                    $title = isset($item_sub->json_params->title->{$locale}) && $item_sub->json_params->title->{$locale} != '' ? $item_sub->json_params->title->{$locale} : $item_sub->name;
                                    $url = $item_sub->url_link;
                
                                    $content .= '<li class="menu-item"><a class="menu-link" href="' . $url . '"><div>' . $title . '</div></a>';
                
                                    if ($item_sub->sub > 0) {
                                        $content .= '<ul class="sub-menu-container">';
                                        foreach ($menu as $item_sub_2) {
                                            $url = $title = '';
                                            if ($item_sub_2->parent_id == $item_sub->id) {
                                                $title = isset($item_sub_2->json_params->title->{$locale}) && $item_sub_2->json_params->title->{$locale} != '' ? $item_sub_2->json_params->title->{$locale} : $item_sub_2->name;
                                                $url = $item_sub_2->url_link;
                
                                                $content .= '<li class="menu-item"><a class="menu-link" href="' . $url . '"><div>' . $title . '</div></a></li>';
                                            }
                                        }
                                        $content .= '</ul>';
                                    }
                                    $content .= '</li>';
                                }
                            }
                            $content .= '</ul>';
                        }
                        $content .= '</li>';
                    }
                }
                echo $content;
              @endphp
            @endisset
          </ul>

        </nav>
        <!-- #primary-menu end -->

        <form class="top-search-form" action="{{ route('frontend.search.index') }}" method="get">
          <input type="text" name="keyword" class="form-control" value="" placeholder="@lang('Type and hit enter...')"
            autocomplete="off">
        </form>

      </div>
    </div>
  </div>
  <div class="header-wrap-clone"></div>
</header>
