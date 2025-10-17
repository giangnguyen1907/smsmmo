@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    {{-- Form tìm kiếm --}}
    <form action="{{ route('frontend.service.history') }}" method="GET">
      <div class="box-header pb-3">
        <h3 class="box-title mb-3"><i class="fa fa-list"></i> Lịch sử thuê sim</h3>

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
                @foreach($services as $key => $ser)
                <option value="{{ $ser->service_id }}" data-price="{{ $ser->price_per_unit }}">{{ $ser->name }}</option>
                @endforeach
            </select>
          </div>

          <div class="col-md-2 mb-2">
            <select name="status" class="form-control">
              <option value="">-- Trạng thái --</option>
              <option value="success" {{ request('status')=='success' ? 'selected' : '' }}>Thành công</option>
              <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Thất bại</option>
            </select>
          </div>

          <div class="col-md-1 mb-2 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary mr-2" data-toggle="tooltip" title="Tìm kiếm">
              <i class="fa fa-search"></i>
            </button>
            <a class="btn btn-secondary" href="{{ route('frontend.service.history') }}" data-toggle="tooltip" title="Làm mới">
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

      {{-- Dữ liệu --}}
      @if ($histories->isEmpty())
        <div class="alert alert-warning">
          <i class="fa fa-info-circle"></i> Không có lịch sử thuê sim nào!
        </div>
      @else
        <table class="table table-hover table-bordered">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Dịch vụ</th>
              <th>Số thuê</th>
              <th>Giá</th>
              <th>Thời gian thuê</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($histories as $key=> $his)
              <tr class="valign-middle">
                <td>{{ $key+1 }}</td>
                <td>{{ $services[$his->service_id]->name ?? '' }}</td>
                <td><strong>{{ $his->sim_number }}</strong></td>
                <td>{{ number_format($his->price) }} VNĐ</td>
                <td>{{ $his->created_at ?? now()->format('Y-m-d H:i') }}</td>
                <td>
                  @if ($his->status == 'success')
                    <span class="badge badge-success">Thành công</span>
                  @else
                    <span class="badge badge-danger">Thất bại</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>

    @if ($histories->hasPages())
      <div class="box-footer clearfix">
        {{ $histories->withQueryString()->links('pagination::bootstrap-4') }}
      </div>
    @endif
  </div>
</section>

<style>
.box { background: #fff; border: 1px solid #ddd; border-radius: 6px; }
.badge-success { background-color: #28a745; }
.badge-danger { background-color: #dc3545; }
.valign-middle td { vertical-align: middle !important; }
</style>
@endsection
