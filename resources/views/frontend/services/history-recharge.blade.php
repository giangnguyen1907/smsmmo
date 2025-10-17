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
          <div class="">
            <div class="card">
              <div class="card-header d-flex justify-content-between myAdvertise">
                <h4>
                  Lịch Sử Nạp Tiền
                </h4>
              </div>
              <div class="card-body">
                <div class="media align-items-center d-flex justify-content-between alert alert-warning mb-4">
                  <div>
                    {!! $translates[4] ?? ''!!}
                  </div>
                </div>
                <div class="cmn-table">
                  <form method="POST" method="#" class="mb-4">
                    <div class="input-group" style="display: flex">
                      <input type="text" name="gift_code" class="form-control" placeholder="Nhập mã quà tặng">
                      <button type="submit" name="submit_gift_code" class="btn btn-primary">
                        Nhận quà
                      </button>
                    </div>
                  </form>
                  <div class="table-responsive" style="margin-top: 30px">
                    <table class="table table-hover table-bordered">
                      <thead class="bg-light">
                        <tr>
                          <th>NO.</th>
                          <th>ORDER ID</th>
                          <th>Số tiền</th>
                          <th>Thời gian</th>
                          <th>Trạng thái</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                        $tongtiennap = 0;
                        $array_status = [0=>'Thất bại',1=>'Thành công',2=>'Bị hủy',3=>'Chờ duyệt',4=>'Thu hồi'];
                        @endphp
                        @foreach($histories as $key=> $historie)
                        @php
                        $tongtiennap = $tongtiennap + $historie->payment;
                        @endphp
                        <tr class="valign-middle">
                          <td>{{$key+1}}</td>
                          <td>{{$historie->recharge_info}}</td>
                          <td>{{ number_format($historie->payment) }} VNĐ</td>
                          <td>{{$historie->created_at}}</td>
                          <td>
                            @if($historie->status == 1)
                            <span class="badge badge-success">
                              Thành công
                            </span>
                            @else
                            <span class="badge badge-danger">
                              {{$array_status[$historie->status] ?? '' }}
                            </span>
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <div class="mt-3">
                    <strong>
                      Tổng số tiền đã nạp thành công:
                    </strong>
                    {{number_format($tongtiennap)}} VNĐ
                  </div>
                </div>
              </div>
              <div class="card-footer bg-white">
                <div class="pagination-section">
                  <nav aria-label="...">
                    <ul class="pagination">
                      <li class="page-item">
                        <a href="javascript:void(0)" class="page-link">
                          <i class="fal fa-long-arrow-left">
                          </i>
                        </a>
                      </li>
                    </ul>
                  </nav>
                </div>
              </div>
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
