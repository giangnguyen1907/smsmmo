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
<?php $locale = 'en'; ?>
@section('content')

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
                            <label>@lang('Service category')</label>
                            <select name="taxonomy" id="taxonomy" class="form-control select2" style="width: 100%;">
                                <option value="">@lang('Please select')</option>
								
								@foreach ($parents as $item)
								  @if ($item->parent_id == 0 || $item->parent_id == null)
									<option value="{{ $item->id }}"
									  >{{ $item->title->$locale }}</option>

									@foreach ($parents as $sub)
									  @if ($item->id == $sub->parent_id)
										<option value="{{ $sub->id }}"
										  >- - {{ $sub->title->$locale }}
										</option>

										@foreach ($parents as $sub_child)
										  @if ($sub->id == $sub_child->parent_id)
											<option value="{{ $sub_child->id }}"
											  >- - - -
											  {{ $sub_child->title->$locale }}</option>
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

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Danh sách dịch vụ</h3>
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
								<th>@lang('Category')</th>
                                <th>@lang('Title')</th>
                                <th>@lang('Brief')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Order')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($rows)
                                @foreach ($rows as $sub)
									<form action="{{ route(Request::segment(2) . '.destroy', $sub->id) }}"
										method="POST" onsubmit="return confirm('@lang('confirm_action')')">
										<tr class="valign-middle bg-gray-light">
											<td>
												<?php $json_title = json_decode($sub->json_title);
												echo $json_title->$locale;
												?>
											</td>
											<td>
												<input onkeyup="saveService({{ $sub->id }})"
													id="title_{{ $sub->id }}" value="{{ $sub->title }}"
													class="form-control" />

											</td>
											<td>
												<input onkeyup="saveService({{ $sub->id }})"
													id="brief_{{ $sub->id }}" value="{{ $sub->brief }}"
													class="form-control" />

											</td>
											<td>
												<input onkeyup="saveService({{ $sub->id }})"
													id="price_{{ $sub->id }}" value="{{ $sub->price }}"
													class="form-control" />
											</td>
											<td>
												<input onkeyup="saveService({{ $sub->id }})"
													id="iorder_{{ $sub->id }}"
													value="{{ $sub->iorder != '' ? $sub->iorder : '' }}"
													class="form-control" style="width: 80px" />

											</td>
											<td>
												@lang($sub->status)
											</td>
											<td>
												<a class="btn btn-sm btn-primary" data-toggle="tooltip"
													title="@lang('save')"
													data-original-title="@lang('save')"
													onclick="saveService({{ $sub->id }})"
													href="javascript:;">
													<i class="fa fa-floppy-o"></i>
												</a>
												<a class="btn btn-sm btn-warning" data-toggle="tooltip"
													title="@lang('update')"
													data-original-title="@lang('update')"
													href="{{ route(Request::segment(2) . '.edit', $sub->id) }}">
													<i class="fa fa-pencil-square-o"></i>
												</a>
												@csrf
												@method('DELETE')
												<button class="btn btn-sm btn-danger" type="submit"
													data-toggle="tooltip" title="@lang('delete')"
													data-original-title="@lang('delete')">
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
    </section>

    <script>
        function saveService(id) {

            var title = $('#title_' + id).val();
            var brief = $('#brief_' + id).val();
            var price = $('#price_' + id).val();
            var iorder = $('#iorder_' + id).val();
			
            $.ajax({
                url: '{{ route('cms_dichvu.save_ajax') }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'title': title,
                    'brief': brief,
                    'price': price,
                    'iorder': iorder,
                    'id': id
                },
                context: document.body,
            }).done(function(data) {
                //alert('Lưu dữ liệu thành công');
            });

        }
    </script>

@endsection
