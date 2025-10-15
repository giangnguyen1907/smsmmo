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
                <h3 class="box-title">@lang('search')</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>

            <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="status" id="status" class="form-control select2" style="width: 100%;">
                                    <option value="">@lang('status')</option>
                                    @foreach (App\Consts::ORDER_STATUS as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                                            {{ __($value) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-sm">@lang('search')</button>
                                <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                                    @lang('reset')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {{-- End search form --}}

        <div class="box">
            <div class="box-header">

                <div class="row" style="padding: 0 10px;">
                    <div class="col-md-4">
                        <h3 class="box-title">Danh sách đơn hàng</h3>
                    </div>
                    <div class="col-md-8">
                        <button class="pull-right" style="padding: 5px 10px; ">
                            <i class="fa fa-file-excel-o" aria-hidden="true" style="color: green;"></i>
                            <a href="{{ route('order.export') }}" style="margin-left: 5px; color: #000;">
                                Xuất đơn hàng
                            </a>
                        </button>
                    </div>
                </div>


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
                            <th>Thông tin đơn hàng</th>
                            <th>Giá trị</th>
                            <th>Thanh toán</th>
                            <th>Giao dịch</th>
                            <th>@lang('status')</th>
                            <th>@lang('action')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
				$stt = 0;
				foreach ($rows as $row){ $stt ++;
				?>
                        <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                            onsubmit="return confirm('@lang('confirm_action')')">
                            <tr class="valign-middle">
                                <td>
                                    {{ $stt }}
                                </td>
                                <td>
                                    <a href="javascript:;">{{ $row->name }} - {{ $row->phone }}</a><br>
                                    <span>{{ $row->address }}</span><br>
                                    <span>Ngày tạo: {{ date('d/m/Y H:i', strtotime($row->created_at)) }}</span><br>
                                    <span>Ghi chú: {{ $row->customer_note }}</span>
                                </td>
                                <td>
                                    <span class="nowrap">Tiền hàng:
                                        <b>{{ number_format($row->total_payment) }}</b></span><br>
                                    <span class="nowrap">Vận chuyển: <b>{{ number_format($row->ship) }}</b></span><br>
                                    <span class="nowrap">Voucher: <b>{{ number_format($row->discount) }}</b></span><br>
                                    <span class="nowrap">Thanh toán: <b
                                            style="color:brown">{{ number_format($row->payment) }}</b></span>
                                    <?php if($row->arise > 0){?>
                                    <br><span class="nowrap">Phát sinh: <b
                                            style="color:brown">{{ number_format($row->arise) }}</b></span>
                                    <?php } ?>
                                </td>

                                <td>
                                    {{ $array_payment_method[$row->payment_method] ?? '' }}<br>
                                    <span
                                        class="badge {{ $row->payment_status == 1 ? 'badge-success' : 'badge-soft-secondary' }}">
                                        {{ $array_payment_staus[$row->payment_status] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="nowrap">Mã: <b>{{ $row->trans_code }}</b></span><br>
                                    <span class="nowrap">Code: <b style="color:brown">{{ $row->response_code }}</b></span>
                                </td>

                                <td>
                                    <?php if (isset(App\Consts::ORDER_STATUS[$row->status])) {
                                        echo App\Consts::ORDER_STATUS[$row->status];
                                    } ?>
                                    {{--
							<select id="status_{{$row->id}}" class="form-control" onchange="updateStatus({{$row->id}})">
								@foreach (App\Consts::ORDER_STATUS as $key => $item)
								<option value="{{ $key }}" <?php if ($row->status == $key) {
            echo 'selected';
        } ?>>{{ $item }}</option>
								@endforeach
							</select>
							--}}
                                </td>
                                <td>

                                    <a class="btn btn-xs btn-primary" target="_blank" data-toggle="tooltip"
                                        title="@lang('Print')" data-original-title="@lang('view')"
                                        href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                                        <i class="fa fa-print"></i>
                                    </a>

                                    <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="@lang('view')"
                                        data-original-title="@lang('view')"
                                        href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-xs btn-danger" type="submit" data-toggle="tooltip"
                                        title="@lang('delete')" data-original-title="@lang('delete')">
                                        <i class="fa fa-trash"></i>
                                    </button>
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
