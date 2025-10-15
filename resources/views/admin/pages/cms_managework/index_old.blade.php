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

                    <div class="col-md-2">
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
                    </div>

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
          
          <div class="box-body">
            <div class="row">
              @if (count($manageWorks) == 0)
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"
                        aria-hidden="true">&times;</button>
                    @lang('not_found')
                </div>
              @else
                @foreach($manageWorks as $manageWork)
                <div class="col-md-4" style="padding-right: 15px;padding-top:15px; background-color: #ebecee;border-right: 1px dotted #c0c1c2;">
                  <div class="form-group">
                    <label>{{ $manageWork->title }}</label>
                      @if(Auth::guard('admin')->user()->id == $manageWork->admin_created_id)

                      <a type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#editModal{{$manageWork->id}}">
                        <span class="glyphicon glyphicon-pencil"></span>
                      </a>

                        <div class="modal fade" id="editModal{{$manageWork->id}}" data-backdrop="false" role="dialog" aria-hidden="true" style="display: none;">
                          <div class="modal-dialog">
                            <form method="POST" action="{{ route(Request::segment(2) . '.update', $manageWork->id) }}" class="form-horizontal">
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
                                      <input class="form-control" type="text" placeholder="Nhập tên công việc" name="title" id="title" value="{{ $manageWork->title }}">
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

                      <a type="button" class="btn btn-default btn-sm" onclick="destroyManageWork('{{ route('cms_work.destroy_manage_work', ['id' => $manageWork->id]) }}')"><span class="glyphicon glyphicon-trash"></span></a>
                      
                      @endif
                    <br/><br/>
                    @if($manageWork->admin_created_id == Auth::guard('admin')->user()->id)
                      <a class="btn" style="width: 100%;background-color:white" href="{{ route('admin.creat_relation_work', ['id' => $manageWork->id]) }}"> <i class="fa fa-plus" aria-hidden="true"></i> </a>
                    @endif
                  </div>
                  @if($manageWork->relationWorks->count() > 0)
                    @foreach($manageWork->relationWorks as $relationWork)
                      @php
                        // Phân tách chuỗi user_work thành mảng các ID người dùng
                        $userIds = explode(',', $relationWork->user_work);
                        // Kiểm tra xem ID của người dùng hiện tại có trong mảng user_work không
                        $userCanAccess = in_array(Auth::guard('admin')->user()->id, $userIds) || Auth::guard('admin')->user()->id == $relationWork->admin_created_id || $relationWork->is_public == 1;
                      @endphp
                      @if($userCanAccess)
                        <div class="form-group" style="background-color:white">
                           <div class="panel panel-default">
                              <div class="panel-heading" style="background-color: white;border-color: white;">
                                <div class="btn-group pull-right">
                                  {{-- <button type="button" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-eye-open"></span></button> --}}

                                  <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#detailModal{{$relationWork->id}}"><span class="glyphicon glyphicon-eye-open"></span></button>

                                    <div class="modal fade" id="detailModal{{ $relationWork->id }}" data-backdrop="false" role="dialog" aria-hidden="true" style="display: none;">
                                      <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                              <h4 class="modal-title">Xem chi tiết công việc</h4>
                                            </div>
                                            <div class="modal-body">
                                              <div class="row">
                                                <div class="col-md-12">
                                                  <h4>{{$relationWork->title}}</h4>
                                                  <p><strong>Nội dung:</strong> {!!$relationWork->content!!}</p>
                                                  <p><strong>Người tham gia:</strong></p>
                                                  <div class="row">
                                                    @if($relationWork->is_public == 1)
                                                      <div class="col-md-3 text-center">
                                                          <img src="{{ asset('themes/admin/img/user.png') }}" alt="Tất cả người dùng trong hệ thống" class="img-circle" style="width: 50px;">
                                                          <p>Tất cả người dùng</p>
                                                      </div>
                                                    @else
                                                      @foreach($userIds as $userId)
                                                          @php
                                                              $user = \App\Models\Admin::find($userId);
                                                          @endphp
                                                          @if($user)
                                                              <div class="col-md-3 text-center">
                                                                  <img src="{{ asset('themes/admin/img/user.png') }}" alt="{{$user->name}}" class="img-circle" style="width: 50px;" data-toggle="tooltip" data-placement="top" title="{{ $user->email }}">
                                                                  <p>{{$user->name}} ({{ Illuminate\Support\Str::limit($user->email, 13) }})</p>
                                                              </div>
                                                          @endif
                                                      @endforeach
                                                    @endif
                                                  </div>
                                                  <p><strong>Trạng thái:</strong> 
                                                    @if($relationWork->status == 'prioritize')
                                                      <a class="status prioritize">
                                                      @lang($relationWork->status) </a>
                                                    @elseif($relationWork->status == 'processing')
                                                      <a class="status processing">
                                                      @lang($relationWork->status) </a>
                                                    @elseif($relationWork->status == 'complete')
                                                      <a class="status complete">
                                                      @lang($relationWork->status) </a>
                                                    @elseif($relationWork->status == 'reprocess')
                                                      <a class="status reprocess">
                                                      @lang($relationWork->status) </a>
                                                    @endif</p>
                                                  <p><strong>Thời hạn:</strong> {{ date('d-m-Y H:i', strtotime($relationWork->deadline)) }}</p>
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
                                      </div>
                                    </div>


                                  @if(Auth::guard('admin')->user()->id == $relationWork->admin_created_id)

                                    <a type="button" class="btn btn-default btn-sm" href="{{ route('admin.edit_relation_work', ['id' => $relationWork->id]) }}"><span class="glyphicon glyphicon-pencil"></span></a>

                                    <a type="button" class="btn btn-default btn-sm" onclick="deleteRelationWork({{ $relationWork->id }})"><span class="glyphicon glyphicon-trash"></span></a>
                                    
                                  @endif
                                </div>
                              </div>
                              <div class="panel-body" style="padding-top:25px">
                                <h3 class="panel-title">{{ $relationWork->title }}</h3>
                                <div class="text-info" style="margin-top:10px;margin-bottom: 10px;">

                                  
                                    <div class="status-container">
                                      @if($relationWork->status == 'prioritize')
                                          <a class="status prioritize">@lang($relationWork->status)</a>
                                      @elseif($relationWork->status == 'processing')
                                          <a class="status processing">@lang($relationWork->status)</a>
                                      @elseif($relationWork->status == 'complete')
                                          <a class="status complete">@lang($relationWork->status)</a>
                                      @elseif($relationWork->status == 'reprocess')
                                          <a class="status reprocess">@lang($relationWork->status)</a>
                                      @endif

                                      @if($relationWork->admin_created_id == Auth::guard('admin')->user()->id)
                                          <select class="form-control select-status" data-relation-work-id="{{ $relationWork->id }}">
                                              <option value="">Thay đổi trạng thái</option>
                                              @foreach ($status_work as $key => $value)
                                                  <option value="{{ $key }}">
                                                      {{ $value }}
                                                  </option>
                                              @endforeach
                                          </select>
                                      @endif
                                  </div>

                                </div>
                                <div class="row mt-3">
                                  <div class="col-xs-6">
                                    @if($relationWork->is_public == 1)
                                      Tất cả người dùng
                                    @else
                                      @if($relationWork->user_work)
                                        @php
                                            $userIds = explode(',', $relationWork->user_work);
                                            $users = \App\Models\Admin::whereIn('id', $userIds)->get();
                                            $userCount = $users->count();
                                            $maxVisible = 4;
                                        @endphp
                                        @foreach($users as $index => $user)
                                          @if($index < $maxVisible)
                                              <img src="{{ asset('themes/admin/img/user.png') }}" alt="User" class="img-circle" style="width: 30px; height: 30px;" data-toggle="tooltip" data-placement="top" title="{{ $user->name }}">
                                          @elseif($index == $maxVisible)
                                              <span>+ {{ $userCount - $maxVisible }} Khác</span>
                                              @break
                                          @endif
                                        @endforeach
                                      @endif
                                    @endif
                                  </div>
                                  <div class="col-xs-4">
                                    <div class="text-muted" style="margin-top: 5px">{{ date('d-m-Y', strtotime($relationWork->deadline)) }}</div>
                                  </div>
                                  <div class="col-xs-2">
                                    <a type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#historyModal{{ $relationWork->id }}">
                                      <span class="glyphicon glyphicon-comment"></span> 
                                      {{ $relationWork->historyWorks->count() }}
                                    </a>
                                    <div class="modal fade" id="historyModal{{ $relationWork->id }}" data-backdrop="false" role="dialog" aria-hidden="true" style="display: none;">
                                      <div class="modal-dialog">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                              <h4 class="modal-title">Lịch sử báo cáo công việc: <span style="color:red">{{ $relationWork->title }}</span></h4>
                                            </div>
                                            <div class="modal-body">
                                              <div class="scrollable-list">
                                              <ul class="list-group">
                                                  @foreach($relationWork->historyWorks as $historyWork)
                                                    <li class="list-group-item">
                                                        <div class="media">
                                                            <div class="media-left">
                                                                <img src="{{ asset('themes/admin/img/user.png') }}" alt="Avatar" class="media-object" style="width: 50px;">
                                                            </div>
                                                            <div class="media-body">
                                                                <h4 class="media-heading" style="margin-bottom: 10px">{{ $historyWork->user->name }} ({{ $historyWork->user->email }}) </h4>
                                                                @if($historyWork->status == 'prioritize')
                                                                  <a class="status prioritize">
                                                                  @lang($historyWork->status) </a>
                                                                @elseif($historyWork->status == 'processing')
                                                                  <a class="status processing">
                                                                  @lang($historyWork->status) </a>
                                                                @elseif($historyWork->status == 'complete')
                                                                  <a class="status complete">
                                                                  @lang($historyWork->status) </a>
                                                                @elseif($historyWork->status == 'reprocess')
                                                                  <a class="status reprocess">
                                                                  @lang($historyWork->status) </a>
                                                                @endif
                                                                <p style="margin: 10px 0 10px;font-size: 15px">{{ $historyWork->comment }}</p>
                                                                <p style="margin: 10px 0 10px;">{{ $historyWork->file }}</p>
                                                                <p style="margin: 10px 0 10px;"><small class="text-muted">{{ date('d-m-Y H:i', strtotime($historyWork->created_at)) }}</small></p>
                                                            </div>
                                                        </div>
                                                    </li>
                                                  @endforeach
                                              </ul>
                                              </div>
                                              <div class="form-group">
                                                <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                              </div>
                                              <ul class="list-group">
                                                <li class="list-group-item">
                                                  <div class="row">
                                                      <div class="col-sm-6">
                                                          <h4 style="margin-bottom: 10px">Báo cáo công việc</h4>
                                                      </div>
                                                      <div class="col-sm-6 text-right">
                                                          <button type="button" class="btn btn-primary" id="toggleCommentForm{{ $relationWork->id }}" onclick="toggleCommentForm({{ $relationWork->id }})">Thêm mới</button>
                                                      </div>
                                                  </div>
                                                  <div id="commentFormWrapper{{ $relationWork->id }}">
                                                    <form id="commentForm{{ $relationWork->id }}" method="POST" 
                                                      data-form-id="{{ $relationWork->id }}" action="{{ route('cms_work.store_history_work') }}" style="display: none;">
                                                      @csrf
                                                      <input type="hidden" value="{{ $relationWork->id }}" name="relation_work_id" id="relation_work_id">
                                                      <div class="form-group">
                                                        <label>Trạng thái </label>
                                                        <select name="status" id="status" class="form-control select2">
                                                          <option value="">Vui lòng chọn</option>
                                                          @foreach ($status_work as $key => $value)
                                                            <option value="{{ $key }}">
                                                                {{ $value }}
                                                            </option>
                                                          @endforeach
                                                        </select>
                                                      </div>
                                                      <div class="form-group">
                                                          <label for="comment">Nội dung:</label>
                                                          <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                                                      </div>
                                                      <button type="submit" class="btn btn-primary" id="sendComment">Gửi</button>
                                                    </form>
                                                  </div>
                                                </li>
                                              </ul>
                                            </div>
                                            <div class="modal-footer">
                                              <div class="form-group">
                                                <div class="col-md-12 text-center">
                                                  <button type="button" class="btn btn-default " data-dismiss="modal">Đóng lại</button>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                          </div>
                        </div>
                      @endif
                    @endforeach
                  @endif
                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>
                </div>  
                @endforeach
              @endif
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
  $(document).ready(function () {

    $('.select-status').change(function () {
        var relationWorkId = $(this).data('relation-work-id');
        var newStatus = $(this).val();
        $.ajax({
            url: '{{ route('cms_work.update_status_relation_work') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                relation_work_id: relationWorkId,
                status: newStatus
            },
            success: function (response) {
                // alert('Cập nhật trạng thái công việc thành công.');
                window.location.reload();
            },
            error: function (xhr, status, error) {
                alert('Cập nhật trạng thái công việc không thành công, vui lòng thử lại.'); 
            }
        });
    });

    $('#sendComment').click(function () {
        var formId = $(this).data('form-id'); 
        var form = $('#commentForm' + formId); 
        var url = form.attr('action'); 
        $.ajax({
            type: 'POST',
            url: url,
            data: form.serialize(), 
            success: function (data) {
                if (response.success) {
                    alert(response.message);
                    window.location.reload();
                } else {
                    alert('Đã xảy ra lỗi, vui lòng thử lại sau.'); 
                }
            },
            error: function (data) {
                console.error(xhr.responseText);
                alert('Đã xảy ra lỗi, vui lòng thử lại sau.');
            }
        });
    });

  });

  function toggleCommentForm(id){
    var form = $('#commentForm' + id);
        if (form.is(':visible')) {
            form.hide();
            $(this).text('Mở form');
        } else {
            form.show();
            $(this).text('Đóng form');
        }
  }

  function deleteRelationWork(id){
    if(confirm("Bạn chắc chắn muốn xóa công việc này?")) {
      var f = "id=" + id ;
      var _url = "{{ route('cms_posts.destroy_relation_work') }}";
      $.ajax({
        url: _url,
        data: f,
        processData: false,
        contentType: false,
        success: function(data) {
            if (data.success) {
                window.location.reload();
            } else {
                alert("Đã xảy ra lỗi khi xóa công việc. Vui lòng thử lại sau.");
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            alert("Đã xảy ra lỗi khi xóa công việc. Vui lòng thử lại sau.");
        }
      });
    }
  }

  function destroyManageWork(link_delete){
      if(confirm("Xóa công việc này bao gồm xóa các công việc bên trong, bạn có chắc chắn xóa?")){
        window.location.href = link_delete;
      }
  }
</script>
@endsection