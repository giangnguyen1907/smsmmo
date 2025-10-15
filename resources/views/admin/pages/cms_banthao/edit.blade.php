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
            $theloai_banthao = App\Consts::THELOAI_BANTHAO;
        @endphp
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">@lang('Update form')</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST"
                enctype="multipart/form-data">
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

                            <li>
                                <a href="#tab_2" data-toggle="tab">
                                    <h5>Thông tin nhà xuất bản</h5>
                                </a>
                            </li>

                            <li>
                                <a href="#tab_3" data-toggle="tab">
                                    <h5>Thông tin quyết định</h5>
                                </a>
                            </li>

                            <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" id="capnhat"
                                name="submit" value="news_edit">Cập nhật</button>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="tacpham">Tên tác phẩm</label>
                                            <input type="text" class="form-control" id="tacpham"
                                                placeholder="Nhập tên tác phẩm..." name="tacpham" required
                                                value="{{ $detail->tacpham }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="tacgia">Tên tác giả</label>
                                            <input type="text" class="form-control" id="tacgia"
                                                placeholder="Nhập tên tác giả..." name="tacgia" required
                                                value="{{ $detail->tacgia }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="butdanh">Bút danh</label>
                                            <input type="text" class="form-control" id="butdanh"
                                                placeholder="Nhập bút danh..." name="butdanh" required
                                                value="{{ $detail->butdanh }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="diachi">Địa chỉ liên hệ</label>
                                            <input type="text" class="form-control" id="diachi"
                                                placeholder="Nhập địa chỉ liên hệ..." name="diachi"
                                                value="{{ $detail->diachi }}">
                                        </div>
                                    </div>
									
									<div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="nguoidangky">Người đăng ký</label>
                                            <input type="text" class="form-control" id="nguoidangky"
                                                placeholder="Nhập người đăng ký..." name="nguoidangky"
                                                value="{{ $detail->nguoidangky }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="dienthoai">Điện thoại</label>
                                            <input type="text" class="form-control" id="dienthoai"
                                                placeholder="Nhập điện thoại..." name="dienthoai"
                                                value="{{ $detail->dienthoai }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email"
                                                placeholder="Nhập email..." name="email" required
                                                value="{{ $detail->email }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="loginEmail">Thể loại</label>
                                            <select class="form-control" name="theloai" id="theloai" required>
                                                @foreach ($theloai_banthao as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ $detail->theloai == $key ? 'selected' : '' }}>
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="sotrang">Số trang</label>
                                            <input type="text" class="form-control" id="sotrang"
                                                placeholder="Nhập số trang..." name="sotrang"
                                                value="{{ $detail->sotrang }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="khuonkho">Khuôn khổ</label>
                                            <input type="text" class="form-control" id="khuonkho"
                                                placeholder="Nhập khuôn khổ..." name="khuonkho"
                                                value="{{ $detail->khuonkho }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="dinhdang">Định dạng</label>
                                            <input type="text" class="form-control" id="dinhdang"
                                                placeholder="Nhập định dạng..." name="dinhdang"
                                                value="{{ $detail->dinhdang }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="dungluong">Dung lượng (MB)</label>
                                            <input type="text" class="form-control" id="dungluong"
                                                placeholder="Nhập dung lượng..." name="dungluong"
                                                value="{{ $detail->dungluong }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="lanxuatban">Lần xuất bản</label>
                                            <input type="text" class="form-control" id="lanxuatban"
                                                placeholder="Nhập lần xuất bản..." name="lanxuatban"
                                                value="{{ $detail->lanxuatban }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="lantaiban">Lần tái bản</label>
                                            <input type="text" class="form-control" id="lantaiban"
                                                placeholder="Nhập lần tái bản..." name="lantaiban"
                                                value="{{ $detail->lantaiban }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="soluong">Số lượng (cuốn)</label>
                                            <input type="text" class="form-control" id="soluong"
                                                placeholder="Nhập số lượng..." name="soluong"
                                                value="{{ $detail->soluong }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="nhain">Nhà in</label>
                                            <input type="text" class="form-control" id="nhain"
                                                placeholder="Nhập nhà in..." name="nhain"
                                                value="{{ $detail->nhain }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="diachinhain">Địa chỉ nhà in</label>
                                            <input type="text" class="form-control" id="diachinhain"
                                                placeholder="Nhập địa chỉ nhà tin..." name="diachinhain"
                                                value="{{ $detail->diachinhain }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="trangweb">Xuất bản tại nền tảng (tên trang web)</label>
                                            <input type="text" class="form-control" id="trangweb"
                                                placeholder="Nhập nền tảng..." name="trangweb"
                                                value="{{ $detail->trangweb }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="list_file_goc">Bản thảo đính kèm</label>
                                            <input type="file" class="form-control" name="list_file_goc[]" multiple
                                                title="Chọn file bản thảo"
                                                accept=".doc,.docx,.pdf,.txt,.rtf,.odt,.xls,.xlsx,.csv,.ods,.ppt,.pptx,.odp">
                                        </div>

                                        @if ($detail->list_file_goc != ';')
                                            <div class="form-group">
                                                @foreach (explode(';', $detail->list_file_goc) as $key => $value)
                                                    @if ($value != '')
                                                        <a href="{{ $value }}"
                                                            download>{{ basename($value) }}</a><br>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label for="list_file_goc">Chưa có bản thảo đính kèm</label>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="noidung">Nội dung tóm tắt</label>
                                            <textarea name="noidung" id="noidung" class="form-control" placeholder="Nhập nội dung..." rows="3">{{ $detail->noidung }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                    </div>

                                </div>

                            </div>

                            <div class="tab-pane" id="tab_2">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="bientapvien">Biên tập viên</label>
                                            <input type="text" class="form-control" id="bientapvien"
                                                placeholder="Nhập tên biên tập viên..." name="bientapvien"
                                                value="{{ $detail->bientapvien }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="tongbientap">Tổng biên tập</label>
                                            <input type="text" class="form-control" id="tongbientap"
                                                placeholder="Nhập tên tổng biên tập..." name="tongbientap"
                                                value="{{ $detail->tongbientap }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="giamdoc">Giám đốc</label>
                                            <input type="text" class="form-control" id="giamdoc"
                                                placeholder="Nhập tên giám đốc..." name="giamdoc"
                                                value="{{ $detail->giamdoc }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="nguoivebia">Người vẽ bìa</label>
                                            <input type="text" class="form-control" id="nguoivebia"
                                                placeholder="Nhập tên người vẽ bìa..." name="nguoivebia"
                                                value="{{ $detail->nguoivebia }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="nguoitrinhbay">Người trình bày</label>
                                            <input type="text" class="form-control" id="nguoitrinhbay"
                                                placeholder="Nhập tên người trình bày..." name="nguoitrinhbay"
                                                value="{{ $detail->nguoitrinhbay }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="nguoisuabanin">Người sửa bản in</label>
                                            <input type="text" class="form-control" id="nguoisuabanin"
                                                placeholder="Nhập tên người sửa bản in..." name="nguoisuabanin"
                                                value="{{ $detail->nguoisuabanin }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="nguoiveminhhoa">Người vẽ minh hoạ</label>
                                            <input type="text" class="form-control" id="nguoiveminhhoa"
                                                placeholder="Nhập tên người vẽ minh hoạ..." name="nguoiveminhhoa"
                                                value="{{ $detail->nguoiveminhhoa }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="doitaclienket">Đối tác liên kết xuất bản</label>
                                            <input type="text" class="form-control" id="doitaclienket"
                                                placeholder="Nhập đối tác liên kết xuất bản..." name="doitaclienket"
                                                value="{{ $detail->doitaclienket }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                                    </div>

                                </div>

                            </div>

                            <div class="tab-pane" id="tab_3">
                                <div class="row">

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="sogiayphep">Số giấy phép</label>
                                            <input type="text" class="form-control" id="sogiayphep"
                                                placeholder="Nhập số giấy phép..." name="sogiayphep"
                                                value="{{ $detail->sogiayphep }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="soqdcapphep">Số QĐ cấp phép</label>
                                            <input type="text" class="form-control" id="soqdcapphep"
                                                placeholder="Nhập số QĐ cấp phép..." name="soqdcapphep"
                                                value="{{ $detail->soqdcapphep }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="maisbn">Mã ISBN</label>
                                            <input type="text" class="form-control" id="maisbn"
                                                placeholder="Nhập mã isbn..." name="maisbn"
                                                value="{{ $detail->maisbn }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="thoigianluuchieu">Thời gian lưu chiểu</label>
                                            <input type="text" class="form-control" id="thoigianluuchieu"
                                                placeholder="Nhập thời gian lưu chiểu..." name="thoigianluuchieu"
                                                value="{{ $detail->thoigianluuchieu }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="soqdphathanh">Số QĐ phát hành</label>
                                            <input type="text" class="form-control" id="soqdphathanh"
                                                placeholder="Nhập số QĐ phát hành..." name="soqdphathanh"
                                                value="{{ $detail->soqdphathanh }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="socongvanthuhoi">Số công văn thu hồi</label>
                                            <input type="text" class="form-control" id="socongvanthuhoi"
                                                placeholder="Nhập số công văn thu hồi..." name="socongvanthuhoi"
                                                value="{{ $detail->socongvanthuhoi }}">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="lydothuhoi">Lý do thu hồi</label>
                                            <textarea name="lydothuhoi" id="lydothuhoi" class="form-control" placeholder="Nhập lý do thu hồi..."
                                                rows="3">{{ $detail->lydothuhoi }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
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

                    <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" id="capnhat" name="submit"
                        value="news_edit">Cập nhật</button>

                </div>
            </form>
        </div>
    </section>

    <div class="toast" style="display: none">
        <div class="toast-body">
            Thay đổi ảnh đại diện bài viết thành công
        </div>
    </div>
    <style>
        .content_textarea div.ck-editor__editable {
            height: 1150px !important;
            overflow: scroll;
        }

        .description div.ck-editor__editable {
            height: 150px !important;
            overflow: scroll;
        }
    </style>
@endsection

@section('script')
    <script>
        function searchPost() {
            var keyword = $('#keyword').val();
            //alert(keyword);
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

        const initializeEditor = (selector, toolbarItems) => {
            ClassicEditor.create(document.querySelector(selector), {
                    toolbar: {
                        items: toolbarItems
                    },
                    language: 'vi',
                    licenseKey: ''
                })
                .then(editor => {
                    window[selector.substring(1) + 'Editor'] = editor;
                })
                .catch(error => {
                    console.error('Oops, something went wrong with editor!', error);
                });
        };

        initializeEditor('#content', [
            'CKFinder', "|",
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
        ]);

        initializeEditor('#description', [
            'bold',
            'italic',
            'link',
            '|',
            'undo',
            'redo',
            'fontColor'
        ]);
    </script>
@endsection
