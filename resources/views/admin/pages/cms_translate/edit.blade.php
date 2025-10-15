@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
          class="fa fa-plus"></i>
        @lang('add_new')</a>
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    @if (session('successMessage'))
      <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('successMessage') }}
      </div>
    @endif

    @if (session('errorMessage'))
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {{ session('errorMessage') }}
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
        <h3 class="box-title">@lang('update_form')</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form role="form" action="{{ route(Request::segment(2) . '.update', $rows->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="box-body">
          <div class="col-md-6">
            <div class="form-group">
              <label>@lang('Tên nhãn') <small class="text-red">*</small></label>
              <input type="text" class="form-control" name="local" placeholder="Tên nhãn là duy nhất, không được trùng với các nhãn đã có..."
                value="{{ $rows->local }}" required>
            </div>
          </div>
          <div class="col-md-6">

            <div class="form-group">
             @foreach($languages as $language)
             @php 
                $dich = '';
                $lang = $language->lang_code; 
                if(isset($rows->json_param->$lang)){
                  $dich = $rows->json_param->$lang;
                }

             @endphp
                  <div class="col-md-2">
                    <label for="translations[{{ $language->lang_code }}]">{{ $language->title }}:</label>
                  </div>
                  <div class="col-md-10">
                    <input type="text" class="form-control" name="translations[{{ $language->lang_code }}]" placeholder="{{ $language->lang_code }}" required value="{{ $dich }}">
                  </div>
            @endforeach
            </div>
          </div>

        </div>
        <!-- /.box-body -->

        <div class="box-footer">
          <a class="btn btn-sm btn-success" href="{{ route(Request::segment(2) . '.index') }}">
            <i class="fa fa-bars"></i> @lang('list')
          </a>
          <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-floppy-o"></i>
            @lang('save')</button>
        </div>
      </form>
    </div>
  </section>
@endsection
