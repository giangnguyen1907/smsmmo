@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" data-toggle="modal" data-target="#showCNTT">
        <i class="fa fa-plus"></i> @lang('Add')
      </a>
    </h1>
  </section>
@endsection

@section('content')

  <style>
    a.status.prioritize{
      padding: 5px;
      border-radius: 10%;
      background-color: red;
      color:white;
    }
    a.status.processing{
      padding: 6px;
      border-radius: 5%;
      background-color: #00A157;
      color:white;
    }
    a.status.complete{
      padding: 5px;
      border-radius: 10%;
      background-color: #f39c12;
      color:white;
    }
    a.status.reprocess{
      padding: 5px;
      border-radius: 10%;
      background-color: black;
      color:white;
    }
    .scrollable-list {
      max-height: 300px;
      overflow-y: auto;
    }
    .status-container {
      display: flex;
      align-items: center;
      text-align: right;  
    }

    .status {
        margin-right: 10px; 
    }

    .select-status { 
        width: auto;
    }
  </style>

  @php
    $status_work = App\Consts::STATUS_WORK;
  @endphp

  <!-- Main content -->
  <section class="content">
    <div class="box box-default">
        <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
            <div class="box-body">
                <div class="row">

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>@lang('Keyword') </label>
                            <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                        </div>
                    </div>

                    {{-- <div class="col-md-2">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="status_relationWork" id="status_relationWork" class="form-control select2">
                                <option value="">-Chọn trạng thái-</option>
                                @foreach ($status_work as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ isset($params['status_relationWork']) && $params['status_relationWork'] == $key ? 'selected' : '' }}>
                                        @lang($value)</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Người tham gia</label>
                            <select name="user_id" id="user_id" class="form-control select2">
                                <option value="">-Chọn người tham gia-</option>
                                @foreach ($admins as $key => $value)
                                    <option value="{{ $value->id }}"
                                        {{ isset($params['user_id']) && $params['user_id'] == $value->id ? 'selected' : '' }}>
                                        {{ $value->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Từ ngày</label>
                            <input type="date" class="form-control" name="from_date"
                                value="{{ isset($params['from_date']) ? $params['from_date'] : '' }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                      <div class="form-group">
                          <label>Đến ngày</label>
                          <input type="date" class="form-control" name="to_date"
                              value="{{ isset($params['to_date']) ? $params['to_date'] : '' }}">
                      </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>@lang('Filter')</label>
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

    <div class="box">

      <div class="box-header">
          <h3 class="box-title">@lang('Danh sách công việc')</h3>
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

        @if (count($manageWorks) == 0)
          <div class="alert alert-warning alert-dismissible">
              <button type="button" class="close" data-dismiss="alert"
                  aria-hidden="true">&times;</button>
              @lang('not_found')
          </div>
        @else
          <table class="table table-hover table-bordered">
            <thead>
                <tr>
                    <th>@lang('STT')</th>
                    <th>@lang('Tên công việc')</th>
                    <th>@lang('Trạng thái')</th>
                    <th>@lang('Loại công việc')</th>
                    <th>@lang('Người cập nhật')</th>
                    <th>@lang('Updated')</th>
                    <th style="min-width: 80px">@lang('Action')</th>
                </tr>
            </thead>
            <tbody>
              @foreach ($manageWorks as $stt => $row)
                <tr class="valign-middle">
                    <td>
                        {{ $stt + 1 }}
                    </td>
                    <td>
                        {{ $row->title }}
                    </td>
                    <td>
                        @lang($row->status)
                    </td>
                    <td>
                        {{ $row->is_public == 0 ? 'Nội bộ' : 'Công khai' }}
                    </td>
                    <td>
                        {{ $row->admin->name }}
                    </td>
                    <td>
                        {{ date('H:i d/m/Y', strtotime($row->updated_at)) }}
                    </td>
                    <td class="text-center">
                        
                        <a class="btn btn-sm btn-info" href="{{ route('admin.view_relation_work', ['id' => $row->id]) }}"><i class="fa fa-eye"></i></a>

                        @if(Auth::guard('admin')->user()->id == $row->admin_created_id)
                          <a type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal{{$row->id}}">
                            <i class="fa fa-pencil-square-o"></i>
                          </a>

                          <div class="modal fade" id="editModal{{$row->id}}" data-backdrop="false" role="dialog" aria-hidden="true" style="display: none;">
                          <div class="modal-dialog">
                            <form method="POST" action="{{ route(Request::segment(2) . '.update', $row->id) }}" class="form-horizontal">
                              @csrf
                              @method('PUT')
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                  <h4 class="modal-title">Cập nhật công việc</h4>
                                </div>
                                <div class="modal-body">
                                  <div class="form-group">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-8">
                                      <input class="form-control" type="text" placeholder="Nhập tên công việc" name="title" id="title" value="{{ $row->title }}">
                                    </div>
                                    <div class="col-md-2">
                                      <button type="submit" class="btn btn-success" id="updateManageWork">Cập nhật</button>
                                    </div>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <div class="form-group">
                                    <div class="col-md-12 text-center">
                                      <button type="button" class="btn btn-default " data-dismiss="modal">Đóng lại</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </form>
                          </div>
                        </div>

                        <a type="button" class="btn btn-sm btn-danger" onclick="destroyManageWork('{{ route('cms_work.destroy_manage_work', ['id' => $row->id]) }}')"><i class="fa fa-trash"></i></a>
                        @endif
                    </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif

      </div>
      <div class="box-footer clearfix">
        <div class="row">
          <div class="col-sm-5">
            Tìm thấy {{ $manageWorks->total() }} kết quả
          </div>
          <div class="col-sm-7">
            {{ $manageWorks->withQueryString()->links('admin.pagination.default') }}
          </div>
        </div>
      </div>
    
    </div>
  </section>

<div class="modal fade" id="showCNTT" data-backdrop="false" role="dialog" aria-hidden="true" style="display: none;"><div class="modal-dialog">
  <form method="POST" action="{{ route(Request::segment(2) . '.store') }}" class="form-horizontal">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Thêm công việc</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <div class="col-md-1"></div>
          <div class="col-md-8">
            <input class="form-control" type="text" placeholder="Nhập tên công việc" name="title" id="title" >
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-success">Thêm mới</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="form-group">
          <div class="col-md-12 text-center">
            <button type="button" class="btn btn-default " data-dismiss="modal">Đóng lại</button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
</div>
@endsection

@section('script')

<script>
  function destroyManageWork(link_delete){
      if(confirm("Xóa công việc này bao gồm xóa các công việc bên trong, bạn có chắc chắn xóa?")){
        window.location.href = link_delete;
      }
  }
</script>
@endsection