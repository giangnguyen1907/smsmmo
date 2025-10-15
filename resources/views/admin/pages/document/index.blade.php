@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
        </h1>
    </section>
@endsection
<?php //echo $_REQUEST['category'];
?>
@section('content')
    <style type="text/css">
        .underline {
            text-decoration: underline;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        {{-- Search form --}}
        <div class="box box-default">

            <div class="box-header with-border">
                <h3 class="box-title">@lang('Filter')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <input type="hidden" name="task" value="{{ $_REQUEST['task'] }}" />
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="category" class="form-control select2">
                                    <option value="">-Chọn chuyên mục-</option>
                                    <?php foreach($parents as $parent){ ?>
                                    <option value="<?= $parent->id ?>" <?php if (isset($params['category']) and $params['category'] == $parent->id) {
                                        echo 'selected';
                                    } ?>><?= $parent->title->$locale ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="main_author" class="form-control select2">
                                    <option value="">-Chọn tác giả-</option>
                                    <?php foreach($authors as $author){ ?>
                                    <option value="{{ $author->id }}" <?php if (isset($params['main_author']) and $params['main_author'] == $author->id) {
                                        echo 'selected';
                                    } ?>> {{ $author->title }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
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
        {{-- End search form --}}

        <div class="box">

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

                @php
                    $pr = '';
                    $pr .= isset($params['keyword']) ? '&keyword=' . $params['keyword'] : '';
                    $pr .= isset($params['category']) ? '&category=' . $params['category'] : '';
                    //$pr .= isset($params['admin_created_id']) ? '&admin_created_id='.$params['admin_created_id'] : '';
                    //$pr .= isset($params['admin_updated_id']) ? '&admin_updated_id='.$params['admin_updated_id'] : '';
                @endphp
                <ul class="nav nav-tabs">
                    <li class="<?php if ($_REQUEST['task'] == 'rollback') {
                        echo 'active';
                    } ?>">
                        <a href="/admin/document?task=rollback{{ $pr }}">
                            <h5>Trả lại</h5>
                        </a>
                    </li>
                    <li class="<?php if ($_REQUEST['task'] == 'waiting') {
                        echo 'active';
                    } ?>">
                        <a href="/admin/document?task=waiting{{ $pr }}">
                            <h5>Chờ duyệt</h5>
                        </a>
                    </li>
                    <li class="<?php if ($_REQUEST['task'] == 'pending') {
                        echo 'active';
                    } ?>">
                        <a href="/admin/document?task=pending{{ $pr }}">
                            <h5>Chờ xuất bản</h5>
                        </a>
                    </li>
                    <li class="<?php if ($_REQUEST['task'] == 'active') {
                        echo 'active';
                    } ?>">
                        <a href="/admin/document?task=active{{ $pr }}">
                            <h5>Đã xuất bản</h5>
                        </a>
                    </li>

                    <li class="<?php if ($_REQUEST['task'] == 'deactive') {
                        echo 'active';
                    } ?>">
                        <a href="/admin/document?task=deactive{{ $pr }}">
                            <h5>Từ chối</h5>
                        </a>
                    </li>

                    <li class="<?php if ($_REQUEST['task'] == 'lock') {
                        echo 'active';
                    } ?>">
                        <a href="/admin/document?task=lock{{ $pr }}">
                            <h5>Đã gỡ</h5>
                        </a>
                    </li>
                    <li class="<?php if ($_REQUEST['task'] == 'draft') {
                        echo 'active';
                    } ?>">
                        <a href="/admin/document?task=draft{{ $pr }}">
                            <h5>Nháp</h5>
                        </a>
                    </li>
                </ul>
                @if (count($rows) == 0)
                    {{--
            <div class="alert alert-warning alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              Không có tài dữ liệu
            </div>
            --}}
                @else
                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Ảnh bìa</th>
                                <th>Tên sách/ thể loại / tác giả / giá bán</th>
                                <th>Thông tin</th>
                                <th>Thống kê</th>
                                <th>Số lượng</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($rows as $row)
                                @if ($row->parent_id == 0 || $row->parent_id == null)
                                    <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                                        onsubmit="return confirm('@lang('confirm_action')')">
                                        <tr class="valign-middle">
                                            <td>
                                                <img src="{{ $row->image }}" style="max-height: 80px; max-width: 80px" />
                                            </td>
                                            <td>
                                                <strong style="font-size: 14px;">{{ $row->title }}</strong>
                                                <br>Chuyên mục: <span
                                                    class="underline">{{ json_decode($row->taxonomy_title)->$locale ?? '' }}</span>
                                                <br>Tác giả: <i><?php echo $row->author_name; ?></i>
                                                <br>Giá: <i style="color: #F00"><?php echo number_format($row->price); ?></i>
                                            </td>
                                            <td>
                                                Size: <?php echo $row->book_size_title; ?>cm<br>
                                                Năm XB: <i>{{ $row->publishing_year }}</i><br>
                                                Mã ISBN: <i><?php echo $row->isbn; ?></i><br>
                                                Giá bìa: <i style="color: #F00"><?php echo number_format($row->cover_price); ?></i>
                                            </td>
                                            <td>
                                                <i class="fa fa-eye"> {{ $row->view }}</i> / <br>
                                                <i class="fa fa-download"> {{ $row->download }}</i> / <br>
                                                <i class="fa fa-file-pdf-o"> {{ $row->number_page }}p</i>
                                            </td>
                                            <td>
                                                <span class="nowrap">Nhập:<b> {!! $row->import !!}</b></span><br>
                                                <span class="nowrap">Xuất: <b> {!! $row->export !!}</b></span>
                                                <hr style="margin-top:5px; margin-bottom:5px">
                                                <span class="nowrap">Tồn: <b>{!! $row->inventory !!}</b></span>
                                            </td>
                                            <td class="text-center">

                                                <a class="btn btn-xs btn-warning" data-toggle="tooltip"
                                                    title="Cập nhật nội dung" data-original-title="Cập nhật nội dung"
                                                    href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
                                                <?php if(Auth::guard('admin')->user()->is_super_admin == 3 || Auth::guard('admin')->user()->is_super_admin == 1){ ?>
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-xs btn-danger" type="submit"
                                                    data-toggle="tooltip" title="Gỡ tài liệu"
                                                    data-original-title="Gỡ tài liệu">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                                <?php } ?>
                                                <?php if($row->filepdf !=''){ ?>
                                                <hr style="margin-top:5px; margin-bottom:5px">
                                                <a class="btn btn-xs btn-primary" data-toggle="tooltip" title="Đọc thử"
                                                    data-original-title="Đọc thử"
                                                    href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                                    <i class="fa fa-book"></i> Đọc
                                                </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    </form>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

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
    </section>
@endsection
