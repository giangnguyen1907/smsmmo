@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="alert bg-danger text-white">
    <div>{!! $translates[1] ?? ''!!}</div>
    <div></div>
  </div>
  <div class="box box-primary">
    
    {{-- Form tìm kiếm --}}
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
                      {!! $translates[2] ?? ''!!}
                    </div>
                  </div>
                  <form class="MinhChien-MSource" id="rentsimOldCreate" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-4 mb-9">
                      <div class="mb-3" style="display: none">
                        <input type="hidden" id="csrf_token" value="MTgzYzdhMWRlNDQ0NjQ1NDllMDk1OTgzMjZjM2M2ZGE1OWY4ZTkxMmM5NDliMjg1Zjc3NmYyYmFkZTJmNzM5MQ==">
                      </div>
                      <div class="form-group">
                        <label>🥷 Chọn Dịch Vụ <span class="text-danger">*</span></label>
                        <select name="service_id" id="DichVuSim" required class="form-control select2" onchange="chooseService()">
                          <option value="">-- Chọn dịch vụ --</option>
                          @foreach($services as $service)
                          <option value="{{$service->id}}" data-price="{{round($service->price_per_unit)}}">{{$service->name}}</option>
                          @endforeach
                        </select>
                      </div>
                      
                      <div class="form-group">
                        <label>💞 Chọn Số Cũ <span class="text-danger">*</span></label> 
                        <select name="rent_id" id="listNumber" required class="form-control select2">
                          <option value="">-- Chọn Số Cũ --</option>
                          @foreach($items as $item)
                          <option value="{{$item->rent_id}}" >{{$item->sim_number}}</option>
                          @endforeach
                        </select>
                      </div>

                      <div class="mb-1">
                        <label>💰 Thanh toán: <b id="priceFM" class="text-danger">0đ</b></label>
                        <input type="hidden" name="price" id="price-input">
                        <br>
                        <button class="btn btn-primary mr-2" type="button" onclick="createService()" id="btnBuy">Tạo yêu cầu</button>
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
                {{--
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
                --}}
              </div>

              {{-- Phân trang 
              @if ($oldNumbers->hasPages())
                <div class="box-footer clearfix">
                  {{ $oldNumbers->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
              @endif
              --}}
            </div>
          </div>
        </div>
      </div>

  </div>
</section>


<script>
  
  function chooseService(){
    const serviceSelect = document.getElementById('DichVuSim'); // Dropdown dịch vụ
    const priceLabel = document.getElementById('priceFM'); // Label hiển thị giá

    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
    let price = 0;

    // Nếu có giá trong thuộc tính data-price
    if (selectedOption && selectedOption.dataset && selectedOption.dataset.price) {
        price = parseInt(selectedOption.dataset.price); // Chuyển đổi giá trị thành số
    }
    $('#price-input').val(price);
    // Cập nhật giá hiển thị
    priceLabel.textContent = price.toLocaleString('vi-VN') + 'đ';

  }
  
  function createService(){
    
    let price = $('#price-input').val();
    let wallet = @json(Auth()->user()->wallet);
    let service_id = $('#DichVuSim').val();
    let rent_id = $('#listNumber').val();
    
    if(service_id > 0 && rent_id !=""){
      if(price*1 > wallet*1){
        alert('Số tiền trong ví của bạn không đủ, vui lòng nạp thêm tiền để tiếp tục dịch vụ!');
        window.location.href = "/recharge-account";
      }else{
        $('#rentsimOldCreate').attr("action","{{ route('rentsimold.create') }}");
        $('#rentsimOldCreate').submit();
      }
    }else{
      alert('Vui lòng chọn dịch vụ hoặc sim cũ');
    }
  }

</script>


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
