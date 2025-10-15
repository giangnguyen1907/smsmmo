@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content')
<?php $lang_code = 'vi'; ?>

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
              @foreach($languages as $key_lang => $lang)
              <li class="<?php if($key_lang == 0) { echo 'active'; $lang_code = $lang->lang_code; } ?>">
                <a href="#tab_{{ $key_lang }}" data-toggle="tab">
                  <h5>Thông tin {{ $lang->title }} <span class="text-danger">*</span></h5>
                </a>
              </li>
              @endforeach

              <button type="submit" class="btn btn-primary btn-sm pull-right">
                <i class="fa fa-floppy-o"></i>
                @lang('Save')
              </button>
            </ul>

            <div class="tab-content">
              @foreach($languages as $key_la => $lang_tab)
              @php
                $content = $title = $brief = $url_link_title = '';
                $language = $lang_tab->lang_code; 
                if(isset($detail->content->$language)){
                  $content = $detail->content->$language;
                }
                if(isset($detail->title->$language)){
                  $title = $detail->title->$language;
                }
                if(isset($detail->brief->$language)){
                  $brief = $detail->brief->$language;
                }
                if(isset($detail->url_link_title->$language)){
                  $url_link_title = $detail->url_link_title->$language;
                }
              @endphp
                <div class="tab-pane <?php if($key_la == 0) { echo 'active'; } ?>" id="tab_{{ $key_la }}">
                  <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label>
                          @lang('Title') {{ $lang_tab->title }}
                          <small class="text-red">*</small>
                        </label>
                        <input type="text" class="form-control" name="title[{{ $lang_tab->lang_code }}]" placeholder="@lang('Title')"
                          value="{{ $title }}" required>
                      </div>
                       <div class="form-group">
                        <label>@lang('Brief') {{ $lang_tab->title }}</label>
                        <textarea name="brief[{{ $lang_tab->lang_code }}]" id="brief" class="form-control" rows="5">{{ $brief }}</textarea>
                      </div>
                      
                      <div class="form-group">
                        <label>@lang('Url redirect title') {{ $lang_tab->title }}</label>
                        <input type="text" class="form-control" name="url_link_title[{{ $lang_tab->lang_code }}]"
                          placeholder="@lang('Url redirect title')"
                          value="{{ $url_link_title }}">
                      </div>
                      
                    </div>
            
                    <div class="col-md-6">
                      
                      <div class="form-group">
                        <label>@lang('Content') {{ $lang_tab->title }}</label>
                        <textarea name="content[{{ $lang_tab->lang_code }}]" id="content_{{ $lang_tab->lang_code }}" class="form-control">{{ $content }}</textarea>
                      </div>
                    </div>
            
                  </div>
                </div>
              @endforeach
              <div class="col-md-12">
                <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
              </div>
              <div class="row">
                <div class="col-md-6">
					<div class="form-group">
                      <label>@lang('Parent element')</label>
                      <select name="parent_id" id="parent_id" class="form-control select2" style="width: 100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($parents as $item)
                          @if ($item->parent_id == 0 || $item->parent_id == null)
                            <option value="{{ $item->id }}" {{ $detail->parent_id == $item->id ? 'selected' : '' }}>{{ $item->title->$lang_code }}</option>
                            @foreach ($parents as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}" {{ $detail->parent_id == $sub->id ? 'selected' : '' }}>- - {{ $sub->title->$lang_code }}</option>
                                @foreach ($parents as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}" {{ $detail->parent_id == $sub_child->id ? 'selected' : '' }}>- - - - {{ $sub_child->title->$lang_code }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>@lang('Danh mục')</label>
                      <select name="taxonomy[]" id="taxonomy" class="form-control select2" multiple="multiple" style="width: 100%">
                        <option value="">-Vui lòng chọn danh mục-</option>
						<option value="-1" {{ in_array('-1',explode(',',$detail->taxonomy)) ? 'selected' : '' }}>-Home (Trang chủ - FAQ)-</option>
                        @foreach ($taxonomy as $dm)
                          @if ($dm->parent_id == 0 || $dm->parent_id == null)
                            <option value="{{ $dm->id }}" {{ in_array($dm->id,explode(',',$detail->taxonomy)) ? 'selected' : '' }}>
                              {{ $dm->title->$lang_code }}</option>

                            @foreach ($taxonomy as $sub_dm)
                              @if ($dm->id == $sub_dm->parent_id)
                                <option value="{{ $sub_dm->id }}"
								  {{ in_array($sub_dm->id,explode(',',$detail->taxonomy)) ? 'selected' : '' }}>- - {{ $sub_dm->title->$lang_code }}
                                </option>

                                @foreach ($taxonomy as $sub_child)
                                  @if ($sub_dm->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}" {{ in_array($sub_child->id,explode(',',$detail->taxonomy)) ? 'selected' : '' }}>- - - -
                                      {{ $sub_child->title->$lang_code }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>
                        @lang('Block type')
                        <small class="text-red">*</small>
                      </label>
                      <select name="block_code" id="block_code" class="form-control select2" style="width: 100%" required>
                        <option value="">@lang('Please select')</option>
                        @foreach ($blocks as $item)
                          <option value="{{ $item->block_code }}"
                            {{ $item->block_code == $detail->block_code ? 'selected' : '' }}>{{ $item->name }} {{ $item->block_code }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>@lang('Url redirect')</label>
                      <input type="text" class="form-control" name="url_link" placeholder="@lang('Url redirect')"
                        value="{{ $detail->url_link }}">
                    </div>
                    <div class="form-group">
                      <label>@lang('Image')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
                            data-type="cms-image_background">
                            <i class="fa fa-picture-o"></i> @lang('Select')
                          </a>
                        </span>
                        <input id="image" class="form-control" type="text" name="image"
                          placeholder="@lang('Image source')" value="{{ $detail->image }}">
                      </div>
                      <div id="image-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image != '')
                          <img id="view_image" style="height: 5rem;" src="{{ $detail->image }}">
                        @endif
                      </div>
                    </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                      <label>@lang('Status')</label>
                      <div class="form-control">
                        @foreach (App\Consts::STATUS as $key => $value)
                          <label>
                            <input type="radio" name="status" value="{{ $key }}"
                              {{ $detail->status == $key ? 'checked' : '' }}>
                            <small class="mr-15">{{ __($value) }}</small>
                          </label>
                        @endforeach
                      </div>
                    </div>
                    <div class="form-group">
                      <label>@lang('Order')</label>
                      <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                        value="{{ $detail->iorder }}">
                    </div>
                    <div class="form-group">
                      <label>@lang('Icon')</label>
                      <input type="text" class="form-control" name="icon" placeholder="Ex: fa fa-folder"
                        value="{{ $detail->icon }}">
                    </div>
                    <div class="form-group">
                      <label>@lang('Image background')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image_background"  data-preview="image_background-holder" class="btn btn-primary lfm"
                            data-type="cms-image_background">
                            <i class="fa fa-picture-o"></i> @lang('Select')
                          </a>
                        </span>
                        <input id="image_background" class="form-control" type="text" name="image_background"
                          placeholder="@lang('Image source')" value="{{ $detail->image_background }}">
                      </div>
                      <div id="image_background-holder" style="margin-top:15px;max-height:100px;">
                        @if($detail->image_background != '')
                          <img id="view_image_background" style="height: 5rem;" src="{{ $detail->image_background }}">
                        @endif
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>

        </div>

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
    height: 300px !important;
  }
</style>
@section('script')
<script>
  @foreach($languages as $lang)
    CKEDITOR.replace('content_{{ $lang->lang_code }}', ck_options);
  @endforeach
</script>
  <script>
	 
   CKEDITOR.replace('content', ck_options); 

    $(document).ready(function() {
      $(document).on('change', '#block_code', function() {
        let block_code = $(this).val();
        var _targetLayout = $('#block_layout');
        var _targetStyle = $('#block_style');
        _targetLayout.html('');
        _targetStyle.html('');
        var url = "{{ route('blocks.params') }}/";
        $.ajax({
          type: "GET",
          url: url,
          data: {
            "block_code": block_code,
          },
          success: function(response) {
            var _optionListLayout = '<option value="">@lang("Please select")</option>';
            var _optionListStyle = '<option value="">@lang("Please select")</option>';
            if (response.data != null) {
              let json_params = JSON.parse(response.data);
              if (json_params.hasOwnProperty('layout')) {
                Object.entries(json_params.layout).forEach(([key, value]) => {
                  _optionListLayout += '<option value="' + value + '"> ' + value + ' </option>';
                });
              }
              _targetLayout.html(_optionListLayout);
              if (json_params.hasOwnProperty('style')) {
                Object.entries(json_params.style).forEach(([key, value]) => {
                  _optionListStyle += '<option value="' + value + '"> ' + value + ' </option>';
                });
              }
              _targetStyle.html(_optionListStyle);
            }
            $(".select2").select2();
          },
          error: function(response) {
            // Get errors
            var errors = response.responseJSON.message;
            console.log(errors);
          }
        });
      });
    });
  </script>
@endsection
