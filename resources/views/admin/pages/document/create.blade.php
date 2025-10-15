@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            {{ $module_name }}
            <a class="btn btn-success btn-sm pull-right" href="{{ route(Request::segment(2) . '.index') }}">
                <i class="fa fa-bars"></i> @lang('List')
            </a>
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
                            <?php if(Auth::guard('admin')->user()->is_super_admin == 0){ ?>
                            <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                                value="waiting">Lưu lại</button>

                            <?php }else if(Auth::guard('admin')->user()->is_super_admin == 2){ // Ban biên tập ?>
                            <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                                value="pending">Chờ Xuất bản</button>
                            <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" name="submit"
                                value="deactive">Từ chối</button>
                            <?php }else{ ?>
                            <button type="submit" class="btn btn-success pull-right btn-sm mg-5" name="submit"
                                value="active">Xuất bản</button>
                            <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                                value="pending">Chờ xuất bản</button>
                            <?php } ?>
                            <button type="submit" class="btn btn-warning pull-right btn-sm mg-5" name="submit"
                                value="draft">Lưu nháp</button>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên sách <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="title"
                                                placeholder="@lang('Tên sách')" value="{{ old('title') }}" required
                                                id="txtTitle" onchange="getUrlPart('txtTitle','txtUrlPart')"
                                                onclick="getUrlPart('txtTitle','txtUrlPart')"
                                                onblur="getUrlPart('txtTitle','txtUrlPart')">
                                            <input type="hidden" class="form-control" id="txtUrlPart" name="alias"
                                                placeholder="@lang('Alias')" value="{{ old('alias') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Tác giả chính <small class="text-red">*</small></label>
                                            <span class=" pull-right"><a class="btn btn-xs btn-warning" data-toggle="modal"
                                                    data-target="#createAuthor"><i class="fa fa-plus"></i> Tác
                                                    giả</a></span>
                                            <select name="main_author" id="main_author" required
                                                class="form-control select2" style="width: 100%">
                                                <option value="">-Chọn tác giả chính-</option>
                                                @foreach ($authors as $author)
                                                    <option value="{{ $author->id }}" <?php if ($author->id == old('main_author')) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $author->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Khổ sách</label><span class=" pull-right"><a
                                                    class="btn btn-xs btn-warning" data-toggle="modal"
                                                    data-target="#createBookSize"><i class="fa fa-plus"></i> Khổ
                                                    sách</a></span>
                                            <select name="book_size" id="book_size" class="form-control select2"
                                                style="width: 100%">
                                                <option value="">-Chọn khổ sách-</option>
                                                <?php foreach($booksize as $size){ ?>
                                                <option value="{{ $size->id }}">
                                                    {{ $size->width . 'x' . $size->height }}
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Giá bán <small class="text-red">*</small></label>
                                            <input type="number" class="form-control" name="price"
                                                placeholder="Giá bán" value="{{ old('price') }}">
                                        </div>

                                        {{-- <div class="form-group">
                                            <label>Số trang</label>
                                            <input type="number" class="form-control" name="number_page"
                                                placeholder="Số trang" value="{{ old('number_page') }}">
                                        </div> --}}

                                        <div class="form-group">
                                            <label>% Đơn sách giấy</label>
                                            <input type="number" class="form-control" name="percent_paper"
                                                placeholder="% đơn sách giấy" value="{{ old('percent_paper') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>% Đơn Ebook</label>
                                            <input type="number" class="form-control" name="percent_ebook"
                                                placeholder="% đơn sách giấy" value="{{ old('percent_ebook') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Nhà xuất bản</label>
                                            <select name="publisher" id="publisher" class="form-control select2"
                                                style="width: 100%">
                                                <option value="">-Chọn Nhà xuất bản-</option>
                                                <?php foreach($managershop as $shop){ ?>
                                                <option value="{{ $shop->id }}">{{ $shop->title }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Mã ISBN</label>
                                            <input type="text" class="form-control" name="isbn"
                                                placeholder="Mã ISBN" value="{{ old('isbn') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Năm xb sách giấy</label>
                                            <input type="text" class="form-control" name="publishing_year"
                                                placeholder="Năm xuất bản sách giấy"
                                                value="{{ old('publishing_year') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Ngôn ngữ</label>
                                            <input type="text" class="form-control" name="language"
                                                placeholder="Nhập ngôn ngữ" value="{{ old('language') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Loại xb phẩm</label>
                                            <select class="form-control select2" name="publication_type"
                                                style="width: 100%">
                                                <option value="0">Chọn loại xuất bản phẩm</option>
                                                <option value="1">Sách liên kết xuất bản</option>
                                                <option value="2">Tự phát hành</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Danh mục') <small class="text-red">*</small></label>
                                            <select name="category" id="categorys" class="form-control select2" required
                                                style="width: 100%">
                                                <option value="">-Chọn danh mục sách-</option>
                                                @foreach ($parents as $item)
                                                    @if ($item->parent_id == 0 || $item->parent_id == null)
                                                        <option value="{{ $item->id }}" <?php if ($item->id == old('category')) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $item->title->$locale }}</option>

                                                        @foreach ($parents as $sub)
                                                            @if ($item->id == $sub->parent_id)
                                                                <option value="{{ $sub->id }}" <?php if ($sub->id == old('category')) {
                                                                    echo 'selected';
                                                                } ?>>-
                                                                    - {{ $sub->title->$locale }}
                                                                </option>

                                                                @foreach ($parents as $sub_child)
                                                                    @if ($sub->id == $sub_child->parent_id)
                                                                        <option value="{{ $sub_child->id }}"
                                                                            <?php if ($sub_child->id == old('category')) {
                                                                                echo 'selected';
                                                                            } ?>>- - - -
                                                                            {{ $sub_child->title->$locale }}</option>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Đồng tác giả</label>
                                            <select name="authors[]" id="authors" class="form-control select2" multiple
                                                style="width: 100%">
                                                @foreach ($authors as $author)
                                                    <option value="{{ $author->id }}"> {{ $author->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Tags</label>
                                            <select name="tags[]" id="tags" class="form-control select2" multiple
                                                style="width: 100%">
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag->id }}"> {{ $tag->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Giá bìa sách</label>
                                            <input type="number" class="form-control" name="cover_price"
                                                placeholder="Giá bìa sách" value="{{ old('cover_price') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Dung lượng Audio(MB):</label>
                                            <input type="number" class="form-control" name="capacity"
                                                placeholder="Dung lượng" value="{{ old('capacity') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Người dịch</label>
                                            <input type="text" class="form-control" name="translater"
                                                placeholder="Người dịch" value="{{ old('translater') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>% Đơn audio</label>
                                            <input type="number" class="form-control" name="percent_audio"
                                                placeholder="% Đơn audio" value="{{ old('percent_audio') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>% Gói đọc</label>
                                            <input type="number" class="form-control" name="percent_ebook_package"
                                                placeholder="% Gói đọc" value="{{ old('percent_ebook_package') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Mã ISBN Ebook</label>
                                            <input type="text" class="form-control" name="isbn"
                                                placeholder="Mã ISBN Ebook" value="{{ old('isbne') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Năm xb Ebook</label>
                                            <input type="text" class="form-control" name="publishing_year_ebook"
                                                placeholder="Năm xuất bản Ebook"
                                                value="{{ old('publishing_year_ebook') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Quốc gia</label>
                                            <input type="text" class="form-control" name="country"
                                                placeholder="Quốc gia" value="{{ old('country') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Thời hạn bản quyền</label>
                                            <input type="date" class="form-control" name="copyright_period"
                                                placeholder="Thời hạn bản quyền" value="{{ old('copyright_period') }}">
                                        </div>

                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label class="custom-file-label">File pdf</label>
                                            <input type="file" name="filepdf" accept=".pdf" id="fileInput"
                                                title="Chọn file PDF" placeholder="Chọn tệp tải lên"
                                                class="form-control">
                                        </div>

                                        <div class="form-group hidden">
                                            <label>File đầy đủ</label>
                                            <input id="files" class="form-control" type="file" name="file_other"
                                                placeholder="File tài liệu đầy đủ" value="{{ old('file_other') }}">
                                        </div>

                                        <div class="form-group">

                                            <label>Ảnh bìa sách</label>
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                    <a data-input="image" data-preview="image-holder"
                                                        class="btn btn-primary lfm" data-type="other">
                                                        <i class="fa fa-picture-o"></i> @lang('choose')
                                                    </a>
                                                </span>
                                                <input id="image" class="form-control" type="text" name="image"
                                                    placeholder="@lang('image_link')..." value="{{ old('image') }}">
                                            </div>
                                            <div id="image-holder" style="margin-top:15px;max-height:100px;">
                                                @if (old('image') != '')
                                                    <img id="view_image" style="max-height: 5rem;"
                                                        src="{{ old('image') }}">
                                                @else
                                                    <img id="view_image" style="max-height: 5rem;" src="">
                                                @endif
                                            </div>

                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="row">

                                            <div class="col-xs-6">

                                                <div class="form-group">
                                                    <label>Giới hạn trang hiển thị</label>
                                                    <input type="number" class="form-control" name="limit_page"
                                                        placeholder="Giới hạn trang" value="{{ old('limit_page') }}">
                                                </div>

                                            </div>

                                            <div class="col-xs-6">

                                                <div class="form-group">
                                                    <label>Tổng số trang</label>
                                                    <input type="number" class="form-control" name="number_page"
                                                        placeholder="Tổng số trang" value="{{ old('number_page') }}">
                                                </div>

                                            </div>
                                            <div class="col-xs-6">

                                                <div class="form-group">
                                                    <label>Thứ tự <small class="text-red">*</small></label>
                                                    <input type="number" class="form-control" name="iorder"
                                                        placeholder="Thứ tự" value="{{ old('iorder') }}">
                                                </div>

                                            </div>

                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Quyền xem <small class="text-red">*</small></label>
                                                    <div class="form-control">
                                                        <label>
                                                            <input type="radio" name="is_public" value="0"
                                                                <?php if (old('is_public') == 0) {
                                                                    echo 'checked';
                                                                } ?>>
                                                            <small>Công khai</small>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="is_public" value="1"
                                                                <?php if (old('is_public') == 1) {
                                                                    echo 'checked';
                                                                } ?> class="ml-15">
                                                            <small>Đăng nhập</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Trạng thái sách <small class="text-red">*</small></label>
                                                    <div class="form-control">
                                                        <label>
                                                            <input type="radio" name="status_hang" value="0"
                                                                <?php if (old('status_hang') == 0) {
                                                                    echo 'checked';
                                                                } ?>>
                                                            <small>Hết hàng</small>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="status_hang" value="1"
                                                                <?php if (old('status_hang') == 1) {
                                                                    echo 'checked';
                                                                } ?> class="ml-15">
                                                            <small>Còn hàng</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-control">
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_0" name="hienthi[]"
                                                        value="0">
                                                    <label for="vehicle1"> Sách nổi bật</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_1" name="hienthi[]"
                                                        value="1">
                                                    <label for="vehicle1"> Sách hay</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_2" name="hienthi[]"
                                                        value="2">
                                                    <label for="vehicle1"> Sách mới</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_3" name="hienthi[]"
                                                        value="3">
                                                    <label for="vehicle1"> Có thể bạn thích</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_4" name="hienthi[]"
                                                        value="4">
                                                    <label for="vehicle1"> Sắp phát hành</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_5" name="hienthi[]"
                                                        value="5">
                                                    <label for="vehicle1"> Sách khuyên đọc</label>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 hidden">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-thead-bordered table-nowrap table-align-middle card-table">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Theo định dạng</th>
                                                        <th class="text-center">Sách giấy</th>
                                                        <th class="text-center">Ebook</th>
                                                        <th class="text-center">Audio</th>
                                                        <th class="text-center">Video</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php 
													$array_format = array(0=>'Định dạng',1=>'Hiển thị web', 2=>'Hiển thị gói đọc', 3=>'Sách miễn phí');
													foreach($array_format as $key=> $format){
													?>
                                                    <tr>
                                                        <td><b><?= $format ?></b></td>
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" id="IsPaper"
                                                                    name="json_params[<?= $key ?>][paper]" type="checkbox"
                                                                    value="true">
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" id="IsEbook"
                                                                    name="json_params[<?= $key ?>][ebook]" type="checkbox"
                                                                    value="true">
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" id="IsAudio"
                                                                    name="json_params[<?= $key ?>][audio]" type="checkbox"
                                                                    value="true">
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" id="IsVideo"
                                                                    name="json_params[<?= $key ?>][video]" type="checkbox"
                                                                    value="true">
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Mô tả ngắn:</label>
                                                <textarea name="brief" class="form-control" rows="3" id="brief">{{ old('brief') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Chi tiết:</label>
                                                <textarea name="description" class="form-control" id="description">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Mục lục:</label>
                                                <textarea name="detail" class="form-control" id="detail">{{ old('detail') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>SEO Title</label>
                                                <input name="seo_title" class="form-control" id="seo_title"
                                                    value="{{ old('seo_title') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>SEO Description:</label>
                                                <input name="seo_description" class="form-control" id="seo_description"
                                                    value="{{ old('seo_description') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>SEO Keyword:</label>
                                                <input name="seo_keyword" class="form-control" rows="3"
                                                    id="brief" value="{{ old('seo_keyword') }}">
                                            </div>
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
                    <?php if(Auth::guard('admin')->user()->is_super_admin == 0){ ?>
                    <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                        value="waiting">Lưu lại</button>
                    <button type="submit" class="btn btn-warning pull-right btn-sm mg-5" name="submit"
                        value="draft">Lưu nháp</button>
                    <?php }else if(Auth::guard('admin')->user()->is_super_admin == 2){ // Ban biên tập ?>
                    <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                        value="pending">Chờ Xuất bản</button>
                    <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" name="submit"
                        value="deactive">Từ chối</button>
                    <button type="submit" class="btn btn-warning pull-right btn-sm mg-5" name="submit"
                        value="draft">Lưu nháp</button>
                    <?php }else{ ?>
                    <button type="submit" class="btn btn-success pull-right btn-sm mg-5" name="submit"
                        value="active">Xuất bản</button>
                    <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                        value="pending">Chờ xuất bản</button>
                    <button type="submit" class="btn btn-warning pull-right btn-sm mg-5" name="submit"
                        value="draft">Lưu nháp</button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </section>

    <div class="modal fade" id="createAuthor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm mới tác giả</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Tên tác giả <small class="text-red">*</small></label>
                                    <input type="text" name="title" class="form-control" required id="title_author"
                                        value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Năm sinh</label>
                                    <input type="text" name="birthday" class="form-control" id="birthday_author"
                                        value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Giới thiệu</label>
                                    <input type="text" name="description" class="form-control"
                                        id="description_author" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" onclick="saveAuthor()" class="btn btn-primary">Lưu lại</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="createBookSize" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Thêm khổ sách</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Rộng <small class="text-red">*</small></label>
                                    <input type="text" name="width" class="form-control" required required
                                        id="width_book" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Cao</label>
                                    <input type="text" name="height" class="form-control" required id="height_book"
                                        value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" onclick="saveBookSize()" class="btn btn-primary">Lưu lại</button>
                </div>
            </div>
        </div>
    </div>

@endsection

<style>
    .cke_contents {
        height: 300px !important
    }
</style>

@section('script')
    <script>
        function saveAuthor() {

            $('#main_author').select2('val', '');
            var title = $('#title_author').val();
            var birthday = $('#birthday_author').val();
            var description = $('#description_author').val();
            //alert(title);

            $.ajax({
                url: '{{ route('cms_author.create_author') }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'birthday': birthday,
                    'title': title,
                    'description': description
                },
                context: document.body,
            }).done(function(data) {

                $('#main_author').append($("<option selected></option>").attr("value", data).text(title));
                $('#main_author').select2('val', data);

                $('#createAuthor').modal('hide');
                //location.reload();
                //alert(data);
            });
        }

        function saveBookSize() {

            $('#book_size').select2('val', '');
            var width = $('#width_book').val();
            var height = $('#height_book').val();
            //alert(title);

            $.ajax({
                url: '{{ route('booksize.create_ajax') }}',
                type: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'width': width,
                    'height': height,
                },
                context: document.body,
            }).done(function(data) {
                var txt = width + 'x' + height;
                $('#book_size').append($("<option selected></option>").attr("value", data).text(txt));
                $('#book_size').select2('val', data);

                $('#createBookSize').modal('hide');
                //location.reload();
                //alert(data);
            });
        }

        CKEDITOR.replace('description', ck_options);
        CKEDITOR.replace('detail', ck_options);

        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type !== 'application/pdf') {
                alert('Vui lòng chọn file PDF...');
                e.target.value = '';
            }
        })
    </script>
@endsection
