@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Cập nhật thông tin
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
            <form role="form" action="{{ route('admins.update_profile') }}" method="GET">
                @csrf
                @method('PUT')
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>@lang('Tên đăng nhập') <small class="text-red">*</small></label>
                            <input type="text" class="form-control" name="email" value="{{ $admin->email }}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('name') <small class="text-red">*</small></label>
                            <input type="text" class="form-control" name="name" value="{{ $admin->name }}" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Mật khẩu') <small class="text-muted"><i>(Để trống nếu không muốn đổi mật khẩu
                                        mới)</i></small></label>
                            <input type="password" class="form-control" name="password_new"
                                placeholder="Mật khẩu ít nhất 8 ký tự" value="" autocomplete="new-password">
                        </div>

                        <div class="form-group">
                            <label>@lang('Phòng ban') <small class="text-red">*</small></label>
                            <select name="department_id" id="department_id" class="form-control select2" required>
                                <option value="" disabled>@lang('please_chosen')</option>
                                @foreach ($department as $dp)
                                    <option value="{{ $dp->id }}"
                                        {{ $admin->department_id == $dp->id ? 'selected' : 'disabled' }}>
                                        {{ $dp->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>@lang('Nhóm quyền') <small class="text-red">*</small></label>
                            <select name="role" id="role" class="form-control select2" required>
                                <option value="" disabled>@lang('please_chosen')</option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $admin->role == $item->id ? 'selected' : 'disabled' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Chức vụ / vai trò <small class="text-red">*</small></label>
                            <select name="is_super_admin" id="is_super_admin" class="form-control select2" required>
                                <option value="0" {{ $admin->is_super_admin == 0 ? 'selected' : 'disabled' }}>Phóng
                                    viên đăng bài</option>
                                <option value="2" {{ $admin->is_super_admin == 2 ? 'selected' : 'disabled' }}>Phó /
                                    trưởng ban biên tập</option>
                                <option value="3" {{ $admin->is_super_admin == 3 ? 'selected' : 'disabled' }}>Phó
                                    tổng/ tổng biên tập</option>
                                <?php if( Auth::guard('admin')->user()->is_super_admin == 1 ){ ?>
                                <option value="1" {{ $admin->is_super_admin == 1 ? 'selected' : 'disabled' }}>Quản trị
                                    hệ thống</option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>@lang('status')</label>
                            <div class="form-control">
                                <label>
                                    <input type="radio" name="status" value="active"
                                        {{ $admin->status == 'active' ? 'checked' : 'disabled' }}>
                                    <small>@lang('active')</small>
                                </label>
                                <label>
                                    <input type="radio" name="status" value="deactive" class="ml-15"
                                        {{ $admin->status == 'deactive' ? 'checked' : 'disabled' }}>
                                    <small>@lang('deactive')</small>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Ảnh đại diện</label>
                            <div class="input-group">
                                <span class="input-group-btn">
                                    <a data-input="avatar" onclick="openPopupImg('avatar')" data-preview="image-holder"
                                        class="btn btn-primary lfm" data-type="cms-image">
                                        <i class="fa fa-picture-o"></i> @lang('choose')
                                    </a>
                                </span>
                                <input id="avatar" class="form-control" type="text" name="avatar"
                                    value="{{ $admin->avatar }}" placeholder="@lang('image_link')...">
                            </div>
                            <div id="image-holder" style="margin-top:15px;max-height:100px;">
                                @if ($admin->avatar != '')
                                    <img id="view_avatar" style="height: 5rem;" src="{{ $admin->avatar }}">
                                @else
                                    <img id="view_avatar" style="height: 5rem;" src="">
                                @endif
                            </div>
                        </div>


                    </div>

                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-floppy-o"></i>
                        @lang('save')</button>
                </div>
            </form>
        </div>
    </section>
@endsection
