@extends('admin.layouts.app')
@section('title')
  {{ $module_name }}
@endsection
@section('style')
  <style>
    .checkbox_list {
      min-height: 300px;
    }

    .checkbox_list li {
      border-bottom: 1px dashed;
    }
  </style>
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
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">@lang('Create form')</h3>
      </div>
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
        @csrf
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_1" data-toggle="tab">
                  <h5>
                    @lang('General information')
                    <span class="text-danger">*</span>
                  </h5>
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
                      <label>@lang('Name')</label>
                      <small class="text-red">*</small>
                      <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                      <input type="text" class="form-control" name="name" placeholder="@lang('Name')"
                        value="{{ old('name') }}" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Url customize')</label>
                      <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                      <small class="form-text">
                        (
                        <i class="fa fa-info-circle"></i>
                        @lang('Maximum 100 characters in the group: "A-Z", "a-z", "0-9" and "-_"')
                        )
                      </small>
                      <input type="text" class="form-control" name="alias" placeholder="@lang('Url customize')"
                        value="{{ old('alias') }}">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Title')</label>
                      <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                      <input type="text" class="form-control" name="title" placeholder="@lang('Title')"
                        value="{{ old('title') }}">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Keyword')</label>
                      <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                      <input type="text" class="form-control" name="keyword" placeholder="@lang('Keyword')"
                        value="{{ old('keyword') }}">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Description')</label>
                      <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                      <textarea type="text" class="form-control" name="description" placeholder="@lang('Description')">{{ old('description') }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('SEO Open Graph image')</label>
                      <i class="fa fa-coffee text-red" aria-hidden="true"></i>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="og_image" data-preview="og_image-holder" data-type="cms-image"
                            class="btn btn-primary lfm">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="og_image" class="form-control" type="text" name="json_params[og_image]"
                          placeholder="@lang('image_link')..." value="{{ old('json_params[og_image]') }}">
                      </div>
                      <div id="og_image-holder" style="margin-top:15px;max-height:100px;">
                        @if (old('json_params[og_image]') != '')
                          <img style="height: 5rem;" src="{{ old('json_params[og_image]') }}">
                        @endif
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('Content Page')</label>
                      <textarea type="text" class="form-control" name="content" id="content">{{ old('content') }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Status')</label>
                      <div class="form-control">
                        @foreach (App\Consts::STATUS as $key => $value)
                          <label>
                            <input type="radio" name="status" value="{{ $value }}"
                              {{ $loop->index == 0 ? 'checked' : '' }}>
                            <small class="mr-15">{{ __($value) }}</small>
                          </label>
                        @endforeach
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Order')</label>
                      <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                        value="{{ old('iorder') }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Route Name')</label>
                      <small class="text-red">*</small>
                      <select name="route_name" id="route_name" class="form-control select2" style="width:100%" required
                        autocomplete="off">
                        <option value="">@lang('Please select')</option>
                        @foreach (App\Consts::ROUTE_NAME as $key => $item)
                          <option value="{{ $item['name'] }}">
                            {{ __($item['title']) }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Template')</label>
                      <small class="text-red">*</small>
                      <select name="json_params[template]" id="template" class="form-control select2" style="width:100%"
                        required>
                        <option value="">@lang('Please select')</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>
                  <div class="col-md-12">
                    <h3>
                      @lang('Setting Block Content')
                    </h3>
                  </div>
                  <div class="col-md-6">
                    <h4 class="mb-15">
                      @lang('Selected Blocks')
                    </h4>
                    <ul class="checkbox_list" id="block_selected"></ul>
                  </div>
                  <div class="col-md-6">
                    <h4 class="mb-15">
                      @lang('Available Blocks')
                    </h4>
                    <ul class="checkbox_list" id="block_available"></ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="box-footer">
          <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i>
            @lang('List')
          </a>
          <button type="submit" class="btn btn-primary btn-sm pull-right">
            <i class="fa fa-floppy-o"></i>
            @lang('Save')
          </button>
        </div>
      </form>
    </div>
  </section>
@endsection

@section('script')
  <script>
    CKEDITOR.replace('content', ck_options);
    // Change to filter
    $(document).ready(function() {

      // Routes get all
      var routes = @json(App\Consts::ROUTE_NAME ?? []);
      $(document).on('change', '#route_name', function() {
        let _value = $(this).val();
        let _targetHTML = $('#template');
        let _list = filterArray(routes, 'name', _value);
        let _optionList = '<option value="">@lang('Please select')</option>';
        if (_list) {
          _list.forEach(element => {
            element.template.forEach(item => {
              _optionList += '<option value="' + item.name + '"> ' + item.title + ' </option>';
            });
          });
          _targetHTML.html(_optionList);
        }
        $(".select2").select2();
      });

      // Fill Available Blocks by template
      $(document).on('change', '#template', function() {
        let template = $(this).val();
        let _targetHTML = $('#block_available');
        _targetHTML.html('');
        let url = "{{ route('block_contents.get_by_template') }}/";
        $.ajax({
          type: "GET",
          url: url,
          data: {
            "template": template,
          },
          success: function(response) {
            if (response.message == 'success') {
              let list = response.data || null;
              let _item = '';
              if (list.length > 0) {
                list.forEach(item => {
                  _item += '<li class="cursor">';
                  _item += '<input name="json_params[block_content][]" type="checkbox" value="' +
                    item.id + '" class="mr-15 block_item cursor" id="block_content_' + item.id + '">';
                  _item += '<label for="block_content_' + item.id + '" class="cursor">';
                  _item += '<strong>' + item.title + ' (' + item.block_name + ')</strong>';
                  _item += '</label>';
                  _item += '</li>';
                });
                _targetHTML.html(_item);
              }
            } else {
              _targetHTML.html(response.message);
            }
          },
          error: function(response) {
            // Get errors
            let errors = response.responseJSON.message;
            _targetHTML.html(errors);
          }
        });
      });

      // Checked and unchecked block item event
      $(document).on('click', '.block_item', function() {
        let ischecked = $(this).is(':checked');
        let _root = $(this).closest('li');
        let _targetHTML;

        if (ischecked) {
          _targetHTML = $("#block_selected");
        } else {
          _targetHTML = $("#block_available");
        }
        _targetHTML.append(_root);
      });
    });
  </script>
@endsection
