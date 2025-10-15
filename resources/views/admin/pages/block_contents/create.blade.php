@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection
<?php $lang_code = 'en'; ?>
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
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
        @csrf
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              @foreach($languages as $key_lang => $lang)
              <li class="<?php if($key_lang == 0) { echo 'active'; $lang_code = $lang->lang_code;} ?>">
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
                <div class="tab-pane <?php if($key_la == 0) { echo 'active'; } ?>" id="tab_{{ $key_la }}">
                  <div class="row">
                    <div class="col-md-6">
                     <div class="form-group">
                        <label>
                          @lang('Title') {{ $lang_tab->title }}
                          <small class="text-red">*</small>
                        </label>
                        <input type="text" class="form-control" name="title[{{ $lang_tab->lang_code }}]" placeholder="@lang('Title')"
                          value="{{ old('title') }}" required>
                      </div>
                       <div class="form-group">
                        <label>@lang('Brief') {{ $lang_tab->title }}</label>
                        <textarea name="brief[{{ $lang_tab->lang_code }}]" id="brief" class="form-control" rows="5">{{ old('brief') }}</textarea>
                      </div>
                      
                      <div class="form-group">
                        <label>@lang('Url redirect title') {{ $lang_tab->title }}</label>
                        <input type="text" class="form-control" name="url_link_title[{{ $lang_tab->lang_code }}]"
                          placeholder="@lang('Url redirect title')"
                          value="{{ old('url_link_title') }}">
                      </div>
                      
                    </div>
            
                    <div class="col-md-6">
                      
                      <div class="form-group">
                        <label>@lang('Content') {{ $lang_tab->title }}</label>
                        <textarea name="content[{{ $lang_tab->lang_code }}]" id="content_{{ $lang_tab->lang_code }}" class="form-control">{{ old('content') }}</textarea>
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
                            <option value="{{ $item->id }}">{{ $item->title->$lang_code }}</option>
                            @foreach ($parents as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}">- - {{ $sub->title->$lang_code }}</option>
                                @foreach ($parents as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}">- - - - {{ $sub_child->title->$lang_code }}</option>
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
                      <select name="taxonomy[]" id="taxonomy_id" multiple="multiple"  class="form-control select2" style="width: 100%">
                        <option value="">-Vui lòng chọn danh mục-</option>
                        @foreach ($taxonomy as $dm)
                          @if ($dm->parent_id == 0 || $dm->parent_id == null)
                            <option value="{{ $dm->id }}">
                              {{ $dm->title->$lang_code }}</option>

                            @foreach ($taxonomy as $sub_dm)
                              @if ($dm->id == $sub_dm->parent_id)
                                <option value="{{ $sub_dm->id }}">- - {{ $sub_dm->title->$lang_code }}
                                </option>

                                @foreach ($taxonomy as $sub_child)
                                  @if ($sub_dm->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}">- - - -
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
                            {{ $item->block_code == old('block_code') ? 'selected' : '' }}>{{ $item->name }} {{ $item->block_code }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>@lang('Url redirect')</label>
                      <input type="text" class="form-control" name="url_link" placeholder="@lang('Url redirect')"
                        value="{{ old('url_link') }}">
                    </div>
                    <div class="form-group">
                      <label>@lang('Image')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image"  data-preview="image-holder" class="btn btn-primary lfm"
                            data-type="cms-image_background">
                            <i class="fa fa-picture-o"></i> @lang('Select')
                          </a>
                        </span>
                        <input id="image" class="form-control" type="text" name="image"
                          placeholder="@lang('Image source')" value="{{ old('image') }}">
                      </div>
                      <div id="image-holder" style="margin-top:15px;max-height:100px;">
                        @if (old('image') != '')
                          <img id="view_image" style="height: 5rem;" src="{{ old('image') }}">
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
                              {{ $loop->index == 0 ? 'checked' : '' }}>
                            <small class="mr-15">{{ __($value) }}</small>
                          </label>
                        @endforeach
                      </div>
                    </div>
                    <div class="form-group">
                      <label>@lang('Order')</label>
                      <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                        value="{{ old('iorder') }}">
                    </div>
                    <div class="form-group">
                      <label>@lang('Icon')</label>
                      <input type="text" class="form-control" name="icon" placeholder="Ex: fa fa-folder"
                        value="{{ old('icon') }}">
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
                          placeholder="@lang('Image source')" value="{{ old('image_background') }}">
                      </div>
                      <div id="image_background-holder" style="margin-top:15px;max-height:100px;">
                        @if (old('image_background') != '')
                          <img id="view_image_background" style="height: 5rem;" src="{{ old('image_background') }}">
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
</style>
@section('script')
<script>
  @foreach($languages as $lang)
    CKEDITOR.replace('content_{{ $lang->lang_code }}', ck_options);
  @endforeach
</script>
@endsection
