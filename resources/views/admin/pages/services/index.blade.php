@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content-header')
    <section class="content-header">
        <h1>
            {{ $module_name }}
            <a class="btn btn-sm btn-warning pull-right" href="{{ route('services.create') }}">
                <i class="fa fa-plus"></i> @lang('Add')
            </a>
        </h1>
    </section>
@endsection

<?php $locale = 'en'; ?>

@section('content')
<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Danh sách dịch vụ</h3>
        </div>

        <div class="box-body table-responsive">
            {{-- Hiển thị thông báo --}}
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

            @if ($services->isEmpty())
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
                        
                        @foreach ($services as $service)
                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                            <tr class="valign-middle bg-gray-light">
                                <td>
                                    <?php $json_title = json_decode($service->json_title); ?>
                                    {{ $json_title->$locale ?? '' }}
                                </td>
                                <td>
                                    <input onkeyup="saveService({{ $service->id }})"
                                        id="title_{{ $service->id }}" value="{{ $service->title }}"
                                        class="form-control" />
                                </td>
                                <td>
                                    <input onkeyup="saveService({{ $service->id }})"
                                        id="brief_{{ $service->id }}" value="{{ $service->brief }}"
                                        class="form-control" />
                                </td>
                                <td>
                                    <input onkeyup="saveService({{ $service->id }})"
                                        id="price_{{ $service->id }}" value="{{ $service->price }}"
                                        class="form-control" />
                                </td>
                                <td>
                                    <input onkeyup="saveService({{ $service->id }})"
                                        id="iorder_{{ $service->id }}" value="{{ $service->iorder ?? '' }}"
                                        class="form-control" style="width: 80px" />
                                </td>
                                <td>
                                    @lang($service->status)
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" onclick="saveService({{ $service->id }})" href="javascript:;">
                                        <i class="fa fa-floppy-o"></i>
                                    </a>
                                    <a class="btn btn-sm btn-warning" href="{{ route('services.edit', $service->id) }}">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">
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

        {{-- Phân trang --}}
        <div class="box-footer clearfix">
            <div class="row">
                <div class="col-sm-5">
                    Tìm thấy {{ $services->total() }} kết quả
                </div>
                <div class="col-sm-7">
                    {{ $services->withQueryString()->links('admin.pagination.default') }}
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
