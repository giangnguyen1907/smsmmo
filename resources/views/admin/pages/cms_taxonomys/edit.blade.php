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
                  @foreach($languages as $lang)
                    @php
                      $dich = '';
                      $language = $lang->lang_code; 
                      if(isset($detail->title->$language)){
                        $dich = $detail->title->$language;
                      }
                      if($lang->is_default == 1){
                        $onchange = "onchange=getUrlPart('txtTitle','txtUrlPart')";
                        $onclick = "onclick=getUrlPart('txtTitle','txtUrlPart')";
                        $onblur = "onblur=getUrlPart('txtTitle','txtUrlPart')";
                        $id = "txtTitle";
                      }else{
                        $onchange = $onclick = $onblur = $id = "";
                      }
                    @endphp
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Tiêu đề: {{ $lang->title }} <small class="text-red">*</small></label>
                        <input type="text" class="form-control" name="title[{{ $lang->lang_code }}]" id="{{ $id }}" {{ $onchange }} {{ $onclick }} {{ $onblur }} placeholder="@lang('Title') {{ $lang->title }}" value="{{ $dich }}" required>
                      </div>
                    </div>
					<div class="col-md-6">
                      <div class="form-group">
                        <label>Mô tả {{ $lang->title }} <small class="text-red">*</small></label>
                        <input type="text" class="form-control" name="brief[{{ $lang->lang_code }}]" placeholder="@lang('brief') {{ $lang->title }}" value="{{ $detail->brief->$language ?? '' }}">
                      </div>
                    </div>
                    @endforeach
                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Taxonomy') <small class="text-red">*</small></label>
                      <select name="taxonomy" id="taxonomy" class="form-control select2" required>
                        <option value="">@lang('Please select')</option>
                        @foreach (App\Consts::TAXONOMY as $key => $value)
                          <option value="{{ $key }}" {{ $key == $detail->taxonomy ? 'selected' : '' }}>
                            {{ __($value) }}</option>
                        @endforeach
                      </select>
                    </div>
           
                    <div class="form-group">
                      <label>@lang('Order')</label>
                      <input type="number" class="form-control" name="iorder" placeholder="@lang('iorder')"
                        value="{{ $detail->iorder }}">
                    </div>
                    <div class="form-group">
						<div class="row">
							
							<div class="col-xs-12">
								<label>@lang('Image')</label>
								  <div class="input-group">
									<span class="input-group-btn">
									  <a data-input="image" data-preview="image-holder" class="btn btn-primary lfm"
										data-type="cms-image_background">
										<i class="fa fa-picture-o"></i> @lang('choose')
									  </a>
									</span>
									<input id="image" class="form-control" type="text" name="json_params[image]"
									  placeholder="@lang('image_link')..."
									  value="{{ isset($detail->json_params->image) ? $detail->json_params->image : old('json_params[image]') }}">
								  </div>
								  <div id="image-holder" style="margin-top:15px;max-height:100px;">
									@if (isset($detail->json_params->image) && $detail->json_params->image != '')
									  <img style="height: 5rem;"
										src="{{ isset($detail->json_params->image) ? $detail->json_params->image : old('json_params[image]') }}">
									@endif

								  </div>
							</div>
							<div class="col-xs-12">
							  <label>@lang('Image background')</label>
							  <div class="input-group">
								<span class="input-group-btn">
								  <a data-input="image_background"  data-preview="image_background-holder"
									class="btn btn-primary lfm" data-type="cms-image_background">
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
							</div>
              

						</div>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('Parent element')</label>
                      <select name="parent_id" id="parent_id" class="form-control select2">
                        <option value="">== @lang('ROOT') ==</option>
                        @foreach ($taxonomys as $item)
                          {{-- @if (($item->parent_id == 0 || $item->parent_id == null) && $item->taxonomy == $detail->taxonomy) --}}
                          @if (($item->parent_id == 0 || $item->parent_id == null))
                            <option value="{{ $item->id }}"
                              {{ $detail->parent_id == $item->id ? 'selected' : '' }}>{{ $item->title->$locale }}</option>

                            @foreach ($taxonomys as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}"
                                  {{ $detail->parent_id == $sub->id ? 'selected' : '' }}>- - {{ $sub->title->$locale }}
                                </option>

                                @foreach ($taxonomys as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}"
                                      {{ $detail->parent_id == $sub_child->id ? 'selected' : '' }}>- - - -
                                      {{ $sub_child->title->$locale }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>

                    </div>

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
                  <label>@lang('Chọn giao diện hiển thị trang chủ')</label>
                  <select name="news_position" class="form-control" id="news_position">
                      <option value="" style="font-size: 15px"> - Vui lòng chọn - </option>
                      @foreach($array_location as $key => $position_text)
                      <option style="font-size: 15px" value="{{ $key }}" {{ $detail->news_position == $key ? 'selected' : '' }}>{{ $position_text }}</option>
                      @endforeach
                    </select>
                </div>
					
					@php 
					$hienthi = explode(';',trim($detail->hienthi,';'));
					@endphp
					<div class="form-group">
						<label>Vị trí hiển thị</label>
						<div class="form-control">
							<input type="checkbox" id="hienthi_0" name="hienthi[]" <?php if(in_array('0',$hienthi)) echo 'checked'; ?> value="0">
							<label for="vehicle1"> Menu chính</label>
							<input type="checkbox" id="hienthi_1" name="hienthi[]" <?php if(in_array('1',$hienthi)) echo 'checked'; ?> value="1">
							<label for="vehicle1"> Menu footer</label>
							
						</div>
                    </div>
                  </div>
				
                  <div class="col-md-12 hidden">
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
                  </div>
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
    height: 500px !important;
</style>
@section('script')

<script>
  
    CKEDITOR.replace('content', ck_options); 

    
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
            _content += '<option value="' + element.id + '"> ' + element.title.en + ' </option>';
            let _child = taxonomys.filter(function(e, i) {
              return ((e.parent_id == element.id) && e.taxonomy == _value);
            });
            if (_child) {
              _child.forEach(element => {
                _content += '<option value="' + element.id + '">- - ' + element.title.en + ' </option>';
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
