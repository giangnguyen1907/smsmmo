@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
    </h1>
  </section>
  <!-- Main content -->
  <section class="content">
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
    <div class="row">
      <div class="col-md-5">
        <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
          @csrf
          @method('PUT')
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="profile-username">@lang('Order number') #{{ $detail->id }}</h3>
              <p class="text-muted">{{ __('Created at') }}: {{ $detail->created_at }}</p>
            </div>
            <div class="box-body">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Fullname'):</label>
                  <label class="col-sm-9 col-xs-12">{{ $detail->name ?? '' }}</label>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Email'):</label>
                  <label class="col-sm-9 col-xs-12">
                    {{ $detail->email ?? '' }}
                  </label>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Phone'):</label>
                  <label class="col-sm-9 col-xs-12">
                    {{ $detail->phone ?? '' }}
                  </label>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Address'):</label>
                  <label class="col-sm-9 col-xs-12">{{ $detail->address ?? '' }}</label>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Content note'):</label>
                  <label class="col-sm-9 col-xs-12">{{ $detail->customer_note ?? '' }}</label>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Status'):</label>
                  <div class="col-sm-9 col-xs-12 ">
                    @foreach (App\Consts::ORDER_STATUS as $key => $value)
                      <label>
                        <input type="radio" name="status" value="{{ $key }}"
                          {{ $detail->status == $key ? 'checked' : '' }}>
                        <small class="mr-15">{{ __($value) }}</small>
                      </label>
                    @endforeach
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 text-right text-bold">@lang('Admin note'):</label>
                  <div class="col-md-9 col-xs-12">
                    <textarea name="admin_note" class="form-control" rows="5">{{ $detail->admin_note ?? old('admin_note') }}</textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                <i class="fa fa-bars"></i> @lang('List')
              </a>
              <button type="submit" class="btn btn-primary pull-right btn-sm">
                <i class="fa fa-floppy-o"></i>
                @lang('Save')
              </button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-md-7">
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">@lang('Order product detail')</h3>
          </div>

          <div class="box-body">
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>@lang('#')</th>
                  <th>@lang('Product')</th>
                  <th>@lang('Price')</th>
                  <th>@lang('Quantity')</th>
                  <th>@lang('Total')</th>
                  <th>@lang('Action')</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($rows as $row)
                  @php
                    $alias_detail = Str::slug($row->post_title);
                    $url_link = route('frontend.cms.product', ['alias_category' => 'chi-tiet', 'alias_detail' => $alias_detail]) . '.html?id=' . $row->id;
                  @endphp
                  <form action="{{ route('order_details.update', $row->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <tr class="valign-middle">
                      <td>
                        {{ $loop->index + 1 }}
                      </td>
                      <td>
                        <a href="{{ $url_link }}" target="_blank">
                          <img src="{{ $row->image_thumb ?? $row->image }}" style="height:100px">
                          {{ $row->post_title }}
                        </a>
                      </td>
                      <td>
                        <input class="form-control" name="price" type="number" value="{{ $row->price }}" min="0"
                          onchange="this.form.submit();">
                      </td>
                      <td>
                        <input class="form-control" type="number" name="quantity" value="{{ $row->quantity }}" min="1"
                          onchange="this.form.submit();">
                      </td>
                      <td>
                        {{ number_format($row->price * $row->quantity) }}
                      </td>
                      <td>
                        <button class="btn btn-sm btn-danger remove-order-detail" type="button" data-toggle="tooltip"
                          title="@lang('Delete')" data-original-title="@lang('Delete')"
                          data-id="{{ $row->id }}">
                          <i class="fa fa-trash"></i>
                        </button>
                        <input class="hidden" type="submit">
                      </td>
                    </tr>
                  </form>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
  </section>
@endsection
@section('script')
  <script>
    $(function() {
      $(".remove-order-detail").click(function(e) {
        e.preventDefault();
        var ele = $(this);
        var id = ele.attr("data-id");
        if (confirm("{{ __('Are you sure want to remove?') }}")) {
          $.ajax({
            url: '{{ route('order_details.destroy') }}',
            method: "DELETE",
            data: {
              _token: '{{ csrf_token() }}',
              id: ele.attr("data-id")
            },
            success: function(response) {
              window.location.reload();
            }
          });
        }
      });

    });
  </script>
@endsection