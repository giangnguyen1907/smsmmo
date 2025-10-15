@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
    @if (session('errorMessage'))
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('errorMessage') }}
      </div>
    @endif
    @if (session('successMessage'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('successMessage') }}
      </div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif
    <div class="row">
      <div class="col-md-5">
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="profile-username">@lang('Order number') #{{ $detail->order_info }}</h3>
              <p class="text-muted">{{ __('Created at') }}: {{ $detail->created_at }}</p>
            </div>
            <div class="box-body">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Fullname'):</label>
                  <label class="col-sm-9 col-xs-12">{{ $detail->name ?? '' }}</label>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Email'):</label>
                  <label class="col-sm-9 col-xs-12">
                    {{ $detail->email ?? '' }}
                  </label>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Phone'):</label>
                  <label class="col-sm-9 col-xs-12">
                    {{ $detail->phone ?? '' }}
                  </label>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Address'):</label>
                  <label class="col-sm-9 col-xs-12">{{ $detail->address ?? '' }}</label>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Tổng tiền'):</label>
                  <label class="col-sm-9 col-xs-12">{{ number_format($detail->payment) ?? '' }} <?php if($detail->arise > 0) echo " / Phát sinh thêm: ".number_format($detail->arise) ?></label>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Ghi chú'):</label>
                  <label class="col-sm-9 col-xs-12">{{ $detail->customer_note ?? '' }}</label>
                </div>
				
				        <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">Thanh toán:</label>
                  <div class="col-sm-9 col-xs-12 ">
                     <label>
                        <input type="radio" name="payment_status" value="0"
                          {{ $detail->payment_status == 0 ? 'checked' : '' }}>
                        <small class="mr-15">Chưa thanh toán</small>
					            </label>
					            <label>
                        <input type="radio" name="payment_status" value="1"
                          {{ $detail->payment_status == 1 ? 'checked' : '' }}>
                        <small class="mr-15">Đã thanh toán </small>
					            </label>
                  </div>
                </div>
				
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Status'):</label>
                  <div class="col-sm-9 col-xs-12 ">
                    @foreach ($array_status as $key => $value)
                      <label>
                        <input type="radio" name="status" value="{{ $key }}"
                          {{ $detail->status == $key ? 'checked' : '' }}>
                        <small class="mr-15">{{ __($value) }}</small>
                      </label>
                    @endforeach
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Admin note'):</label>
                  <div class="col-md-9 col-xs-12">
                    <textarea name="admin_note" class="form-control" rows="5">{{ $detail->admin_note ?? old('admin_note') }}</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                <i class="fa fa-bars"></i> @lang('List')
              </a>
              <button type="submit" class="btn btn-primary pull-right btn-sm">
                <i class="fa fa-floppy-o"></i>
                @lang('Save')
              </button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-md-7">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">@lang('Order product detail')</h3>
          </div>

          <div class="box-body">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>@lang('#')</th>
                  <th>@lang('Product')</th>
                  <th>@lang('Price')</th>
                  <th>@lang('Quantity')</th>
                  <th>@lang('Total')</th>
                  <th>@lang('Action')</th>
                </tr>
              </thead>
              <tbody>
                <?php $tongtien = 0; ?>
                @foreach ($rows as $row)
                 <form action="{{ route('order_details.update', $row->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <tr class="valign-middle">
                      <td>
                        {{ $loop->index + 1 }}
                      </td>
                      <td>
                        {{ $row->post_title }}
                      </td>
                      <?php if($detail->status == 'pending'){ ?>
                      <td>
                        <input class="form-control" name="price" type="number" value="{{ $row->price }}" min="0"
                          onchange="this.form.submit();">
                      </td>
                      <td>
                        <input class="form-control" type="number" name="quantity" value="{{ $row->quantity }}" min="1"
                          onchange="this.form.submit();">
                      </td>
                      <td>
                      <?php $tongtien = $tongtien + ($row->price * $row->quantity);  echo number_format($row->price * $row->quantity) ?>
                      </td>

                      <td>
                        <button class="btn btn-sm btn-danger remove-order-detail" type="button" data-toggle="tooltip"
                          title="@lang('Delete')" data-original-title="@lang('Delete')"
                          data-id="{{ $row->id }}">
                          <i class="fa fa-trash"></i>
                        </button>
                        <input class="hidden" type="submit">
                      </td>
                    <?php }else{ ?>
                      <td class="text-right">{{ $row->price }}</td>
                      <td class="text-right">{{ $row->quantity }}</td>
                      <td class="text-right">
                        <?php $tongtien = $tongtien + ($row->price * $row->quantity);?>
                        {{ number_format($row->price * $row->quantity) }}
                      </td>
                      <td></td>
                    <?php } ?>
                    </tr>
                  </form>
                @endforeach
        				<tr>
        					<td colspan="4" class="text-right">Tổng đơn hàng</td>
        					<td class="text-right">{{ number_format($tongtien) }}</td>
        					<td></td>
        				</tr>
        				<tr>
        					<td colspan="4" class="text-right">Chiết khấu</td>
        					<td class="text-right">{{ number_format($detail->discount) }}</td>
        					<td></td>
        				</tr>
        				<tr>
        					<td colspan="4" class="text-right">Phí vận chuyển</td>
        					<td class="text-right">{{ number_format($detail->ship) }}</td>
        					<td></td>
        				</tr>
        				<tr>
        					<td colspan="4" class="text-right">Tổng tiền</td>
        					<td class="text-right">
                    <?php $tongdonhang = $tongtien - $detail->discount + $detail->ship;
                    echo number_format($tongdonhang);

                    if($detail->payment_status == 1 ){ // Nếu đã thanh toán
                      // Kiểm tra phát sinh.
                      if($tongdonhang - $detail->payment > 0){
                        $detail->arise = $tongdonhang - $detail->payment;
                        $detail->save();
                        if($detail->status != 'complete'){
                          // Cập nhật cả vào giao dịch
                          $checkTransaction = App\Models\Transaction::where('order_code',$detail->order_info)->first();
                          if($checkTransaction){
                              $checkTransaction->arise = $detail->arise;
                              $checkTransaction->status = 3; // Trạng thái chưa hoàn thành
                              $checkTransaction->save();
                          }
                        }
                      }
                    }else{
                      // Kiểm tra tổng đơn, cập nhật lại đơn hàng
                      $detail->payment = $tongdonhang;
                      $detail->save();
                    }
                    ?>
                    </td>
        					<td></td>
        				</tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
	</div>
  </section>
@endsection
@section('script')
  <script>
    $(function() {
      $(".remove-order-detail").click(function(e) {
        e.preventDefault();
        var ele = $(this);
        var id = ele.attr("data-id");
        if (confirm("{{ __('Are you sure want to remove?') }}")) {
          $.ajax({
            url: '{{ route('order_details.destroy') }}',
            method: "DELETE",
            data: {
              _token: '{{ csrf_token() }}',
              id: ele.attr("data-id")
            },
            success: function(response) {
              window.location.reload();
            }
          });
        }
      });

    });
  </script>
@endsection