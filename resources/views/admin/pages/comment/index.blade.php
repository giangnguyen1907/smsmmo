@extends('admin.layouts.app')

@section('title')
{{ $module_name }}
@endsection

@section('content-header')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    {{ $module_name }}
    
  </h1>
</section>
@endsection

@section('content')

<!-- Main content -->
<section class="content">
  {{-- Search form --}}
  <div class="box box-default">

    <div class="box-header with-border">
      <h3 class="box-title">@lang('Filter')</h3>
      <div class="box-tools pull-right">
        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
      </div>
    </div>
    <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
      <div class="box-body">
        <div class="row">
          <div class="col-md-2">
            <div class="form-group">
              <label>Loại bình luận</label>
              <select name="is_type" id="is_type" class="form-control select2" style="width: 100%;">
                <option value="">@lang('Please select')</option>
                @foreach (App\Consts::IS_TYPE_COMMENT as $key => $value)
                  <option value="{{ $key }}"
                    {{ isset($params['is_type']) && $key == $params['is_type'] ? 'selected' : '' }}>
                    {{ __($value) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label>Trạng thái</label>
              <select name="status" id="status" class="form-control select2" style="width: 100%;">
                <option value="">@lang('Please select')</option>
                @foreach (App\Consts::STATUS_COMMENT as $key => $value)
                  <option value="{{ $key }}"
                    {{ isset($params['status']) && $key == $params['status'] ? 'selected' : '' }}>
                    {{ __($value) }}</option>
                @endforeach
              </select>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label>Từ khóa</label>
              <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
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
    <div class="box-header">
      <h3 class="box-title">@lang('Danh sách bình luận')</h3>
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
            <th>@lang('Bài viết')</th>
            <th>@lang('Người bình luận')</th>
            <th>@lang('Nội dung bình luận')</th>
            <th style="min-width: 115px;">@lang('Trạng thái')</th>
            <th>@lang('Updated at')</th>
            <th style="min-width: 80px">@lang('Action')</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($rows as $row)

          <form action="{{ route(Request::segment(2).'.destroy', $row->id) }}" method="POST"
            onsubmit="return confirm('@lang('confirm_action')')">
            <tr class="valign-middle">
              <td>
                {{ $row->post_title }}
              </td>
              <td>
                {{ $row->member_name }}
              </td>
              <td>
                {!! App\Http\Services\ContentService::stringTruncate ( $row->content, 200) !!}
                <a href="{{ route(Request::segment(2).'.edit', $row->id) }}" class="btn btn-default btn-xs txt-color-blueLight" data-toggle="tooltip" data-original-title="Xem chi tiết"
                rel="popover-hover"
                data-html="true"><i class="fa fa-eye"></i></a>
              </td>
              <td>
                <select class="form-control" name="" id="apcept_comment_{{ $row->id }}" onchange="updateStatusComment({{ $row->id, $row->is_type }})">
                  @foreach(App\Consts::STATUS_COMMENT as $key=>$status_comment)
                  <option value="{{ $key }}" {{ $row->status == $key ? 'selected' : '' }}>{{ $status_comment }}</option>
                  @endforeach
                </select>
                <img id="ic-loading_{{ $row->id }}" style="display: none;vertical-align: middle;" src="/images/load.gif" width="20px">
              </td>
              <td>
                {{$row->updated_at}}
              </td>
              <td class="text-center">
                <a class="btn btn-xs btn-warning" data-toggle="tooltip" title="@lang('Update')"
                  data-original-title="@lang('update')" href="{{ route(Request::segment(2).'.edit', $row->id) }}" style="margin-right: 5px;">
                  <i class="fa fa-pencil-square-o"></i>
                </a>
                @csrf
                @method('DELETE')
                <button class="btn btn-xs btn-danger" type="submit" data-toggle="tooltip" title="@lang('Delete')"
                  data-original-title="@lang('delete')" >
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
<script type="text/javascript">

  // lấy is_type từ url
  function getIsType() {
    const currentUrl = window.location.search;
    const searchParams = new URLSearchParams(currentUrl);
    var isType = searchParams.get('is_type');
    return isType;
  }
  let isType = getIsType();

  function updateStatusComment(id){
    var status = $('#apcept_comment_'+id).val();
    $('#ic-loading_'+id).show();
    $.ajax({
      url: '{{ route('cms_posts.update_comment_status') }}',
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        status: status,
        id: id,
        is_type: isType,
      },
      context: document.body,
    }).done(function(data) {
      $('#ic-loading_'+id).hide();
    });
  }
</script>

@endsection