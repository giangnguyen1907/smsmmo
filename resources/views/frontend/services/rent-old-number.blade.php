@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    {{-- Form tìm kiếm --}}
    <form action="{{ route('frontend.service.rent-old-number') }}" method="GET">
      <div class="box-header pb-3">
        <h3 class="box-title mb-3"><i class="fa fa-history"></i> Thuê số cũ - Đổi dịch vụ</h3>
      <div class="row box-body table-responsive">
        <div class="col-12">
          <div class="alert alert-warning rounded-3 p-3">
            - Sim bên mình đảo số liên tục , lên trường hợp Không Online các bạn cũng thông cảm (có thể hôm khác sẽ được lắp lại, nhưng các bạn bảo lắp lại thì không có)
</br>
- Dịch vụ này chỉ là góp 1 phần thuê lại số , nó không hoàn hảo tuyệt đối , mong các bạn thông cảm , có trường hợp thuê 5 ngày vẫn thuê lại được , có trường hợp hết lượt cái đúng lúc bị tháo sim lên anh em chú ý giúp.

          </div>
      </div>
</div>
        <div class="row container">
        
          <div class="col-md-12 mb-2">
            <label>🥷 Chọn Dịch Vụ <span class="text-danger">*</span></label>
           <select id="DichVuSim" name="DichVuSim" class="form-control">
              <option value="">-- Chọn dịch vụ --</option>
                                <option value="4" data-price="18000">Gửi SMS - VIP2 (Viettel) - (18.000đ) - Live 10 phút</option>
                                <option value="6" data-price="17000">Gửi SMS - VIP1 (Mạng Khác) - (17.000đ) - Live 15 phút</option>
                                <option value="29" data-price="10000">Nhận ALL GAME - (10.000đ) - Live 5 phút</option>
                                <option value="37" data-price="10000">Nhận - OKVIP2 - 789BET - (10.000đ) - Live 5 phút</option>
                                <option value="11" data-price="10000">Nhận - OKVIP - (10.000đ) - Live 5 phút</option>
                                <option value="89" data-price="20000">CuocGoi 5day (Chuyển cuộc gọi) - (20.000đ) - Live 10 phút</option>
                                <option value="33" data-price="10000">DV KHÁC - (10.000đ) - Live 8.3 phút</option>
                                <option value="81" data-price="3000">Facebook - (3.000đ) - Live 6 phút</option>
                                <option value="83" data-price="15000">Telegram - (15.000đ) - Live 8 phút</option>
                                <option value="84" data-price="20000">Zalopc - (20.000đ) - Live 10 phút</option>
            </select>
          </div>

           
                  <div class=""></br>  <label>💞 Chọn Số Cũ <span class="text-danger">*</span></label> 
Không có số nào được tìm thấy! 
</div>
 
    <div class="box-body table-responsive">

      {{-- Thông báo --}}
      @if (session('errorMessage'))
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          {{ session('errorMessage') }}
        </div>
      @endif

      @if (session('successMessage'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          {{ session('successMessage') }}
        </div>
      @endif

      {{-- Nếu không có dữ liệu --}}
      @if ($oldNumbers->isEmpty())
        <div class="alert alert-warning">
          <i class="fa fa-info-circle"></i> Không tìm thấy số cũ nào!
        </div>
      @else
        <table class="table table-hover table-bordered">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Nhà mạng</th>
              <th>Dịch vụ</th>
              <th>Số thuê</th>
              <th>Giá (VNĐ)</th>
              <th>Trạng thái</th>
              <th>Ngày hết hạn</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($oldNumbers as $num)
              <tr class="valign-middle">
                <td>{{ $num->id }}</td>
                <td>{{ $num->network }}</td>
                <td>{{ $num->service }}</td>
                <td><strong>{{ $num->number }}</strong></td>
                <td>{{ number_format($num->price) }}</td>
                <td>
                  @if ($num->status == 'available')
                    <span class="badge badge-success">Còn trống</span>
                  @else
                    <span class="badge badge-secondary">Đang thuê</span>
                  @endif
                </td>
                <td>{{ $num->expired_at ?? '—' }}</td>
                <td>
                  @if ($num->status == 'available')
                    <button class="btn btn-sm btn-primary"><i class="fa fa-redo"></i> Thuê lại</button>
                  @else
                    <button class="btn btn-sm btn-secondary" disabled>Đang thuê</button>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    {{-- Phân trang --}}
    @if ($oldNumbers->hasPages())
      <div class="box-footer clearfix">
        {{ $oldNumbers->withQueryString()->links('pagination::bootstrap-4') }}
      </div>
    @endif
                                <div class="mb-1"></br>
                                   <button class="btn btn-success" type="button" id="btnBuy">Tạo Yêu Cầu</button> <button class="btn btn-warning"> <a href="/lich-su-thue-sim">Lịch Sử Mua</a></button>
                                </div>
        </div>
      </div>
    </form>

  </div>
</section>

<style>
.box {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  margin-top: 20px;
}
.badge-success { background-color: #28a745; }
.badge-secondary { background-color: #6c757d; }
.valign-middle td { vertical-align: middle !important; }
</style>
@endsection
