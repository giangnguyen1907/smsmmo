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

@section('content')
    <style>
        .css-class-for-status-1 {
            background-color: green;
            color: white;
            padding: 5px;
            border-radius: 10%
        }

        .css-class-for-status-0 {
            background-color: red;
            color: white;
            padding: 5px;
            border-radius: 10%
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
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Từ khóa tìm kiếm</label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Tác giả</label>
                                <select name="author" id="author" class="form-control select2">
                                    <option value="">-Chọn tác giả-</option>
                                    <?php foreach($author as $au){ ?>
                                    <option value="{{ $au->id }}" <?php if (isset($params['author']) and $params['author'] == $au->id) {
                                        echo 'selected';
                                    } ?>>{{ $au->title }}</option>
                                    <?php } ?>
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
                                <label>Phương thức thanh toán</label>
                                <select name="payment_method" id="payment_method" class="form-control select2">
                                    <option value="">-Chọn phương thức thanh toán-</option>
                                    <?php foreach($array_payment_method as $key=> $istype){ ?>
                                    <option value="{{ $key }}" <?php if (isset($params['payment_method']) and $params['payment_method'] == $key) {
                                        echo 'selected';
                                    } ?>>{{ $istype }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select name="status" id="status" class="form-control select2">
                                    <option value="">-Chọn trạng thái-</option>
                                    <?php foreach($array_status as $key_st => $is_status){ ?>
                                    <option value="{{ $key_st }}" <?php if (isset($params['status']) and $params['status'] == $key_st) {
                                        echo 'selected';
                                    } ?>>{{ $is_status }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Người dùng</label>
                                <select name="customer_id" id="customer_id" class="form-control select2">
                                    <option value="">-Chọn người dùng-</option>
                                    <?php foreach($user as $us){ ?>
                                    <option value="{{ $us->id }}" <?php if (isset($params['customer_id']) and $params['customer_id'] == $us->id) {
                                        echo 'selected';
                                    } ?>>{{ $us->name }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <label>Tìm kiếm</label>
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
                <h3 class="box-title">Danh sách lịch sửa mua Ebook</h3>
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
                            <th>Ảnh bìa</th>
                            <th>Tên sách/ tác giả/ giá tiền</th>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Thanh toán</th>
                            <th>Gói mua</th>
                            <th>Thời gian mua</th>
                            <th style="width:100px">Trạng thái</th>
                            <th>Mã giao dịch</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPaymentSuccess = 0;
                            $totalPaymentFailed = 0;
                            $totalPayment = 0;
                        @endphp
                        @foreach ($rows as $stt => $row)
                            <tr class="valign-middle">
                                <td>
                                    {{ $stt + 1 }}
                                </td>
                                <td>
                                    <img src="{{ $row->image_document }}" style="max-height: 80px; max-width: 80px" />
                                </td>
                                <td>
                                    <strong style="font-size: 14px;">{{ $row->title_document }}</strong>
                                    <br>Tác giả: <i style="color: green">{{ $row->title_author }}</i>
                                    <br>Giá: <i style="color: #F00">{{ number_format($row->payment) }}</i>
                                </td>
                                <td>
                                    {{ $row->buy_info }}
                                </td>
                                <td>
                                    {{ $row->customer_name }}
                                </td>
                                <td>
                                    {{ $array_payment_method[$row->payment_method] ?? '' }}
                                </td>

                                <td>
                                    {{ $row->title_package }}
                                </td>
                                <td>
                                    {{ date('d/m/Y H:i', strtotime($row->buy_date)) }}
                                </td>
                                <td>
                                    <span
                                        class="{{ $row->status == 1 ? 'btn btn-xs btn-success' : 'btn btn-xs btn-warning' }}">
                                        {{ $array_status[$row->status] ?? '' }}
                                    </span>
                                </td>
                                <td>
									<?php if($row->status == 3){ ?>
										<button type="button" class="btn btn-success btn btn-xs"
                                            onclick="updateStatus({{ $row->id }}, 'approve')">Duyệt</button>
										<button type="button" class="btn btn-danger btn-xs"
                                            onclick="updateStatus({{ $row->id }}, 'cancel')">Hủy</button>
									<?php }else{
										echo $row->transaction_no . ' - ' . $row->response_code;
									} ?>
                                </td>
                                @php
                                    if ($row->status == 1) {
                                        $totalPaymentSuccess += $row->payment;
                                    } elseif ($row->status == 0) {
                                        $totalPaymentFailed += $row->payment;
                                    }
                                    $totalPayment += $row->payment;
                                @endphp
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="box-footer clearfix">
                <p>Thành công: <i style="color: #F00">{{ number_format($totalPaymentSuccess) }}</i></p>
                <p>Thất bại: <i style="color: #F00">{{ number_format($totalPaymentFailed) }}</i></p>
                <p>Tổng tiền: <i style="color: #F00">{{ number_format($totalPayment) }}</i></p>
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
<script>	
	function updateStatus(id, action) {
		
		//alert(id+action);
		
		let confirmMessage = (action === 'approve') ? 'Bạn có chắc chắn muốn duyệt giao dịch này?' :
            'Bạn có chắc chắn muốn hủy giao dịch này?';
        if (confirm(confirmMessage)) {
            $.ajax({
                url: "{{ route('cms_history_buyebook.update_ebook') }}",
                type: "GET",
                data: {
                    id: id,
                    action: action
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
					
					//alert('Đã xảy ra lỗi. Vui lòng thử lại.'+xhr);
                }
            });
        }/**/
    }
</script>
@endsection
