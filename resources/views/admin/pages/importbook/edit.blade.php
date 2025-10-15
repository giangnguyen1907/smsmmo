@extends('admin.layouts.app')
@section('title')
  {{ $module_name }}
@endsection

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
          class="fa fa-plus"></i> @lang('Add')</a>
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

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">@lang('Update form')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_1" data-toggle="tab">
                  <h5>Thông tin chính <span class="text-danger">*</span></h5>
                </a>
              </li>

              <button type="submit" class="btn btn-primary btn-sm pull-right">
                <i class="fa fa-floppy-o"></i>
                @lang('Save')
              </button>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
					
					<div class="col-md-9">
						<div class="form-group">
							<input type="text" class="form-control" name="search" placeholder="Tìm tên sách"
							value="{{ old('search') }}" required>
						</div>
					</div>
					<div class="col-md-3">
						<a class="btn btn-success btn-sm pull-right" href="{{ route(Request::segment(2) . '.index') }}">
							<i class="fa fa-bars"></i> @lang('List')
						</a>
						<button type="submit" class="btn btn-primary btn-sm">
							<i class="fa fa-floppy-o"></i>
							@lang('Save')
						</button>
					</div>
					
					<div class="col-md-12">
						<hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
					</div>
					
					<div class="col-md-9">
						<table id="ListProduct" class="table table-thead-bordered table-nowrap table-align-middle">
							<thead class="thead-light">
								<tr>
									<th style="width: 50%">Tên SP</th>
									<th style="text-align:center;">Giá nhập</th>
									<th style="text-align:center;">Số lượng</th>
									<th style="">Thành tiền</th>
									<th style="width:100px"></th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						
						<hr class="my-5">
						
						<div class="row justify-content-md-end mb-12 text-right">
							<div class="col-md-12 col-lg-12">
								<dl class="row text-sm-right">
									<dt class="col-sm-8">Số cuốn:</dt>
									<dd class="col-sm-4" id="TotalBook">0</dd>
									<dt class="col-sm-8">Số lượng nhập:</dt>
										<dd class="col-sm-4" id="TotalQuantity">0</dd>

									<dt class="col-sm-8">Tổng tiền nhập:</dt>
									<dd class="col-sm-4" id="TotalPrice">0</dd>
								</dl>
							</div>
						</div>
						
						<div class="form-group">
							<label for="invoiceNotesLabel" class="input-label">Ghi chú</label>
							<textarea class="form-control" rows="3" name="note"></textarea>
						</div>
					</div>
					
					<div class="col-md-3">
						<div class="form-group">
							<label>Mã phiếu <small class="text-red">*</small></label>
							<input type="text" class="form-control" name="code" placeholder="Mã phiếu" value="{{ $detail->code ?? old('code') }}" required>
						</div>
						
						<div class="form-group">
							<label>Loại phiếu nhập <small class="text-red">*</small></label>
							<select name="bill_id" class="form-control select2">
								<option value="">-Chọn loại phiếu-</option>
							</select>
						</div>
						
						<div class="form-group">
							<label>Kho sách <small class="text-red">*</small></label>
							<select name="bookshop" class="form-control select2">
								<option value="">-Chọn kho-</option>
							</select>
						</div>
						
						<div class="form-group">
							<label>Nhập từ xưởng</label>
							<select name="workshop" class="form-control select2">
								<option value="">-Chọn xưởng-</option>
							</select>
						</div>
						
						<div class="form-group">
							<label>Khách hàng</label>
							<select name="customer_id" class="form-control select2">
								<option value="">-Chọn khách hàng-</option>
							</select>
						</div>
						<div class="form-group">
							<label>Thời gian <small class="text-red">*</small></label>
							<input type="date" class="form-control" name="date_at" placeholder="Tháng/ngày/năm" value="{{ $detail->date_at ?? old('date_at') }}">
						</div>
						<div class="form-group">
							<label>Thanh toán <small class="text-red">*</small></label>
							<input type="number" class="form-control" name="payment" placeholder="Nhập số tiền" value="{{ $detail->payment ?? old('payment') }}">
						</div>
					</div>
					<div class="col-md-12">
						<hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
					</div>
                </div>

              </div>

            </div><!-- /.tab-content -->
          </div><!-- nav-tabs-custom -->

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i> @lang('List')
          </a>
          <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
            @lang('Save')</button>
        </div>
      </form>
    </div>
  </section>
@endsection

