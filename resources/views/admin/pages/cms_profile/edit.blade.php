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

        <h3 class="box-title">@lang('Update form')</h3>

      </div>

      <!-- /.box-header -->

      <!-- form start -->

      <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">

        @csrf

        @method('PUT')

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

                      <label>@lang('Taxonomy') <small class="text-red">*</small></label>

                      <select name="taxonomy" id="taxonomy" class="form-control select2" required>

                        <option value="">@lang('Please select')</option>

                        @foreach ($taxonomys as $value)

                          <option value="{{ $value->id }}" {{ $value->id == $detail->taxonomy ? 'selected' : '' }}>

                            {{ $value->title }}</option>

                        @endforeach

                      </select>



                    </div>

                    <div class="form-group">

                      <label>@lang('Họ tên') <small class="text-red">*</small></label>

                      <input type="text" class="form-control" id="txtTitle" onchange="getUrlPart('txtTitle','txtUrlPart')" onclick="getUrlPart('txtTitle','txtUrlPart')" onblur="getUrlPart('txtTitle','txtUrlPart')" name="title" placeholder="@lang('Title')" placeholder="@lang('Họ tên')"

                        value="{{ $detail->title }}" required>

                    </div>



                    <div class="form-group">

                      <div class="row">

                         <div class="col-md-6">

                          <label>@lang('SĐT')</label>

                          <input type="text" class="form-control" name="sdt" placeholder="@lang('Số điện thoại')"

                          value="{{ $detail->sdt }}">

                        </div>

                         <div class="col-md-6">

                          <label>@lang('Email')</label>

                          <input type="email" class="form-control" name="email" placeholder="@lang('email')"

                          value="{{ $detail->email }}">

                        </div>

                      </div>

                    </div>



                    <div class="form-group">

                      <div class="row">

                        <div class="col-md-6">

                        <label>@lang('Trình độ')</label>

                        <input type="text" class="form-control" name="trinhdo" placeholder="@lang('Trình độ')"

                          value="{{ $detail->trinhdo }}">

                        </div>

                         <div class="col-md-6">

                          <label>@lang('Chức vụ')</label>

                          <input type="text" class="form-control" name="chucvu" placeholder="@lang('Chức vụ')"

                          value="{{ $detail->chucvu }}">

                        </div>

                      </div>

                    </div>



                    

                  </div>



                  <div class="col-md-6">

                    {{-- <div class="form-group">

                      <label>@lang('Parent element')</label>

                      <select name="parent_id" id="parent_id" class="form-control select2">

                        <option value="">== @lang('ROOT') ==</option>

                        @foreach ($taxonomys as $item)

                          @if (($item->parent_id == 0 || $item->parent_id == null))

                            <option value="{{ $item->id }}"

                              {{ $detail->parent_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>



                            @foreach ($taxonomys as $sub)

                              @if ($item->id == $sub->parent_id)

                                <option value="{{ $sub->id }}"

                                  {{ $detail->parent_id == $sub->id ? 'selected' : '' }}>- - {{ $sub->title }}

                                </option>



                                @foreach ($taxonomys as $sub_child)

                                  @if ($sub->id == $sub_child->parent_id)

                                    <option value="{{ $sub_child->id }}"

                                      {{ $detail->parent_id == $sub_child->id ? 'selected' : '' }}>- - - -

                                      {{ $sub_child->title }}</option>

                                  @endif

                                @endforeach

                              @endif

                            @endforeach

                          @endif

                        @endforeach

                      </select>



                    </div> --}}



                    <div class="form-group hidden">

                      <label>@lang('Alias') <small class="text-red">*</small></label>

                      <input type="text" class="form-control" name="url_part" id="txtUrlPart" onchange="getUrlPart('txtUrlPart','txtUrlPart')" onclick="getUrlPart('txtUrlPart','txtUrlPart')" onblur="getUrlPart('txtTitle','txtUrlPart')" placeholder="@lang('Alias')"

                        value="{{ $detail->url_part }}" required>

                    </div>



                    <div class="form-group">

                      <label>@lang('Status')</label>

                      <div class="form-control">

                        <label>

                          <input type="radio" name="status" value="active"

                            {{ $detail->status == 'active' ? 'checked' : '' }}>

                          <small>@lang('active')</small>

                        </label>

                        <label>

                          <input type="radio" name="status" value="deactive"

                            {{ $detail->status == 'deactive' ? 'checked' : '' }} class="ml-15">

                          <small>@lang('deactive')</small>

                        </label>

                      </div>

                    </div>



                    <div class="form-group">

                      <label>@lang('Nổi bật trang chủ')</label>

                      <div class="form-control">

                        <label>

                          <input type="radio" name="position" value="1" {{ $detail->position == '1' ? 'checked' : '' }}>

                          <small>@lang('Có')</small>

                        </label>

                        <label>

                          <input type="radio" name="position" value="2" class="ml-15" {{ $detail->position == '2' ? 'checked' : '' }}>

                          <small>@lang('Không')</small>

                        </label>

                      </div>

                    </div>



                    <div class="form-group">

                      <label>@lang('Order')</label>

                      <input type="number" class="form-control" name="iorder" placeholder="@lang('iorder')"

                        value="{{ $detail->iorder }}">

                    </div>

                    <div class="form-group">

                      <div class="row">

                        {{-- <div class="col-xs-6 hidden">

                          <label>@lang('Image background')</label>

                          <div class="input-group">

                          <span class="input-group-btn">

                            <a data-input="image_background" data-preview="image_background-holder"

                            class="btn btn-primary lfm" data-type="category">

                            <i class="fa fa-picture-o"></i> @lang('choose')

                            </a>

                          </span>

                          <input id="image_background" class="form-control" type="text"

                            name="json_params[image_background]" placeholder="@lang('image_link')..."

                            value="{{ $detail->json_params->image_background ?? old('json_params[image_background]') }}">

                          </div>

                          <div id="image_background-holder" style="margin-top:15px;max-height:100px;">

                          @if (isset($detail->json_params->image_background) && $detail->json_params->image_background != '')

                            <img style="height: 5rem;"

                            src="{{ $detail->json_params->image_background ?? old('json_params[image_background]') }}">

                          @endif

                          </div>

                        </div> --}}

                        <div class="col-xs-12">

                          <label>@lang('Image')</label>

                            <div class="input-group">

                            <span class="input-group-btn">

                              <a data-input="image" data-preview="image-holder" onclick="openPopupImg('image')" class="btn btn-primary lfm"

                              data-type="category">

                              <i class="fa fa-picture-o"></i> @lang('choose')

                              </a>

                            </span>

                            <input id="image" class="form-control" type="text" name="image"

                              placeholder="@lang('image_link')..."

                              value="{{ isset($detail->image) ? $detail->image : old('image') }}">

                            </div>

                            <div id="image-holder" style="margin-top:15px;max-height:100px;">

                            @if (isset($detail->image) && $detail->image != '')

                              <img style="height: 5rem;"

                              src="{{ isset($detail->image) ? $detail->image : old('image') }}">

                            @endif



                            </div>

                        </div>



                        {{-- <div class="col-md-12">

                          <div class="form-group">

                            <label>@lang('Chọn giao diện hiển thị trang chủ')</label>

                            <select name="news_position" class="form-control" id="news_position">

                                <option value="" style="font-size: 15px"> - Vui lòng chọn - </option>

                                @foreach($array_location as $key=>$position_text)

                                <option style="font-size: 15px" value="{{ $key }}" {{ $detail->news_position == $key ? 'selected' : '' }}>{{ $position_text }}</option>

                                @endforeach

                              </select>

                          </div>

                        </div> --}}



                      </div>

                    </div>

					

					

					   {{-- <div class="form-group">

						<label>Vị trí hiển thị</label>

						<div class="form-control">

							<input type="checkbox" id="hienthi_0" name="hienthi[]" <?php if(in_array('0',$hienthi)) echo 'checked'; ?> value="0">

							<label for="vehicle1"> Menu chính</label>

							<input type="checkbox" class="ml-15" id="hienthi_1" name="hienthi[]" value="1" <?php if(in_array('1',$hienthi)) echo 'checked'; ?>>

							<label for="vehicle1"> Menu trái</label>

							<input type="checkbox" class="ml-15" id="hienthi_2" name="hienthi[]" value="2" <?php if(in_array('2',$hienthi)) echo 'checked'; ?>>

							<label for="vehicle1"> Menu footer</label>

						</div> 

                    </div>--}}

                  </div>



                  <div class="col-md-12">

                    <div class="form-group">

                      <label>@lang('Description')</label>

                      <textarea name="content" class="form-control" id="content_vi">{{ $detail->content }}</textarea>

                      {{-- <textarea name="brief" class="form-control" rows="5">{{ $detail->brief ?? old('brief') }}</textarea> --}}

                    </div>

                  </div>

                  {{-- div class="col-md-12 hidden">

                    <div class="form-group">

                      <div class="form-group">

                        <label>@lang('Content')</label>

                        <textarea name="content" class="form-control" id="content">{{ $detail->content ?? old('content') }}</textarea>

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

                        value="{{ $detail->json_params->seo_title ?? old('json_params[seo_title]') }}">

                    </div>

                  </div>

                  <div class="col-md-6 hidden">

                    <div class="form-group">

                      <label>@lang('seo_keyword')</label>

                      <input name="json_params[seo_keyword]" class="form-control"

                        value="{{ $detail->json_params->seo_keyword ?? old('json_params[seo_keyword]') }}">

                    </div>

                  </div>

                  <div class="col-md-12 hidden">

                    <div class="form-group">

                      <label>@lang('seo_description')</label>

                      <input name="json_params[seo_description]" class="form-control"

                        value="{{ $detail->json_params->seo_description ?? old('json_params[seo_description]') }}">

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

    ClassicEditor.create( document.querySelector( '#content' ), {

      toolbar: {

        items: [

          'CKFinder',"|",

          'heading',

          'bold',

          'link',

          'italic',

          '|',

          'blockQuote',

          'alignment:left', 'alignment:right', 'alignment:center', 'alignment:justify',

          'insertTable',

          'undo',

          'redo',

          'LinkImage',

          'bulletedList',

          'numberedList',

          'mediaEmbed',

          'fontBackgroundColor',

          'fontColor',

          'fontSize',

          'fontFamily'

        ]

      },

      language: 'vi',

      image: {

        toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full','imageStyle:side', 'imageStyle:alignCenter','linkImage'],

        styles: [

            'full',

            'side',

            'alignCenter',

            'alignLeft',

            'alignRight'

        ]

      },

      table: {

        contentToolbar: [

          'tableColumn',

          'tableRow',

          'mergeTableCells'

        ]

      },

      licenseKey: '',

      

      

    } ) .then( editor => {

      window.editor = editor;

      

    } ) .catch( error => {

      console.error( 'Oops, something went wrong!' );

      console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );

      console.warn( 'Build id: v10wxmoi2tig-mwzdvmyjd96s' );

      console.error( error );

    } );



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

