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
                <h3 class="box-title">@lang('Create form')</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ route(Request::segment(2) . '.store') }}" method="POST"
                enctype="multipart/form-data">
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

                            <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                                value="save">Lưu bản thảo</button>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row" id="info_general">

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="tacpham">Tên tác phẩm</label>
                                            <input type="text" class="form-control" id="tacpham"
                                                placeholder="Nhập tên tác phẩm..." name="tacpham" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="tacgia">Tên tác giả</label>
                                            <input type="text" class="form-control" id="tacgia"
                                                placeholder="Nhập tên tác giả..." name="tacgia" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="butdanh">Bút danh</label>
                                            <input type="text" class="form-control" id="butdanh"
                                                placeholder="Nhập bút danh..." name="butdanh" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="diachi">Địa chỉ liên hệ</label>
                                            <input type="text" class="form-control" id="diachi"
                                                placeholder="Nhập địa chỉ liên hệ..." name="diachi">
                                        </div>
                                    </div>
									
									<div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="nguoidangky">Người đăng ký</label>
                                            <input type="text" class="form-control" id="nguoidangky"
                                                placeholder="Nhập người đăng ký..." name="nguoidangky">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="dienthoai">Điện thoại</label>
                                            <input type="text" class="form-control" id="dienthoai"
                                                placeholder="Nhập điện thoại..." name="dienthoai">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email"
                                                placeholder="Nhập email..." name="email" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="loginEmail">Thể loại</label>
                                            <select class="form-control" name="theloai" id="theloai" required>
                                                @foreach ($theloai_banthao as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="sotrang">Số trang</label>
                                            <input type="text" class="form-control" id="sotrang"
                                                placeholder="Nhập số trang..." name="sotrang">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="khuonkho">Khuôn khổ</label>
                                            <input type="text" class="form-control" id="khuonkho"
                                                placeholder="Nhập khuôn khổ..." name="khuonkho">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="dinhdang">Định dạng</label>
                                            <input type="text" class="form-control" id="dinhdang"
                                                placeholder="Nhập định dạng..." name="dinhdang">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="dungluong">Dung lượng (MB)</label>
                                            <input type="text" class="form-control" id="dungluong"
                                                placeholder="Nhập dung lượng..." name="dungluong">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="lanxuatban">Lần xuất bản</label>
                                            <input type="text" class="form-control" id="lanxuatban"
                                                placeholder="Nhập lần xuất bản..." name="lanxuatban">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="lantaiban">Lần tái bản</label>
                                            <input type="text" class="form-control" id="lantaiban"
                                                placeholder="Nhập lần tái bản..." name="lantaiban">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="soluong">Số lượng (cuốn)</label>
                                            <input type="text" class="form-control" id="soluong"
                                                placeholder="Nhập số lượng..." name="soluong">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="nhain">Nhà in</label>
                                            <input type="text" class="form-control" id="nhain"
                                                placeholder="Nhập nhà in..." name="nhain">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="diachinhain">Địa chỉ nhà in</label>
                                            <input type="text" class="form-control" id="diachinhain"
                                                placeholder="Nhập địa chỉ nhà in..." name="diachinhain">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="trangweb">Xuất bản tại nền tảng (tên trang web)</label>
                                            <input type="text" class="form-control" id="trangweb"
                                                placeholder="Nhập nền tảng..." name="trangweb">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="list_file_goc">Bản thảo đính kèm</label>
                                            <input type="file" class="form-control" name="list_file_goc[]" multiple
                                                title="Chọn file bản thảo"
                                                accept=".doc,.docx,.pdf,.txt,.rtf,.odt,.xls,.xlsx,.csv,.ods,.ppt,.pptx,.odp">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="noidung">Nội dung tóm tắt</label>
                                            <textarea name="noidung" id="noidung" class="form-control" placeholder="Nhập nội dung..." rows="3"></textarea>
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

                    <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                        value="save">Lưu
                        bản thảo</button>
                </div>
            </form>
        </div>
    </section>

@endsection
