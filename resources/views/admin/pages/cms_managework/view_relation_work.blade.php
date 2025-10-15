@extends('admin.layouts.app')

@section('title')
  Danh sách công việc: {{ $module_name }}
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Danh sách công việc: <a style="color: red">{{ $module_name }}</a>
      @if($manage_created_id == Auth::guard('admin')->user()->id)
        <a class="btn btn-sm btn-warning pull-right" href="{{ route('admin.creat_relation_work', ['id' => $manage_id]) }}"> <i class="fa fa-plus"></i> @lang('Add')</a>
      @endif
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
                  @if($manageWork->relationWorks->count() > 0)
                    @foreach($manageWork->relationWorks as $relationWork)
                    @php
                      $userIds = explode(',', $relationWork->user_work);
                      $userCanAccess = in_array(Auth::guard('admin')->user()->id, $userIds) || Auth::guard('admin')->user()->id == $relationWork->admin_created_id || $relationWork->is_public == 1;
                    @endphp
                    @if($userCanAccess)
                      <div class="col-md-4" style="padding-right: 15px;padding-top:15px; background-color: #ebecee;border-right: 1px dotted #c0c1c2;">
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
													<br>
													@if($relationWork->content)
														<p style="font-size:15px"><strong>Nội dung:</strong> {!!$relationWork->content!!}</p>
													@endif
                                                    <p style="font-size:15px"><strong>Người tham gia:</strong></p>
                                                    <div class="row">
                                                      @if($relationWork->is_public == 1)
                                                        <div class="col-md-3 text-center">
                                                            <img src="{{ asset('themes/admin/img/user.png') }}" alt="Tất cả người dùng trong hệ thống" class="img-circle" style="width: 50px;">
                                                            <p style="font-size:15px">Tất cả người dùng</p>
                                                        </div>
                                                      @else
                                                        @foreach($userIds as $userId)
                                                            @php
                                                                $user = \App\Models\Admin::find($userId);
                                                            @endphp
                                                            @if($user)
                                                                <div class="col-md-4 text-center">
                                                                    <img src="{{ $user->avatar ?? asset('themes/admin/img/user.png') }}" alt="{{$user->name}}" class="img-circle" style="width: 50px;" data-toggle="tooltip" data-placement="top" title="{{ $user->email }}">
                                                                    <p style="font-size:15px">{{$user->name}} {{--({{ Illuminate\Support\Str::limit($user->email, 13) }}) --}}</p>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                      @endif
                                                    </div>
                                                    <p style="font-size:15px"><strong>Trạng thái:</strong> 
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
                                                    <p style="font-size:15px"><strong>Thời gian:</strong> {{ date('d-m-Y H:i', strtotime($relationWork->deadline)) }}</p>
													@if($relationWork->fileRelation->count() > 0)
														<p style="font-size:15px"><strong>File đính kèm</strong></p>
														@foreach($relationWork->fileRelation as $file)
															<a style="cursor:pointer;font-size:15px" href="{{ $file->link_file }}" target="_blank" download>{{ $file->name }} <i class="fa fa-download" aria-hidden="true"></i></a><br>
														@endforeach
													@endif
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
                                  <span class="panel-title" style="height: 3em;display: grid;">
									  <a style="display: -webkit-box;
										-webkit-box-orient: vertical;
										overflow: hidden;
										-webkit-line-clamp: 2;line-height: 1.5em;
										height: 3em;">{{ $relationWork->title }}
										</a>
									</span>
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
                                        <img src="{{ asset('themes/admin/img/user.png') }}" alt="User" class="img-circle" style="width: 30px; height: 30px;" data-toggle="tooltip" data-placement="top" title="Tất cả người dùng trong hệ thống"> Tất cả người dùng
                                      @else
                                        @if($relationWork->user_work)
                                          @php
                                              $userIds = explode(',', $relationWork->user_work);
                                              $users = \App\Models\Admin::whereIn('id', $userIds)->get();
                                              $userCount = $users->count();
                                              $maxVisible = 3;
                                          @endphp
                                          @foreach($users as $index => $user)
                                            @if($index < $maxVisible)
                                                <img src="{{ $user->avatar ?? asset('themes/admin/img/user.png') }}" alt="User" class="img-circle" style="width: 30px; height: 30px;object-fit:cover" data-toggle="tooltip" data-placement="top" title="{{ $user->name }}">
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
																  @foreach($historyWork->fileRelationHis as $fileRelationH)
                                                                  <a style="margin: 10px 0 10px;font-size: 14px; cursor:pointer" target="_blank" download href="{{ $fileRelationH->link_file }}">{{ $fileRelationH->name }}</a><br>
																  @endforeach
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
                                                        data-form-id="{{ $relationWork->id }}" action="{{ route('cms_work.store_history_work') }}" style="display: none;" enctype='multipart/form-data'>
                                                        @csrf
                                                        <input type="hidden" value="{{ $relationWork->id }}" name="relation_work_id" id="relation_work_id">
                                                        <input type="hidden" value="{{ $manageWork->id }}" name="manage_work_id" id="manage_work_id">
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
														  <label>Tệp đính kèm</label>
														  <input class="form-control" name="file_work[]" type="file" multiple="multiple" title="Chọn tệp tải lên" placeholder="Chọn tệp tải lên">
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
                            
                          <div class="col-md-12">
                            <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                          </div>
                        </div>
                      @endif
                    @endforeach
                  @endif
                @endforeach
              @endif
            </div>
          </div>

      </div>
      <div class="box-footer">
        <a class="btn btn-success btn-sm" href="/admin/manage_work">
          <i class="fa fa-bars"></i> @lang('List')
        </a>
      </div>
    </div>
  </section>

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
		var formData = new FormData(form[0]);
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
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

</script>
@endsection