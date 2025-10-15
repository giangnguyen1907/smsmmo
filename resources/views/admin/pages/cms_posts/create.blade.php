@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection
<?php $locale = 'vi'; ?>
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
            $array_location = APP\Consts::POST_POSITION;
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
                            @foreach ($languages as $key_lang => $lang)
                                <li class="<?php if ($key_lang == 0) {
                                    echo 'active';
                                } ?>">
                                    <a href="#tab_{{ $key_lang }}" data-toggle="tab">
                                        <h5>Thông tin {{ $lang->title }} <span class="text-danger">*</span></h5>
                                    </a>
                                </li>
                            @endforeach
                            <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" name="submit"
                                value="xuatban">Lưu lại</button>

                        </ul>

                        <div class="tab-content">
                            @foreach ($languages as $key_la => $lang_tab)
                                <div class="tab-pane <?php if ($key_la == 0) {
                                    echo 'active';
                                } ?>" id="tab_{{ $key_la }}">
                                    <div class="row" id="info_general">
                                        <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label>@lang('Content') {{ $lang_tab->title }}:</label>
                                                    <textarea name="content[{{ $lang_tab->lang_code }}]" class="form-control" id="content_{{ $lang_tab->lang_code }}"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>@lang('Tiêu đề') {{ $lang_tab->title }} <small
                                                        class="text-red">*</small></label>
                                                <input type="text" class="form-control"
                                                    id="txtTitle_{{ $lang_tab->lang_code }}"
                                                    onchange="demKytu('txtTitle_{{ $lang_tab->lang_code }}','remainingInput_text_{{ $lang_tab->lang_code }}');getUrlPart('txtTitle_{{ $lang_tab->lang_code }}','txtUrlPart_{{ $lang_tab->lang_code }}')"
                                                    onclick="demKytu('txtTitle_{{ $lang_tab->lang_code }}','remainingInput_text_{{ $lang_tab->lang_code }}');getUrlPart('txtTitle_{{ $lang_tab->lang_code }}','txtUrlPart_{{ $lang_tab->lang_code }}')"
                                                    onblur="demKytu('txtTitle_{{ $lang_tab->lang_code }}','remainingInput_text_{{ $lang_tab->lang_code }}');getUrlPart('txtTitle_{{ $lang_tab->lang_code }}','txtUrlPart_{{ $lang_tab->lang_code }}')"
                                                    name="title[{{ $lang_tab->lang_code }}]"
                                                    placeholder="@lang('Title') {{ $lang_tab->title }}" required>
                                                {{-- <span id='remainingInput_text_{{ $lang_tab->lang_code }}' class="note pull-right">{{ mb_strlen(old('title')) }}/160</span> --}}
                                            </div>
                                            <div class="form-group">
                                                <label>@lang('Mô tả') {{ $lang_tab->title }}</label>
                                                <textarea name="brief[{{ $lang_tab->lang_code }}]" id="description_{{ $lang_tab->lang_code }}"
                                                    onkeyup="demKytu('description_{{ $lang_tab->lang_code }}','remainingInput_textarea_{{ $lang_tab->lang_code }}');"
                                                    onblur="demKytu('description_{{ $lang_tab->lang_code }}','remainingInput_textarea_{{ $lang_tab->lang_code }}');"
                                                    onclick="demKytu('description_{{ $lang_tab->lang_code }}','remainingInput_textarea_{{ $lang_tab->lang_code }}');"
                                                    class="form-control" rows="5">{{ old('brief') }}</textarea>
                                                {{-- <span id='remainingInput_textarea_{{ $lang_tab->lang_code }}' class="note pull-right">{{ mb_strlen(old('brief')) }}/1000</span> --}}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                        </div>

                                    </div>
                                </div>
                            @endforeach

                            <div class="tab">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('alias') <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" id="txtUrlPart_{{ $locale }}"
                                                onchange="getUrlPart('txtUrlPart_{{ $locale }}','txtUrlPart_{{ $locale }}')"
                                                onclick="getUrlPart('txtUrlPart_{{ $locale }}','txtUrlPart_{{ $locale }}')"
                                                onblur="getUrlPart('txtUrlPart_{{ $locale }}','txtUrlPart_{{ $locale }}')"
                                                name="url_part" placeholder="@lang('Alias')"
                                                value="{{ old('url_part') }}" required readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Danh mục') <small class="text-red">*</small></label>
                                            <select name="taxonomy_id" id="taxonomy_id" class="form-control select2"
                                                style="width: 100%">

                                                @foreach ($parents as $item)
                                                    @if ($item->parent_id == 0 || $item->parent_id == null)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('taxonomy_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->title->$locale }}</option>

                                                        @foreach ($parents as $sub)
                                                            @if ($item->id == $sub->parent_id)
                                                                <option value="{{ $sub->id }}"
                                                                    {{ old('taxonomy_id') == $sub->id ? 'selected' : '' }}>
                                                                    - - {{ $sub->title->$locale }}
                                                                </option>

                                                                @foreach ($parents as $sub_child)
                                                                    @if ($sub->id == $sub_child->parent_id)
                                                                        <option value="{{ $sub_child->id }}"
                                                                            {{ old('taxonomy_id') == $sub_child->id ? 'selected' : '' }}>
                                                                            - - - -
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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Chọn loại tin') <small class="text-red">*</small></label>
                                            <select name="news_position" class="form-control" id="news_position">
                                                @foreach ($array_location as $key => $position_text)
                                                    <option value="{{ $key }}"
                                                        {{ old('news_position') == $key ? 'selected' : '' }}>
                                                        {{ $position_text }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Ảnh đại diện')</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a data-input="image" data-preview="image-holder"
                                                        class="btn btn-primary lfm" data-type="cms-image_background">
                                                        <i class="fa fa-picture-o"></i> @lang('choose')
                                                    </a>
                                                </span>
                                                <input id="image" class="form-control" type="text" name="image"
                                                    placeholder="@lang('image_link')...">
                                            </div>
                                            <div id="image-holder" style="margin-top:15px;max-height:100px;">
                                                @if (old('image') != '')
                                                    <img id="view_image" style="height: 5rem;"
                                                        src="{{ old('image') }}">
                                                @else
                                                    <img id="view_image" style="height: 5rem;" src="">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>@lang('Mã nhúng video yotube')</label>
                                            <input type="text" class="form-control" id="iframe_video"
                                                name="iframe_video" placeholder="@lang('Mã nhúng video')"
                                                value="{{ old('iframe_video') }}">
                                        </div>
                                    </div>
                                    <div class="form-group hidden">
                                        <label>@lang('seo_title')</label>
                                        <input id="txtSeoTitle" maxlength="160" name="seo_title" class="form-control"
                                            value="{{ old('seo_title') }}"
                                            onkeyup="demKytu('txtSeoTitle','remainingInput_seoTitle');"
                                            onblur="demKytu('txtSeoTitle','remainingInput_seoTitle');"
                                            onclick="demKytu('txtSeoTitle','remainingInput_seoTitle');">
                                        <span id='remainingInput_seoTitle'
                                            class="note pull-right">{{ mb_strlen(old('seo_title')) }}/160</span>
                                    </div>
                                    <div class="form-group hidden">
                                        <label>@lang('seo_keyword')</label>
                                        <input id="txtSeoKeyword" maxlength="255" name="seo_keyword"
                                            class="form-control" value="{{ old('seo_keyword') }}"
                                            onkeyup="demKytu('txtSeoKeyword','remainingInput_seoKeyword');"
                                            onblur="demKytu('txtSeoKeyword','remainingInput_seoKeyword');"
                                            onclick="demKytu('txtSeoKeyword','remainingInput_seoKeyword');">
                                        <span id='remainingInput_seoKeyword'
                                            class="note pull-right">{{ mb_strlen(old('seo_keyword')) }}/255</span>
                                    </div>
                                    <div class="form-group hidden">
                                        <label>@lang('seo_description')</label>
                                        <input id="txtSeoDescription" maxlength="255" name="seo_description"
                                            class="form-control" value="{{ old('seo_description') }}"
                                            onkeyup="demKytu('txtSeoDescription','remainingInput_seoDescription');"
                                            onblur="demKytu('txtSeoDescription','remainingInput_seoDescription');"
                                            onclick="demKytu('txtSeoDescription','remainingInput_seoDescription');">
                                        <span id='remainingInput_seoDescription'
                                            class="note pull-right">{{ mb_strlen(old('seo_description')) }}/255</span>
                                    </div>

                                </div> <!-- end class row -->
                            </div>
                        </div><!-- /.tab-content -->
                    </div><!-- nav-tabs-custom -->

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <a class="btn btn-success btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                        <i class="fa fa-bars"></i> @lang('List')
                    </a>

                    <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" name="submit"
                        value="xuatban">Lưu lại</button>

                </div>
            </form>
        </div>
    </section>
    <script type="text/javascript">
        var index = 0;

        function openPopupMultiImg(id) {
            CKFinder.popup({
                chooseFiles: true,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {

                        // update multifile
                        var files = evt.data.files;
                        files.forEach(function(file, i) {

                            $("#frames")
                                .append('<div class="form-group" id ="' + index + '">' +
                                    // '<div class="col-md-2" style="padding-left:0px;">' +
                                    // '<image id="srcImage_'+ index +'" src="'+ file.getUrl() +'" style="height:100px; width:100%;" />'+
                                    // '</div>' +
                                    '<div class="col-md-6" style="padding-left:0px;">' +
                                    '<input class="form-control" type="text" readonly name="imagelist[' +
                                    index + ']" id="imagelist_' + index +
                                    '" txthide="imagelist_' + index +
                                    '" placeholder="Đường dẫn ' + index + '" value="' + file
                                    .getUrl() + '" />' +
                                    '</div>' +
                                    '<div class="col-md-2" style="padding-left:0px;">' +
                                    '<input class="btn btn-info form-control" onclick="openPopupImg2(' +
                                    index + ')" txthide="imagelist_' + index +
                                    '" type="button" value="Chọn file ' + index +
                                    '" name="imageadd[' + index + ']" id="imageadd_' + index +
                                    '" />' +
                                    '</div>' +

                                    '<div class="col-md-2" style="padding-left:0px;">' +
                                    '<input class="btn btn-danger form-control"  onclick="delete_img(' +
                                    index + ')" value="Xóa' + index + '"/>' +
                                    '</div>' +
                                    '</div>');
                            index++;
                        });
                    });
                    finder.on('file:choose:resizedImage', function(evt) {
                        document.getElementById(id).value = evt.data.resizedUrl;
                    });
                }
            });
        }

        function delete_img(id) {
            if (confirm("Bạn có muốn xóa")) {
                $("#" + id).remove();
                document.getElementById('remove').value += "," + id;
            }
        }

        function openPopupImg2(id) {
            CKFinder.popup({
                chooseFiles: true,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {
                        var file = evt.data.files.first();
                        console.log(evt.data.files);

                        document.getElementById("imagelist_" + id).value = file.getUrl();
                        // document.getElementById( "srcImage_" + id ).src = file.getUrl();


                    });
                    finder.on('file:choose:resizedImage', function(evt) {
                        document.getElementById(id).value = evt.data.resizedUrl;
                    });
                }
            });
        }

        function openPopupMultiDocument(id) {
            CKFinder.popup({
                chooseFiles: true,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {

                        // update multifile
                        var files = evt.data.files;
                        files.forEach(function(file, i) {

                            $("#frames_document")
                                .append('<div class="form-group" id ="' + index + '">' +
                                    // '<div class="col-md-2" style="padding-left:0px;">' +
                                    // '<image id="srcImage_'+ index +'" src="'+ file.getUrl() +'" style="height:100px; width:100%;" />'+
                                    // '</div>' +
                                    '<div class="col-md-6" style="padding-left:0px;">' +
                                    '<input class="form-control" type="text" readonly name="documentlist[' +
                                    index + ']" id="documentlist_' + index +
                                    '" txthide="documentlist_' + index +
                                    '" placeholder="Đường dẫn ' + index + '" value="' + file
                                    .getUrl() + '" />' +
                                    '</div>' +
                                    '<div class="col-md-2" style="padding-left:0px;">' +
                                    '<input class="btn btn-info form-control" onclick="openPopupDocument2(' +
                                    index + ')" txthide="documentlist_' + index +
                                    '" type="button" value="Chọn file ' + index +
                                    '" name="imageadd[' + index + ']" id="imageadd_' + index +
                                    '" />' +
                                    '</div>' +

                                    '<div class="col-md-2" style="padding-left:0px;">' +
                                    '<input class="btn btn-danger form-control"  onclick="delete_document(' +
                                    index + ')" value="Xóa' + index + '"/>' +
                                    '</div>' +
                                    '</div>');
                            index++;
                        });
                    });
                    finder.on('file:choose:resizedImage', function(evt) {
                        document.getElementById(id).value = evt.data.resizedUrl;
                    });
                }
            });
        }

        function delete_document(id) {
            if (confirm("Bạn có muốn xóa")) {
                $("#" + id).remove();
                document.getElementById('remove').value += "," + id;
            }
        }

        function openPopupDocument2(id) {
            CKFinder.popup({
                chooseFiles: true,
                onInit: function(finder) {
                    finder.on('files:choose', function(evt) {
                        var file = evt.data.files.first();
                        console.log(evt.data.files);

                        document.getElementById("documentlist_" + id).value = file.getUrl();
                        // document.getElementById( "srcImage_" + id ).src = file.getUrl();


                    });
                    finder.on('file:choose:resizedImage', function(evt) {
                        document.getElementById(id).value = evt.data.resizedUrl;
                    });
                }
            });
        }
    </script>

    <div class="modal modal-login fade" id="postnewModal" tabindex="-1" role="dialog"
        aria-labelledby="postnewModalModalLabel" aria-hidden="true">

        @csrf
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Chọn tin liên quan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        style="margin-top: -22px;">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="input-group">
                        <input id="keyword" type="text" class="form-control" name="search"
                            placeholder="Keyword...">
                        <span class="input-group-addon" onclick="searchPost()"><i class="fa fa-search"></i></span>
                    </div>

                    <table class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Bài viết</th>
                                <th>Chọn</th>
                            </tr>
                        </thead>
                        <tbody id="dataTablePost">
                            @foreach ($cms_posts as $post)
                                <tr>
                                    <td>{{ $post->title->$locale }}</td>
                                    <td>
                                        <input type="checkbox" onclick="checkPostReative({{ $post->id }})"
                                            id="post_id_{{ $post->id }}" name="check_realtive[]"
                                            value="{{ $post->id }}" />
                                        <input type="hidden" id="post_title_{{ $post->id }}"
                                            value="{{ $post->title->$locale }}" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-right">
                        <button type="buttom" name="btnLogin" id="btnLogin" class="btn btn-primary" class="close"
                            data-dismiss="modal" aria-label="Close" spellcheck="false">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="toast" style="display: none">
        <div class="toast-body">
            Thay đổi ảnh đại diện bài viết thành công
        </div>
    </div>
@endsection

@section('script')
    <script>
        @foreach ($languages as $lang)
            CKEDITOR.replace('content_{{ $lang->lang_code }}', ck_options);
        @endforeach
    </script>

    <script>
        function searchPost() {
            var keyword = $('#keyword').val();

            $.ajax({
                url: '{{ route('cms_posts.post_relative') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    keyword: keyword
                },
                context: document.body,
            }).done(function(data) {
                $('#dataTablePost').html(data);
            });
        }

        function getContent() {
            var url_craw = $("#crawl").val();
            $('#ic-loading').show();
            //alert(url_craw);
            $.ajax({
                url: '{{ route('cms_posts.load_crawler') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    url_craw: url_craw
                },
                context: document.body,
            }).done(function(data) {
                $('#ic-loading').hide();
                $('#info_general').html(data);
            });
        }
    </script>
@endsection
