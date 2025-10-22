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
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
                                <select name="payment_method" id="payment_method" class="form-control select2">
                                    <option value="">-Chọn phương thức nạp tiền-</option>
                                    <?php foreach($array_istype as $key=> $istype){ ?>
                                    <option value="{{ $key }}" <?php if (isset($params['payment_method']) and $params['payment_method'] == $key) {
                                        echo 'selected';
                                    } ?>>{{ $istype }}</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="form-group">
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
                        <div class="col-md-3 col-xs-12">
                            <div class="form-group">
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
                <h3 class="box-title">Danh sách nạp tiền</h3>
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
                            <th>Khuyến mại</th>
                            <th>Phương thức thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $stt => $row)
                            <tr class="valign-middle">
                                <td>
                                    {{ $stt + 1 }}
                                </td>
                                <td>
                                    {{ $row->recharge_info }}
                                </td>
                                <td>
                                    {{ $row->customer_name }}
                                </td>
                                <td id="payment-{{ $row->id }}">
                                    {{ number_format($row->payment) }} 
                                </td>
                                <td id="voucher-{{ $row->id }}">
                                    {{ number_format($row->voucher) }}
                                </td>
                                <td>
                                    {{ $array_istype[$row->payment_method] ?? '' }}
                                </td>
                                <td>
                                    {{ $array_status[$row->status] ?? '' }}
                                </td>
                                <td>
                                    {{ date('d/m/Y H:i', strtotime($row->created_at)) }}
                                </td>
                                <td>
                                    @if ($row->status == 3)
                                        <button type="button" class="btn btn-success btn btn-xs"
                                            onclick="updateStatus({{ $row->id }}, 'approve')">Duyệt</button>
                                        <button type="button" class="btn btn-warning btn-xs"
                                            onclick="editPayment({{ $row->id }}, {{ $row->payment }})">Chỉnh
                                            sửa</button>
                                        <button type="button" class="btn btn-danger btn-xs"
                                            onclick="updateStatus({{ $row->id }}, 'cancel')">Hủy</button>
                                    @elseif ($row->status == 1)
                                        <button type="button" class="btn btn-danger btn-xs"
                                            onclick="updateStatus({{ $row->id }}, 'recall')">Thu hồi</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
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

<script>
    function editPayment(id, currentPayment) {
        if (confirm("Bạn có chắc muốn sửa giao dịch này?")) {
            let paymentTd = document.getElementById('payment-' + id);
            paymentTd.innerHTML =
                `<input type="number" id="payment-input-${id}" value="${currentPayment}" class="form-control" style="width: 100px; display: inline-block;">
            <button type="button" class="btn btn-sm btn-primary" onclick="savePayment(${id})">Lưu</button>`;
        }
    }

    function savePayment(id) {
        let newPayment = document.getElementById('payment-input-' + id).value;

        $.ajax({
            url: "{{ route('cms_history_rechargeuser.update_payment') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                payment: newPayment
            },
            success: function(response) {
                document.getElementById('payment-' + id).innerHTML =
                    `${parseFloat(newPayment).toLocaleString()} VNĐ`;
                alert('Cập nhật thành công!');
            },
            error: function(xhr) {
                alert('Cập nhật thất bại!');
            }
        });
    }

    function updateStatus(id, action) {
        let confirmMessage = (action === 'approve') ? 'Bạn có chắc chắn muốn duyệt giao dịch này?' :
            'Bạn có chắc chắn muốn hủy giao dịch này?';
        if (confirm(confirmMessage)) {
            $.ajax({
                url: "{{ route('cms_history_rechargeuser.updateStatus') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
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
                    alert('Đã xảy ra lỗi. Vui lòng thử lại.');
                }
            });
        }
    }
</script>
