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
            <h3 class="box-title">@lang('Order product detail') #{{$detail->order_info}}</h3>
          </div>
			
          <div class="box-body">
			
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center">@lang('#')</th>
                  <th class="">@lang('Product')</th>
                  <th class="text-center">@lang('Price')</th>
                  <th class="text-center">@lang('Quantity')</th>
                  <th class="text-center">@lang('Total')</th>
                </tr>
              </thead>
              <tbody>
                <?php $tongtien = 0; ?>
                @foreach ($rows as $row)
                <tr class="valign-middle">
                  <td>
                    {{ $loop->index + 1 }}
                  </td>
                  <td>
                    <img src="{{ $row->image_thumb ?? $row->post_image }}" style="height:80px">
                    {{ $row->post_title }}
                  </td>
                  <td class="text-right">
                    {{ number_format($row->price) }}
                  </td>
                  <td class="text-center">
                    {{ $row->quantity }}
                  </td>
                  <td class="text-right">
                    <?php $tongtien = $tongtien + ($row->price * $row->quantity); ?>
                    {{ number_format($row->price * $row->quantity) }}
                  </td>
                </tr>
                @endforeach
        				<tr>
        					<td colspan="3" rowspan="4">
        						<p>Họ tên: <b>{{ $detail->name ?? '' }} - {{ $detail->phone ?? '' }}</b></p>
        						<p>{{ $detail->address }}</p>
        						<p><b>{{ $array_payment_method[$detail->payment_method] }}</b> : 
        						<?php echo $array_payment_staus[$detail->payment_status]; if($detail->arise > 0){ echo ": ".number_format($detail->payment).' / <b>Phát sinh: '.number_format($detail->arise).'</b>đ'; } ?></p>
        						<p><b>Ghi chú:</b> {{ $detail->customer_note }}</p>
        					</td>
        					<td class="text-right">Tổng đơn hàng</td>
        					<td class="text-right">{{ number_format($tongtien) }}</td>
        				</tr>
        				<tr>
        					<td class="text-right">Chiết khấu</td>
        					<td class="text-right">{{ number_format($detail->discount) }}</td>
        				</tr>
        				<tr>
        					<td class="text-right">Phí vận chuyển</td>
        					<td class="text-right">{{ number_format($detail->ship) }}</td>
        				</tr>
        				<tr>
        					<td class="text-right">Tổng tiền</td>
        					<td class="text-right">{{ number_format($tongtien-$detail->discount+$detail->ship) }}</td>
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
