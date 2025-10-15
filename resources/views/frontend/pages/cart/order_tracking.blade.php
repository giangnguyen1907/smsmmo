@extends('frontend.layouts.default')

@php
    $page_title = $taxonomy->title ?? ($page->title ?? ($page->name ?? ''));
    $image_background =
        $taxonomy->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? '');

    $order_detail_status = App\Consts::ORDER_DETAIL_STATUS;
@endphp

@section('content')
    <section id="content">
        <div class="breadcrum container" style="font-size: 17px;">
            <a href="{{ route('frontend.home') }}">{{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}</a>
            <span> / {{ $array_translate[strtolower('Order tracking')]->$locale ?? 'Order tracking' }}</span>
        </div>

        @if (Auth::check())
            <div class="main-content full-width inner-page">
                <div class="background-content"></div>
                <div class="background">
                    <div class="shadow"></div>
                    <div class="pattern">
                        <div class="container">

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('success') }}
                                </div>

                                <script>
                                    var linearGradient = getComputedStyle(document.documentElement).getPropertyValue('--linear-gradient');
                                    document.addEventListener("DOMContentLoaded", function() {
                                        Toastify({
                                            text: "Hủy đơn thành công!",
                                            duration: 5000, 
                                            gravity: "top", 
                                            position: "right", 
                                            backgroundColor: linearGradient
                                        }).showToast();
                                    });
                                </script>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('error') }}
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

                            <div class="btn-group" role="group" style="display: flex; justify-content: space-between;">
                                <button type="button" class="btn btn-order" onclick="loadOrders('pending')">Chờ duyệt</button>
                                <button type="button" class="btn btn-order" onclick="loadOrders('reject')">Hủy</button>
                                <button type="button" class="btn btn-order click" onclick="loadOrders('complete')">Hoàn thành</button>
                                <button type="button" class="btn btn-order" onclick="loadOrders('delivery')">Đang giao</button>
                            </div>

                            <div id="orders-table" style="margin-top: 10px;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Mã đơn hàng</th>
                                            <th>Ngày mua</th>
                                            @if ($status == 'pending')
                                                <th>Thao tác</th>
                                            @endif
                                            <th>Tổng tiền</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Số lượng</th>
                                            <th>Giá</th>
                                            <th>Tạm tính</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($details as $detail)
                                            @if ($detail->orderDetails->count() > 0)
                                                <tr>
                                                    <td rowspan="{{ $detail->orderDetails->count() }}" style="text-align: left;">#{{ $detail->order_info ?? $detail->id }}
                                                    </td>
                                                    <td rowspan="{{ $detail->orderDetails->count() }}">
                                                        {{ $detail->order_date }}</td>
                                                    <td rowspan="{{ $detail->orderDetails->count() }}">
                                                        @foreach ($order_detail_status as $key => $status)
                                                            {{ isset($detail->status) && $detail->status == $key ? $status : '' }}
                                                        @endforeach
                                                    </td>

                                                    {{-- hủy đơn --}}
                                                    @if ($detail->status == 'pending')
                                                        <td rowspan="{{ $detail->orderDetails->count() }}">
                                                            <form action="{{ route('frontend.order.cancel', $detail->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger btn-sm">{{ $array_translate[strtolower('Cancel')]->$locale ?? 'Cancel' }}</button>
                                                            </form>
                                                        </td>
                                                    @endif

                                                    <td rowspan="{{ $detail->orderDetails->count() }}" style="text-align: right;">
                                                        {{ number_format($detail->payment) }}&#8363</td>

                                                    @foreach ($detail->orderDetails as $key => $item)
                                                        @if ($key > 0)
                                                            </tr><tr>
                                                        @endif
                                                        {{-- <td style="text-align: left;">{{ $item->product->title }}</td> --}}
                                                        <td>{{ $item->quantity }}</td>
                                                        <td style="text-align: right;">{{ number_format($item->price) }}&#8363</td>
                                                        <td style="text-align: right;">{{ number_format($item->quantity * $item->price) }}&#8363</td>
                                                    @endforeach
                                                    
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="main-content full-width inner-page">
                <div class="background-content"></div>
                <div class="background">
                    <div class="shadow"></div>
                    <div class="pattern">
                        <div class="container">

                            <div class="container">
                                <h3 class="alert alert-warning" style="text-align: center">Vui lòng đăng nhập để xem chi tiết!
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>
@endsection

<style>
    table {
        width: 100%;
    }

    th,
    td {
        text-align: center;
    }

    .breadcrum {
        padding: 20px 0;
        border-bottom: 1px solid #ccc;
        margin: 210px auto 40px;
    }

    th {
        background-color: #f2f2f2;
    }

    .btn-order {
        border: 1px solid #fafafa !important; 
        background: #ccc;
        width: 100%;
    }

    .btn-order.click {
        background: var(--primary-color);
        color: #fff !important;
    }

    #orders-table {
        overflow-x: scroll;
        -webkit-overflow-scrolling: touch;
        white-space: nowrap;
        border: 1px solid #ccc;
    }
</style>

{{-- click danh mục order --}}
<script>
    function loadOrders(status) 
    {
        //đổi background nút được click
        document.querySelectorAll('.btn-order').forEach(function (btn) {
            btn.classList.remove('click');
        })
        event.target.classList.add('click');

        $.ajax({
            url: "{{ route('frontend.status.order') }}",
            data: {
                status: status
            },
            success: function (data) {
                $('#orders-table').html(data);
            },
            error: function (xhr, status, error) {
                console.log('AJAX ERROR:' + status + error);
            }
        })
    }
</script>
