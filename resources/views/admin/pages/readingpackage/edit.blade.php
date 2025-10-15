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
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Tên gói<small class="text-red">*</small></label>
                      <input type="text" class="form-control" name="title" placeholder="Tên gói"
                        value="{{ $detail->title ?? old('title') }}" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Loại tài khoản<small class="text-red">*</small></label>
                      <select name="is_type" class="form-control">
						<option value="0" <?php if($detail->is_type==0) echo 'selected';?>>Cá nhân</option>
						<option value="1" <?php if($detail->is_type==1) echo 'selected';?>>Tổ chức</option>
					  </select>
                    </div>
                  </div>
				  <div class="col-md-6">
                    <div class="form-group">
                      <label>Chuyên mục<small class="text-red">*</small></label>
                      <select name="category[]" id="category" multiple class="form-control select2" style="width: 100%">
                        <?php 
						$txt_category = trim($detail->category,',');
						$arr_category = explode(',',$txt_category);
						?>
                        @foreach ($parents as $item)
                          @if ($item->parent_id == 0 || $item->parent_id == null)
                            <option value="{{ $item->id }}"  <?php if(in_array($item->id ,$arr_category)) echo 'selected'; ?> >
                              {{ $item->title->$locale }}</option>

                            @foreach ($parents as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}"
                                  <?php if(in_array($sub->id ,$arr_category)) echo 'selected'; ?>>- - {{ $sub->title->$locale }}
                                </option>

                                @foreach ($parents as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}"
                                      <?php if(in_array($sub_child->id ,$arr_category)) echo 'selected'; ?>>- - - -
                                      {{ $sub_child->title->$locale }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>
                    </div>
                  </div>
				  
					<div class="col-md-6">
						<div class="form-group">
							<label>Trạng thái <small class="text-red">*</small></label>
							<div class="form-control">
								<label>
									<input type="radio" name="status" value="1" <?php if($detail->status==1) echo 'checked';?>>
									<small>@lang('active')</small>
								</label>
								<label>
									<input type="radio" name="status" value="0" <?php if($detail->status==0) echo 'checked';?>
										class="ml-15">
									<small>@lang('deactive')</small>
								</label>
							</div>
						</div>
					</div>
				  
					<div class="col-md-3">
						<div class="form-group">
							<div class="form-control">
								<label>
									<input type="checkbox" name="is_checkip" value="1" <?php if($detail->is_checkip==1) echo 'checked';?>>
									<small>Giới hạn IP</small>
								</label>
							</div>
						</div>
					</div>
				  
					<div class="col-md-3">
						<div class="form-group">
							<div class="form-control">
								<label>
									<input type="checkbox" name="is_ebook" value="1" <?php if($detail->is_ebook==1) echo 'checked';?>>
									<small>Ebook</small>
								</label>
							</div>
						</div>
					</div>
				  
					<div class="col-md-3">
						<div class="form-group">
							<div class="form-control">
								<label>
									<input type="checkbox" name="is_audio" value="1" <?php if($detail->is_audio==1) echo 'checked';?>>
									<small>Audio</small>
								</label>
							</div>
						</div>
					</div>
				  
					<div class="col-md-3">
						<div class="form-group">
							<div class="form-control">
								<label>
									<input type="checkbox" name="is_video" value="1" <?php if($detail->is_video==1) echo 'checked';?>>
									<small>Video</small>
								</label>
							</div>
						</div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
						  <label>Giá cá nhân</label>
						  <input type="number" class="form-control" name="price" placeholder="Giá cá nhân"
							value="{{  $detail->price ?? old('price') }}" required>
						</div>
					</div>
				  
					<div class="col-md-6">
						<div class="form-group">
						  <label>Số ngày đọc cá nhân</label>
						  <input type="text" class="form-control" name="number_day" placeholder="Số ngày đọc cá nhân"
							value="{{ $detail->number_day ?? old('number_day') }}">
						</div>
					</div>
				  
					<div class="col-md-6">
						<div class="form-group">
						  <label>Thành tiền</label>
						  <input type="text" class="form-control" name="total_price" placeholder="Thành tiền"
							value="{{  $detail->total_price ?? old('total_price') }}">
						</div>
					</div>
				  
					<div class="col-md-6">
						<div class="form-group">
						  <label>Thanh toán</label>
						  <input type="text" class="form-control" name="payment" placeholder="Thanh toán"
							value="{{  $detail->payment ?? old('payment') }}">
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

