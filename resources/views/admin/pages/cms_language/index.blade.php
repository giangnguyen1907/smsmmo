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
          class="fa fa-plus"></i>
        @lang('add_new')</a>
    </h1>
  </section>
@endsection

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="box">
      <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
        <div class="box-header">
          <h3 class="box-title">@lang('list')</h3>

          <div class="box-tools">
            <div class="input-group input-group-sm">
              <input type="text" name="keyword" class="form-control pull-right" placeholder="Tìm theo tên hoặc mã ngôn ngữ"
                value="{{ $keyword }}">

              <div class="input-group-btn">
                <button type="submit" class="btn btn-default" data-toggle="tooltip" title="@lang('search')">
                  <i class="fa fa-search"></i>
                </button>
                <a class="btn btn-primary" href="{{ route(Request::segment(2) . '.index') }}" data-toggle="tooltip"
                  title="@lang('refresh')">
                  <i class="fa fa-refresh"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      </form>

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

        @if (!$rows->total())
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            @lang('not_found')
          </div>
        @else
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>STT</th>
                <th>Mã ngôn ngữ</th>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Trạng thái</th>
                <th>Mặc định</th>
                <th>Cập nhật lúc</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>

              @foreach ($rows as $stt => $row)
                <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                  onsubmit="return confirm('@lang('confirm_action')')">
                  <tr class="valign-middle">
                    <td>
                      {{ $stt+1 }}
                    </td>
                    <td>
                      {{ $row->lang_code }}
                    </td>
                    <td>
                      {{ $row->title }}
                    </td>
                    <td>
                      <img src="{{ $row->image }}">
                    </td>
                    <td>
                      {{ ($row->status == 1) ? 'Hoạt động' : 'Không hoạt động' }}
                    </td>
                    <td>
                      {{ ($row->is_default == 1) ? 'Có' : 'Không' }}
                    </td>
                    <td>
                      {{ $row->updated_at }}
                    </td>
                    <td>
                      <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('update')"
                        data-original-title="@lang('update')"
                        href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                        <i class="fa fa-pencil-square-o"></i>
                      </a>
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip" title="@lang('delete')"
                        data-original-title="@lang('delete')">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                  </tr>
                </form>
              @endforeach

            </tbody>
          </table>
        @endif
      </div>

      @if ($rows->hasPages())
        <div class="box-footer clearfix">
          {{ $rows->withQueryString()->links('admin.pagination.default') }}
        </div>
      @endif
    </div>
  </section>
@endsection
