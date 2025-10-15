@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content')
    <?php $lang_code = 'vi'; ?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
            <a class="btn btn-sm btn-warning pull-right" href="{{ route(Request::segment(2) . '.create') }}"><i
                    class="fa fa-plus"></i> @lang('Add')</a>
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
            <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="box-body">
                  <!-- Custom Tabs -->
                  <div class="nav-tabs-custom">
                     
                      <div class="row">
                          <div class="col-md-12">
                              
                              <div class="form-group">
                                <label>@lang('Title') <small class="text-red">*</small></label>
                                <input type="text" class="form-control" maxlength="255" id="txtTitle" name="title" placeholder="@lang('Title')"
                                  value="{{ $detail->title }}" required>
                                  <span id='remainingInput_text' class="note pull-right">{{ mb_strlen(old('title')) }}/255</span>
                              </div>
                              
                          </div>
                          
                      </div>
						
                  </div>

              </div>

                <div class="box-footer">
                    <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>
                    <button type="submit" class="btn btn-primary pull-right btn-sm"><i class="fa fa-floppy-o"></i>
                        @lang('Save')</button>
                </div>
            </form>
        </div>
    </section>

@endsection
<style>
    div.ck-editor__editable {
        height: 300px !important;
    }
</style>
