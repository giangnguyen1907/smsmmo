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

    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">@lang('Update form')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route('web.update', $detail->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">
          @foreach (json_decode($detail->option_value) as $key => $value)
            <div class="col-md-12">
              <div class="form-group">
                <label>{{ __($key) }}</label>
                <div class="input-group">
                  <span class="input-group-btn">
                    <a data-input="{{ $key }}" data-preview="{{ $key }}-holder" data-type="other"
                      class="btn btn-primary lfm">
                      <i class="fa fa-picture-o"></i> @lang('choose')
                    </a>
                  </span>
                  <input id="{{ $key }}" class="form-control" type="text"
                    name="option_value[{{ $key }}]" placeholder="@lang('image_link')..."
                    value="{{ $value ?? old('option_value[$key]') }}">
                </div>
                <div id="{{ $key }}-holder" style="margin-top:15px;max-height:100px;">
                  @if (isset($value) && $value != '')
                    <img id="view_{{ $key }}" style="height: 5rem;" src="{{ $value ?? old('option_value[$key]') }}">
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
            @lang('Save')</button>
        </div>
      </form>
    </div>
  </section>
@endsection
