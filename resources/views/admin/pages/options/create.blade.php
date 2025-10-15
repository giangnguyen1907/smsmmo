@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2).'.create') }}"><i
        class="fa fa-plus"></i> @lang('add_new')</a>
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
      <h3 class="box-title">@lang('create_form')</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" action="{{ route(Request::segment(2).'.store') }}" method="POST">
      @csrf
      <div class="box-body">
        <div class="col-md-6">

          <div class="form-group">
            <label>@lang('option_name') <small class="text-red">*</small>: <small><i>Viết theo cấu trúc dạng tên biến (snake_case, SNAKE_CASE hoặc camelCase)</i></small></label>
            <input type="text" class="form-control" name="option_name" placeholder="@lang('option_name')"
              value="{{ old('option_name') }}" required>
          </div>

          <div class="form-group">
            <label>@lang('option_value') <small class="text-red">*</small>: <small><i>Có thể là dạng dữ liệu có cấu trúc (json, array,...)</i></small></label>
            <textarea name="option_value" id="option_value" class="form-control"
              rows="5" required>{{ old('option_value') }}</textarea>
          </div>

        </div>

        <div class="col-md-6">

          <div class="form-group">
            <label>@lang('is_system_param')</label>
            <div class="form-control">
              <label>
                <input type="radio" name="is_system_param" value="1" checked="">
                <small>@lang('true')</small>
              </label>
              <label>
                <input type="radio" name="is_system_param" value="0" class="ml-15">
                <small>@lang('false')</small>
              </label>
            </div>
          </div>

          <div class="form-group">
            <label>@lang('description')</label>
            <textarea name="description" id="description" class="form-control"
              rows="5">{{ old('description') }}</textarea>
          </div>

        </div>

      </div>
      <!-- /.box-body -->

      <div class="box-footer">
        <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2).'.index') }}">
          <i class="fa fa-bars"></i> @lang('list')
        </a>
        <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
          @lang('save')</button>
      </div>
    </form>
  </div>
</section>
@endsection