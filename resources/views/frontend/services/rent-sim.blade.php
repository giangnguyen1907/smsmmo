@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    {{-- Form tìm kiếm --}}
     <form id="rentsimCreate" method="POST">
      @csrf
      <div class="box-header pb-3">
        <h3 class="box-title mb-3"><i class="fa fa-sim-card"></i> Thuê sim nhanh </h3>
                <div class="row box-body table-responsive">
                <div class="col-12">
                  <div class="alert alert-warning rounded-3 p-3">
                    {!! $translates[7] ?? ''!!}
                  </div>
            </div>
        </div>
        
        <h4 class="box-title mb-3"><i class="fa fa-sim-card"></i> Vui lòng chọn </h4>
        
        <div class="row mt-3">
          
          <div class="col-md-12 mb-2">
            <div class="form-group">
              <select name="service_id" id="DichVuSim" required class="form-control select2" onchange="chooseService()">
                <option value="">-- Chọn dịch vụ --</option>
                @foreach($services as $service)
                <option value="{{$service->id}}" data-price="{{round($service->price_per_unit)}}">{{$service->name}}</option>
                @endforeach
              </select>
              
            </div>
          </div>

          <div class="col-md-12 mb-2">
            <div class="alert bg-danger text-white">
              <div>Nhà mạng quét mạnh lên khuyến khích anh em mua gói <b>Gửi SMS - VIP2 (Viettel) </b>để tránh bị nhà mạnh chặn số , nội dung spam nha </div>
              <div></div>
            </div>
            <div class="form-group">
              <select name="network" id="NhaMang" class="form-control">
                <option value="OTHER2">Chọn nhà mạng</option>
                <option value="VIETTEL">VIETTEL</option>
                <option value="MOBIFONE">MOBIFONE</option>
                <option value="VINAPHONE">VINAPHONE</option>
                <option value="VIETNAMOBILE">VIETNAMOBILE</option>
                <option value="GMOBILE">GMOBILE</option>
              </select>
            </div>
          </div>


          <div class="col-md-12">
            <div class="form-group">
              <label>🚀 Chọn Đầu Số (Ngẫu nhiên) </label>
              <select id="prefixs" name="prefixs[]" multiple class="form-control select2">
                
                <option value="32">32</option>
                <option value="33">33</option>
                <option value="34">34</option>
                <option value="35">35</option>
                <option value="36">36</option>
                <option value="37">37</option>
                <option value="38">38</option>
                <option value="39">39</option>
              </select>
            </div>
          </div>

  
        </div>
        <div class="row mt-3">
            <div class="col-md-12 mt-3">
              <label>💰 Thanh toán: <b id="priceFM" class="text-danger">0đ</b></label> </br>
              <input type="hidden" name="price" id="price-input">
              <button class="btn btn-primary form-control mr-2" type="button" onclick="createService()" id="btnBuy">Tạo Tiến Trình</button>
            </div>
        </div>
      </div>
    </form>
   
    <div class="box-body table-responsive">
      
      {{-- Hiển thị thông báo --}}
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
      
      <div class="media align-items-center d-flex justify-content-between alert alert-warning mb-4">
        <div>
          {!! $translates[6] ?? ''!!}
        </div>
      </div>

      {{-- Nếu không có sim --}}
      @if ($sims->isEmpty())
        <div class="alert alert-warning">
          <i class="fa fa-info-circle"></i> Không tìm thấy sim khả dụng!
        </div>
      @else
        <table class="table table-hover table-bordered">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Dịch vụ</th>
              <th>Số thuê</th>
              <th>OTP</th>
              <th>Giá (VNĐ)</th>
              <th>Trạng thái</th>
              <th>Thời gian</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($sims as $sim)
              <tr class="valign-middle">
                <td>{{ $sim->id }}</td>
                <td>{{ $services[$sim->service_id]->name ?? '' }}</td>
                <td><strong>{{ $sim->sim_number }}</strong></td>
                <td><strong>{{ $sim->otp_code }}</strong></td>
                <td>{{ number_format($sim->price) }}</td>
                <td>
                  @if ($sim->status == 'available')
                    <span class="badge badge-success">Còn trống</span>
                  @else
                    <span class="badge badge-secondary">Đang thuê</span>
                  @endif
                </td>
                <td>
                  {{$sim->created_at}}
                  {{-- @if ($sim->status == 'available')
                    <button class="btn btn-sm btn-primary rent-btn"
                      data-id="{{ $sim->id }}"
                      data-number="{{ $sim->sim_number }}"
                      data-service="{{ $sim->service_id }}"
                      data-price="{{ $sim->price }}"
                      data-toggle="modal" data-target="#rentModal">
                      <i class="fa fa-shopping-cart"></i> Thuê ngay
                    </button>
                  @else
                    <button class="btn btn-sm btn-secondary" disabled>Đang thuê</button>
                  @endif --}}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
    {{-- Phân trang --}}
    @if ($sims->hasPages())
      <div class="box-footer clearfix">
        {{ $sims->withQueryString()->links('pagination::bootstrap-4') }}
      </div>
    @endif
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
    
    if(service_id > 0){
      if(price*1 > wallet*1){
        alert('Số tiền trong ví của bạn không đủ, vui lòng nạp thêm tiền để tiếp tục dịch vụ!');
        window.location.href = "/recharge-account";
      }else{
        $('#rentsimCreate').attr("action","{{ route('rentsim.create') }}");
        $('#rentsimCreate').submit();
      }
    }else{
      alert('Vui lòng chọn dịch vụ');
    }
  }

</script>

<style>
.box {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
}
.badge-success {
  background-color: #28a745;
}
.badge-secondary {
  background-color: #6c757d;
}
.valign-middle td {
  vertical-align: middle !important;
}
.alert {
    width: 100%;
    box-sizing: border-box;
}
</style>
@endsection
