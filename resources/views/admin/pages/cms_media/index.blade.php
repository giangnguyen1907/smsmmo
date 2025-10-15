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

@php
$array_location = APP\Consts::POST_POSITION;
@endphp

@section('content')
@php 
$array_category = array();
foreach ($parents as $item){
  $array_category[$item->id] = $item->title;
}
$array_location = APP\Consts::POST_POSITION;
@endphp
  <!-- Main content -->
  <section class="content">
    {{-- Search form --}}
    <div class="box box-default">

      
      <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
        <input type="hidden" name="task" value="{{ $_REQUEST['task'] }}" />
        <div class="box-body">
          <div class="row">

            <div class="col-md-3">
              <div class="form-group">
                <label>@lang('Keyword') </label>
                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                  value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
              </div>
            </div>

            <div class="col-md-2">
              <div class="form-group">
                <label>Trạng thái</label>
                  <select name="status" id="status" class="form-control select2">
                    <option value="">-Chọn trạng thái-</option>
                    @foreach(APP\Consts::POST_STATUS_TEXT as $key=>$trangthai)
                    <option value="{{ $key }}" {{ ( isset($params['status']) && $params['status'] == $key) ? 'selected' : '' }}>{{ $trangthai }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>@lang('Chọn loại tin') <small class="text-red">*</small></label>
                <select name="news_position" class="form-control" id="news_position">
                  <option value="">-Chọn vị trí-</option>
                  @foreach($array_location as $key => $position_text)
                  <option value="{{ $key }}" {{ ( isset($params['news_position']) && $params['news_position'] == $key) ? 'selected' : '' }}>{{ $position_text }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            {{-- @if($hidden == 0)
            <div class="col-md-2">
              <div class="form-group">
                <label>Tác giả</label>
                  <select name="admin_created_id" id="admin_created_id" class="form-control select2">
                    <option value="">-Chọn tác giả-</option>
                    @foreach($admins as $admin)
                    <option value="{{ $admin->id }}" {{ ( isset($params['admin_created_id']) && $params['admin_created_id'] == $admin->id) ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            @endif
            <div class="col-md-2">
              <div class="form-group">
                <label>Người duyệt</label>
                <select name="admin_updated_id" id="admin_updated_id" class="form-control select2">
                  <option value="">-Chọn tác giả-</option>
                  @foreach($admins as $admin)
                  <option value="{{ $admin->id }}" {{ ( isset($params['admin_updated_id']) && $params['admin_updated_id'] == $admin->id) ? 'selected' : '' }}>{{ $admin->name }}</option>
                  @endforeach
                </select>
              </div>
            </div> --}}
            
            <div class="col-md-3">
              <div class="form-group">
                <label>@lang('Post category')</label>
                <select name="taxonomy_id" id="taxonomy_id" class="form-control select2" style="width: 100%;">
                  <option value="">@lang('Please select')</option>
                  @foreach ($parents as $item)
                    @if ($item->parent_id == 0 || $item->parent_id == null)
                      <option value="{{ $item->id }}"
                        {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $item->id ? 'selected' : '' }}>
                        {{ $item->title }}</option>
                      @foreach ($parents as $sub)
                        @if ($item->id == $sub->parent_id)
                          <option value="{{ $sub->id }}"
                            {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $sub->id ? 'selected' : '' }}>
                            - -
                            {{ $sub->title }}
                          </option>
                          @foreach ($parents as $sub_child)
                            @if ($sub->id == $sub_child->parent_id)
                              <option value="{{ $sub_child->id }}"
                                {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $sub_child->id ? 'selected' : '' }}>
                                - - - -
                                {{ $sub_child->title }}</option>
                            @endif
                          @endforeach
                        @endif
                      @endforeach
                    @endif
                  @endforeach
                </select>
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
    {{-- End search form --}}
    
    <div class="box">
      <div class="nav-tabs-custom">
      
      <div class="tab-content">
        <div class="tab-pane active">
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

          @if (count($rows) == 0)
            <div class="alert alert-warning alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              @lang('not_found')
            </div>
          @else
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>@lang('STT')</th>
                  <th>@lang('Title')</th>
                  <th>@lang('Người tạo')</th>
                  {{-- <th>@lang('Cập nhật')</th> --}}
                  <th>@lang('Post category')</th>
                  <th>@lang('Loại tin')</th>
                  <th>@lang('Xuất bản')</th>
                  <th width="120px">@lang('Thao tác')</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rows as $stt=>$row)
                  @if ($row->parent_id == 0 || $row->parent_id == null)

                  @php 
                  if(strtotime($row->aproved_date) > time()){
                    $class_e = 'post_waiting';
                  }else{
                    $class_e = '';
                  }
                  @endphp
                    <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                      onsubmit="return confirm('@lang('confirm_action')')">
                      <tr class="valign-middle {{ $class_e }}">
                        <td class="text-center">
                          {{ $stt+1 }}
                        </td>
                        <td>
                          <a href="{{ route(Request::segment(2) . '.edit', $row->id) }}" style="font-size: 14px;">{{ $row->title }}</a>
                        </td>
                        <td>
                          {{ $row->fullname }}
                        </td>
                        {{-- <td>
                          {{ $row->admin_name }}
                        </td> --}}
                        <td>
							{{ $row->taxonomy_title }} 
                        </td>
                        <td>
                          @lang($array_location[$row->news_position])
                        </td>
                        <td>
                          {{ date('H:i d/m/Y',strtotime($row->aproved_date)) }}
                        </td>
                        @php
                          $url_mapping = url('');
                          $url_mapping .= '/'.APP\Consts::ROUTE_PREFIX_TAXONOMY['post_detail'] .'/';
                          $url_mapping .= $row->url_part;
                          $url_mapping .= '.html';
                        @endphp
                        <td class="text-center">
                          <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="@lang('Cập nhật tin bài')"
                            data-original-title="@lang('Cập nhật tin bài')"
                            href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                            <i class="fa fa-pencil-square-o"></i>
                          </a>
                          <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip" title="@lang('Link')"
                            data-original-title="@lang('Link')">
                            <span class="btn btn-xs btn-flat btn-info">
                              <i class="fa fa-link"></i>
                            </span>
                          </a>
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-xs btn-danger" type="submit" data-toggle="tooltip"
                            title="@lang('Gỡ bài đăng')" data-original-title="@lang('Gỡ bài đăng')">
                            <i class="fa fa-trash"></i>
                          </button>
                          
                        </td>
                      </tr>
                    </form>
                  @endif
                @endforeach
              </tbody>
            </table>
          @endif
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
        
      </div>

    </div>

    </div>
  </section>
@endsection
