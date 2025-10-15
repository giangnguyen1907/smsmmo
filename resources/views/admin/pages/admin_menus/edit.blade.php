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

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">@lang('Update form')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.update', $adminMenu->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">
          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('Parent menu')</label>
              <select name="parent_id" id="parent_id" class="form-control select2">
                <option value="">@lang('Please select')</option>
                @foreach ($rootMenus as $item)
                  <option value="{{ $item->id }}" {{ $adminMenu->parent_id == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="form-group">
              <label>@lang('Name') <small class="text-red">*</small></label>
              <input type="text" class="form-control" name="name" placeholder="@lang('Name')"
                value="{{ $adminMenu->name }}" required>
            </div>

            <div class="form-group">
              <label>@lang('Url')</label>
              <input type="text" class="form-control" name="url_link" placeholder="@lang('Url')"
                value="{{ $adminMenu->url_link }}">
            </div>


          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('Icon')</label>
              <input type="text" class="form-control" name="icon" placeholder="VD: fa fa-folder"
                value="{{ $adminMenu->icon }}">
            </div>

            <div class="form-group">
              <label>@lang('Order')</label>
              <input type="number" class="form-control" name="iorder" placeholder="@lang('Order')"
                value="{{ $adminMenu->iorder }}">
            </div>

            <div class="form-group">
              <label>@lang('Status')</label>
              <div class="form-control">
                <label>
                  <input type="radio" name="status" value="active"
                    {{ $adminMenu->status == 'active' ? 'checked' : '' }}>
                  <small>@lang('active')</small>
                </label>
                <label>
                  <input type="radio" name="status" value="deactive" class="ml-15"
                    {{ $adminMenu->status == 'deactive' ? 'checked' : '' }}>
                  <small>@lang('deactive')</small>
                </label>
              </div>
            </div>

            <div class="form-group">
              <label>@lang('Hiển thị menu ngang')</label>
              <div class="form-control">
                <label>
                  <input type="radio" name="is_check" value="active"
                    {{ $adminMenu->is_check == 'active' ? 'checked' : '' }}>
                  <small>@lang('yes')</small>
                </label>
                <label>
                  <input type="radio" name="is_check" value="deactive" class="ml-15"
                    {{ $adminMenu->is_check == 'deactive' ? 'checked' : '' }}>
                  <small>@lang('no')</small>
                </label>
              </div>
            </div>

          </div>

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
