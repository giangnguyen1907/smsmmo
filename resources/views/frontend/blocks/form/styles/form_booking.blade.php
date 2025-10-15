@if ($block)
  @php
    $title = $block->json_params->title->{$locale} ?? $block->title;
    $brief = $block->json_params->brief->{$locale} ?? $block->brief;
    $image = $block->image != '' ? $block->image : null;
    $background = $block->image_background != '' ? $block->image_background : null;
    $url_link = $block->url_link != '' ? $block->url_link : '';
    $url_link_title = $block->json_params->url_link_title->{$locale} ?? $block->url_link_title;
    
    $params['status'] = App\Consts::POST_STATUS['active'];
    $params['is_type'] = App\Consts::POST_TYPE['doctor'];
    $doctors = App\Http\Services\ContentService::getCmsPost($params)->get();
    
    $params_departments['status'] = App\Consts::TAXONOMY_STATUS['active'];
    $params_departments['taxonomy'] = App\Consts::CATEGORY['department'];
    
    $departments = App\Http\Services\ContentService::getCmsTaxonomy($params_departments)->get();
  @endphp
  <style>
    #booking h2,
    #booking label {
      color: #FFFFFF;
    }

  </style>

  <section id="booking" class="section row p-0 align-items-stretch m-0">
    <div class="col-lg-6"
      style="background: url('{{ $image ?? $background }}') center center no-repeat; background-size: cover; min-height: 250px;">
      <div>&nbsp;</div>
    </div>
    <div class="col-lg-6 p-0">
      <div class="bg-color col-padding">
        <h2>{!! $title !!}</h2>
        <div class="form-result">
        </div>
        <form class="row mb-0" id="form-booking" name="form-booking" method="post"
          action="{{ route('frontend.booking.store') }}">
          @csrf
          <div class="form-process">
            <div class="css3-spinner">
              <div class="css3-spinner-scaler"></div>
            </div>
          </div>
          <div class="col-md-6 form-group">
            <label for="name">
              @lang('Fullname')
              <span class="text-danger">*</span>
            </label>
            <input type="text" id="name" name="name" class="form-control form-control-lg" value="" required
              autocomplete="off">
          </div>
          <div class="col-md-6 form-group">
            <label for="phone">
              @lang('Phone')
              <span class="text-danger">*</span>
            </label>
            <input type="text" id="phone" name="phone" class="form-control form-control-lg" value="" required
              autocomplete="off">
          </div>
          <div class="w-100"></div>
          <div class="col-md-6 form-group">
            <label for="department_id">
              @lang('Department')
              <span class="text-danger">*</span>
            </label>
            <select id="department_id" name="department_id" class="form-select form-control-lg select2" required
              autocomplete="off">
              <option value="">@lang('Please select')</option>
              @isset($departments)
                @foreach ($departments as $item)
                  @php
                    $title = $item->json_params->title->{$locale} ?? $item->title;
                    $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                  @endphp
                  <option value="{{ $item->id }}">{{ $title }}</option>
                @endforeach
              @endisset
            </select>
          </div>
          <div class="col-md-6 form-group">
            <label for="doctor_id">
              @lang('Doctor')
              <span class="text-danger">*</span>
            </label>
            <select id="doctor_id" name="doctor_id" class="form-select form-control-lg select2" required
              autocomplete="off">
              <option value="">@lang('Please select')</option>
              @isset($doctors)
                @foreach ($doctors as $item)
                  @php
                    $title = $item->json_params->title->{$locale} ?? $item->title;
                    $brief = $item->json_params->brief->{$locale} ?? $item->brief;
                  @endphp
                  <option value="{{ $item->id }}">{{ $title }}</option>
                @endforeach
                <script>
                  var doctors = @json($doctors);
                </script>
              @endisset
            </select>
          </div>
          <div class="w-100"></div>
          <div class="col-md-5">
            <div class="row">
              <div class="col-12 form-group">
                <label for="booking_time">@lang('Appointment Hour'):</label>
                <input type="text" class="form-control form-control-lg  datetimepicker-input datetimepicker text-start"
                  data-toggle="datetimepicker" data-target=".datetimepicker" placeholder="00:00 AM/PM"
                  name="booking_time" id="booking_time" />
              </div>
              <div class="col-12 form-group">
                <label for="booking_date">
                  @lang('Appointment Date')
                  <span class="text-danger">*</span>
                </label>
                <input type="text" id="booking_date" name="booking_date"
                  class="form-control form-control-lg text-start component-datepicker today" placeholder="dd-mm-yyyy"
                  required>
              </div>
            </div>
          </div>
          <div class="col-md-7 form-group">
            <label for="customer_note">@lang('Content note'):</label>
            <textarea id="customer_note" name="customer_note" class="form-control " cols="30" rows="5"
              autocomplete="off"></textarea>
          </div>
          <div class="col-12 form-group text-end">
            <button class="button button-rounded button-light text-dark bg-white border m-0" type="submit">
              {{ $url_link_title }}
            </button>
          </div>
        </form>

      </div>
    </div>
  </section>
@endif
