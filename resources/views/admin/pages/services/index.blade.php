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
	<div class="box-body p-4 bg-white rounded shadow-sm" style="max-width: 400px;">
  <h4 class="fw-semibold text-secondary mb-3">Phần trăm chênh lệch</h4>

  <form 
    action="{{ route('services.updatePrice') }}" 
    method="POST" 
    class="row g-2 align-items-center"
  >
    @csrf
    <div class="col-12 col-md-8">
      <input 
        type="number" 
        name="percent" 
        class="form-control py-2" 
        placeholder="Nhập giá trị %" 
        min="0" 
        max="100" 
        step="0.01"
        required
      >
    </div>

    <div class="col-12 col-md-4">
      <button type="submit" class="btn btn-warning text-white w-100 py-2">
        Cập nhật
      </button>
    </div>
  </form>
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
                            <th>@lang('Mã dịch vụ gốc')</th>
                            <th>@lang('Title')</th>
                            <th>@lang('Price')</th>
                            <th>@lang('Thời lượng (phút)')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($services as $service)
                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" onsubmit="return confirm('@lang('confirm_action')')">
                            <tr class="valign-middle bg-gray-light">
                                <td>
                                    <select style="width: 100%" id="service_id_{{ $service->id }}" class="form-control select2">
                                        @foreach($allServiceRoot as $serviceRoot)
                                        <option value="{{$serviceRoot['_id']}}" {{$service->service_id == $serviceRoot['_id'] ? 'selected' : ''}}>{{$serviceRoot['name'].' - '.number_format($serviceRoot['price'])}}</option>
                                        @endforeach
                                    </select>
                                    
                                </td>

                                <td>
                                    <input 
                                        id="title_{{ $service->id }}" value="{{ $service->name }}"
                                        class="form-control"  />
                                </td>
                                
                                <td>
                                    <input 
                                        id="price_{{ $service->id }}" value="{{ $service->price_per_unit }}"
                                        class="form-control" />
                                </td>
                                <td>
                                    <input 
                                        id="price_{{ $service->id }}" value="{{ $service->duration_minutes }}"
                                        class="form-control" />
                                </td>
                               <td>
                                    <select class="form-control" id="status_{{ $service->id }}">
                                        <option value="1" {{$service->status == 1 ? 'selected' : ''}}>Hoạt động</option>
                                        <option value="0" {{$service->status == 0 ? 'selected' : ''}}>Không HĐ</option>
                                    </select>
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
    var service_id = $('#service_id_' + id).val();
    var price = $('#price_' + id).val();
    var status = $('#status_' + id).val();
    // alert(title+service_id+price+status);
    $.ajax({
        url: '{{ route('service.save_ajax') }}',
        type: 'GET',
        data: {
            '_token': '{{ csrf_token() }}',
            'title': title,
            'service_id': service_id,
            'price': price,
            'status': status,
            'id': id
        },
        context: document.body,
    }).done(function(data) {
        // alert('Lưu dữ liệu thành công');
    });
}
</script>

@endsection
