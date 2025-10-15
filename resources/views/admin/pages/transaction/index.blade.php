@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}

        </h1>
    </section>
@endsection

@section('content')

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
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="is_type" class="form-control select2">
                                    <option value="">-Chọn loại giao dịch-</option>
                                    <?php foreach($array_istype as $key=> $istype){ ?>
                                    <option value="{{ $key }}" <?php if (isset($params['is_type']) and $params['is_type'] == $key) {
                                        echo 'selected';
                                    } ?>>{{ $istype }}</option>
                                    <?php } ?>
                                </select>
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
            <div class="box-header">
                <h3 class="box-title">Danh sách giao dịch</h3>
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

                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Số tiền</th>
                            <th>Phát sinh</th>
                            <th>Số GD</th>
                            <th>Mã phản hồi</th>
                            <th>Ngày tạo</th>
                            <th>Loại GD</th>
                            <th>Trạng thái</th>
                            <th>Nội dung</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $stt = 0;
                    foreach ($rows as $row){
    				$stt++;
    				?>
                        <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            <tr class="valign-middle">

                                <td>
                                    {{ $stt }}
                                </td>
                                <td>
                                    {{ $row->order_code }}
                                </td>
                                <td>
                                    {{ $row->guest }}
                                </td>
                                <td>
                                    {{ number_format($row->amount) }}
                                </td>
                                <td>
                                    {{ number_format($row->arise) }}
                                </td>
                                <td>
                                    {{ $row->transaction_no }}
                                </td>
                                <td>
                                    {{ $row->response_code }}
                                </td>
                                <td>
                                    {{ date('d/m/Y H:i', strtotime($row->date_at)) }}
                                </td>
                                <td>
                                    {{ $array_istype[$row->is_type] }}
                                </td>
                                <td>
                                    {{ $array_status[$row->status] }}
                                </td>
                                <td>
                                    {{ $row->content }}
                                </td>
                            </tr>
                        </form>
                        <?php } ?>
                    </tbody>
                </table>
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
