@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-success pull-right" href="{{ route(Request::segment(2) . '.index') }}">
		<i class="fa fa-bars"></i> @lang('List')</a>
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
    {{-- dd(session('importbook')) --}}
    <div class="box box-primary">
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
        @csrf
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
					
					<div class="col-md-9">
						<div class="form-group">
							<input type="text" class="form-control search-book" id="search-book" name="search" placeholder="Nhập tên sách..."
							value="{{ old('search') }}">
						</div>
					</div>
					<div class="col-md-3">
						<a class="btn btn-success btn-sm"  onclick="InfomationBook()" href="javascript:;">
							<i class="fa fa-search"></i> Tìm kiếm
						</a>
						<button type="submit" class="btn btn-primary btn-sm pull-right">
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
									<th style="width: 40%">Tên SP</th>
									<th style="text-align:center;">Giá nhập</th>
									<th style="text-align:center;">Số lượng</th>
									<th style="">Thành tiền</th>
									<th style="width:60px"></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									$index=0; $TotalPrice = $TotalQuantity = 0; 
									if(session('importbook')){ 
									foreach(session('importbook') as $id=> $s_import){ $index++;
										$TotalQuantity = $TotalQuantity + $s_import['sluong'];
										$tongtien = $s_import['sluong'] * $s_import['cost'];
										$TotalPrice = $TotalPrice + $tongtien;
									?>
									<tr id="rowTable-{{$index}}">
										<td>{{ $s_import['name'] }}</td>
										<td><input type="number" id="cost_{{$index}}" name="cost[{{$id}}]" readonly="" value="{{ $s_import['cost'] }}" class="form-control"></td>
										<td><input type="number" id="quantity_{{$index}}" onclick="updateQuantity({{$index}},{{$id}})" name="quantity[{{$id}}]" value="{{ $s_import['sluong'] }}" class="form-control"></td>
										<td><span id="totalprice-{{$index}}">{{ $tongtien }}</span></td>
										<td class="text-right"><span id="RemoveRow-{{$index}}" onclick="RowremoveTab({{$index}},{{$id}});" style="cursor:pointer"><i class="fa fa-trash-o"></i></span></td>
									</tr>

								<?php } } ?>
							</tbody>
						</table>
						
						<hr class="my-5">
						
						<div class="row justify-content-md-end mb-12 text-right">
							<div class="col-md-12 col-lg-12">
								<dl class="row text-sm-right">
									<dt class="col-sm-8">Số cuốn:</dt>
									<dd class="col-sm-4" id="TotalBook">{{$index}}</dd>
									<dt class="col-sm-8">Số lượng nhập:</dt>
										<dd class="col-sm-4" id="TotalQuantity">{{$TotalQuantity}}</dd>
									<dt class="col-sm-8">Tổng tiền nhập:</dt>
									<dd class="col-sm-4" id="TotalPrice">{{$TotalPrice}}</dd>
									<dt class="col-sm-8">Nợ cũ:</dt>
									<dd class="col-sm-4" id="nocu">0</dd>
									<dt class="col-sm-8">Chiết khấu:</dt>
									<dd class="col-sm-4"><input type="number" onchange="haveToPay()" onclick="haveToPay()" onchange="haveToPay()" onblur="haveToPay()" id="discount" name="discount" ></dd>
									<dt class="col-sm-8">Phải thanh toán:</dt>
									<dd class="col-sm-4" id="phaithanhtoan">{{ $TotalPrice }}</dd>
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
							<input type="text" class="form-control" name="code" placeholder="Mã phiếu" value="{{ old('code') }}" required>
						</div>
						
						<div class="form-group">
							<label>Loại phiếu nhập <small class="text-red">*</small></label>
							<select name="bill_id" class="form-control select2">
								<option value="">-Chọn loại phiếu-</option>
								<?php foreach($listBill as $bill){ ?>
								<option value="{{ $bill->id }}">{{ $bill->title }}</option>
								<?php } ?>
							</select>
						</div>
						
						<div class="form-group">
							<label>Kho sách <small class="text-red">*</small></label>
							<select name="bookshop" class="form-control select2">
								<option value="">-Chọn kho-</option>
								<?php foreach($managerShop as $mnshop){ if($mnshop->code == 2){ ?>
								<option value="{{$mnshop->id}}">{{$mnshop->title}}</option>
								<?php } } ?>
							</select>
						</div>
						
						<div class="form-group">
							<label>Nhập từ xưởng</label>
							<select name="workshop" class="form-control select2">
								<option value="">-Chọn xưởng-</option>
								<?php foreach($managerShop as $mnshop){ if($mnshop->code == 1){ ?>
								<option value="{{$mnshop->id}}">{{$mnshop->title}}</option>
								<?php }} ?>
							</select>
						</div>
						
						<div class="form-group">
							<label>Khách hàng</label><span class=" pull-right"><a class="btn btn-xs btn-warning" data-toggle="modal" data-target="#createUsers"><i class="fa fa-plus"></i> Khách hàng</a></span>
							<select id="customer_id" onchange="loadCustomer()" name="customer_id" class="form-control select2">
								<option value="">-Chọn khách hàng-</option>
								<?php foreach($listUsers as $user){ ?>
								<option value="{{$user->id}}">{{ $user->name.' - '.$user->phone }}</option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group">
							<label>Thời gian <small class="text-red">*</small></label>
							<input type="date" class="form-control" name="date_at" placeholder="Tháng/ngày/năm" value="{{ old('date_at') }}">
						</div>
						<div class="form-group">
							<label>Thanh toán <small class="text-red">*</small></label>
							<input type="number" class="form-control" name="payment" id="payment" placeholder="Nhập số tiền" value="{{ old('payment') }}">
							
							<input type="hidden" name="olddebt" id="olddebt" value="0" >
							<input type="hidden" name="debt" id="debt" value="0" >
							<input type="hidden" name="totalbill" id="totalbill" value="{{$TotalPrice}}" >
							<input type="hidden" id="tongsodong" value="{{$index}}" >
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

<div class="modal fade" id="createUsers" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Thêm mới khách hàng</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-md-6">
        		<div class="form-group">
							<div class="form-group">
							  <label>Tên khách hàng <small class="text-red">*</small></label>
							  <input type="text" name="name" class="form-control" required id="name_user" value="" >
							</div>
						</div>
        	</div>
        	<div class="col-md-6">
        		<div class="form-group">
							<div class="form-group">
							  <label>Số ĐT <small class="text-red">*</small></label>
							  <input type="text" name="phone" class="form-control" id="phone_user" value="" >
							</div>
						</div>
        	</div>
        	<div class="col-md-6">
        		<div class="form-group">
							<div class="form-group">
							  <label>Email <small class="text-red">*</small></label>
							  <input type="email" name="email" class="form-control" required id="email_user" value="" >
							</div>
						</div>
        	</div>
        	<div class="col-md-6">
        		<div class="form-group">
							<div class="form-group">
							  <label>Dư nợ <small class="text-red">*</small></label>
							  <input type="number" name="debt" class="form-control" id="debt_user" value="0" >
							</div>
						</div>
        	</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
        <button type="button" onclick="saveUsers()" class="btn btn-primary">Lưu lại</button>
      </div>
    </div>
  </div>
</div>
<style>
.twitter-typeahead{width: 100%;}
.tt-dropdown-menu {
    width: 100%;
    margin-top: 1px;
    padding: 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.2);
    box-shadow: 0 5px 10px rgba(0,0,0,.2);
}
.tt-query, .tt-hint {
    width: 100%;
    height: 33px;
    padding: 8px 12px;
    font-size: 14px;
    outline: none;
}
.tt-suggestion p{
	font-size: 14px;
}
</style>

