@extends('admin.layouts.app')
@section('title')
  {{ $module_name }}
@endsection
<?php $locale='vi'; ?>
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
              <li class="<?php if($key_lang == 0) { echo 'active'; } ?>">
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
                $content = $title = $brief = '';
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
              @endphp
                <div class="tab-pane <?php if($key_la == 0) { echo 'active'; } ?>" id="tab_{{ $key_la }}">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>@lang('Title') {{ $lang_tab->title }}<small class="text-red">*</small></label>
                        <input type="text" class="form-control" name="title[{{ $lang_tab->lang_code }}]" placeholder="@lang('Title') {{ $lang_tab->title }}"
                          value="{{ $title }}" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>@lang('Description') {{ $lang_tab->title }}</label>
                        <textarea name="brief[{{ $lang_tab->lang_code }}]" class="form-control" rows="5">{{ $brief }}</textarea>
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="form-group">
                          <label>@lang('Content') {{ $lang_tab->title }}</label>
                          <textarea name="content[{{ $lang_tab->lang_code }}]" class="form-control" id="content_{{ $lang_tab->lang_code }}">{{ $content }}</textarea>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                    </div>
                  </div>
                </div>
              @endforeach
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>@lang('Product category') <small class="text-red">*</small></label>
                    <select name="taxonomy_id" id="taxonomy_id" class="form-control select2" style="width: 100%">
                      <option value="">@lang('Please select')</option>
                      @foreach ($parents as $item)
                        @if ($item->parent_id == 0 || $item->parent_id == null)
                          <option value="{{ $item->id }}" {{ $detail->taxonomy_id == $item->id ? 'selected' : '' }}>
                            {{ $item->title->$locale }}</option>

                          @foreach ($parents as $sub)
                            @if ($item->id == $sub->parent_id)
                              <option value="{{ $sub->id }}"
                                {{ $detail->taxonomy_id == $sub->id ? 'selected' : '' }}>- - {{ $sub->title->$locale }}
                              </option>

                              @foreach ($parents as $sub_child)
                                @if ($sub->id == $sub_child->parent_id)
                                  <option value="{{ $sub_child->id }}"
                                    {{ $detail->taxonomy_id == $sub_child->id ? 'selected' : '' }}>- - - -
                                    {{ $sub_child->title->$locale }}</option>
                                @endif
                              @endforeach
                            @endif
                          @endforeach
                        @endif
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label>@lang('Image')</label>
                    <div class="input-group">
                      <span class="input-group-btn">
                        <a data-input="image" onclick="openPopupImg ('image')" data-preview="image-holder" class="btn btn-primary lfm"
                          data-type="product">
                          <i class="fa fa-picture-o"></i> @lang('choose')
                        </a>
                      </span>
                      <input id="image" class="form-control" type="text" name="image"
                        placeholder="@lang('image_link')..." value="{{ $detail->image }}">
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
                    <label>@lang('Order')</label>
                    <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                      value="{{ $detail->iorder }}">
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
                  <div class="form-group hidden">
                    <label>@lang('Image thumb')</label>
                    <div class="input-group">
                      <span class="input-group-btn">
                        <a data-input="image_thumb" onclick="openPopupImg('image_thumb')" data-preview="image_thumb-holder" class="btn btn-primary lfm"
                          data-type="product">
                          <i class="fa fa-picture-o"></i> @lang('choose')
                        </a>
                      </span>
                      <input id="image_thumb" class="form-control" type="text" name="image_thumb"
                        placeholder="@lang('image_link')...">
                    </div>
                    <div id="image_thumb-holder" style="margin-top:15px;max-height:100px;">
                      @if (old('image_thumb') != '')
                        <img id="view_image_thumb" style="height: 5rem;" src="{{ old('image_thumb') }}">
                      @endif
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
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

@section('script')
<script>
  @foreach($languages as $lang)
    CKEDITOR.replace('content_{{ $lang->lang_code }}', ck_options);
  @endforeach
</script>

<style>
  div.ck-editor__editable {
    height: 705px !important;
    overflow: scroll;
  }
</style>
@endsection
