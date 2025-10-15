<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>In Đơn Hàng</title>
	<link rel="stylesheet" href="/themes/admin/css/bootstrap.min.css">
	<script src="/themes/admin/js/bootstrap.min.js"></script>
</head>
<body>
  <!-- Main content -->
  <section class="content">
    
	<div class="row">
      
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">@lang('Order product detail')</h3>
          </div>
			
          <div class="box-body">
			
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center">@lang('STT')</th>
                  <th class="">@lang('Product')</th>
                  <th class="text-center">@lang('Price')</th>
                  <th class="text-center">@lang('Quantity')</th>
                  <th class="text-center">@lang('Total')</th>
                  <th class="text-center">Mã phiếu</th>
                  <th class="text-center">Loại phiếu</th>
                  <th class="text-center">Nhà cung cấp</th>
                  <th class="text-center">Ngày nhập</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($rows->exportBookDetails as $index => $item)
                    @php
                        $alias_detail = Str::slug($item->document->title);
                        $url_link = route('frontend.cms.view', ['alias' => $alias_detail]);
                    @endphp
                    <tr class="valign-middle">
                      <td class="text-center" style="vertical-align: middle;">
                        #{{ $loop->index + 1 }}
                      </td>
                      <td>
                        <img src="{{ $item->document->image_thumb ?? $item->document->image }}" style="height:80px; margin-right: 10px;">
                        <p>{{ $item->document->title }}</p>
                      </td>
                      <td class="text-right" style="vertical-align: middle;">
                        {{ number_format($item->document->cost) }}&#8363;
                      </td>
                      <td class="text-center" style="vertical-align: middle;">
                        {{ $item->quantity }}
                      </td>
                      <td class="text-right" style="vertical-align: middle;">
                        {{ number_format($item->document->cost * $item->quantity) }}&#8363;
                      </td>

                      @if ($index == 0)
                        <td rowspan="{{ $rows->exportBookDetails->count() }}" class="text-center" style="vertical-align: middle;">
                          {{ $rows->code ?? '' }}
                        </td>
                        <td rowspan="{{ $rows->exportBookDetails->count() }}" class="text-center" style="vertical-align: middle;">
                          {{ $rows->bill->title ?? '' }}
                        </td>
                        <td rowspan="{{ $rows->exportBookDetails->count() }}" class="text-center" style="vertical-align: middle;">
                          {{ $rows->workShop->title ?? '' }}
                        </td>
                        <td rowspan="{{ $rows->exportBookDetails->count() }}" class="text-center" style="vertical-align: middle;">
                          {{ $rows->date_at ?? '' }}
                        </td>
                      @endif
                    </tr>
                  @endforeach
                  <tr>
                    <td colspan="5" rowspan="5">
                      <p><b>Họ tên:</b> {{ $rows->customer->name ?? '' }} - {{ $rows->customer->phone ?? '' }}</p>
                      <p><b>Địa chỉ:</b> {{ $rows->customer->address }}</p>
                      @if (isset($rows->customer->json_params))
                        <p>{{ $rows->customer->json_params->province_name }}, {{ $rows->customer->json_params->district_name }}, {{ $rows->customer->json_params->ward_name }}</p>
                      @endif
                      <p><b>Trạng thái:</b> {{ $rows->status == 1 ? 'Đã thanh thoán' : 'Chưa thanh toán' }}</p>
                      <p><b>Ghi chú:</b> {{ $rows->note }}</p>
                    </td>
                    <td class="text-center" colspan="2">Tổng đơn hàng</td>
                    <td class="text-right" colspan="2">{{ number_format($rows->totalbill) }}&#8363;</td>
                  </tr>
                  <tr>
                    <td class="text-center" colspan="2">Chiết khấu</td>
                    <td class="text-right" colspan="2">{{ number_format($rows->discount) }}&#8363;</td>
                  </tr>
                  <tr>
                    <td class="text-center" colspan="2">Nợ cũ</td>
                    <td class="text-right" colspan="2">{{ number_format($rows->olddebt) }}&#8363;</td>
                  </tr>
                  <tr>
                    <td class="text-center" colspan="2">Thanh toán</td>
                    <td class="text-right" colspan="2">{{ number_format($rows->payment) }}&#8363;</td>
                  </tr>
                  <tr>
                    <td class="text-center" colspan="2">Dư nợ</td>
                    <td class="text-right" colspan="2">{{ number_format($rows->debt) }}&#8363;</td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
	</div>
  </section>
</body>
</html>  	
<script>
	window.print();
</script>
