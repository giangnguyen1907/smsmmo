@extends('admin.layouts.app')

@section('title')
    {{ $module_name }}
@endsection

<?php

$arr_authors = explode(',', trim($detail->authors, ','));
$arr_tags = explode(';', trim($detail->tags, ','));

?>

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

        <div class="box box-primary">
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="{{ route(Request::segment(2) . '.update', $detail->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div id="myModal" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Nhập góp ý của bạn</h4>
                            </div>
                            <div class="modal-body">
                                <div class="coment-old" style="margin-bottom: 20px;">
                                    <p><b>Những góp ý trước đó:</b></p>
                                    {!! $detail->comment !!}
                                </div>
                                <div class="">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <textarea name="comment" class="form-control" rows="5" id="comment"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" name="submit"
                                    value="deactive">Từ chối</button>
                                <button type="submit" class="btn btn-warning pull-right btn-sm mg-5" name="submit"
                                    value="rollback">Trả lại</button>
                                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-body">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a href="#tab_1" data-toggle="tab">
                                    <h5>Cập nhật thông tin <span class="text-danger">*</span></h5>
                                </a>
                            </li>
                            <li class="">
                                <a href="#tab_2" data-toggle="tab">
                                    <h5>Các góp ý</h5>
                                </a>
                            </li>
                            <?php if(Auth::guard('admin')->user()->is_super_admin == 0){ ?>
                            <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                                value="waiting">Lưu lại</button>
                            <?php }else if(Auth::guard('admin')->user()->is_super_admin == 2){ // Ban biên tập ?>
                            <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                                value="pending">Chờ Xuất bản</button>
                            <?php 
							// Ban biên tập không thể trả lại bài, từ chối cho chính mình được
							if(Auth::guard('admin')->user()->id != $detail->admin_created_id){ ?>
                            <button type="button" data-toggle="modal" data-target="#myModal"
                                class="btn btn-danger pull-right btn-sm mg-5">Từ chối</button>
                            <button type="button" data-toggle="modal" data-target="#myModal"
                                class="btn btn-info pull-right btn-sm mg-5">Trả lại</button>
                            <?php } ?>
                            <?php }else{ ?>

                            <?php if($detail->status == 1){?>
                            <button type="submit" class="btn btn-success pull-right btn-sm mg-5" name="submit"
                                value="active">Cập nhật</button>
                            <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" name="submit"
                                value="lock">Gỡ bỏ</button>
                            <?php } else{ ?>
                            <button type="submit" class="btn btn-success pull-right btn-sm mg-5" name="submit"
                                value="active">Xuất bản</button>
                            <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                                value="pending">Chờ Xuất bản</button>
                            <?php } ?>

                            <?php 
							// Ban biên tập không thể trả lại bài, từ chối cho chính mình được
							if(Auth::guard('admin')->user()->id != $detail->admin_created_id){ ?>
                            <button type="button" data-toggle="modal" data-target="#myModal"
                                class="btn btn-danger pull-right btn-sm mg-5">Từ chối</button>
                            <button type="button" data-toggle="modal" data-target="#myModal"
                                class="btn btn-info pull-right btn-sm mg-5">Trả lại</button>
                            <?php } ?>
                            <?php } ?>
                            <?php if($detail->status != 1 and Auth::guard('admin')->user()->id == $detail->admin_created_id){ ?>
                            <button type="submit" class="btn btn-warning pull-right btn-sm mg-5" name="submit"
                                value="draft">Lưu nháp</button>
                            <?php } ?>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_1">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Tên sách <small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="title"
                                                placeholder="@lang('Tên sách')" value="{{ $detail->title }}" required
                                                id="txtTitle" onchange="getUrlPart('txtTitle','txtUrlPart')"
                                                onclick="getUrlPart('txtTitle','txtUrlPart')"
                                                onblur="getUrlPart('txtTitle','txtUrlPart')">

                                            <input type="hidden" class="form-control" id="txtUrlPart" name="alias"
                                                placeholder="@lang('Alias')" value="{{ $detail->alias }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Tác giả chính <small class="text-red">*</small></label>
                                            <span class=" pull-right"><a class="btn btn-xs btn-warning"
                                                    data-toggle="modal" data-target="#createAuthor"><i
                                                        class="fa fa-plus"></i> Tác giả</a></span>
                                            <select name="main_author" id="main_author" class="form-control select2"
                                                required style="width: 100%">
                                                <option value="">-Chọn tác giả chính-</option>
                                                @foreach ($authors as $author)
                                                    <option value="{{ $author->id }}" <?php if ($author->id == $detail->main_author) {
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
                                                <option value="{{ $size->id }}" <?php if ($size->id == $detail->book_size) {
                                                    echo 'selected';
                                                } ?>>
                                                    {{ $size->width . 'x' . $size->height }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Giá bán <small class="text-red">*</small></label>
                                            <input type="number" class="form-control" name="price"
                                                placeholder="Giá bán" value="{{ $detail->price }}">
                                        </div>

                                        {{-- <div class="form-group">
                                            <label>Số trang</label>
                                            <input type="number" class="form-control" name="number_page"
                                                placeholder="Số trang" value="{{ $detail->number_page }}">
                                        </div> --}}

                                        <div class="form-group">
                                            <label>% Đơn sách giấy</label>
                                            <input type="number" class="form-control" name="percent_paper"
                                                placeholder="% đơn sách giấy" value="{{ $detail->percent_paper }}">
                                        </div>

                                        <div class="form-group">
                                            <label>% Đơn Ebook</label>
                                            <input type="number" class="form-control" name="percent_ebook"
                                                placeholder="% đơn sách giấy" value="{{ $detail->percent_ebook }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Nhà xuất bản</label>
                                            <select name="publisher" id="publisher" class="form-control select2"
                                                style="width: 100%">
                                                <option value="">-Chọn Nhà xuất bản-</option>
                                                <?php foreach($managershop as $shop){ ?>
                                                <option value="{{ $shop->id }}" <?php if ($detail->publisher == $shop->id) {
                                                    echo 'selected';
                                                } ?>>
                                                    {{ $shop->title }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Mã ISBN</label>
                                            <input type="text" class="form-control" name="isbn"
                                                placeholder="Mã ISBN" value="{{ $detail->isbn }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Năm xb sách giấy</label>
                                            <input type="text" class="form-control" name="publishing_year"
                                                placeholder="Năm xuất bản sách giấy"
                                                value="{{ $detail->publishing_year }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Ngôn ngữ</label>
                                            <input type="text" class="form-control" name="language"
                                                placeholder="Nhập ngôn ngữ"
                                                value="{{ $detail->language ?? old('language') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Loại xuất bản phẩm</label>
                                            <select class="form-control select2" name="publication_type"
                                                style="width: 100%">
                                                <option value="0">Chọn loại xuất bản phẩm</option>
                                                <option value="1" <?php if ($detail->publication_type == 1) {
                                                    echo 'selected';
                                                } ?>>Sách liên kết xuất bản</option>
                                                <option value="2" <?php if ($detail->publication_type == 1) {
                                                    echo 'selected';
                                                } ?>>Tự phát hành</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Danh mục') <small class="text-red">*</small></label>
                                            <select name="category" id="categorys" class="form-control select2" required
                                                style="width: 100%">

                                                @foreach ($parents as $item)
                                                    @if ($item->parent_id == 0 || $item->parent_id == null)
                                                        <option value="{{ $item->id }}" <?php if ($item->id == $detail->category) {
                                                            echo 'selected';
                                                        } ?>>
                                                            {{ $item->title->$locale }}</option>

                                                        @foreach ($parents as $sub)
                                                            @if ($item->id == $sub->parent_id)
                                                                <option value="{{ $sub->id }}" <?php if ($sub->id == $detail->category) {
                                                                    echo 'selected';
                                                                } ?>>-
                                                                    - {{ $sub->title->$locale }}
                                                                </option>

                                                                @foreach ($parents as $sub_child)
                                                                    @if ($sub->id == $sub_child->parent_id)
                                                                        <option value="{{ $sub_child->id }}"
                                                                            <?php if ($sub_child->id == $detail->category) {
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
                                                    <option value="{{ $author->id }}" <?php if (in_array($author->id, $arr_authors)) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $author->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Tags</label>
                                            <select name="tags[]" id="tags" class="form-control select2" multiple
                                                style="width: 100%">
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag->id }}" <?php if (in_array($tag->id, $arr_tags)) {
                                                        echo 'selected';
                                                    } ?>>
                                                        {{ $tag->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Giá bìa sách</label>
                                            <input type="number" class="form-control" name="cover_price"
                                                placeholder="Giá bìa sách" value="{{ $detail->cover_price }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Dung lượng Audio(MB):</label>
                                            <input type="number" class="form-control" name="capacity"
                                                placeholder="Dung lượng" value="{{ $detail->capacity }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Người dịch</label>
                                            <input type="text" class="form-control" name="translater"
                                                placeholder="Người dịch" value="{{ $detail->translater }}">
                                        </div>

                                        <div class="form-group">
                                            <label>% Đơn audio</label>
                                            <input type="number" class="form-control" name="percent_audio"
                                                placeholder="% Đơn audio" value="{{ $detail->percent_audio }}">
                                        </div>

                                        <div class="form-group">
                                            <label>% Gói đọc</label>
                                            <input type="number" class="form-control" name="percent_ebook_package"
                                                placeholder="% Gói đọc" value="{{ $detail->percent_ebook_package }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Mã ISBN Ebook</label>
                                            <input type="text" class="form-control" name="isbne"
                                                placeholder="Mã ISBN Ebook" value="{{ $detail->isbne }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Năm xb Ebook</label>
                                            <input type="text" class="form-control" name="publishing_year_ebook"
                                                placeholder="Năm xuất bản Ebook"
                                                value="{{ $detail->publishing_year_ebook }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Quốc gia</label>
                                            <input type="text" class="form-control" name="country"
                                                placeholder="Quốc gia" value="{{ $detail->country }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Thời hạn bản quyền</label>
                                            <input type="date" class="form-control" name="copyright_period"
                                                placeholder="Thời hạn bản quyền" value="{{ $detail->copyright_period }}">
                                        </div>

                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>File pdf</label>
                                            <input class="form-control" type="file" accept=".pdf" id="fileInput"
                                                name="filepdf" placeholder="@lang('image_link')...">
                                            <input type="text" class="form-control" value="{{ $detail->filepdf }}">
                                        </div>

                                        <div class="form-group hidden">
                                            <label>File đầy đủ</label>
                                            <input class="form-control" type="file" name="file_other"
                                                placeholder="@lang('image_link')...">
                                            <input type="text" class="form-control"
                                                value="{{ $detail->file_other }}">
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
                                                    placeholder="@lang('image_link')..." value="{{ $detail->image }}">
                                            </div>
                                            <div id="image-holder" style="margin-top:15px;max-height:100px;">
                                                @if ($detail->image != '')
                                                    <img id="view_image" style="max-height: 5rem;"
                                                        src="{{ $detail->image }}">
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
                                                        placeholder="Giới hạn trang" value="{{ $detail->limit_page }}">
                                                </div>

                                            </div>

                                            <div class="col-xs-6">

                                                <div class="form-group">
                                                    <label>Tổng số trang</label>
                                                    <input type="number" class="form-control" name="number_page"
                                                        placeholder="Tổng số trang" value="{{ $detail->number_page }}">
                                                </div>

                                            </div>

                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Thứ tự </label>
                                                    <input type="number" class="form-control" name="iorder"
                                                        placeholder="Thứ tự" value="{{ $detail->iorder }}">
                                                </div>

                                            </div>

                                            <div class="col-xs-6">
                                                <div class="form-group">
                                                    <label>Quyền xem <small class="text-red">*</small></label>
                                                    <div class="form-control">
                                                        <label>
                                                            <input type="radio" name="is_public" value="0"
                                                                <?php if ($detail->is_public == 0) {
                                                                    echo 'checked';
                                                                } ?>>
                                                            <small>Công khai</small>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="is_public" value="1"
                                                                <?php if ($detail->is_public == 1) {
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
                                                                <?php if ($detail->status_hang == 0) {
                                                                    echo 'checked';
                                                                } ?>>
                                                            <small>Hết hàng</small>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="status_hang" value="1"
                                                                <?php if ($detail->status_hang == 1) {
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

                                <?php $hienthi = explode(',', trim($detail->hienthi, ',')); ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-control">
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_0" name="hienthi[]"
                                                        value="0" <?php if (in_array('0', $hienthi)) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <label for="vehicle1"> Sách nổi bật</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_1" name="hienthi[]"
                                                        value="1" <?php if (in_array('1', $hienthi)) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <label for="vehicle1"> Sách hay</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_2" name="hienthi[]"
                                                        value="2" <?php if (in_array('2', $hienthi)) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <label for="vehicle1"> Sách mới</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_3" name="hienthi[]"
                                                        value="3" <?php if (in_array('3', $hienthi)) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <label for="vehicle1"> Có thể bạn thích</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_4" name="hienthi[]"
                                                        value="4" <?php if (in_array('4', $hienthi)) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <label for="vehicle1"> Sắp phát hành</label>
                                                </div>
                                                <div class="col-md-2 col-sm-4 col-xs-6">
                                                    <input type="checkbox" id="hienthi_5" name="hienthi[]"
                                                        value="5" <?php if (in_array('5', $hienthi)) {
                                                            echo 'checked';
                                                        } ?>>
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
                                                                    value="true" <?php if (isset($detail->json_params[$key]->paper) and $detail->json_params[$key]->paper == 'true') {
                                                                        echo 'checked';
                                                                    } ?>>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" id="IsEbook"
                                                                    name="json_params[<?= $key ?>][ebook]" type="checkbox"
                                                                    value="true" <?php if (isset($detail->json_params[$key]->ebook) and $detail->json_params[$key]->ebook == 'true') {
                                                                        echo 'checked';
                                                                    } ?>>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" id="IsAudio"
                                                                    name="json_params[<?= $key ?>][audio]" type="checkbox"
                                                                    value="true" <?php if (isset($detail->json_params[$key]->audio) and $detail->json_params[$key]->audio == 'true') {
                                                                        echo 'checked';
                                                                    } ?>>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="custom-control custom-checkbox">
                                                                <input class="custom-control-input" id="IsVideo"
                                                                    name="json_params[<?= $key ?>][video]" type="checkbox"
                                                                    value="true" <?php if (isset($detail->json_params[$key]->video) and $detail->json_params[$key]->video == 'true') {
                                                                        echo 'checked';
                                                                    } ?>>
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
                                                <textarea name="brief" class="form-control" rows="3" id="brief">{{ $detail->brief }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Chi tiết:</label>
                                                <textarea name="description" class="form-control" id="description">{{ $detail->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Mục lục:</label>
                                                <textarea name="detail" class="form-control" id="detail">{{ $detail->detail }}</textarea>
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
                                                    value="{{ $detail->seo_title }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>SEO Description:</label>
                                                <input name="seo_description" class="form-control" id="seo_description"
                                                    value="{{ $detail->seo_description }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>SEO Keyword:</label>
                                                <input name="seo_keyword" class="form-control" rows="3"
                                                    id="brief" value="{{ $detail->seo_keyword }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="tab_2" style="min-height: 250px">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="coment-old" style="margin-bottom: 20px;">
                                            <p><b>Những góp ý trước đó:</b></p>
                                            {!! $detail->comment !!}
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
                    <?php }else if(Auth::guard('admin')->user()->is_super_admin == 2){ // Ban biên tập ?>
                    <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                        value="pending">Chờ Xuất bản</button>
                    <?php 
          	// Ban biên tập không thể trả lại bài, từ chối cho chính mình được
          	if(Auth::guard('admin')->user()->id != $detail->admin_created_id){ ?>
                    <button type="button" data-toggle="modal" data-target="#myModal"
                        class="btn btn-danger pull-right btn-sm mg-5">Từ chối</button>
                    <button type="button" data-toggle="modal" data-target="#myModal"
                        class="btn btn-info pull-right btn-sm mg-5">Trả lại</button>
                    <?php } ?>
                    <?php }else{ ?>

                    <?php if($detail->status == 1){?>
                    <button type="submit" class="btn btn-success pull-right btn-sm mg-5" name="submit"
                        value="active">Cập nhật</button>
                    <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" name="submit"
                        value="lock">Gỡ bỏ</button>
                    <?php } else{ ?>
                    <button type="submit" class="btn btn-success pull-right btn-sm mg-5" name="submit"
                        value="active">Xuất bản</button>
                    <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" name="submit"
                        value="pending">Chờ Xuất bản</button>
                    <?php } ?>

                    <?php 
          	// Ban biên tập không thể trả lại bài, từ chối cho chính mình được
          	if(Auth::guard('admin')->user()->id != $detail->admin_created_id){ ?>
                    <button type="button" data-toggle="modal" data-target="#myModal"
                        class="btn btn-danger pull-right btn-sm mg-5">Từ chối</button>
                    <button type="button" data-toggle="modal" data-target="#myModal"
                        class="btn btn-info pull-right btn-sm mg-5">Trả lại</button>
                    <?php } ?>
                    <?php } ?>
                    <?php if($detail->status != 1 and Auth::guard('admin')->user()->id == $detail->admin_created_id){ ?>
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
    </script>
    <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type !== 'application/pdf') {
                alert('Vui lòng chọn file PDF...');
                e.target.value = '';
            }
        })
    </script>
@endsection
