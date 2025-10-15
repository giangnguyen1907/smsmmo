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
          class="fa fa-plus"></i>
        @lang('add_new')</a>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    @if (session('successMessage'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('successMessage') }}
      </div>
    @endif

    @if (session('errorMessage'))
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('errorMessage') }}
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
        <h3 class="box-title">@lang('create_form')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
        @csrf
        <div class="box-body">
          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('Mã ngôn ngữ') <small class="text-red">*</small></label>
              <input type="text" class="form-control" name="lang_code" placeholder="Mã ngôn ngữ"
                value="{{ old('lang_code') }}" required>
            </div>

            <div class="form-group">
              <label>@lang('Tiêu đề ngôn ngữ') <small class="text-red">*</small></label>
              <input type="text" class="form-control" name="title" placeholder="Tiêu đề ngôn ngữ"
                value="{{ old('title') }}" required>
            </div>

            <div class="form-group">
              <label>@lang('status')</label>
              <div class="form-control">
                <label>
                  <input type="radio" name="status" value="1" checked>
                  <small>@lang('active')</small>
                </label>
                <label>
                  <input type="radio" name="status" value="2" class="ml-15">
                  <small>@lang('deactive')</small>
                </label>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('Ngôn ngữ mặc định')</label>
              <div class="form-control">
                <label>
                  <input type="radio" name="is_default" value="1" >
                  <small>@lang('Có')</small>
                </label>
                <label>
                  <input type="radio" name="is_default" value="2" class="ml-15" checked>
                  <small>@lang('Không')</small>
                </label>
              </div>
            </div>

            
			<div class="form-group">
			  <label>Ảnh</label>
			  <div class="input-group">
				<span class="input-group-btn">
				  <a data-input="image" onclick="openPopupImg('image')" data-preview="image-holder" class="btn btn-primary lfm"
					data-type="cms-image">
					<i class="fa fa-picture-o"></i> @lang('choose')
				  </a>
				</span>
				<input id="image" class="form-control" type="text" name="image"
				  placeholder="@lang('image_link')...">
			  </div>
			  <div id="image-holder" style="margin-top:15px;max-height:100px;">
				@if (old('image') != '')
				<img id="view_avatar" style="height: 5rem;" src="{{ old('image') }}">
				@else
				<img id="view_avatar" style="height: 5rem;" src="">
				@endif
			  </div>
			</div>
			
          </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i> @lang('list')
          </a>
          <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-floppy-o"></i>
            @lang('save')</button>
        </div>
      </form>
    </div>
  </section>
@endsection
