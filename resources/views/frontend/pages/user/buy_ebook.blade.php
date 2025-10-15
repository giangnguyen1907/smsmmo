@if (Auth::check())
    <div class="main-content full-width inner-page">
        <div class="background-content"></div>
        <div class="background">
            <div class="shadow"></div>
            <div class="pattern">
                <div class="">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Thanh toán</th>
                                <th>Sản phẩm</th>
                                <th>Thời gian mua</th>
                                <th>Thời gian đọc</th>
                                <th>Giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buyEbook as $item)
                                @php
                                    $url_doc =
                                        route('frontend.cms.view', ['alias' => $item->document->alias ?? '']) . '.html';
                                    $name_doc = $item->document->title ?? '';
                                @endphp
                                <tr>
                                    <td>#{{ $item->buy_info }}</td>
                                    <td>{{ $array_payment_method[$item->payment_method] ?? "" }}</td>
                                    <td><a href="{{ $url_doc }}">{{ $name_doc }}</a></td>
                                    <td>{{ date('d-m-Y H:i', strtotime($item->buy_date)) }}</td>
                                    <td>{{ $item->time_read }} ngày</td>
                                    <td>{{ number_format($item->payment) }} VNĐ</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
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
</style>
