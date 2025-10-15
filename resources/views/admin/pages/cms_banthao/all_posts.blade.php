@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content-header')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
            <a class="btn btn-sm btn-warning pull-right" href="{{ route('cms_posts.create') }}"><i class="fa fa-plus"></i>
                @lang('Add')</a>
        </h1>
    </section>
@endsection

@php
    $array_position = App\Consts::POST_POSITION_SORT;
    $array_location = App\Consts::POST_POSITION;
@endphp

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

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Keyword') </label>
                                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                                    value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Vị trí') </label>
                                <select class="form-control" name="news_position" id="news_position">
                                    @foreach ($array_location as $key2 => $val)
                                        <option value="{{ $key2 }}"
                                            {{ (isset($params['news_position']) and $params['news_position']) == $key2 ? 'selected' : '' }}>
                                            {{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>@lang('Post category')</label>
                                <select name="taxonomy_id" id="taxonomy_id" class="form-control select2"
                                    style="width: 100%;">
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
        @php
            $array_category = [];
            foreach ($parents as $item) {
                $array_category[$item->id] = $item->title;
            }
        @endphp
        <div class="box">
            <div class="nav-tabs-custom">

                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <form id="form_news_update_iorder" action="" method="post">
                            @csrf
                            @if (session('errorMessage'))
                                <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('errorMessage') }}
                                </div>
                            @endif
                            @if (session('successMessage'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('successMessage') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>

                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach

                                </div>
                            @endif

                            @if (count($rows) == 0)
                                <div class="alert alert-warning alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    @lang('not_found')
                                </div>
                            @else
                                <table id="table-2" class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>@lang('STT')</th>
                                            <th>@lang('Title')</th>
                                            <th>@lang('Loại Tin')</th>
                                            <th>@lang('Vị trí')</th>
                                            <th>@lang('Post category')</th>
                                            <th>@lang('Xuất bản')</th>
                                            <th width="120px">@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $stt = 0; ?>
                                        @foreach ($rows as $row)
                                            @if ($row->parent_id == 0 || $row->parent_id == null)
                                                <?php $stt++; ?>
                                                <form action="{{ route('cms_posts.destroy', $row->id) }}" method="POST"
                                                    onsubmit="return confirm('@lang('confirm_action')')">
                                                    <tr id="{{ $row->id }}" class="valign-middle">
                                                        <td class="text-center">
                                                            {{ $stt }}
                                                        </td>
                                                        <td>
                                                            <strong style="font-size: 14px;">{{ $row->title }}</strong>
                                                        </td>
                                                        <td>
                                                            {{ $row->is_type }}<br><i
                                                                class="fa fa-eye">{{ $row->number_view }}</i>
                                                        </td>
                                                        @php
                                                            $url_mapping = url('');
                                                            $url_mapping .= '/';
                                                            $url_mapping .=
                                                                App\Consts::ROUTE_PREFIX_TAXONOMY['tin-tuc'] . '/';
                                                            $url_mapping .=
                                                                $row->url_part == ''
                                                                    ? Str::slug($row->title)
                                                                    : $row->url_part;
                                                            $url_mapping .= '.html';
                                                        @endphp
                                                        <td>
                                                            <select class="form-control" name=""
                                                                id="tin_noi_bat_{{ $row->id }}"
                                                                onchange="updatePosition({{ $row->id }})">
                                                                @foreach ($array_location as $key => $position)
                                                                    <option value="{{ $key }}"
                                                                        {{ $row->news_position == $key ? 'selected' : '' }}>
                                                                        {{ $position }}</option>
                                                                @endforeach
                                                            </select>
                                                            <img id="ic-loading_{{ $row->id }}"
                                                                style="display: none;vertical-align: middle;"
                                                                src="/images/load.gif" width="20px">
                                                        </td>
                                                        <td>
                                                            @php
                                                                // $category = explode(',', trim($row->category, ','));
                                                                // foreach ($category as $cat_id) {
                                                                //     echo $array_category[$cat_id] . ', ';
                                                                // }
                                                                echo $array_category[$row->taxonomy_id];
                                                            @endphp
                                                        </td>
                                                        <td>
                                                            {{ date('H:i d/m/Y', strtotime($row->aproved_date)) }}
                                                        </td>
                                                        <td class="text-center">
                                                            <a class="btn btn-xs btn-warning" data-toggle="tooltip"
                                                                title="@lang('Cập nhật tin bài')"
                                                                data-original-title="@lang('Cập nhật tin bài')"
                                                                href="{{ route('cms_posts.edit', $row->id) }}">
                                                                <i class="fa fa-pencil-square-o"></i>
                                                            </a>
                                                            <a target="_new" href="{{ $url_mapping }}"
                                                                data-toggle="tooltip" title="@lang('Link')"
                                                                data-original-title="@lang('Link')">
                                                                <span class="btn btn-xs btn-flat btn-info">
                                                                    <i class="fa fa-link"></i>
                                                                </span>
                                                            </a>
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-xs btn-danger" type="submit"
                                                                data-toggle="tooltip" title="@lang('Gỡ bài đăng')"
                                                                data-original-title="@lang('Gỡ bài đăng')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                            <a target="_new"
                                                                href="{{ route('history.index') . '?post_id=' . $row->id }}"
                                                                data-toggle="tooltip" title="@lang('Truy vết')"
                                                                data-original-title="@lang('Truy vết')">
                                                                <span class="btn btn-xs btn-flat btn-primary">
                                                                    <i class="fa fa-history"></i>
                                                                </span>
                                                            </a>
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
                        </form>
                    </div>

                </div>

            </div>

        </div>
    </section>
    <script type="text/javascript">
        function updatePosition(id) {
            var position = $('#tin_noi_bat_' + id).val();
            $('#ic-loading_' + id).show();
            $.ajax({
                url: '{{ route('cms_posts.update_position') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    position: position,
                    id: id,
                },
                context: document.body,
            }).done(function(data) {
                $('#ic-loading_' + id).hide();
                $('#tin_noi_bat_' + id).attr('readonly', true)
                //window.location.reload();
            });
        }
    </script>

    </div>
@endsection
