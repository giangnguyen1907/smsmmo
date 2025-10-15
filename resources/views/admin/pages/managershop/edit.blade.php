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
                      <label>Chọn loại<small class="text-red">*</small></label>
                      <select name="code" class="form-control select2" required>
						<?php foreach($array_code as $key=>$value){ ?>
						<option value="{{ $key }}" <?php if($detail->code == $key) echo 'selected'; ?>>{{ $value }}</option>
						<?php } ?>
					  </select>
                    </div>
                    
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Tên<small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="title" placeholder="Tên"
                        value="{{ $detail->title ?? old('title') }}" required>
                    </div>
					
                  </div>
				  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Điện thoại</label>
                      <input type="text" class="form-control" name="phone" placeholder="Điện thoại"
                        value="{{ $detail->phone ?? old('phone') }}">
                    </div>
				 </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Địa chỉ</label>
                      <input type="text" class="form-control" name="address" placeholder="Điạ chỉ"
                        value="{{ $detail->address ?? old('address') }}">
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

