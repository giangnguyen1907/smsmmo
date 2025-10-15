@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}">
        <i class="fa fa-plus"></i> @lang('Add')
      </a>
    </h1>
  </section>
@endsection

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">@lang('Taxonomy list')</h3>
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
        @if (count($rows) == 0)
          <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            @lang('not_found')
          </div>
        @else
          <table class="table table-hover table-bordered">
            <thead>
              <tr>
                <th>Hình ảnh</th>
                <th>@lang('Họ tên')</th>
                <th>Chức vụ</th>
                <th>@lang('Taxonomy')</th>
                <th>@lang('Updated at')</th>
                <th>@lang('Status')</th>
                <th>@lang('Thao tác')</th>
              </tr>
            </thead>
            <tbody>
              @if ($rows)
                @foreach ($rows as $row)
                  <form action="{{ route(Request::segment(2) . '.destroy', $row->id) }}" method="POST"
                      onsubmit="return confirm('@lang('confirm_action')')">
                      <tr class="valign-middle">
                        <td><img style="height:150px;object-fit: cover;" src="{{ $row->image ?? $row->image ?? '\public\themes\frontend\yenbinh\themes\chuyennt\images\pngwing.com.png' }}"></td>
                        <td>
                          <strong style="font-size: 14px;">{{ $row->title }}</strong>
                        </td>
                        <td>
                          {{ $row->chucvu }}
                        </td>
                        <td>
                          @foreach($taxonomys as $taxonomy)
                            @if($taxonomy->id == $row->taxonomy)
                              {{ $taxonomy->title }}
                            @endif
                          @endforeach
                        </td>
                        <td>
                          {{ $row->updated_at }}
                        </td>
                        <td>
                          @lang($row->status)
                        </td>
                        <td>
                          <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('update')"
                            data-original-title="@lang('update')"
                            href="{{ route(Request::segment(2) . '.edit', $row->id) }}">
                            <i class="fa fa-pencil-square-o"></i>
                          </a>
                          @csrf
                          @method('DELETE')
                          <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip"
                            title="@lang('delete')" data-original-title="@lang('delete')">
                            <i class="fa fa-trash"></i>
                          </button>
                        </td>
                      </tr>
                      </form>
                @endforeach
              @endif
            </tbody>
          </table>
        @endif
      </div>

    </div>
  </section>
@endsection
