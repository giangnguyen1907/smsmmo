@extends('frontend.layouts.default')

@section('content')
<section class="content">
  <div class="box box-primary">
    <div class="alert bg-danger text-white">
      <div>{!! $translates[1] ?? ''!!}</div>
      <div></div>
    </div>
    <div class="form-box" style="width: auto;">
      <h2>
        Nạp 1000 Vào số bạn đang thuê (chỉ hỗ trợ Viettel)
      </h2>
      <form method="POST" action="#">
        <div class="alert bg-danger text-white">
          <div>
            {!! $translates[5] ?? ''!!}
          </div>
          <div>
          </div>
        </div>
        <div class="form-group">
          <label>💞 Chọn Số Cũ <span class="text-danger">*</span></label> 
          <select name="rent_id" id="listNumber" required="" class="form-control select2">
            <option value="">-- Chọn Số Cũ --</option>
            @foreach($sims as $sim)
            <option value="{{$sim->sim_number}}">{{$sim->sim_number}}</option>
            @endforeach
          </select>
        </div>
        <button class="btn btn-primary mr-2" type="button" id="btnBuy">Gửi</button>
      </form>
    </div>

    {{-- Form nạp tiền --}}
    <div class="box-body">
      @if (session('successMessage'))
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          {{ session('successMessage') }}
        </div>
      @endif

      @if (session('errorMessage'))
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          {{ session('errorMessage') }}
        </div>
      @endif
{{-- 
      <form action="{{ route('frontend.service.recharge-sim.submit') }}" method="POST" class="form-horizontal">
        @csrf
        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Chọn số thuê</label>
          <div class="col-md-6">
            <select name="sim_id" class="form-control" required>
              <option value="">-- Chọn số cần nạp --</option>
              @foreach ($activeSims as $sim)
                <option value="{{ $sim->id }}">{{ $sim->number }} ({{ $sim->network }})</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right">Số tiền nạp (VNĐ)</label>
          <div class="col-md-6">
            <input type="number" name="amount" class="form-control" min="1000" step="500" required placeholder="Nhập số tiền...">
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-md-right"></label>
          <div class="col-md-6">
            <button type="submit" class="btn btn-primary">
              <i class="fa fa-paper-plane"></i> Nạp tiền
            </button>
            <a href="{{ route('frontend.service.recharge') }}" class="btn btn-secondary">
              <i class="fa fa-refresh"></i> Làm mới
            </a>
          </div>
        </div>
      </form> --}}
    </div>

    {{-- Lịch sử nạp tiền 
    <div class="box-body table-responsive mt-4">
      <h4><i class="fa fa-clock"></i> Lịch sử nạp tiền</h4>
      @if ($recharges->isEmpty())
        <div class="alert alert-warning mt-2">
          <i class="fa fa-info-circle"></i> Chưa có giao dịch nạp tiền nào!
        </div>
      @else
        <table class="table table-bordered table-hover">
          <thead class="bg-light">
            <tr>
              <th>ID</th>
              <th>Số thuê</th>
              <th>Nhà mạng</th>
              <th>Số tiền</th>
              <th>Thời gian</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($recharges as $r)
              <tr class="valign-middle">
                <td>{{ $r->id }}</td>
                <td>{{ $r->number }}</td>
                <td>{{ $r->network }}</td>
                <td>{{ number_format($r->amount) }} VNĐ</td>
                <td>{{ $r->created_at ?? now()->format('Y-m-d H:i') }}</td>
                <td>
                  @if ($r->status == 'success')
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

    @if ($recharges->hasPages())
      <div class="box-footer clearfix">
        {{ $recharges->withQueryString()->links('pagination::bootstrap-4') }}
      </div>
    @endif
    --}}
  </div>
</section>

<style>
.box { background: #fff; border: 1px solid #ddd; border-radius: 6px; }
.badge-success { background-color: #28a745; }
.badge-danger { background-color: #dc3545; }
.valign-middle td { vertical-align: middle !important; }
.form-box {
    border: 1px solid #ccc;
    padding: 15px;
    width: 300px;
    border-radius: 8px;
    font-family: Arial;
}
.alert {
    border: 1px solid transparent;
    border-radius: .25rem;
    margin-bottom: 1rem;
    padding: .75rem 1.25rem;
    position: relative;
}

</style>
@endsection
