@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
          class="fa fa-plus"></i> @lang('Add')</a>
    </h1>
  </section>
@endsection

@section('content')

  <!-- Main content -->
  <section class="content">
    {{-- Search form --}}
    <div class="box box-default">

      <div class="box-header with-border">
        <h3 class="box-title">@lang('Filter')</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
        <div class="box-body">
          <div class="row">


            <div class="col-md-3">
              <div class="form-group">
                <label>Khách hàng</label>
                <select name="customer_id" class="form-control select2">
                  <option value="">-Chọn khách hàng-</option>
                  <?php foreach($listUsers as $user){ ?>
                    <option value="<?=$user->id?>" <?php if(isset($params['customer_id']) and $params['customer_id'] == $user->id) echo 'selected'; ?>><?=$user->name.' ('.$user->phone.')'?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            
            <div class="col-md-2">
              <div class="form-group">
                <label>Từ ngày</label>
                <input type="date" class="form-control" name="from_date" placeholder=""
                  value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>Đến ngày</label>
                <input type="date" class="form-control" name="to_date" placeholder=""
                  value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label>Từ khóa</label>
                <input type="text" class="form-control" name="keyword" placeholder="Nhập mã đơn hàng,..."
                  value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
              </div>
            </div>
			
            <div class="col-md-2">
              <div class="form-group">
                <label>Lọc</label>
                <div>
                  <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                  <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                    @lang('Reset')
                  </a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
    {{-- End search form --}}

    <div class="box">
      <div class="box-header">
        
		<div class="row" style="padding: 0 10px;">
			<div class="col-md-4">
				<h3 class="box-title">Danh sách phiếu xuất</h3>
			</div>
			<div class="col-md-8">
				<button class="pull-right" style="padding: 5px 10px; ">
					<i class="fa fa-file-excel-o" aria-hidden="true" style="color: green;"></i>
					<a href="{{ route('exportbook.export.excel') }}" style="margin-left: 5px; color: #000;">
						Xuất phiếu xuất kho
					</a>
				</button>
			</div>
		</div>
		
		
      </div>
      <div class="box-body table-responsive">
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

          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>STT</th>
                <th>Mã phiếu</th>
                <th>Khách hàng</th>
                <th>Ngày xuất</th>
                <th>Đơn hàng</th>
                <th>Nợ cũ</th>
                <th>C.Khấu</th>
                <th>Ship</th>
                <th>Thanh toán</th>
                <th>Còn nợ</th>
                <th>Ghi chú</th>
                <th>@lang('Action')</th>
              </tr>
            </thead>
            <tbody>
				<?php $stt = 0;
				foreach ($rows as $row){
				$stt++;
				?>
                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                  onsubmit="return confirm('@lang('confirm_action')')">
                  <tr class="valign-middle">
                    
                    <td>
                      {{ $stt }}
                    </td>
                    <td>
                      {{ $row->code }}
                    </td>
                    <td>
                      {{ $row->customer_name }}
                    </td>
                    <td>
                      {{ $row->date_at }}
                    </td>
                    <td>{{ number_format($row->totalbill) }}</td>
                    <td>{{ number_format($row->olddebt) }}</td>
                    <td>{{ number_format($row->discount) }}</td>
                    <td>{{ number_format($row->ship) }}</td>
                    <td>{{ number_format($row->payment) }}</td>
                    <td>{{ number_format($row->debt) }}</td>
                    <td>{{ $row->note }}</td>
					          <td>
                      <a class="btn btn-xs btn-primary" target="_blank" data-toggle="tooltip" title="@lang('Print')"
                        data-original-title="@lang('view')"
                        href="{{ route(Request::segment(2) . '.show', $row->id) }}">
                        <i class="fa fa-print"></i>
                      </a>
                      {{--
                      <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="@lang('update')"
                        data-original-title="@lang('update')"
                        href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                        <i class="fa fa-pencil-square-o"></i>
                      </a>
                      
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-xs btn-danger" type="submit" data-toggle="tooltip"
                        title="@lang('delete')" data-original-title="@lang('delete')">
                        <i class="fa fa-trash"></i>
                      </button>
                      --}}
                    </td>
                  </tr>
                </form>
				      <?php } ?>
            </tbody>
          </table>
        
      </div>

      <div class="box-footer clearfix">
        <div class="row">
          <div class="col-sm-5">
            Tìm thấy {{ $rows->total() }} kết quả
          </div>
          <div class="col-sm-7">
            {{ $rows->withQueryString()->links('admin.pagination.default') }}
          </div>
        </div>
      </div>

    </div>
  </section>
@endsection
