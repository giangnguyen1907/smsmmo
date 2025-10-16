@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="alert bg-danger text-white">
    <div>Xin chào mọi người đã đến với SMSMMO , Chúc các bạn có một  ngày làm việc vui vẻ . Niềm vui của bạn cũng chính là hạnh phúc của tôi</div>
    <div></div>
  </div>
  <div class="box box-primary">
    
    {{-- Form tìm kiếm --}}
    <form action="{{ route('frontend.service.rent-old-number') }}" method="GET">
      <div class="box-header pb-3">
        
        <div class="row box-body table-responsive">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between myAdvertise">
                  <h4><i class="fa fa-history"></i> Thuê Số Cũ - Đổi Dịch Vụ</h4>
              </div>
              <div class="card-body">
                <div class="profile-form-section">
                  <div class="media align-items-center d-flex justify-content-between alert alert-warning mb-4">
                    <div style="">
                      - Sim bên mình đảo số liên tục , lên trường hợp Không Online các
                      bạn cũng thông cảm (có thể hôm khác sẽ được lắp lại, nhưng các
                      bạn bảo lắp lại thì không có)
                      <br>
                      - Dịch vụ này chỉ là góp 1 phần thuê lại số , nó không hoàn
                      hảo tuyệt đối , mong các bạn thông cảm , có trường hợp thuê 5
                      ngày vẫn thuê lại được , có trường hợp hết lượt cái đúng lúc
                      bị tháo sim lên anh em chú ý giúp.
                    </div>
                  </div>
                  <form class="MinhChien-MSource">
                    
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-4 mb-9">
                      <div class="mb-3" style="display: none">
                        <input type="hidden" id="csrf_token" value="MTgzYzdhMWRlNDQ0NjQ1NDllMDk1OTgzMjZjM2M2ZGE1OWY4ZTkxMmM5NDliMjg1Zjc3NmYyYmFkZTJmNzM5MQ==">
                      </div>
                      <div class="form-group">
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
                      
                      <div class="form-group">
                        <label>💞 Chọn Số Cũ <span class="text-danger">*</span></label> 
                        <div id="listNumber">Không có số nào được tìm thấy!</div>
                      </div>

                      <div class="mb-1">
                        <br>
                        <button class="btn btn-success" type="button" id="btnBuy">
                          Tạo Yêu Cầu
                        </button>
                        <button class="btn btn-warning">
                          <a href="/lich-su-thue">
                            Lịch Sử Mua
                          </a>
                        </button>
                      </div>
                      <div class="mb-1">
                      </div>
                    </div>
                    <label class="text-sm" id="divResult" style="display: none">
                      Thông báo:
                      <b id="msgResult" class="text-danger">
                        Lỗi Hiển Thị Ở Đây
                      </b>
                    </label>
                  </form>
                </div>
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
            </div>
          </div>
        </div>
      </div>
    </form>

  </div>
</section>

<style>
.card {
    word-wrap: break-word;
    background-clip: border-box;
    background-color: #fff;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
    flex-direction: column;
    min-width: 0;
    position: relative;
}
.card-header {
    background-color: rgba(0, 0, 0, .03);
    border-bottom: 1px solid rgba(0, 0, 0, .125);
    margin-bottom: 0;
    padding: .75rem 1.25rem;
}
.card-body {
    flex: 1 1 auto;
    min-height: 1px;
    padding: 10px;
}
.box {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
}
.badge-success { background-color: #28a745; }
.badge-secondary { background-color: #6c757d; }
.valign-middle td { vertical-align: middle !important; }
</style>
@endsection
