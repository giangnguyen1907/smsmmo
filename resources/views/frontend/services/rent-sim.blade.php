@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    {{-- Form tìm kiếm --}}
     <form action="{{ route('rentsim.create') }}" method="POST">
      @csrf
      <div class="box-header pb-3">
        <h3 class="box-title mb-3"><i class="fa fa-sim-card"></i> Thuê sim nhanh </h3>
        <div class="row box-body table-responsive">
        <div class="col-12">
          <div class="alert alert-warning rounded-3 p-3">
            - Không đồng ý dùng SMS trái pháp luật.<br>
            - Chọn dịch vụ và đầu số, nếu không chọn đầu số thì chọn nhà mạng.<br>
            - Tạo yêu cầu đồng nghĩa chấp nhận rủi ro sim bị chặn.
          </div>
    </div>
</div>
        
        <h4 class="box-title mb-3"><i class="fa fa-sim-card"></i> Vui lòng chọn </h4>
        
        <div class="row mt-3">
          
          
          <div class="col-md-4 mb-2">
            <select id="DichVuSim" name="DichVuSim" class="form-control">
              <option value="">-- Chọn dịch vụ --</option>
                                @foreach($services as $key => $ser)
                                <option value="{{ $ser->service_id }}" data-price="{{ $ser->price_per_unit }}">{{ $ser->name }}</option>
                                @endforeach
            </select>
          </div>

          <div class="col-md-4 mb-2">
            <select name="network"  id="NhaMang" class="form-control">
              <option value="OTHER2">Chọn nhà mạng</option>
                                <option value="VIETTEL">VIETTEL</option>
                                <option value="MOBIFONE">MOBIFONE</option>
                                <option value="VINAPHONE">VINAPHONE</option>
                                <option value="VIETNAMOBILE">VIETNAMOBILE</option>
                                <option value="GMOBILE">GMOBILE</option>
            </select>
          </div>


          <div class="col-md-4">
                            <select id="prefixs" name="prefixs[]" size="1" class="form-control">
                              <option value="">Chọn đầu số</option>
                            @for ($i = 32; $i <= 99; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                            </select>
            </div>

  
        </div>
        <div class="row mt-3">
                  <div class="col-md-6 mt-3">
    <label>💰 Thanh toán: <b id="priceFM" class="text-danger">0đ</b></label> </br>
    <button class="btn btn-primary mr-2" type="submit" id="btnBuy">Tạo Tiến Trình</button>
    </div>
        </div>
      </div>
    </form>
   <script>
            document.addEventListener("DOMContentLoaded", function () {
                const serviceSelect = document.getElementById('DichVuSim'); // Dropdown dịch vụ
                const priceLabel = document.getElementById('priceFM'); // Label hiển thị giá

                // Hàm cập nhật giá
                function updatePrice() {
                    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
                    let price = 0;

                    // Nếu có giá trong thuộc tính data-price
                    if (selectedOption && selectedOption.dataset && selectedOption.dataset.price) {
                        price = parseInt(selectedOption.dataset.price); // Chuyển đổi giá trị thành số
                    }

                    // Cập nhật giá hiển thị
                    priceLabel.textContent = price.toLocaleString('vi-VN') + 'đ';
                }

                // Bắt sự kiện khi người dùng thay đổi lựa chọn
                serviceSelect.addEventListener('change', updatePrice);

                // Khởi tạo giá khi tải trang
                updatePrice();
            });
        </script>
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

      {{-- Nếu không có sim --}}
      @if ($sims->isEmpty())
        <div class="alert alert-warning">
          <i class="fa fa-info-circle"></i> Không tìm thấy sim khả dụng!
        </div>
      @else
        <!-- <table class="table table-hover table-bordered">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Nhà mạng</th>
              <th>Dịch vụ</th>
              <th>Số thuê</th>
              <th>Giá (VNĐ)</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($sims as $sim)
              <tr class="valign-middle">
                <td>{{ $sim->id }}</td>
                <td>{{ $sim->network }}</td>
                <td>{{ $sim->service }}</td>
                <td><strong>{{ $sim->number }}</strong></td>
                <td>{{ number_format($sim->price) }}</td>
                <td>
                  @if ($sim->status == 'available')
                    <span class="badge badge-success">Còn trống</span>
                  @else
                    <span class="badge badge-secondary">Đang thuê</span>
                  @endif
                </td>
                <td>
                  @if ($sim->status == 'available')
                    <button class="btn btn-sm btn-primary rent-btn"
                      data-id="{{ $sim->id }}"
                      data-number="{{ $sim->number }}"
                      data-service="{{ $sim->service }}"
                      data-price="{{ $sim->price }}"
                      data-toggle="modal" data-target="#rentModal">
                      <i class="fa fa-shopping-cart"></i> Thuê ngay
                    </button>
                  @else
                    <button class="btn btn-sm btn-secondary" disabled>Đang thuê</button>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table> -->
      @endif
    </div>

    {{-- Phân trang --}}
    @if ($sims->hasPages())
      <div class="box-footer clearfix">
        {{ $sims->withQueryString()->links('pagination::bootstrap-4') }}
      </div>
    @endif
  </div>

  {{-- Modal xác nhận thuê sim --}}
  <!-- <div class="modal fade" id="rentModal" tabindex="-1" aria-labelledby="rentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="rentModalLabel"><i class="fa fa-sim-card"></i> Xác nhận thuê sim</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
          <p><strong>Số thuê:</strong> <span id="simNumber"></span></p>
          <p><strong>Dịch vụ:</strong> <span id="simService"></span></p>
          <p><strong>Giá:</strong> <span id="simPrice"></span> VNĐ</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
          <button type="button" class="btn btn-success" id="confirmRent">Xác nhận thuê</button>
        </div>
      </div>
    </div>
  </div> -->

  <!-- {{-- Khu vực hiển thị OTP --}}
  <div id="otpSection" class="mt-3 d-none">
    <div class="alert alert-info">
      <i class="fa fa-spinner fa-spin"></i> Đang chờ mã OTP cho số <strong id="otpSimNumber"></strong>...
    </div>
    <div id="otpResult" class="alert alert-success d-none">
      <i class="fa fa-check-circle"></i> Mã OTP: <strong id="otpCode"></strong>
    </div>
  </div> -->
</section>

@push('scripts')
<script>
  $('#btnRentSim').click(function() {
    const serviceId = $('#service_id').val();
    $.ajax({
        url: '/rent-sim/create',
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            service_id: serviceId
        },
        success: function(res) {
            if (res.success) {
                alert(res.message + '\nSố thuê: ' + res.data.number);
            } else {
                alert('Lỗi: ' + res.message);
            }
        }
    });
});

</script>     

@endpush
<style>
.box {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 6px;
  margin-top: 20px;
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
