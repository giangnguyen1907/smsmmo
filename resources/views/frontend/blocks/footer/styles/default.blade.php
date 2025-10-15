<style>
  .dark .form-control:not(.not-dark),
  .dark .sm-form-control:not(.not-dark),
  .dark .form-select:not(.not-dark) {
    color: #FFFFFF;
    background-color: #FFFFFF;
    border-color: rgba(255, 255, 255, 0.15);
  }
</style>
<footer id="footer" class="dark" style="background-color: #005dab; color: #FFFFFF;">
  <div class="container">
    <div class="footer-widgets-wrap">

      <div class="row col-mb-50">
        <div class="col-md-8">
          <div class="widget clearfix">

            <img src="{{ $web_information->image->logo_footer ?? '' }}" alt="Logo" class="alignleft img-fluid"
              style="margin-top: 8px; padding-right: 18px; border-right: 1px solid #FFFFFF; max-width: 250px;">
            @isset($web_information->information->brief)
              <p>{!! $web_information->information->brief !!}</p>
            @endisset
            <div class="line" style="margin: 30px 0;"></div>
            @isset($menu)
              <div class="row col-mb-30">
                @php
                  $footer_menu = $menu->filter(function ($item, $key) {
                      return $item->menu_type == 'footer' && ($item->parent_id == null || $item->parent_id == 0);
                  });
                  
                  $content = '';
                  $col = 12 / count($footer_menu);
                  foreach ($footer_menu as $main_menu) {
                      $url = $title = '';
                      $title = isset($main_menu->json_params->title->{$locale}) && $main_menu->json_params->title->{$locale} != '' ? $main_menu->json_params->title->{$locale} : $main_menu->name;
                  
                      $content .= '<div class="col-6 col-lg-'.$col.' widget_links">';
                      $content .= '<ul>';
                      foreach ($menu as $item) {
                          if ($item->parent_id == $main_menu->id) {
                              $title = isset($item->json_params->title->{$locale}) && $item->json_params->title->{$locale} != '' ? $item->json_params->title->{$locale} : $item->name;
                              $url = $item->url_link;
                  
                              $active = $url == url()->current() ? 'active' : '';
                  
                              $content .= '<li><a href="' . $url . '">' . $title . '</a>';
                              $content .= '</li>';
                          }
                      }
                  
                      $content .= '</ul></div>';
                  }
                  
                  echo $content;
                @endphp
              </div>
            @endisset

          </div>
        </div>

        <div class="col-md-4">

          <div id="subscriber" class="widget clearfix">

            <h4 class="highlight-me">
              @lang('Subscribe For Latest Offers')
            </h4>
            <p class="mb-2">
              @lang('Subscribe to Our Newsletter to get Important News, Amazing Offers')
            </p>
            <form method="post" action="{{ route('frontend.contact.store') }}" class="my-0 form_ajax">
              @csrf
              <div class="input-group mx-auto">
                <input class="form-control" type="email" name="email" placeholder="@lang('Enter your email')" required
                  autocomplete="off">
                <input type="hidden" name="is_type" value="newsletter">
                <button class="btn btn-primary" type="submit"><i class="icon-email2"></i></button>
              </div>
            </form>
          </div>

        </div>
      </div>

    </div>
  </div>
  @isset($web_information->information->copyright)
    <div id="copyrights">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-12 col-lg-auto text-center text-lg-start">
            {!! $web_information->information->copyright !!}
          </div>
        </div>
      </div>
    </div>
  @endisset

</footer>
