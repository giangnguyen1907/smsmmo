
@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection
<?php $locale='en'; ?>
@section('content')
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

        @php
            $array_location = APP\Consts::POST_POSITION_TAXONMY;
        @endphp

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Create form')</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST">
                @csrf
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
                                            <label>@lang('Title') <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="name" id="txtTitle"
                                                onchange="getUrlPart('txtTitle','txtUrlPart')"
                                                onclick="getUrlPart('txtTitle','txtUrlPart')"
                                                onblur="getUrlPart('txtTitle','txtUrlPart')" placeholder="@lang('Title')"
                                                value="{{ old('name') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>@lang('Brief')</label>
                                            <textarea name="description" id="brief" class="form-control" rows="5">{{ old('description') }}</textarea>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label> Giá dịch vụ </label>
                                                    <input type="text" class="form-control" name="price_per_unit"
                                                        placeholder="price" value="{{ old('price_per_unit') }}">
                                                </div>

                                            </div>
                                        </div>
                                            <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label> Thời lượng (phút) </label>
                                                    <input type="number" class="form-control" name="duration_minutes"
                                                        placeholder="duration minutes" value="{{ old('duration_minutes') }}">
                                                </div>

                                            </div>
                                        </div>
                                             <div class="form-group">
                                                  <label>@lang('Status')</label>
                                                  <div class="form-control">
                                                      <label>
                                                          <input type="radio" name="status" value="active" checked="">
                                                          <small>@lang('active')</small>
                                                      </label>
                                                      <label>
                                                          <input type="radio" name="status" value="deactive"
                                                              class="ml-15">
                                                          <small>@lang('deactive')</small>
                                                      </label>
                                                  </div>
                                              </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                    </div>
                                    <div class="col-md-6 hidden">
                                        <div class="form-group">
                                            <label>@lang('seo_title')</label>
                                            <input name="json_params[seo_title]" class="form-control"
                                                value="{{ old('json_params[seo_title]') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 hidden">
                                        <div class="form-group">
                                            <label>@lang('seo_keyword')</label>
                                            <input name="json_params[seo_keyword]" class="form-control"
                                                value="{{ old('json_params[seo_keyword]') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12 hidden">
                                        <div class="form-group">
                                            <label>@lang('seo_description')</label>
                                            <input name="json_params[seo_description]" class="form-control"
                                                value="{{ old('json_params[seo_description]') }}">
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div><!-- /.tab-content -->
                    </div><!-- nav-tabs-custom -->

                </div>
                <!-- /.box-body -->

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
        height: 500px !important;
    }
</style>
@section('script')
    <script>
        // ClassicEditor.create(document.querySelector('#content'), {
        //     toolbar: {
        //         items: [
        //             'CKFinder', "|",
        //             'heading',
        //             'bold',
        //             'link',
        //             'italic',
        //             '|',
        //             'blockQuote',
        //             'alignment:left', 'alignment:right', 'alignment:center', 'alignment:justify',
        //             'insertTable',
        //             'undo',
        //             'redo',
        //             'LinkImage',
        //             'bulletedList',
        //             'numberedList',
        //             'mediaEmbed',
        //             'fontBackgroundColor',
        //             'fontColor',
        //             'fontSize',
        //             'fontFamily'
        //         ]
        //     },
        //     language: 'vi',
        //     image: {
        //         toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:side',
        //             'imageStyle:alignCenter', 'linkImage'
        //         ],
        //         styles: [
        //             'full',
        //             'side',
        //             'alignCenter',
        //             'alignLeft',
        //             'alignRight'
        //         ]
        //     },
        //     table: {
        //         contentToolbar: [
        //             'tableColumn',
        //             'tableRow',
        //             'mergeTableCells'
        //         ]
        //     },
        //     licenseKey: '',


        // }).then(editor => {
        //     window.editor = editor;

        // }).catch(error => {
        //     console.error('Oops, something went wrong!');
        //     console.error(
        //         'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:'
        //     );
        //     console.warn('Build id: v10wxmoi2tig-mwzdvmyjd96s');
        //     console.error(error);
        // });
        

        $(document).ready(function() {
            var taxonomys = @json($taxonomys ?? null);
            // Change to filter type by name taxonomy
            $(document).on('change', '#taxonomy', function() {
                let _value = $(this).val();
                let _html = $('#parent_id');
                let _list = taxonomys.filter(function(e, i) {
                    return ((e.parent_id == 0 || e.parent_id == null) && e.taxonomy == _value);
                });
                let _content = '<option value="">== @lang('ROOT') ==</option>';

                //cấp 1
                if (_list) {
                    _list.forEach(element => {
                        _content += '<option value="' + element.id + '"> ' + element.title +
                            ' </option>';
                        // cấp 2
                        // let _child = taxonomys.filter(function(e, i) {
                        //     return ((e.parent_id == element.id) && e.taxonomy == _value);
                        // });
                        // if (_child) {
                        //     _child.forEach(element => {
                        //         _content += '<option value="' + element.id + '">- - ' +
                        //             element.title + ' </option>';
                        //     });
                        // }
                    });
                    _html.html(_content);

                    $('#parent_id').select2();
                }
            });

        });
    </script>
@endsection
