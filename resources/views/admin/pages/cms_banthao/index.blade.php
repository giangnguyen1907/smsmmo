@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            {{ $module_name }}
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}">
                <i class="fa fa-plus"></i> Thêm mới </a>
        </h1>
    </section>
@endsection

@section('content')
    @php
        $array_theloai = App\Consts::THELOAI_BANTHAO;
        $numTabsToShow = 0;
        switch ($_REQUEST['task']) {
            case 'chosogiayphep':
                $numTabsToShow = 1;
                break;
            case 'choqdcapphep':
                $numTabsToShow = 2;
                break;
            case 'choluuchieu':
                $numTabsToShow = 3;
                break;
            case 'phathanh':
            case 'thuhoi':
                $numTabsToShow = count($tabs);
                break;
            default:
                $numTabsToShow = count($tabs);
        }
    @endphp
    <style>
        .scrollable-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .modal-dialog {
            width: 80%;
            max-width: 900px;
        }

        .modal-header {
            background-color: #f5f5f5;
            border-bottom: 2px solid #ddd;
        }

        .modal-title {
            font-weight: bold;
        }

        .panel-title-modal {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .panel-body i.new {
            width: 20px;
            color: #00A157;
            margin-right: 25px;
        }

        .text-primary {
            font-weight: bold;
        }

        .font-14 {
            font-size: 14px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-dialog {
                width: 95%;
                margin: 10px auto;
            }

            .col-md-6 {
                margin-bottom: 10px;
            }
        }

        /* Button styles */
        .btn-default {
            background-color: #f4f4f4;
            border-color: #ddd;
        }

        .btn-default:hover {
            background-color: #e7e7e7;
            border-color: #ccc;
        }

        /* Additional spacing */
        .row {
            margin-bottom: 10px;
        }

        h3.text-primary {
            margin-top: 0;
            margin-bottom: 20px;
        }
    </style>
    <section class="content">
        <div class="box box-default">
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <input type="hidden" name="task" value="{{ $_REQUEST['task'] }}" />
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Từ khoá</label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Thể loại</label>
                                <select name="theloai" id="theloai" class="form-control select2">
                                    <option value="">@lang('Please select')</option>
                                    @foreach ($array_theloai as $key => $value)
                                        <option value="key"
                                            {{ isset($params['theloai']) && $params['theloai'] == $key ? 'selected' : '' }}>
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Từ ngày</label>
                                <input type="date" class="form-control" name="from_date"
                                    value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Đến ngày</label>
                                <input type="date" class="form-control" name="to_date"
                                    value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>@lang('Filter')</label>
                                <div>
                                    <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                                    <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                        @lang('Reset')
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>

        <div class="box">
            <div class="nav-tabs-custom">

                <div class="tab-content">
                    <div class="tab-pane active">
                        @if (session('errorMessage'))
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {{ session('errorMessage') }}
                            </div>
                        @endif
                        @if (session('successMessage'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {{ session('successMessage') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>

                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach

                            </div>
                        @endif
                        @php
                            $pr = '';
                            $pr .= isset($params['keyword']) ? '&keyword=' . $params['keyword'] : '';
                            $pr .= isset($params['theloai']) ? '&theloai=' . $params['theloai'] : '';
                            $pr .= isset($params['from_date']) ? '&from_date=' . $params['from_date'] : '';
                            $pr .= isset($params['to_date']) ? '&to_date=' . $params['to_date'] : '';
                        @endphp
                        <ul class="nav nav-tabs">
                            <li class="<?php if ($_REQUEST['task'] == 'chosogiayphep') {
                                echo 'active';
                            } ?>">
                                <a href="/admin/cms_banthao?task=chosogiayphep{{ $pr }}">
                                    <h5>Chờ số giấy phép</h5>
                                </a>
                            </li>
                            <li class="<?php if ($_REQUEST['task'] == 'choqdcapphep') {
                                echo 'active';
                            } ?>">
                                <a href="/admin/cms_banthao?task=choqdcapphep{{ $pr }}">
                                    <h5>Chờ QĐ cấp phép</h5>
                                </a>
                            </li>
                            <li class="<?php if ($_REQUEST['task'] == 'choluuchieu') {
                                echo 'active';
                            } ?>">
                                <a href="/admin/cms_banthao?task=choluuchieu{{ $pr }}">
                                    <h5>Chờ lưu chiểu</h5>
                                </a>
                            </li>
                            <li class="<?php if ($_REQUEST['task'] == 'phathanh') {
                                echo 'active';
                            } ?>">
                                <a href="/admin/cms_banthao?task=phathanh{{ $pr }}">
                                    <h5>Phát hành</h5>
                                </a>
                            </li>

                            <li class="<?php if ($_REQUEST['task'] == 'thuhoi') {
                                echo 'active';
                            } ?>">
                                <a href="/admin/cms_banthao?task=thuhoi{{ $pr }}">
                                    <h5>Thu hồi</h5>
                                </a>
                            </li>
                        </ul>
                        @if (count($rows) == 0)
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                @lang('not_found')
                            </div>
                        @else
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th>@lang('STT')</th>
                                        <th>Tác phẩm/ tác giả</th>
                                        <th>Thông tin</th>
                                        <th>Xuất bản</th>
                                        <th>Bản thảo đính kèm</th>
                                        <th>Cập nhật</th>
                                        <th style="width: 15%">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $stt => $row)
                                        <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}"
                                            method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                                            <tr class="valign-middle">
                                                <td class="text-center">
                                                    {{ $stt + 1 }}
                                                </td>
                                                <td>
                                                    <a data-toggle="modal" data-target="#modal-info-{{ $row->id }}"
                                                        style="cursor: pointer"><strong
                                                            style="font-size: 15px;">{{ $row->tacpham }}</strong></a>
                                                    <br>Thể loại: <span
                                                        class="underline">{{ $array_theloai[$row->theloai] }}</span>
                                                    <br>Tác giả: {{ $row->tacgia }}
                                                    <br>Địa chỉ: {{ $row->diachi }}
                                                    <br>Điện thoại: {{ $row->dienthoai }}
                                                    <br>Email: {{ $row->email }}
                                                </td>
                                                <td>
                                                    Số trang: {{ $row->sotrang }}
                                                    <br>Khuôn khổ: {{ $row->khuonkho }}
                                                    <br>Định dạng: {{ $row->dinhdang }}
                                                    <br>Dung lượng: {{ $row->dungluong }}
                                                </td>
                                                <td>
                                                    Lần xuất bản: {{ $row->lanxuatban }}
                                                    <br>Lần tái bản: {{ $row->lantaiban }}
                                                    <br>Số lượng: {{ $row->soluong }}
                                                    <br>Nhà in: {{ $row->nhain }}
                                                    <br>Nền tảng: {{ $row->trangweb }}
                                                </td>
                                                <td>
                                                    @if ($row->list_file_goc != '')
                                                        @foreach (explode(';', $row->list_file_goc) as $key => $value)
                                                            <a href="{{ $value }}"
                                                                download>{{ basename($value) }}</a><br>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $row->admin_updated }}<br>
                                                    {{ date('d/m/Y H:i', strtotime($row->updated_at)) }}
                                                </td>
                                                <td>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger" type="submit" data-toggle="tooltip"
                                                        title="@lang('delete')" data-original-title="@lang('delete')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>

                                                    <a class="btn btn-info" title="Cập nhật thông tin"
                                                        href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </a>

                                                    @if ($_REQUEST['task'] == 'chosogiayphep')
                                                        <a title="Duyệt quyết định cấp phép" href="javascript:;"
                                                            class="btn btn-success"
                                                            onclick="showUpdateModal({{ $row->id }})">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    @endif

                                                    @if ($_REQUEST['task'] == 'choqdcapphep')
                                                        <a title="Duyệt quyết định lưu chiểu" href="javascript:;"
                                                            class="btn btn-success"
                                                            onclick="showUpdateModalLuuchieu({{ $row->id }})">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    @endif

                                                    @if ($_REQUEST['task'] == 'choluuchieu')
                                                        <a title="Duyệt quyết định phát hành" href="javascript:;"
                                                            class="btn btn-success"
                                                            onclick="showUpdateModalPhathanh({{ $row->id }})">
                                                            <i class="fa fa-check"></i>
                                                        </a>

                                                        <a title="Duyệt quyết định thu hồi" href="javascript:;"
                                                            class="btn btn-danger"
                                                            onclick="showUpdateModalThuhoi({{ $row->id }})">
                                                            <i class="fa fa-ban"></i>
                                                        </a>
                                                    @endif

                                                    <a href="javascript:;" class="btn btn-warning"
                                                        onclick="exportWord({{ $row->id }})">
                                                        <i class="fa fa-file-word-o"></i>
                                                    </a>

                                                    <div class="modal fade" id="modal-info-{{ $row->id }}"
                                                        tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <!-- Modal Header -->
                                                                <div class="modal-header">
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal"
                                                                        aria-hidden="true">&times;</button>
                                                                    <h4 class="modal-title" id="myModalLabel">
                                                                        Thông tin chi tiết
                                                                    </h4>
                                                                </div>

                                                                <!-- Modal Body -->
                                                                <div class="modal-body scrollable-list">
                                                                    <!-- Thông tin cơ bản -->
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title-modal">Thông tin tác
                                                                                phẩm
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body new">
                                                                            <h3 style="color: #00A157;margin-top:0px">
                                                                                {{ $row->tacpham }}
                                                                            </h3>
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <p class="font-14">

                                                                                        <strong>Thể loại:</strong>
                                                                                        <span
                                                                                            style="color: #00A157">{{ $array_theloai[$row->theloai] }}</span>
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <p class="font-14"><strong>Tác
                                                                                            giả:</strong>
                                                                                        {{ $row->tacgia }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <p class="font-14"><strong>Bút
                                                                                            danh:</strong>
                                                                                        {{ $row->butdanh }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Thông tin liên hệ -->
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title-modal">Thông tin liên hệ
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <p class="font-14">
                                                                                        <strong>Địa chỉ:</strong>
                                                                                        {{ $row->diachi }}
                                                                                    </p>
                                                                                    <p class="font-14">
                                                                                        <strong>Điện
                                                                                            thoại:</strong>
                                                                                        {{ $row->dienthoai }}
                                                                                    </p>
                                                                                    <p class="font-14">
                                                                                        <strong>Email:</strong>
                                                                                        {{ $row->email }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Thông tin xuất bản -->
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title-modal">Thông tin xuất
                                                                                bản
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <p class="font-14"><strong>Số
                                                                                            trang:</strong>
                                                                                        {{ $row->sotrang }}
                                                                                    </p>
                                                                                    <p class="font-14">
                                                                                        <strong>Khuôn khổ:</strong>
                                                                                        {{ $row->khuonkho }}
                                                                                    </p class="font-14">
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <p class="font-14">
                                                                                        <strong>Định
                                                                                            dạng:</strong>
                                                                                        {{ $row->dinhdang }}
                                                                                    </p>
                                                                                    <p class="font-14">
                                                                                        <strong>Dung lượng:</strong>
                                                                                        {{ $row->dungluong }} MB
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Thông tin in ấn -->
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title-modal">Thông tin in ấn
                                                                            </h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            <div class="row">
                                                                                <div class="col-md-6">
                                                                                    <p class="font-14">
                                                                                        <strong>Lần
                                                                                            xuất bản:</strong>
                                                                                        {{ $row->lanxuatban }}
                                                                                    </p>
                                                                                    <p class="font-14">
                                                                                        <strong>Lần tái bản:</strong>
                                                                                        {{ $row->lantaiban }}
                                                                                    </p>
                                                                                    <p class="font-14"><strong>Số
                                                                                            lượng:</strong>
                                                                                        {{ $row->soluong }}
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-md-6">
                                                                                    <p class="font-14">
                                                                                        <strong>Nhà in:</strong>
                                                                                        {{ $row->nhain }}
                                                                                    </p>
                                                                                    <p class="font-14">
                                                                                        <strong>Nền
                                                                                            tảng:</strong>
                                                                                        {{ $row->trangweb }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Nội dung -->
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h3 class="panel-title-modal">Nội dung</h3>
                                                                        </div>
                                                                        <div class="panel-body">
                                                                            {{ $row->noidung }}
                                                                        </div>
                                                                    </div>

                                                                    <!-- Thông tin nhà xuất bản -->
                                                                    @if ($row->bientapvien)
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-heading">
                                                                                <h3 class="panel-title-modal">Thông tin nhà
                                                                                    xuất
                                                                                    bản</h3>
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <p class="font-14">
                                                                                            <strong>Biên tập viên:</strong>
                                                                                            {{ $row->bientapvien }}
                                                                                        </p>
                                                                                        <p class="font-14">
                                                                                            <strong>Tổng biên tập:</strong>
                                                                                            {{ $row->tongbientap }}
                                                                                        </p>
                                                                                        <p class="font-14">
                                                                                            <strong>Giám đốc:</strong>
                                                                                            {{ $row->giamdoc }}
                                                                                        </p>
                                                                                        <p class="font-14">
                                                                                            <strong>Người vẽ bìa:</strong>
                                                                                            {{ $row->nguoivebia }}
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <p class="font-14">
                                                                                            <strong>Người trình
                                                                                                bày:</strong>
                                                                                            {{ $row->nguoitrinhbay }}
                                                                                        </p>
                                                                                        <p class="font-14">
                                                                                            <strong>Người sửa bản
                                                                                                in:</strong>
                                                                                            {{ $row->nguoisuabanin }}
                                                                                        </p>
                                                                                        <p class="font-14">
                                                                                            <strong>Người vẽ minh hoạ/ phụ
                                                                                                bản:</strong>
                                                                                            {{ $row->nguoiveminhhoa }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Thông tin các quyết định -->
                                                                    @if ($row->sogiayphep)
                                                                        <div class="panel panel-default">
                                                                            <div class="panel-heading">
                                                                                <h3 class="panel-title-modal">Thông tin
                                                                                    quyết
                                                                                    định
                                                                                </h3>
                                                                            </div>
                                                                            <div class="panel-body">
                                                                                <div class="row">
                                                                                    <div class="col-md-6">
                                                                                        <p class="font-14">
                                                                                            <strong>Số giấy phép:</strong>
                                                                                            {{ $row->sogiayphep }}
                                                                                        </p>
                                                                                        <p class="font-14">
                                                                                            <strong>Số quyết định cấp
                                                                                                phép:</strong>
                                                                                            {{ $row->soqdcapphep }}
                                                                                        </p>
                                                                                        <p class="font-14">
                                                                                            <strong>Mã ISBN:</strong>
                                                                                            {{ $row->maisbn }}
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <p class="font-14">
                                                                                            <strong>Thời gian nộp lưu
                                                                                                chiểu:</strong>
                                                                                            {{ $row->thoigianluuchieu }}
                                                                                        </p>
                                                                                        @if ($row->soqdphathanh)
                                                                                            <p class="font-14">
                                                                                                <strong>Số QĐ phát
                                                                                                    hành:</strong>
                                                                                                {{ $row->soqdphathanh }}
                                                                                            </p>
                                                                                        @endif
                                                                                        @if ($row->socongvanthuhoi)
                                                                                            <p class="font-14">
                                                                                                <strong>Số công văn thu
                                                                                                    hồi:</strong>
                                                                                                {{ $row->socongvanthuhoi }}
                                                                                            </p>
                                                                                        @endif
                                                                                        @if ($row->lydothuhoi)
                                                                                            <p class="font-14">
                                                                                                <strong>Lý do thu
                                                                                                    hồi:</strong>
                                                                                                {{ $row->lydothuhoi }}
                                                                                            </p>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>

                                                                <!-- Modal Footer -->
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Đóng</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </form>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                        <div class="box-footer clearfix">
                            <div class="row">
                                <div class="col-sm-5">
                                    Tìm thấy {{ $rows->total() }} kết quả
                                </div>
                                <div class="col-sm-7">
                                    {{ $rows->withQueryString()->links('admin.pagination.default') }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </section>

    <div class="modal fade" id="modalUpdate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Duyệt quyết định cấp phép
                    </h4>
                </div>
                <form id="updateFormCapphep" onsubmit="handleUpdate(event)">
                    <div class="modal-body scrollable-list">
                        <input type="hidden" id="record_id" name="record_id">
                        <input type="hidden" name="submit" id="submitValue">

                        <!-- Thông tin biên tập -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Thông tin biên
                                    tập
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Biên tập
                                                viên:</label>
                                            <input type="text" class="form-control" name="bientapvien"
                                                id="bientapvien">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tổng biên
                                                tập:</label>
                                            <input type="text" class="form-control" name="tongbientap"
                                                id="tongbientap">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Giám đốc:</label>
                                            <input type="text" class="form-control" name="giamdoc" id="giamdoc">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin thiết kế -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Thông tin thiết
                                    kế
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Người vẽ bìa:</label>
                                            <input type="text" class="form-control" name="nguoivebia"
                                                id="nguoivebia">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Người trình
                                                bày:</label>
                                            <input type="text" class="form-control" name="nguoitrinhbay"
                                                id="nguoitrinhbay">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Người sửa bản
                                                in:</label>
                                            <input type="text" class="form-control" name="nguoisuabanin"
                                                id="nguoisuabanin">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Người vẽ minh
                                                họa:</label>
                                            <input type="text" class="form-control" name="nguoiveminhhoa"
                                                id="nguoiveminhhoa">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thông tin pháp lý -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Thông tin pháp
                                    lý
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Số giấy phép:</label>
                                            <input type="text" class="form-control" name="sogiayphep"
                                                id="sogiayphep">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Số QĐ cấp
                                                phép:</label>
                                            <input type="text" class="form-control" name="soqdcapphep"
                                                id="soqdcapphep">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mã ISBN:</label>
                                            <input type="text" class="form-control" name="maisbn" id="maisbn">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Đối tác liên kết xuất
                                                bản:</label>
                                            <input type="text" class="form-control" name="doitaclienket"
                                                id="doitaclienket">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="document.getElementById('submitValue').value='capnhat'">Cập
                            nhật</button>
                        <button type="submit" class="btn btn-success"
                            onclick="document.getElementById('submitValue').value='duyet'">Cập
                            nhật và duyệt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateLuuchieu" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Duyệt quyết định lưu chiểu
                    </h4>
                </div>
                <form id="updateFormLuuchieu" onsubmit="handleUpdateLuuchieu(event)">
                    <div class="modal-body scrollable-list">
                        <input type="hidden" id="record_id_luuchieu" name="record_id_luuchieu">
                        <input type="hidden" name="submit" id="submitValueLuuchieu">

                        <!-- Thông tin pháp lý -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Thông tin lưu
                                    chiểu
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Giá lẻ xuất bản
                                                phẩm:</label>
                                            <input type="number" min="0" class="form-control" name="giabanle"
                                                id="giabanle">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Sở hữu bản
                                                quyền:</label>
                                            <input type="text" class="form-control" name="sohuubanquyen"
                                                id="sohuubanquyen">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Thời hạn bản
                                                quyền:</label>
                                            <input type="text" class="form-control" name="thoihanbanquyen"
                                                id="thoihanbanquyen">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="document.getElementById('submitValueLuuchieu').value='capnhat'">Cập
                            nhật</button>
                        <button type="submit" class="btn btn-success"
                            onclick="document.getElementById('submitValueLuuchieu').value='duyet'">Cập
                            nhật và duyệt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpdatePhathanh" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Duyệt quyết định phát hành
                    </h4>
                </div>
                <form id="updateFormPhathanh" onsubmit="handleUpdatePhathanh(event)">
                    <div class="modal-body scrollable-list">
                        <input type="hidden" id="record_id_phathanh" name="record_id_phathanh">
                        <input type="hidden" name="submit" id="submitValuePhathanh">

                        <!-- Thông tin pháp lý -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Thông tin phát
                                    hành
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Số quyết định phát
                                                hành:</label>
                                            <input type="text" class="form-control" name="soqdphathanh"
                                                id="soqdphathanh">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="document.getElementById('submitValuePhathanh').value='capnhat'">Cập
                            nhật</button>
                        <button type="submit" class="btn btn-success"
                            onclick="document.getElementById('submitValuePhathanh').value='duyet'">Cập
                            nhật và duyệt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUpdateThuhoi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Duyệt quyết định thu hồi
                    </h4>
                </div>
                <form id="updateFormThuhoi" onsubmit="handleUpdateThuhoi(event)">
                    <div class="modal-body scrollable-list">
                        <input type="hidden" id="record_id_thuhoi" name="record_id_thuhoi">
                        <input type="hidden" name="submit" id="submitValueThuhoi">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Thông tin thu
                                    hồi
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Số quyết định thu
                                                hồi:</label>
                                            <input type="text" class="form-control" name="socongvanthuhoi"
                                                id="socongvanthuhoi">
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Lý do thu
                                                hồi:</label>
                                            <input type="text" class="form-control" name="lydothuhoi"
                                                id="lydothuhoi">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="document.getElementById('submitValueThuhoi').value='capnhat'">Cập
                            nhật</button>
                        <button type="submit" class="btn btn-success"
                            onclick="document.getElementById('submitValueThuhoi').value='duyet'">Cập
                            nhật và duyệt</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalChooseExport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <ul class="nav nav-tabs">
                        @for ($i = 0; $i < $numTabsToShow; $i++)
                            <li <?php echo $tabs[$i]['active'] ? 'class="active"' : ''; ?>>
                                <a href="#<?php echo $tabs[$i]['id']; ?>" data-toggle="tab">
                                    <h5><?php echo $tabs[$i]['title']; ?></h5>
                                </a>
                            </li>
                        @endfor
                    </ul>
                </div>
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default text-center">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Bản
                                                đăng ký
                                                cấp
                                                giấy phép</h3>
                                        </div>
                                        <div class="panel-body">
                                            <i class="fa fa-file-word-o fa-4x text-success new"></i>
                                            <br><br>
                                            <button class="btn btn-success" onclick="processExport(currentId, 'normal')">
                                                Chọn xuất
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel panel-default text-center">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Bản
                                                đăng ký
                                                cấp
                                                giấy phép điện tử</h3>
                                        </div>
                                        <div class="panel-body">
                                            <i class="fa fa-file-word-o fa-4x text-success new"></i>
                                            <br><br>
                                            <button class="btn btn-success"
                                                onclick="processExport(currentId, 'electronic')">
                                                Chọn xuất
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default text-center">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Bản
                                                quyết
                                                định cấp phép</h3>
                                        </div>
                                        <div class="panel-body">
                                            <i class="fa fa-file-word-o fa-4x text-success new"></i>
                                            <br><br>
                                            <button class="btn btn-success"
                                                onclick="processExportCapphep(currentId, 'normal')">
                                                Chọn xuất
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="panel panel-default text-center">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Bản
                                                quyết
                                                định cấp phép điện tử</h3>
                                        </div>
                                        <div class="panel-body">
                                            <i class="fa fa-file-word-o fa-4x text-success new"></i>
                                            <br><br>
                                            <button class="btn btn-success"
                                                onclick="processExportCapphep(currentId, 'electronic')">
                                                Chọn xuất
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default text-center">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Bản
                                                quyết
                                                định lưu chiểu</h3>
                                        </div>
                                        <div class="panel-body">
                                            <i class="fa fa-file-word-o fa-4x text-success new"></i>
                                            <br><br>
                                            <button class="btn btn-success" onclick="processExportLuuchieu(currentId)">
                                                Chọn xuất
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default text-center">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Bản
                                                quyết
                                                định phát hành</h3>
                                        </div>
                                        <div class="panel-body">
                                            <i class="fa fa-file-word-o fa-4x text-success new"></i>
                                            <br><br>
                                            <button class="btn btn-success" onclick="processExportPhathanh(currentId)">
                                                Chọn xuất
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="tab_5">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default text-center">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Bản
                                                quyết
                                                định thu hồi</h3>
                                        </div>
                                        <div class="panel-body">
                                            <i class="fa fa-file-word-o fa-4x text-success new"></i>
                                            <br><br>
                                            <button class="btn btn-success" onclick="processExportThuhoi(currentId)">
                                                Chọn xuất
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Đang tải...</span>
                    </div>
                    <p class="mt-2">Đang xuất file, vui lòng chờ...</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var currentId = null;

        function exportWord(id) {
            currentId = id;
            $('#modalChooseExport').modal('show');
        }

        function processExport(id, type) {
            // Hiển thị loading
            $('#modalChooseExport').modal('hide');

            $('#loadingModal').modal('show');

            // Đặt timeout để xử lý trường hợp xuất file quá lâu
            var exportTimeout = setTimeout(function() {
                $('#loadingModal').modal('hide');
                alert('Quá trình xuất file đang mất nhiều thời gian hơn dự kiến. Vui lòng chờ hoặc thử lại sau.');
            }, 3000);

            // Gọi API để xuất file
            $.ajax({
                url: 'export-word/' + id,
                type: 'POST',
                data: {
                    type: type,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    var blob = new Blob([response], {
                        type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = type === 'normal' ? "ban_dang_ky.docx" : "ban_dang_ky_dien_tu.docx";
                    link.click();

                },
                error: function(xhr, status, error) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    alert('Có lỗi xảy ra khi xuất file!');
                }
            });
        }

        function processExportCapphep(id, type) {
            // Hiển thị loading
            $('#modalChooseExport').modal('hide');

            $('#loadingModal').modal('show');

            // Đặt timeout để xử lý trường hợp xuất file quá lâu
            var exportTimeout = setTimeout(function() {
                $('#loadingModal').modal('hide');
                alert('Quá trình xuất file đang mất nhiều thời gian hơn dự kiến. Vui lòng chờ hoặc thử lại sau.');
            }, 3000);

            // Gọi API để xuất file
            $.ajax({
                url: 'export-word-capphep/' + id,
                type: 'POST',
                data: {
                    type: type,
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    var blob = new Blob([response], {
                        type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "ban_quyet_dinh_cap_phep.docx";
                    link.click();
                },
                error: function(xhr, status, error) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    alert('Có lỗi xảy ra khi xuất file!');
                }
            });
        }

        function processExportLuuchieu(id) {
            // Hiển thị loading
            $('#modalChooseExport').modal('hide');

            $('#loadingModal').modal('show');

            // Đặt timeout để xử lý trường hợp xuất file quá lâu
            var exportTimeout = setTimeout(function() {
                $('#loadingModal').modal('hide');
                alert('Quá trình xuất file đang mất nhiều thời gian hơn dự kiến. Vui lòng chờ hoặc thử lại sau.');
            }, 3000);

            // Gọi API để xuất file
            $.ajax({
                url: 'export-excel-luu-chieu/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    var blob = new Blob([response], {
                        type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "quyet_dinh_luu_chieu.xlsx";
                    link.click();
                },
                error: function(xhr, status, error) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    alert('Có lỗi xảy ra khi xuất file!');
                }
            });
        }

        function processExportPhathanh(id) {
            // Hiển thị loading
            $('#modalChooseExport').modal('hide');
            $('#loadingModal').modal('show');

            // Đặt timeout để xử lý trường hợp xuất file quá lâu
            var exportTimeout = setTimeout(function() {
                $('#loadingModal').modal('hide');
                alert('Quá trình xuất file đang mất nhiều thời gian hơn dự kiến. Vui lòng chờ hoặc thử lại sau.');
            }, 3000);

            // Gọi API để xuất file
            $.ajax({
                url: 'export-word-phathanh/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    var blob = new Blob([response], {
                        type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "ban_quyet_dinh_phat_hanh.docx";
                    link.click();
                },
                error: function(xhr, status, error) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    alert('Có lỗi xảy ra khi xuất file!');
                }
            });
        }

        function processExportThuhoi(id) {
            // Hiển thị loading
            $('#modalChooseExport').modal('hide');
            $('#loadingModal').modal('show');

            // Đặt timeout để xử lý trường hợp xuất file quá lâu
            var exportTimeout = setTimeout(function() {
                $('#loadingModal').modal('hide');
                alert('Quá trình xuất file đang mất nhiều thời gian hơn dự kiến. Vui lòng chờ hoặc thử lại sau.');
            }, 3000);

            // Gọi API để xuất file
            $.ajax({
                url: 'export-word-thuhoi/' + id,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(response) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    var blob = new Blob([response], {
                        type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    });
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "ban_quyet_dinh_thu_hoi.docx";
                    link.click();
                },
                error: function(xhr, status, error) {
                    clearTimeout(exportTimeout);
                    $('#loadingModal').modal('hide');
                    alert('Có lỗi xảy ra khi xuất file!');
                }
            });
        }

        function showUpdateModal(id) {
            $('#record_id').val(id);

            // Lấy thông tin hiện tại
            $.ajax({
                url: 'detail-capphep/' + id,
                type: 'GET',
                success: function(response) {
                    // Điền thông tin vào form
                    $('#bientapvien').val(response.bientapvien);
                    $('#tongbientap').val(response.tongbientap);
                    $('#giamdoc').val(response.giamdoc);
                    $('#nguoivebia').val(response.nguoivebia);
                    $('#nguoitrinhbay').val(response.nguoitrinhbay);
                    $('#nguoisuabanin').val(response.nguoisuabanin);
                    $('#nguoiveminhhoa').val(response.nguoiveminhhoa);
                    $('#sogiayphep').val(response.sogiayphep);
                    $('#soqdcapphep').val(response.soqdcapphep);
                    $('#maisbn').val(response.maisbn);

                    // Hiển thị modal
                    $('#modalUpdate').modal('show');
                },
                error: function() {
                    alert('Có lỗi xảy ra khi lấy thông tin!');
                }
            });
        }

        function showUpdateModalLuuchieu(id) {
            $('#record_id_luuchieu').val(id);

            // Lấy thông tin hiện tại
            $.ajax({
                url: 'detail-capphep/' + id,
                type: 'GET',
                success: function(response) {
                    // Điền thông tin vào form
                    $('#giabanle').val(response.giabanle);
                    $('#sohuubanquyen').val(response.sohuubanquyen);
                    $('#thoihanbanquyen').val(response.thoihanbanquyen);

                    // Hiển thị modal
                    $('#modalUpdateLuuchieu').modal('show');
                },
                error: function() {
                    alert('Có lỗi xảy ra khi lấy thông tin!');
                }
            });
        }

        function showUpdateModalPhathanh(id) {
            $('#record_id_phathanh').val(id);

            // Lấy thông tin hiện tại
            $.ajax({
                url: 'detail-capphep/' + id,
                type: 'GET',
                success: function(response) {
                    // Điền thông tin vào form
                    $('#soqdphathanh').val(response.soqdphathanh);

                    // Hiển thị modal
                    $('#modalUpdatePhathanh').modal('show');
                },
                error: function() {
                    alert('Có lỗi xảy ra khi lấy thông tin!');
                }
            });
        }

        function showUpdateModalThuhoi(id) {
            $('#record_id_thuhoi').val(id);

            // Lấy thông tin hiện tại
            $.ajax({
                url: 'detail-capphep/' + id,
                type: 'GET',
                success: function(response) {
                    // Điền thông tin vào form
                    $('#socongvanthuhoi').val(response.socongvanthuhoi);
                    $('#lydothuhoi').val(response.lydothuhoi);

                    // Hiển thị modal
                    $('#modalUpdateThuhoi').modal('show');
                },
                error: function() {
                    alert('Có lỗi xảy ra khi lấy thông tin!');
                }
            });
        }

        function handleUpdate(e) {
            e.preventDefault();

            // Lấy dữ liệu từ form
            var formData = new FormData(document.getElementById('updateFormCapphep'));

            // Gửi request cập nhật
            $.ajax({
                url: 'update-capphep/' + $('#record_id').val(),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Đóng modal
                    $('#modalUpdate').modal('hide');

                    // Thông báo thành công
                    alert('Cập nhật thành công!');

                    // Reload trang hoặc cập nhật UI
                    location.reload();
                },
                error: function() {
                    alert('Có lỗi xảy ra khi cập nhật!');
                }
            });
        }

        function handleUpdateLuuchieu(e) {
            e.preventDefault();

            // Lấy dữ liệu từ form
            var formData = new FormData(document.getElementById('updateFormLuuchieu'));

            // Gửi request cập nhật
            $.ajax({
                url: 'update-luuchieu/' + $('#record_id_luuchieu').val(),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Đóng modal
                    $('#modalUpdateLuuchieu').modal('hide');

                    // Thông báo thành công
                    alert('Cập nhật thành công!');

                    // Reload trang hoặc cập nhật UI
                    location.reload();
                },
                error: function() {
                    alert('Có lỗi xảy ra khi cập nhật!');
                }
            });
        }

        function handleUpdatePhathanh(e) {
            e.preventDefault();

            // Lấy dữ liệu từ form
            var formData = new FormData(document.getElementById('updateFormPhathanh'));

            // Gửi request cập nhật
            $.ajax({
                url: 'update-phathanh/' + $('#record_id_phathanh').val(),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Đóng modal
                    $('#modalUpdatePhathanh').modal('hide');

                    // Thông báo thành công
                    alert('Cập nhật thành công!');

                    // Reload trang hoặc cập nhật UI
                    location.reload();
                },
                error: function() {
                    alert('Có lỗi xảy ra khi cập nhật!');
                }
            });
        }

        function handleUpdateThuhoi(e) {
            e.preventDefault();

            // Lấy dữ liệu từ form
            var formData = new FormData(document.getElementById('updateFormThuhoi'));

            // Gửi request cập nhật
            $.ajax({
                url: 'update-thuhoi/' + $('#record_id_thuhoi').val(),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Đóng modal
                    $('#modalUpdateThuhoi').modal('hide');

                    // Thông báo thành công
                    alert('Cập nhật thành công!');

                    // Reload trang hoặc cập nhật UI
                    location.reload();
                },
                error: function() {
                    alert('Có lỗi xảy ra khi cập nhật!');
                }
            });
        }
    </script>
@endsection
