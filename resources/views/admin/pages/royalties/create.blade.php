@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    
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
                    <label for="doctor_id">
                      @lang('Bài viết')
                      <small class="text-red">*</small>
                    </label>
                    <input class="form-control" name="post_id" value=""  />
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="doctor_id">
                      @lang('Tiền nhuận bút')
                      <small class="text-red">*</small>
                    </label>
                    <input name="price" type="number" class="form-control" value="" />
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="doctor_id">
                      @lang('Trạng thái')
                      <small class="text-red">*</small>
                    </label>
                    <select name="status" id="status" class="form-control select2" style="width: 100%;">
                      <option value="">@lang('Please select')</option>
                      @foreach (App\Consts::STATUS_ROYALTIES as $key => $value)
                        <option value="{{ $key }}">
                          {{ __($value) }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="doctor_id">
                      @lang('Ghi chú')
                      <small class="text-red">*</small>
                    </label>
                    <input name="note" type="type" class="form-control" value="" />
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
        <button type="submit" class="btn btn-primary pull-right btn-sm">
          <i class="fa fa-floppy-o"></i>
          @lang('Save')
        </button>
      </div>
    </form>
  </div>
</section>
@endsection