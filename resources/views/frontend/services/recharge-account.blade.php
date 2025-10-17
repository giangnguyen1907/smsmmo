@extends('frontend.layouts.default')

@section('content')
<section class="content">
    <div class="alert bg-danger">
        <div>{!! $translates[3] ?? ''!!}</div>
    </div>
    <div class="box box-primary">
    
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
    <form action="{{ route('frontend.service.recharge-account') }}" method="post" class="mb-5 rounded-lg p-2 text-sm font-semibold text-white">
        @csrf
        <div class="card">
            <div class="card-header d-flex justify-content-between myAdvertise">
                <h4>
                Nạp Tiền Tài Khoản
                </h4>
            </div>
            <div class="card-body">
                <div class="profile-form-section">
                    <div class="grid grid-cols-1 gap-5 md:grid-cols-4 mb-9">
                        
                        <div id="payoutSummary">
                            <div class="side-box mt-3">
                            <h2 style="color: #020202;">
                                <b>
                                <center>
                                    Nhập số tiền cần nạp
                                </center>
                                </b>
                            </h2>
                            <!-- Nút chọn nhanh -->
                            <div class="quick-buttons d-flex justify-content-center gap-2 my-3">
                                <button type="button" class="btn btn-outline-primary quick-amount" data-amount="100000">
                                100.000
                                </button>
                                <button type="button" class="btn btn-outline-primary quick-amount" data-amount="200000">
                                200.000
                                </button>
                                <button type="button" class="btn btn-outline-primary quick-amount" data-amount="500000">
                                500.000
                                </button>
                            </div>
                            <!-- Ô nhập tiền -->
                            <input class="form-control mb-3" id="amountInput" type="number" name="amount_payment"
                            required="" placeholder="Nhập số tiền">
                            <button type="button" onclick="checkBanking()" class="btn btn-primary w-100">
                                <i class="fa fa-paper-plane"></i> Nạp Tiền
                            </button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="info-banking" style="display: none">
            <div class="card-header d-flex justify-content-between myAdvertise">
                <h4>
                Nạp Tiền - Hóa đơn nạp tiền
                </h4>
            </div>
            <br>
            <div class="card-body">
                <div class="row g-4 g-lg-5">
                
                    <div class="col-sm-6">

                        <h3>Thông tin chuyển khoản</h3>
                        <p><b>{{ $web_information->payment_information->name_bank_1 ?? "" }}</b></p>
                        <p>STK: <b>{{ $web_information->payment_information->stk_1 ?? "" }}</b></p>
                        <p>CTK: <b>{{ $web_information->payment_information->name_1 ?? "" }}</b></p>
                        <?php $randomNumber = rand(100,999); ?>
                        <p>Nội dung: <b id="rechargeInfo">smsmmo-{{ $randomNumber }}</b>
                            <button type="button" class="btn-warning" onclick="coppyContent()"
                                style="width: max-content; padding: 5px 10px;margin-top: 10px;">
                                Sao chép nội dung chuyển khoản
                                <i id="check_coppy" style="display:none" class="fa fa-check"></i>
                            </button>
                        </p>
                        <p><b>Chú ý:</b> Quý khách vui lòng kiểm tra đúng thông tin thanh toán, "Sao chép nội dung" thanh toán. Sau khi thanh toán thành công xong quý khách bấm nút <b>"Xác nhận đã chuyển khoản"</b> để hoàn thành giao dịch. Xin cảm ơn!</p>
                        <input type="hidden" name="trans_code_transfer" id="trans_code_transfer" value="smsmmo--{{ $randomNumber }}">
                        <button id="accept" type="submit" name="submit" value="accept" class="btn btn-primary"><i class="fa fa-spinner" id="loading" style="display:none;"></i> Xác nhận đã chuyển khoản</button>
                    </div>
                    <div class="col-sm-6 text-center">
                        <img src="{{ $web_information->image->qrbank ?? "" }}" style="max-width: 100%; max-height: 300px" />
                    </div>
                    
                </div>
            </div>
            
        </div>
    </form>
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
.d-flex {
    display: flex !important;
}
.quick-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}
.quick-amount {
    min-width: 100px;
}
.btn-outline-primary {
    border-color: #007bff;
    color: #007bff;
    width: 100%;
}
.w-100{width: 100%;}
.mb-3, .my-3 {
    margin-bottom: 1rem !important;
}
.mt-3, .my-3 {
    margin-top: 1rem !important;
}
.box { background: #fff; border: 1px solid #ddd; border-radius: 6px; }
.badge-success { background-color: #28a745; }
.badge-danger { background-color: #dc3545; }
.valign-middle td { vertical-align: middle !important; }
</style>

<script>
    function checkBanking(){
        $('#info-banking').attr('style','display:block');
    }
</script>
<script>
document.querySelectorAll('.quick-amount').forEach(button => {
    button.addEventListener('click', () => {
        const amount = button.getAttribute('data-amount');
        document.getElementById('amountInput').value = amount;
    });
});
</script>
@endsection
