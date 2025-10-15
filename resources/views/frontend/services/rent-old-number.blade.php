@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    {{-- Form tìm kiếm --}}
    <form action="{{ route('frontend.service.rent-old-number') }}" method="GET">
      <div class="box-header pb-3">
        <h3 class="box-title mb-3"><i class="fa fa-history"></i> Danh sách thuê số cũ</h3>

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
            <a class="btn btn-secondary" href="{{ route('frontend.service.rent-old-number') }}" data-toggle="tooltip" title="Làm mới">
              <i class="fa fa-refresh"></i>
            </a>
          </div>
        </div>
      </div>
    </form>

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
