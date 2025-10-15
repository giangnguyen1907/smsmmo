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
              <label>@lang('Full name') <small class="text-red">*</small></label>
              <input type="text" class="form-control" name="name" placeholder="@lang('Full name')"
                value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
              <label>@lang('email') <small class="text-red">*</small></label>
              <input type="email" class="form-control" name="email" placeholder="Email đăng nhập"
                value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
              <label>@lang('password') <small class="text-red">*</small></label>
              <input type="password" class="form-control" name="password" placeholder="Mật khẩu ít nhất 8 ký tự"
                value="" autocomplete="new-password" required>
            </div>
			
			<div class="form-group">
              <label>@lang('Phòng ban') <small class="text-red">*</small></label>
              <select name="department_id" id="department_id" class="form-control select2" required>
                <option value="">@lang('please_chosen')</option>
                @foreach ($department as $dp)
                  <option value="{{ $dp->id }}">{{ $dp->title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6">

            <div class="form-group">
              <label>Quyền truy cập hệ thống <small class="text-red">*</small></label>
              <select name="role" id="role" class="form-control select2" required>
                <option value="">@lang('please_chosen')</option>
                @foreach ($roles as $item)
                  <option value="{{ $item->id }}" >
                    {{ $item->name }}
                  </option>
                @endforeach
              </select>
            </div>
            
            <div class="form-group">
              <label>Chức vụ / vai trò <small class="text-red">*</small></label>
              <select name="is_super_admin" id="is_super_admin" class="form-control select2" required>
                <option value="0" >Phóng viên đăng bài</option>
                <option value="2" >Phó / trưởng ban biên tập</option>
                <option value="3" >Phó tổng/ tổng biên tập</option>
                <?php if( Auth::guard('admin')->user()->is_super_admin == 1 ){ ?>
                <option value="1" >Quản trị hệ thống</option>
                <?php } ?>
              </select>
            </div>

            <div class="form-group">
              <label>@lang('status')</label>
              <div class="form-control">
                <label>
                  <input type="radio" name="status" value="active" checked>
                  <small>@lang('active')</small>
                </label>
                <label>
                  <input type="radio" name="status" value="deactive" class="ml-15">
                  <small>@lang('deactive')</small>
                </label>
              </div>
            </div>
      			<div class="form-group">
      			  <label>Ảnh đại diện</label>
      			  <div class="input-group">
      				<span class="input-group-btn">
      				  <a data-input="avatar" onclick="openPopupImg('avatar')" data-preview="image-holder" class="btn btn-primary lfm"
      					data-type="cms-image">
      					<i class="fa fa-picture-o"></i> @lang('choose')
      				  </a>
      				</span>
      				<input id="avatar" class="form-control" type="text" name="avatar"
      				  placeholder="@lang('image_link')...">
      			  </div>
      			  <div id="image-holder" style="margin-top:15px;max-height:100px;">
      				@if (old('avatar') != '')
      				<img id="view_avatar" style="height: 5rem;" src="{{ old('avatar') }}">
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
