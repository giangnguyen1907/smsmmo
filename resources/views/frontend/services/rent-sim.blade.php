@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    {{-- Form tìm kiếm --}}
     <form action="{{ route('frontend.service.rent-sim') }}" method="GET">
      <div class="box-header pb-3">
        <h3 class="box-title mb-3"><i class="fa fa-sim-card"></i> Danh sách thuê sim</h3>

        <div class="row">
          <div class="col-md-3 mb-2">
            <input type="text" name="keyword" class="form-control"
              placeholder="Tìm theo số thuê..."
              value="{{ request('keyword') }}">
          </div>

          <div class="col-md-3 mb-2">
            <select name="network" class="form-control">
              <option value="">-- Chọn nhà mạng --</option>
              <option value="Viettel" {{ request('network')=='Viettel' ? 'selected' : '' }}>Viettel</option>
              <option value="Mobifone" {{ request('network')=='Mobifone' ? 'selected' : '' }}>Mobifone</option>
              <option value="Vinaphone" {{ request('network')=='Vinaphone' ? 'selected' : '' }}>Vinaphone</option>
            </select>
          </div>

          <div class="col-md-3 mb-2">
            <select name="service" class="form-control">
              <option value="">-- Chọn dịch vụ --</option>
              <option value="Facebook" {{ request('service')=='Facebook' ? 'selected' : '' }}>Facebook</option>
              <option value="Zalo" {{ request('service')=='Zalo' ? 'selected' : '' }}>Zalo</option>
              <option value="Telegram" {{ request('service')=='Telegram' ? 'selected' : '' }}>Telegram</option>
              <option value="Shopee" {{ request('service')=='Shopee' ? 'selected' : '' }}>Shopee</option>
            </select>
          </div>

          <div class="col-md-2 mb-2">
            <select name="status" class="form-control">
              <option value="">-- Trạng thái --</option>
              <option value="available" {{ request('status')=='available' ? 'selected' : '' }}>Còn trống</option>
              <option value="rented" {{ request('status')=='rented' ? 'selected' : '' }}>Đang thuê</option>
            </select>
          </div>

          <div class="col-md-1 mb-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary mr-2" data-toggle="tooltip" title="Tìm kiếm">
              <i class="fa fa-search"></i>
            </button>
            <a class="btn btn-secondary" href="{{ route('frontend.service.rent-sim') }}" data-toggle="tooltip" title="Làm mới">
              <i class="fa fa-refresh"></i>
            </a>
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

  {{-- Modal xác nhận thuê sim --}}
  <div class="modal fade" id="rentModal" tabindex="-1" aria-labelledby="rentModalLabel" aria-hidden="true">
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
  </div>

  {{-- Khu vực hiển thị OTP --}}
  <div id="otpSection" class="mt-3 d-none">
    <div class="alert alert-info">
      <i class="fa fa-spinner fa-spin"></i> Đang chờ mã OTP cho số <strong id="otpSimNumber"></strong>...
    </div>
    <div id="otpResult" class="alert alert-success d-none">
      <i class="fa fa-check-circle"></i> Mã OTP: <strong id="otpCode"></strong>
    </div>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  let selectedSim = {};
  let rentInterval;

  document.querySelectorAll(".rent-btn").forEach(btn => {
    btn.addEventListener("click", function () {
      selectedSim = {
        id: this.dataset.id,
        number: this.dataset.number,
        service: this.dataset.service,
        price: this.dataset.price
      };
      document.getElementById("simNumber").textContent = selectedSim.number;
      document.getElementById("simService").textContent = selectedSim.service;
      document.getElementById("simPrice").textContent = selectedSim.price;
    });
  });

  document.getElementById("confirmRent").addEventListener("click", function () {
    fetch("{{ route('rentsim.create') }}", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": "{{ csrf_token() }}"
      },
      body: JSON.stringify(selectedSim)
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const modal = bootstrap.Modal.getInstance(document.getElementById('rentModal'));
        modal.hide();

        document.getElementById("otpSection").classList.remove("d-none");
        document.getElementById("otpSimNumber").textContent = selectedSim.number;

        rentInterval = setInterval(() => {
          fetch(`/rentsim/progress/${data.rent_id}`)
            .then(res => res.json())
            .then(res => {
              if (res.success && res.status === 'received') {
                clearInterval(rentInterval);
                document.querySelector('#otpResult').classList.remove('d-none');
                document.querySelector('#otpCode').textContent = res.otp;
              }
            });
        }, 3000);
      }
    });
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
</style>
@endsection
