@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
            <a class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#showCNTT">
                <i class="fa fa-plus"></i> @lang('Add')
            </a>
        </h1>
    </section>
@endsection

@section('content')
    <style>
        a.status.prioritize {
            padding: 5px;
            border-radius: 10%;
            background-color: red;
            color: white;
        }

        a.status.processing {
            padding: 6px;
            border-radius: 5%;
            background-color: #00A157;
            color: white;
        }

        a.status.complete {
            padding: 5px;
            border-radius: 10%;
            background-color: #f39c12;
            color: white;
        }

        a.status.reprocess {
            padding: 5px;
            border-radius: 10%;
            background-color: black;
            color: white;
        }

        .scrollable-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .status-container {
            display: flex;
            align-items: center;
            text-align: right;
        }

        .status {
            margin-right: 10px;
        }

        .select-status {
            width: auto;
        }

        span.checkbox {
            display: inline-block;
            margin-left: 5px;
        }
    </style>

    @php
        $status_work = App\Consts::STATUS_WORK;
        $departmentWorkArray = $userWorkArray = [];
    @endphp

    <!-- Main content -->
    <section class="content">
        <div class="box box-default">
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Từ ngày</label>
                                <input type="date" class="form-control" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Đến ngày</label>
                                <input type="date" class="form-control" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">Tìm kiếm</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        Làm mới
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        <div class="box">

            <div class="box-header">
                <h3 class="box-title">Danh sách dự án</h3>
            </div>

            <div class="box-body table-responsive">
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

                @if (count($manageProject) == 0)
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('not_found')
                    </div>
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Tên dự án')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Phân loại')</th>
                                <th>@lang('Người cập nhật')</th>
                                <th>@lang('Updated')</th>
                                <th style="min-width: 80px">@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($manageProject as $stt => $row)
                                @php
                                    $userWorkArray = explode(',', $row->user_work);
                                    $departmentWorkArray = explode(',', $row->department_user_work);
                                @endphp
                                <tr class="valign-middle">
                                    <td>
                                        {{ $stt + 1 }}
                                    </td>
                                    <td>
                                        {{ $row->title }}
                                    </td>
                                    <td>
                                        @lang($row->status)
                                    </td>
                                    <td>
                                        {{ $row->is_public == 0 ? 'Nội bộ' : 'Công khai' }}
                                    </td>
                                    <td>
                                        {{ $row->admin->name }}
                                    </td>
                                    <td>
                                        {{ date('H:i d/m/Y', strtotime($row->updated_at)) }}
                                    </td>
                                    <td>

                                        <a class="btn btn-sm btn-info"
                                            href="{{ route('cms_project.view_parent_project', ['id' => $row->id]) }}"><i
                                                class="fa fa-eye"></i></a>

                                        @if (Auth::guard('admin')->user()->id == $row->admin_created_id)
                                            <a type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#editModal{{ $row->id }}">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            <div class="modal fade" id="editModal{{ $row->id }}" data-backdrop="false"
                                                role="dialog" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <form method="POST"
                                                        action="{{ route(Request::segment(2) . '.update', $row->id) }}"
                                                        class="form-horizontal">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"><span
                                                                        aria-hidden="true">×</span></button>
                                                                <h4 class="modal-title">Cập nhật thông tin dự án</h4>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <div class="col-md-12 col-xs-12">
                                                                        <label>@lang('Tên dự án')</label>
                                                                        <input class="form-control" type="text"
                                                                            placeholder="Nhập tên dự án" name="title"
                                                                            id="title" required
                                                                            value="{{ $row->title }}">
                                                                    </div>
                                                                    <div class="col-md-12 col-xs-12 navbar-btn">
                                                                        <label>@lang('Cấu hình công việc')</label>
                                                                        <div class="form-control">
                                                                            <input type="radio" name="is_public"
                                                                                value="0" class="check-all-congkhai"
                                                                                {{ $row->is_public == 0 ? 'checked' : '' }}>
                                                                            Cá nhân
                                                                            <input type="radio" name="is_public"
                                                                                value="1"
                                                                                class="ml-15 check-all-congkhai"
                                                                                {{ $row->is_public == 1 ? 'checked' : '' }}>
                                                                            Công khai
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-12 col-xs-12 navbar-btn"
                                                                        id="noinhan_{{ $row->id }}">
                                                                        <label>@lang('Chọn người tham gia')</label>
                                                                        <section class="tbl-header table_scroll">
                                                                            <div class="container_table custom-scroll table-responsive no-margin"
                                                                                style="max-height: 300px; overflow-y: scroll;">
                                                                                <table
                                                                                    class="table table-striped table-bordered table-hover no-footer"
                                                                                    width="100%"
                                                                                    style="border: 1px solid #ccc !important">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th class="col-md-4 col-xs-4">
                                                                                                <label
                                                                                                    style="display: flex; align-items: center;">
                                                                                                    <input
                                                                                                        class="checkbox check-all"
                                                                                                        type="checkbox"
                                                                                                        value="0"><span
                                                                                                        class="checkbox">Chọn
                                                                                                        tất cả</span>
                                                                                                </label>
                                                                                            </th>
                                                                                            <th class="col-md-8 col-xs-8">
                                                                                                <label
                                                                                                    style="display: flex; align-items: center;">
                                                                                                    <input
                                                                                                        class="checkbox check-all-relative"
                                                                                                        type="checkbox"
                                                                                                        value="0"><span
                                                                                                        class="checkbox">Người
                                                                                                        tham
                                                                                                        gia</span>
                                                                                                </label>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody
                                                                                        id="load-ajax_{{ $row->id }}">
                                                                                        @foreach ($departments as $department)
                                                                                            <tr>
                                                                                                <td class="">
                                                                                                    <label
                                                                                                        style="display: flex; align-items: center;">
                                                                                                        <input
                                                                                                            class="_check_all checkbox check_class"
                                                                                                            id="check_class_{{ $department->id }}_{{ $row->id }}"
                                                                                                            type="checkbox"
                                                                                                            name="department_user_work[]"
                                                                                                            value="{{ $department->id }}"
                                                                                                            {{ in_array($department->id, $departmentWorkArray) ? 'checked' : '' }}>
                                                                                                        <span
                                                                                                            class="checkbox">{{ $department->title }}</span>
                                                                                                    </label>
                                                                                                </td>
                                                                                                <td class="">
                                                                                                    @foreach ($admins as $admin)
                                                                                                        @if ($admin->department_id == $department->id)
                                                                                                            <label
                                                                                                                style="display: flex; align-items: center;">
                                                                                                                <input
                                                                                                                    class="checkbox _check_all _check_relative _check_all_{{ $department->id }}_{{ $row->id }}"
                                                                                                                    type="checkbox"
                                                                                                                    name="user_work[]"
                                                                                                                    value="{{ $admin->id }}"
                                                                                                                    data-value="{{ $department->id }}"
                                                                                                                    {{ in_array($admin->id, $userWorkArray) ? 'checked' : '' }}>
                                                                                                                <span
                                                                                                                    class="checkbox">{{ $admin->name }}</span><br>
                                                                                                            </label>
                                                                                                        @endif
                                                                                                    @endforeach
                                                                                                </td>
                                                                                            </tr>
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <div class="form-group">
                                                                    <div class="col-md-6 col-xs-6 text-right">
                                                                        <button type="submit" class="btn btn-success">Cập
                                                                            nhật</button>
                                                                    </div>
                                                                    <div class="col-md-6 col-xs-6 text-left">
                                                                        <button type="button" class="btn btn-default "
                                                                            data-dismiss="modal">Đóng lại</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <a type="button" class="btn btn-sm btn-danger"
                                                onclick="destroyManageProject('{{ route('cms_project.destroy_manage_project', ['id' => $row->id]) }}')"><i
                                                    class="fa fa-trash"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
            <div class="box-footer clearfix">
                <div class="row">
                    <div class="col-sm-5">
                        Tìm thấy {{ $manageProject->total() }} kết quả
                    </div>
                    <div class="col-sm-7">
                        {{ $manageProject->withQueryString()->links('admin.pagination.default') }}
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="modal fade" id="showCNTT" data-backdrop="false" role="dialog" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form method="POST" action="{{ route(Request::segment(2) . '.store') }}" class="form-horizontal">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Thêm mới dự án</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label>@lang('Tên dự án')</label>
                                <input class="form-control" type="text" placeholder="Nhập tên dự án" name="title"
                                    id="title" required>
                            </div>
                            <div class="col-md-12 col-xs-12 navbar-btn">
                                <label>@lang('Cấu hình công việc')</label>
                                <div class="form-control">
                                    <input type="radio" name="is_public" value="0" class="check-all-congkhai"
                                        checked="">
                                    Cá nhân
                                    <input type="radio" name="is_public" value="1"
                                        class="ml-15 check-all-congkhai">
                                    Công khai
                                </div>
                            </div>
                            <div class="col-md-12 col-xs-12 navbar-btn" id="noinhan">
                                <label>@lang('Chọn người tham gia')</label>
                                <section class="tbl-header table_scroll">
                                    <div class="container_table custom-scroll table-responsive no-margin"
                                        style="max-height: 300px; overflow-y: scroll;">
                                        <table class="table table-striped table-bordered table-hover no-footer"
                                            width="100%" style="border: 1px solid #ccc !important">
                                            <thead>
                                                <tr>
                                                    <th class="col-md-4 col-xs-4">
                                                        <label style="display: flex; align-items: center;">
                                                            <input class="checkbox check-all" type="checkbox"
                                                                value="0"><span class="checkbox">Chọn tất cả</span>
                                                        </label>
                                                    </th>
                                                    <th class="col-md-8 col-xs-8">
                                                        <label style="display: flex; align-items: center;">
                                                            <input class="checkbox check-all-relative" type="checkbox"
                                                                value="0"><span class="checkbox">Người tham
                                                                gia</span>
                                                        </label>
                                                </tr>
                                            </thead>
                                            <tbody id="load-ajax">
                                                @foreach ($departments as $department)
                                                    <tr>
                                                        <td class="">
                                                            <label style="display: flex; align-items: center;">
                                                                <input class="_check_all checkbox check_class"
                                                                    id="check_class_{{ $department->id }}_0"
                                                                    type="checkbox" name="department_user_work[]"
                                                                    value="{{ $department->id }}"
                                                                    {{ in_array($department->id, $departmentWorkArray) ? 'checked' : '' }}>
                                                                <span class="checkbox">{{ $department->title }}</span>
                                                            </label>
                                                        </td>
                                                        <td class="">
                                                            @foreach ($admins as $admin)
                                                                @if ($admin->department_id == $department->id)
                                                                    <label style="display: flex; align-items: center;">
                                                                        <input
                                                                            class="checkbox _check_all _check_relative _check_all_{{ $department->id }}_0"
                                                                            type="checkbox" name="user_work[]"
                                                                            value="{{ $admin->id }}"
                                                                            data-value="{{ $department->id }}">
                                                                        <span
                                                                            class="checkbox">{{ $admin->name }}</span><br>
                                                                    </label>
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-6 text-right">
                                <button type="submit" class="btn btn-success">Thêm mới</button>
                            </div>
                            <div class="col-md-6 col-xs-6 text-left">
                                <button type="button" class="btn btn-default " data-dismiss="modal">Đóng lại</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function destroyManageProject(link_delete) {
            if (confirm("Bạn có chắc chắn xóa dự án này?")) {
                window.location.href = link_delete;
            }
        }
    </script>
    <script>
        $(document).ready(function() {

            $('.check-all-congkhai').on('change', function() {
                var $modal = $(this).closest('.modal');

                if ($(this).val() == 1) {
                    $modal.find("[id^=noinhan_]").hide();
                    $modal.find('._check_all').prop('checked', true);
                    $modal.find('.check-all-relative').prop('checked', true);
                } else {
                    $modal.find("[id^=noinhan_]").show();
                    $modal.find('._check_all').prop('checked', false);
                    $modal.find('.check-all-relative').prop('checked', false);
                }
            });

            $('.check-all').on('change', function() {
                var $modal = $(this).closest('.modal');
                var isChecked = $(this).prop('checked');

                $modal.find('._check_all').prop('checked', isChecked);
                $modal.find('.check_class').prop('checked', isChecked);
                $modal.find('.check-all-relative').prop('checked', isChecked);
            });

            $('.check-all-relative').on('change', function() {
                var $modal = $(this).closest('.modal');
                var isChecked = $(this).prop('checked');

                $modal.find('._check_relative').prop('checked', isChecked);
            });

            $('.check_class').on('change', function() {
                var class_id = $(this).val();
                var $modal = $(this).closest('.modal');

                if ($("#check_class_" + class_id + ":checked").val() == class_id) {
                    $modal.find('._check_all_' + class_id).prop('checked', true);
                } else {
                    $modal.find('._check_all_' + class_id).prop('checked', false);
                }
                $modal.find('.check-all-relative').prop('checked', false);
            });

            $('._check_all').on('change', function() {
                var class_id = $(this).attr('data-value');
                var $modal = $(this).closest('.modal');

                $modal.find('.check-all').prop('checked', false);
                $modal.find('#check_class_' + class_id).prop('checked', false);
            });

            $('.btn-psadmin').attr('disabled', 'disabled');

            $('.checkbox').on('change', function() {
                var $modal = $(this).closest('.modal');

                if ($modal.find(".checkbox:checked").length > 0) {
                    $modal.find('.btn-psadmin').attr('disabled', false);
                } else {
                    $modal.find('.btn-psadmin').attr('disabled', 'disabled');
                }
            });

            $('._check_relative').on('change', function() {
                var class_id = $(this).attr('data-value');
                var $modal = $(this).closest('.modal');

                $modal.find('.check-all-relative').prop('checked', false);
                $modal.find('#check_class_' + class_id).prop('checked', false);
            });

        });
    </script>
@endsection