<script>
	
	function saveUsers() {
		
		$('#customer_id').select2('val','');
    var name = $('#name_user').val();
    var phone = $('#phone_user').val();
    var email = $('#email_user').val();
    var debt = $('#debt_user').val();
		//alert(title);
		
    $.ajax({
        url: '{{ route("users.create_ajax") }}',
        type: 'POST',
        data: {
            '_token': '{{ csrf_token() }}',
            'name': name,
            'phone': phone,
            'email': email,
            'debt': debt
        },
        context: document.body,
    }).done(function(data) {
    	
    	$('#customer_id').append($("<option selected></option>").attr("value", data).text(name)); 
			$('#customer_id').select2('val',data);

			$('#createUsers').modal('hide');
      
    });
	}

	function loadCustomer(){
		var x = document.getElementById("customer_id").value;
		
		$.ajax({
      url: '{{ route('import_book.load_debt') }}',
      type: 'POST',
      data: {
          '_token': '{{ csrf_token() }}',
          'id': x,
      },
      context: document.body,
	  }).done(function(data) {
	      document.getElementById("nocu").innerHTML=data;
	      $('#olddebt').val(data);
	      haveToPay();
	  });

	}

	function haveToPay(){

		var olddebt = $('#olddebt').val();
		var ttb = $("#totalbill").val();
		var dis = $("#discount").val();

		//alert('P'+olddebt+' T'+ttb+' D'+dis);

		var ptt = Number(ttb)+Number(olddebt) - Number(dis);

		document.getElementById("phaithanhtoan").innerHTML=ptt;

		$('#payment').val(ptt);

	}

	$(document).ready(function(){
		
		$('#search-book').typeahead({
			name: 'book',
			remote: "{{ route('document.load_book') }}?q=%QUERY"
		});
		
	});
	
	function RowremoveTab(RowId,id){

		$('.search-book').val('');
		$('.tt-query').val('');
		$('.tt-hint').val('');
		$("#rowTable-"+RowId).remove();

		deleteProduct(id);

		//location.reload();

	};
	
	function deleteProduct(id){
		$.ajax({
      url: '{{ route('import_book.save_delete') }}',
      type: 'POST',
      data: {
          '_token': '{{ csrf_token() }}',
          'id': id,
      },
      context: document.body,
	  }).done(function(data) {
	      location.reload();
	  });
	}

	function InfomationBook(){

		var ttb = $('#totalbill').val();
		var ttq = document.getElementById("TotalQuantity").textContent;
		var qb = document.getElementById("TotalBook").textContent;

		var tsd = $('#tongsodong').val();

		//alert(text);

		var book = $('#search-book').val();
		
		var arr = book.split("||");
		
		var id = arr[0];
		$('.tt-query').val('');
		$('.tt-hint').val('');
		if(id > 0){
			
			ttb = Number(ttb) + Number(arr[1]);
			ttq = Number(ttq)+1;
			qb = Number(qb)+1;
			tsd = Number(tsd)+1;
			var rm = qb+','+id;

			//alert(id);
			var row_ = "<tr id='rowTable-"+qb+"'>"
			+"<td>"+arr[2]+"</td>"
			+"<td><input type='number' id='cost_"+qb+"' name='cost["+id+"]' readonly value='"+arr[1]+"' class='form-control' ></td>"
			+"<td><input type='number' id='quantity_"+qb+"' onclick='updateQuantity("+rm+")' name='quantity["+id+"]' value='1' class='form-control' ></td>"
			+"<td><span id='totalprice-"+qb+"'>"+arr[1]+"</span></td>"
			+"<td class='text-right'><span id='RemoveRow-"+qb+"' onclick='RowremoveTab("+rm+");' style='cursor:pointer'><i class='fa fa-trash-o'></i></span></td>";
			
			$("#ListProduct tbody").append(row_);
			
			$.ajax({
	      url: '{{ route('import_book.save_ajax') }}',
	      type: 'POST',
	      data: {
	          '_token': '{{ csrf_token() }}',
	          'id': id,
	          'cost': arr[1],
	          'name': arr[2],
	          'quantity': 1,
	      },
	      context: document.body,
		  }).done(function(data) {
		      //alert('Lưu dữ liệu thành công');
		  });

		}
		
		$('#totalbill').val(ttb);
		$('#tongsodong').val(tsd);

		document.getElementById("TotalPrice").innerHTML=ttb;
		document.getElementById("TotalQuantity").innerHTML=ttq;
		document.getElementById("TotalBook").innerHTML=qb;
		haveToPay();
	}
	
	function updateQuantity(j,id){

		var rowCount = $('#tongsodong').val();
		//alert(rowCount);
		var tongsoluongMua = 0;
		var tongtienmua = 0;
		var sodong = 0
		for(index=1;index <= rowCount;index++){
			var sl = $("#quantity_"+index).val();
			if (sl) {
				var gia = $("#cost_"+index).val();
				var thanhtien = Number(sl) * Number(gia);
				tongtienmua = Number(tongtienmua)+Number(thanhtien);

				sodong = Number(sodong)+1;

				tongsoluongMua = Number(tongsoluongMua) + Number(sl);
				
				document.getElementById("totalprice-"+index).innerHTML=thanhtien;
			}
		}

		document.getElementById("TotalPrice").innerHTML=tongtienmua;
		document.getElementById("TotalQuantity").innerHTML=tongsoluongMua;
		document.getElementById("TotalBook").innerHTML=sodong;

		$('#totalbill').val(tongtienmua);

		//alert(tongtienmua);

		$.ajax({
      url: '{{ route('import_book.update_ajax') }}',
      type: 'POST',
      data: {
          '_token': '{{ csrf_token() }}',
          'id': id,
          'quantity': $('#quantity_'+j).val(),
      },
      context: document.body,
	  }).done(function(data) {
	      
	  });

	  haveToPay();
	}
	
</script>

@endsection
