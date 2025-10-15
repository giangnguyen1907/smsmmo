@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2).'.create') }}"><i
        class="fa fa-plus"></i> @lang('add_new')</a>
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
      <h3 class="box-title">@lang('update_form')</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form role="form" action="{{ route(Request::segment(2).'.update', $detail->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="box-body">

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab_1" data-toggle="tab">
                <h5>Thông tin chính <span class="text-danger">*</span></h5>
              </a></li>
            <li>
              <a href="#tab_2" data-toggle="tab">
                <h5>Menu truy cập</h5>
              </a></li>
            <button type="submit" class="btn btn-primary btn-sm pull-right">
              <i class="fa fa-floppy-o"></i>
              @lang('save')
            </button>
          </ul>

          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="row">
                <div class="col-md-6">

                  <div class="form-group">
                    <label>@lang('name') <small class="text-red">*</small></label>
                    <input type="text" class="form-control" name="name" placeholder="@lang('name')"
                      value="{{ $detail->name }}" required>
                  </div>

                  <div class="form-group">
                    <label>@lang('iorder')</label>
                    <input type="number" class="form-control" name="iorder" placeholder="@lang('iorder')"
                      value="{{ $detail->iorder }}">
                  </div>

                  <div class="form-group">
                    <label>@lang('status')</label>
                    <div class="form-control">
                      <label>
                        <input type="radio" name="status" value="active"
                          {{ ($detail->status == 'active') ? 'checked':'' }}>
                        <small>@lang('active')</small>
                      </label>
                      <label>
                        <input type="radio" name="status" value="deactive"
                          {{ ($detail->status == 'deactive') ? 'checked':'' }} class="ml-15">
                        <small>@lang('deactive')</small>
                      </label>
                    </div>
                  </div>
                </div>

                <div class="col-md-6">

                  <div class="form-group">
                    <label>@lang('description')</label>
                    <textarea name="description" id="description" class="form-control"
                      rows="5">{{ $detail->description }}</textarea>
                  </div>

                </div>
              </div>

            </div><!-- /.tab-pane -->

            <div class="tab-pane" id="tab_2">
              <div class="row">
                @if (count($activeMenus) == 0)
                <div class="col-12">
                  @lang('not_found')
                </div>
                @else
                @foreach ($activeMenus as $row)

                @if ($row->parent_id == 0 || $row->parent_id == null)
                <div class="col-md-6">
                  <ul class="checkbox_list">
                    @php
                    $checked = '';
                    if (isset($detail->json_access->menu_id) && in_array($row->id, $detail->json_access->menu_id))
                    {
                      $checked = 'checked';
                    }
                    
                    $array_role_action = isset($check_action->json_action) ? (array)$check_action->json_action : '';
                    
                    $arrayRoleAction = array();
                    
                    if(isset($array_role_action[$row->id])){
                      $arrayRoleAction = $array_role_action[$row->id];
                    }else{
                      
                    }
                    
                    $check_index = $check_create = $check_update = $check_delete = $check_show = '';
                    foreach ($arrayRoleAction as $key => $acc_action) {
                      
                      if($acc_action == 'index'){
                        $check_index = 'checked';
                      }else if($acc_action == 'create'){
                        $check_create = 'checked';
                      }else if($acc_action == 'update'){
                        $check_update = 'checked';
                      }else if($acc_action == 'delete'){
                        $check_delete = 'checked';
                      }else if($acc_action == 'show'){
                        $check_show = 'checked';
                      }
                      
                    }

                    ///$check_index = $array_role_action[]

                    @endphp

                    <li>
                      <input name="json_access[menu_id][]" type="checkbox" value="{{ $row->id }}" onchange="checkAllRole({{ $row->id }})" 
                        id="json_access_menu_id_{{ $row->id }}" class="mr-15" {{ $checked }}>
                      <label for="json_access_menu_id_{{ $row->id }}"><strong>{{ $row->name }}</strong></label>
                      <p class="ml-15 mb-0 check_action">
                        <input name="json_action[{{ $row->id }}][]" type="checkbox" value="index"
                          class="check_action_{{ $row->id }}" {{ $check_index }}>
                        <label for="json_action_index_{{ $row->id }}"><strong>Danh sách</strong></label>
                        <input name="json_action[{{ $row->id }}][]" type="checkbox" value="create"
                          class="ml-15 check_action_{{ $row->id }}" {{ $check_create }}>
                        <label for="json_action_create_{{ $row->id }}"><strong>Thêm mới</strong></label>
                        <input name="json_action[{{ $row->id }}][]" type="checkbox" value="update"
                          class="ml-15 check_action_{{ $row->id }}" {{ $check_update }}>
                        <label for="json_action_update_{{ $row->id }}"><strong>Cập nhật</strong></label>
                        <input name="json_action[{{ $row->id }}][]" type="checkbox" value="delete"
                          class="ml-15 check_action_{{ $row->id }}" {{ $check_delete }}>
                        <label for="json_action_delete_{{ $row->id }}"><strong>Xóa</strong></label>
                        <input name="json_action[{{ $row->id }}][]" type="checkbox" value="show"
                          class="ml-15 check_action_{{ $row->id }}" {{ $check_show }}>
                        <label for="json_action_show_{{ $row->id }}"><strong>Chi tiết</strong></label>
                      </p>
                    </li>

                    @foreach ($activeMenus as $sub)
                    @if ($sub->parent_id == $row->id)
                    @php
                    $checked = '';
                    if (isset($detail->json_access->menu_id) && in_array($sub->id, $detail->json_access->menu_id))
                    {
                      $checked = 'checked';
                    }
                    
                    //$array_role_action =  (array)$check_action->json_action;
                    
                    if(isset($array_role_action[$sub->id])){
                      $arrayRoleAction = $array_role_action[$sub->id];
                    }else{
                      $arrayRoleAction = array();
                    }

                    $check_index = $check_create = $check_update = $check_delete = $check_show = '';
                    foreach ($arrayRoleAction as $key => $acc_action) {
                      if($acc_action == 'index'){
                        $check_index = 'checked';
                      }else if($acc_action == 'create'){
                        $check_create = 'checked';
                      }else if($acc_action == 'update'){
                        $check_update = 'checked';
                      }else if($acc_action == 'delete'){
                        $check_delete = 'checked';
                      }else if($acc_action == 'show'){
                        $check_show = 'checked';
                      }
                    }
                    @endphp

                    <li>
                      <input name="json_access[menu_id][]" type="checkbox" value="{{ $sub->id }}" onchange="checkAllRole({{ $sub->id }})"
                        id="json_access_menu_id_{{ $sub->id }}" class="mr-15" {{ $checked }}>
                      <label for="json_access_menu_id_{{ $sub->id }}">- - {{ $sub->name }}</label>
                      <p class="ml-15 mb-0 check_action">
                        <input name="json_action[{{ $sub->id }}][]" type="checkbox" value="index"
                          class="check_action_{{ $sub->id }}" {{ $check_index }}>
                        <label for="json_action_index_{{ $sub->id }}"><strong>Danh sách</strong></label>
                        <input name="json_action[{{ $sub->id }}][]" type="checkbox" value="create"
                          class="ml-15 check_action_{{ $sub->id }}" {{ $check_create }}>
                        <label for="json_action_create_{{ $sub->id }}"><strong>Thêm mới</strong></label>
                        <input name="json_action[{{ $sub->id }}][]" type="checkbox" value="update"
                          class="ml-15 check_action_{{ $sub->id }}" {{ $check_update }}>
                        <label for="json_action_update_{{ $sub->id }}"><strong>Cập nhật</strong></label>
                        <input name="json_action[{{ $sub->id }}][]" type="checkbox" value="delete"
                          class="ml-15 check_action_{{ $sub->id }}" {{ $check_delete }}>
                        <label for="json_action_delete_{{ $sub->id }}"><strong>Xóa</strong></label>
                        <input name="json_action[{{ $sub->id }}][]" type="checkbox" value="show"
                          class="ml-15 check_action_{{ $sub->id }}" {{ $check_show }}>
                        <label for="json_action_show_{{ $sub->id }}"><strong>Chi tiết</strong></label>
                      </p>
                    </li>
                    @endif
                    @endforeach

                  </ul>
                </div>
                @endif

                @endforeach
                @endif

              </div>

            </div><!-- /.tab-pane -->
            
          </div><!-- /.tab-content -->
        </div><!-- nav-tabs-custom -->

      </div>
      <!-- /.box-body -->

      <div class="box-footer">
        <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2).'.index') }}">
          <i class="fa fa-bars"></i> @lang('list')
        </a>
        <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
          @lang('save')</button>
      </div>
    </form>
  </div>
</section>
<script>

function checkAllRole(id){
  var menu_id = $('#json_access_menu_id_'+id+':checked').val();
  if(menu_id > 0){
    $('.check_action_'+id).prop('checked',true);
  }else{
    $('.check_action_'+id).prop('checked',false);
  }
}
</script>
@endsection