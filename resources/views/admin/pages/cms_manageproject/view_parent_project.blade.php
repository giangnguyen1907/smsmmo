@extends('admin.layouts.app')

@section('title')
    {{ $cmsManageProject->title }}
@endsection

@section('content-header')
    @php
        $status_work = App\Consts::STATUS_WORK;
        $userCanAccess =
            in_array(Auth::guard('admin')->user()->id, explode(',', $cmsManageProject->user_work)) ||
            Auth::guard('admin')->user()->id == $cmsManageProject->admin_created_id ||
            $cmsManageProject->is_public == 1;
    @endphp

    <section class="content-header">
        <h1>
            <a style="color: red">{{ $cmsManageProject->title }}</a>
            @if ($cmsManageProject->admin_created_id == Auth::guard('admin')->user()->id)
                <a class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#showFormCreat">
                    <i class="fa fa-plus"></i> @lang('Thêm mới công việc dự án')
                </a>

                <a class="btn btn-sm btn-success pull-right mr-15" data-toggle="modal" data-target="#showFormFile">
                    <i class="fa fa-plus"></i> @lang('Thêm file dự án')
                </a>
            @endif
        </h1>
    </section>

    <div class="modal fade" id="showFormCreat" data-backdrop="false" role="dialog" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('cms_project.parent_project', ['id' => $cmsManageProject->id]) }}"
                class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Thêm mới công việc dự án</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label>@lang('Tên công việc')</label>
                                <input class="form-control" type="text" placeholder="Nhập tên công việc" name="title"
                                    id="title" required value="{{ old('title') }}">
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <label>@lang('Mô tả chi tiết')</label>
                                <textarea name="content" class="form-control" id="content">{{ old('content') }}</textarea>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <label>Trạng thái</label>
                                <select name="status" class="form-control" id="status">
                                    @foreach ($status_work as $key => $value)
                                        <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 col-xs-12">
                                <label>Thời hạn</label>
                                <input class="form-control" name="deadline" id="deadline" type="datetime-local"
                                    value="<?= date('Y-m-d\TH:i') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-6 text-right">
                                <button type="submit" class="btn btn-success">Thêm công việc</button>
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

    <div class="modal fade" id="showFormFile" data-backdrop="false" role="dialog" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('cms_project.project_file', ['id' => $cmsManageProject->id]) }}"
                class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Thêm file dự án</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="col-md-12 col-xs-12">
                                <label>@lang('Chọn file')</label>
                                <input class="form-control" name="file_project[]" type="file" multiple="multiple"
                                    title="Chọn tệp tải lên" placeholder="Chọn tệp tải lên">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <div class="col-md-6 col-xs-6 text-right">
                                <button type="submit" class="btn btn-success">Thêm file</button>
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
    </style>

    <!-- Main content -->
    <section class="content">
        <div class="box box-default">
            <div class="box-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>@lang('Tìm kiếm file')</label>
                            <input type="text" class="form-control" name="keyword" id="keyword"
                                placeholder="@lang('keyword_note')" onkeyup="fetchFiles()">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Sắp xếp</label>
                            <select class="form-control" name="orderby" id="orderby" onchange="fetchFiles()">
                                <option value="">Chọn kiểu sắp xếp</option>
                                <option value="orderby-az">
                                    Tên file từ A - Z
                                </option>
                                <option value="orderby-za">
                                    Tên file từ Z - A
                                </option>
                                <option value="orderby-update">
                                    Theo thời gian cập nhật mới nhất - cũ nhất
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Danh sách file trong dự án</h3>
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
                <div class="container_table custom-scroll table-responsive no-margin"
                    style="max-height: 400px; overflow-y: scroll;">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Tên file')</th>
                                <th>@lang('Updated')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody id="fileTableBody">
                            @foreach ($fileProjects as $key => $fileProject)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        <a href="{{ $fileProject->link_file }}" target="_blank"
                                            download>{{ $fileProject->name }}</a>
                                    </td>
                                    <td>
                                        {{ date('H:i d/m/Y', strtotime($fileProject->updated_at)) }}
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-sm btn-danger"
                                            onclick="deleteFile('{{ $fileProject->id }}')"><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>


        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Danh sách công việc trong dự án</h3>
            </div>
            <div class="box-body table-responsive">
                <div class="container_table custom-scroll table-responsive no-margin"
                    style="max-height: 400px; overflow-y: scroll;">
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>@lang('STT')</th>
                                <th>@lang('Công việc')</th>
                                <th>@lang('Mô tả chi tiết')</th>
                                <th>@lang('Trạng thái')</th>
                                <th>@lang('Thời gian hoàn thành')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody id="fileTableBody">
                            @foreach ($parentProjects as $k => $parentProject)
                                <tr class="valign-middle">
                                    <td>
                                        {{ $k + 1 }}
                                    </td>
                                    <td>
                                        {{ $parentProject->title }}
                                    </td>
                                    <td>
                                        {{ $parentProject->content }}
                                    </td>
                                    <td>
                                        <select class="form-control select-status" name="change-status"
                                            id="change-status" onchange="changeStatus(this, '{{ $parentProject->id }}')">
                                            @foreach ($status_work as $k1 => $value)
                                                <option value="{{ $k1 }}"
                                                    {{ $parentProject->status == $k1 ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{ date('d-m-Y', strtotime($parentProject->deadline)) }}
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-sm btn-danger"
                                            onclick="deleteParentProject('{{ $parentProject->id }}')"><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>


        <div class="box">
            <div class="box-footer">
                <a class="btn btn-success btn-sm" href="/admin/manage_project">
                    <i class="fa fa-bars"></i> @lang('List')
                </a>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function fetchFiles() {
            var keyword = $('#keyword').val();
            var orderby = $('#orderby').val();
            var manage_project_id = {{ $cmsManageProject->id }};

            $.ajax({
                url: '{{ route('cms_project.search_project_file') }}',
                type: 'GET',
                data: {
                    keyword: keyword,
                    orderby: orderby,
                    manage_project_id: manage_project_id
                },
                success: function(response) {
                    $('#fileTableBody').html(response);
                }
            });
        }

        function deleteFile(file_id) {
            if (confirm('Bạn chắc chắn muốn xóa file này?')) {
                var f = 'id=' + file_id;
                var _url = '{{ route('cms_project.destroy_project_file') }}';
                $.ajax({
                    url: _url,
                    data: f,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Đã xảy ra lỗi khi xóa file. Vui lòng thử lại sau.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Đã xảy ra lỗi khi xóa file. Vui lòng thử lại sau.');
                    }
                });
            }
        }

        function deleteParentProject(parent_project_id) {
            if (confirm('Bạn chắc chắn muốn xóa công việc này?')) {
                var f = 'id=' + parent_project_id;
                var _url = '{{ route('cms_project.destroy_parent_project') }}';
                $.ajax({
                    url: _url,
                    data: f,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            alert('Đã xảy ra lỗi khi xóa công việc. Vui lòng thử lại sau.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Đã xảy ra lỗi khi xóa công việc. Vui lòng thử lại sau.');
                    }
                });
            }
        }

        function changeStatus(selectElement, id) {
            var status = $(selectElement).val();
            $.ajax({
                url: '{{ route('cms_project.status_project') }}',
                type: 'GET',
                data: {
                    status: status,
                    id: id
                },
                success: function(response) {
                    window.location.reload();
                }
            });
        }
    </script>
@endsection
