@extends('admin.layouts.app')



@section('title')

  {{ $module_name }}

@endsection



@section('content')

  <!-- Content Header (Page header) -->

  <section class="content-header">

    <h1>

      {{ $module_name }}

      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i

          class="fa fa-plus"></i> @lang('Add')</a>

    </h1>

  </section>



  <!-- Main content -->

  <section class="content">

    @if (session('errorMessage'))

      <div class="alert alert-warning alert-dismissible">

        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        {{ session('errorMessage') }}

      </div>

    @endif

    @if (session('successMessage'))

      <div class="alert alert-success alert-dismissible">

        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

        {{ session('successMessage') }}

      </div>

    @endif



    @if ($errors->any())

      <div class="alert alert-danger alert-dismissible">

        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>



        @foreach ($errors->all() as $error)

          <p>{{ $error }}</p>

        @endforeach



      </div>

    @endif

    @php

    $array_location = APP\Consts::POST_POSITION_TAXONMY;

    @endphp

    <div class="box box-primary">

      <div class="box-header with-border">

        <h3 class="box-title">@lang('Create form')</h3>

      </div>

      <!-- /.box-header -->

      <!-- form start -->

      <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">

        @csrf

        <div class="box-body">

          <!-- Custom Tabs -->

          <div class="nav-tabs-custom">

            <ul class="nav nav-tabs">

              <li class="active">

                <a href="#tab_1" data-toggle="tab">

                  <h5>Thông tin chính <span class="text-danger">*</span></h5>

                </a>

              </li>



              <button type="submit" class="btn btn-primary btn-sm pull-right">

                <i class="fa fa-floppy-o"></i>

                @lang('Save')

              </button>

            </ul>



            <div class="tab-content">

              <div class="tab-pane active" id="tab_1">

                <div class="row">

                  <div class="col-md-6">



                    <div class="form-group">

                      <label>@lang('Chuyên khoa') <small class="text-red">*</small></label>

                      <select name="taxonomy" id="taxonomy" class="form-control select2" required>

                        <option value="">@lang('Please select')</option>

                        @foreach ($taxonomys as $value)

                          <option value="{{ $value->id }}" >

                            {{ $value->title }}</option>

                        @endforeach

                      </select>

                    </div>

                    <div class="form-group">

                      <label>@lang('Họ tên') <small class="text-red">*</small></label>

                      <input type="text" class="form-control" id="txtTitle" onchange="getUrlPart('txtTitle','txtUrlPart')" onclick="getUrlPart('txtTitle','txtUrlPart')" onblur="getUrlPart('txtTitle','txtUrlPart')" name="title" placeholder="@lang('Title')" value="{{ old('title') }}" required>

                    </div>



                    <div class="form-group">

                      <div class="row">

                         <div class="col-md-6">

                          <label>@lang('SĐT')</label>

                          <input type="text" class="form-control" name="sdt" placeholder="@lang('Số điện thoại')"

                          value="{{ old('sdt') }}">

                        </div>

                         <div class="col-md-6">

                          <label>@lang('Email')</label>

                          <input type="email" class="form-control" name="email" placeholder="@lang('email')"

                          value="{{ old('email') }}">

                        </div>

                      </div>

                    </div>



                    <div class="form-group">

                      <div class="row">

                        <div class="col-md-6">

                        <label>@lang('Trình độ')</label>

                        <input type="text" class="form-control" name="trinhdo" placeholder="@lang('Trình độ')"

                          value="{{ old('trinhdo') }}">

                        </div>

                         <div class="col-md-6">

                          <label>@lang('Chức vụ')</label>

                          <input type="text" class="form-control" name="chucvu" placeholder="@lang('Chức vụ')"

                          value="{{ old('chucvu') }}">

                        </div>

                      </div>

                    </div>

                    

            			</div>



                  <div class="col-md-6">

                    {{-- <div class="form-group">

                      <label>@lang('Parent element')</label>

                      <select name="parent_id" id="parent_id" class="form-control select2">

                        <option value="">== @lang('ROOT') ==</option>

                      </select>

                    </div> --}}

                    <div class="form-group hidden">

                      <label>@lang('Alias') <small class="text-red">*</small></label>

                      <input type="text" class="form-control" name="url_part" id="txtUrlPart" onchange="getUrlPart('txtUrlPart','txtUrlPart')" onclick="getUrlPart('txtUrlPart','txtUrlPart')" onblur="getUrlPart('txtTitle','txtUrlPart')" placeholder="@lang('Alias')"

                        value="{{ old('url_part') }}" required>

                    </div>



                    <div class="form-group">

                      <label>@lang('Status')</label>

                      <div class="form-control">

                        <label>

                          <input type="radio" name="status" value="active" checked="">

                          <small>@lang('active')</small>

                        </label>

                        <label>

                          <input type="radio" name="status" value="deactive" class="ml-15">

                          <small>@lang('deactive')</small>

                        </label>

                      </div>

                    </div>



                    <div class="form-group">

                      <label>@lang('Nổi bật trang chủ')</label>

                      <div class="form-control">

                        <label>

                          <input type="radio" name="position" value="1" checked="">

                          <small>@lang('Có')</small>

                        </label>

                        <label>

                          <input type="radio" name="position" value="2" class="ml-15">

                          <small>@lang('Không')</small>

                        </label>

                      </div>

                    </div>



                    <div class="form-group">

                      <label>@lang('Order')</label>

                      <input type="number" class="form-control" name="iorder" placeholder="@lang('iorder')"

                        value="{{ old('iorder') }}">

                    </div>

                    <div class="form-group">

                      <div class="row">

                        <div class="col-md-12">

                          

                          <label>@lang('Image')</label>

                          <div class="input-group">

                          <span class="input-group-btn">

                            <a data-input="image" onclick="openPopupImg('image')" data-preview="image-holder" class="btn btn-primary lfm"

                            data-type="cms-image">

                            <i class="fa fa-picture-o"></i> @lang('choose')

                            </a>

                          </span>

                          <input id="image" class="form-control" type="text" name="image"

                            placeholder="@lang('image_link')..." value="{{ old('image') }}">

                          </div>

                          <div id="image-holder" style="margin-top:15px;max-height:100px;">

                          @if (old('image') != '')

                          <img id="view_image" style="height: 5rem;" src="{{ old('image') }}">

                          @else

                          <img id="view_image" style="height: 5rem;" src="">

                          @endif

                          </div>

                          

                        </div>



                        {{-- <div class="col-md-6 hidden">

                          <label>@lang('Image background')</label>

                          <div class="input-group">

                          <span class="input-group-btn">

                            <a data-input="image_background" onclick="openPopupImg('image_background')" data-preview="image_background-holder" class="btn btn-primary lfm"

                            data-type="cms-image">

                            <i class="fa fa-picture-o"></i> @lang('choose')

                            </a>

                          </span>

                          <input id="image_background" class="form-control" type="text"

                            name="json_params[image_background]" placeholder="@lang('image_link')..."

                            value="{{ old('json_params[image_background]') }}">

                          </div>

                          <div id="image_background-holder" style="margin-top:15px;max-height:100px;">

                          @if (old('json_params[image_background]') != '')

                            <img style="height: 5rem;" src="{{ old('json_params[image_background]') }}">

                          @endif

                          </div>

                        </div> --}}



                        {{-- <div class="col-md-12">

                          <div class="form-group">

                            <label>@lang('Chọn giao diện hiển thị trang chủ')</label>

                              <select name="news_position" class="form-control" id="news_position">

                                <option value="" style="font-size: 15px"> - Vui lòng chọn - </option>

                                @foreach($array_location as $key => $position_text)

                                <option style="font-size: 15px" value="{{ $key }}" {{ old('news_position') == $key ? 'selected' : '' }}>{{ $position_text }}</option>

                                @endforeach

                              </select>

                          </div>

                        </div> --}}

                      </div>

                    </div>

					

					{{--<div class="form-group">

						 <label>Vị trí hiển thị</label>

						<div class="form-control">

							<input type="checkbox" id="hienthi_0" name="hienthi[]" value="0">

							<label for="vehicle1"> Menu chính</label>

							<input type="checkbox" class="ml-15" id="hienthi_1" name="hienthi[]" value="1">

							<label for="vehicle1"> Menu trái</label>

							<input type="checkbox" class="ml-15" id="hienthi_2" name="hienthi[]" value="2">

							<label for="vehicle1"> Menu footer</label>

						</div>

                    </div> --}}



                  </div>

                  <div class="col-md-12">

                    <div class="form-group">

                      <label>@lang('Description')</label>

                      <textarea name="content" class="form-control" id="content_vi">{{ old('content') }}</textarea>

                      {{-- <textarea name="brief" class="form-control" rows="5">{{ old('brief') }}</textarea> --}}

                    </div>

                  </div>

                  {{-- <div class="col-md-12 hidden">

                    <div class="form-group">

                      <div class="form-group">

                        <label>@lang('Content')</label>

                        <textarea name="content" class="form-control" id="content_vi">{{ old('content') }}</textarea>

                      </div>

                    </div>

                  </div> 



                  <div class="col-md-12">

                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">

                  </div>

                  <div class="col-md-6 hidden">

                    <div class="form-group">

                      <label>@lang('seo_title')</label>

                      <input name="json_params[seo_title]" class="form-control"

                        value="{{ old('json_params[seo_title]') }}">

                    </div>

                  </div>

                  <div class="col-md-6 hidden">

                    <div class="form-group">

                      <label>@lang('seo_keyword')</label>

                      <input name="json_params[seo_keyword]" class="form-control"

                        value="{{ old('json_params[seo_keyword]') }}">

                    </div>

                  </div>

                  <div class="col-md-12 hidden">

                    <div class="form-group">

                      <label>@lang('seo_description')</label>

                      <input name="json_params[seo_description]" class="form-control"

                        value="{{ old('json_params[seo_description]') }}">

                    </div>

                  </div> --}}



                </div>



              </div>



            </div><!-- /.tab-content -->

          </div><!-- nav-tabs-custom -->



        </div>

        <!-- /.box-body -->



        <div class="box-footer">

          <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">

            <i class="fa fa-bars"></i> @lang('List')

          </a>

          <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>

            @lang('Save')</button>

        </div>

      </form>

    </div>

  </section>



@endsection

<style>

div.ck-editor__editable {

    height: 305px !important;

</style>

@section('script')


  <script>

    CKEDITOR.replace('content_vi', ck_options);



    $(document).ready(function() {

      var taxonomys = @json($taxonomys ?? null);



      // Change to filter type by name taxonomy

      $(document).on('change', '#taxonomy', function() {

        let _value = $(this).val();

        let _html = $('#parent_id');

        let _list = taxonomys.filter(function(e, i) {

          return ((e.parent_id == 0 || e.parent_id == null) && e.taxonomy == _value);

        });

        let _content = '<option value="">== @lang('ROOT') ==</option>';

        if (_list) {

          _list.forEach(element => {

            _content += '<option value="' + element.id + '"> ' + element.title + ' </option>';

            let _child = taxonomys.filter(function(e, i) {

              return ((e.parent_id == element.id) && e.taxonomy == _value);

            });

            if (_child) {

              _child.forEach(element => {

                _content += '<option value="' + element.id + '">- - ' + element.title + ' </option>';

              });

            }

          });

          _html.html(_content);

          $('#parent_id').select2();

        }

      });



    });

  </script>

@endsection

