@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content-header')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2).'.create') }}"><i
        class="fa fa-plus"></i> @lang('Add')</a>
  </h1>
</section>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  <div class="box">
    <div class="box-header">
      <h3 class="box-title">@lang('Block list')</h3>
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
        @lang('No record found')
      </div>
      @else
      <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>@lang('Name')</th>
            <th>@lang('Block code')</th>
            <th>@lang('Is config')</th>
            <th>@lang('Order')</th>
            <th>@lang('Updated at')</th>
            <th>@lang('Status')</th>
            <th>@lang('Action')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($rows as $row)

          <form action="{{ route(Request::segment(2).'.destroy', $row->id) }}" method="POST"
            onsubmit="return confirm('@lang('confirm_action')')">
            <tr class="valign-middle">
              <td>
                <a href="{{ route(Request::segment(2).'.edit', $row->id) }}">
                  <strong style="font-size: 14px">{{ $row->name }}</strong>
                </a>
                <br>
                {{ $row->description }}
              </td>
              <td>
                {{ $row->block_code }}
              </td>
              <td>
                @lang(App\Consts::TITLE_BOOLEAN[$row->is_config])
              </td>
              <td>
                {{ $row->iorder }}
              </td>
              <td>
                {{ $row->updated_at }}
              </td>
              <td>
                @lang($row->status)
              </td>
              <td>
                <a class="btn btn-sm btn-warning" data-toggle="tooltip" title="@lang('Update')"
                  data-original-title="@lang('update')" href="{{ route(Request::segment(2).'.edit', $row->id) }}">
                  <i class="fa fa-pencil-square-o"></i>
                </a>
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit" data-toggle="tooltip" title="@lang('Delete')"
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

    {{ $rows->links() }}

    @if ($rows->hasPages())
    <div class="box-footer clearfix">
      {{-- {{ $rows->withQueryString()->links('pagination.default') }} --}}
    </div>
    @endif

  </div>
</section>
@endsection