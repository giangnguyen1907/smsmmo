@if (Auth::check())
    @php
        $array_theloai = App\Consts::THELOAI_BANTHAO;
    @endphp
    <div class="main-content full-width inner-page">
        <div class="background-content"></div>
        <div class="background">
            <div class="shadow"></div>
            <div class="pattern">
                <div class="">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Tác phẩm/tác giả</th>
                                <th>Thông tin</th>
                                <th>Xuất bản</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($details as $detail)
                                <tr>
                                    <td>
                                        <a style="cursor: pointer"><strong
                                                style="font-size: 15px;">{{ $detail->tacpham }}</strong></a>
                                        <br>Thể loại: {{ $array_theloai[$detail->theloai] }}
                                        <br>Tác giả: {{ $detail->tacgia }}
                                        <br>Địa chỉ: {{ $detail->diachi }}
                                        <br>Điện thoại: {{ $detail->dienthoai }}
                                        <br>Email: {{ $detail->email }}
                                    </td>
                                    <td>
                                        Số trang: {{ $detail->sotrang }}
                                        <br>Khuôn khổ: {{ $detail->khuonkho }}
                                        <br>Định dạng: {{ $detail->dinhdang }}
                                        <br>Dung lượng: {{ $detail->dungluong }}
                                    </td>
                                    <td>
                                        Lần xuất bản: {{ $detail->lanxuatban }}
                                        <br>Lần tái bản: {{ $detail->lantaiban }}
                                        <br>Số lượng: {{ $detail->soluong }}
                                        <br>Nhà in: {{ $detail->nhain }}
                                        <br>Nền tảng: {{ $detail->trangweb }}
                                    </td>
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
        text-align: left !important;
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
