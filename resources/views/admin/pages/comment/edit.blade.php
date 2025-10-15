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
    <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="box-body">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab_1" data-toggle="tab">
                <h5>Thông tin chính <span class="text-danger">*</span></h5>
              </a>
            </li>
            <button type="submit" class="btn btn-primary btn-sm pull-right">
              <i class="fa fa-floppy-o"></i>
              @lang('Save')
            </button>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="row">
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="doctor_id">
                      @lang('Bài viết')
                      <small class="text-red">*</small>
                    </label>
                    <input type="text" id="post_id" class="form-control " style="width:100%" readonly value="{{ $detail->post_title }}">
                    {{-- <select id="post_id" name="post_id" class="form-control select2" style="width:100%" required>
                      <option value="{{ $detail->post_id }}">{{ $detail->post_title }}</option>
                    </select> --}}
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="doctor_id">
                      @lang('Trạng thái')
                      <small class="text-red">*</small>
                    </label>
                    <select name="status" id="status" class="form-control select2" style="width: 100%;">
                      <option value="">@lang('Please select')</option>
                      @foreach (App\Consts::STATUS_COMMENT as $key => $value)
                        <option value="{{ $key }}"
                          {{ isset($detail->status) && $key == $detail->status ? 'selected' : '' }}>
                          {{ __($value) }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                
                <div class="col-md-6">
                  <div class="form-group">
                    <label>@lang('Content')</label>
                    <textarea id="content" class="form-control" rows="10" readonly>{!! $detail->content ?? old('content') !!}</textarea>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label>@lang('Admin Ghi chú')</label>
                    <textarea name="admin_note" id="admin_note" class="form-control" rows="10">{{ $detail->admin_note ?? '' }}</textarea>
                  </div>
                </div>
              </div>
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
    </form>
  </div>
</section>
@endsection

@section('script')
<script>
  ClassicEditor.create( document.querySelector( '#content' ), {
      toolbar: {
        items: [
          'CKFinder',"|",
          'heading',
          'bold',
          'link',
          'italic',
          '|',
          'blockQuote',
          'alignment:left', 'alignment:right', 'alignment:center', 'alignment:justify',
          'insertTable',
          'undo',
          'redo',
          
          'bulletedList',
          'numberedList',
          'mediaEmbed',
          'fontBackgroundColor',
          'fontColor',
          'fontSize',
          'fontFamily'
        ]
      },
      language: 'vi',
      image: {
        toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full','imageStyle:side', 'imageStyle:alignCenter'],
        styles: [
            'full',
            'side',
            'alignCenter',
            'alignLeft',
            'alignRight'
        ]
      },
      table: {
        contentToolbar: [
          'tableColumn',
          'tableRow',
          'mergeTableCells'
        ]
      },
      licenseKey: '',
      
      
    } )
    .then( editor => {
      window.editor = editor;
      
    } )
    .catch( error => {
      console.error( 'Oops, something went wrong!' );
      console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
      console.warn( 'Build id: v10wxmoi2tig-mwzdvmyjd96s' );
      console.error( error );
    } );
</script>

<style>
  div.ck-editor__editable {
    height: 500px !important;
    overflow: scroll;
  }
</style>
@endsection