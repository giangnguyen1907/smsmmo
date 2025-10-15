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
                                            <label>Tên gói<small class="text-red">*</small></label>
                                            <input type="text" class="form-control" name="title" placeholder="Tên gói"
                                                value="{{ $detail->title ?? old('title') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Định dạng<small class="text-red">*</small></label>
                                            <select name="book_type" class="form-control">
                                                <?php foreach($array_booktype as $key_type => $title_type){ ?>
                                                <option value="{{ $key_type }}" <?php if ($detail->book_type == $key_type) {
                                                    echo 'selected';
                                                } ?>> {{ $title_type }}
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Thời gian (ngày)<small class="text-red">*</small></label>
                                            <input type="number" class="form-control" name="time"
                                                placeholder="Thời gian" value="{{ $detail->time ?? old('time') }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Công thức tính giá<small class="text-red">*</small></label>
                                            <select name="recipe" class="form-control">
                                                <?php foreach($array_recipe as $key=>$title_recipr){ ?>
                                                <option value="<?= $key ?>" <?php if ($detail->recipe == $key) {
                                                    echo 'selected';
                                                } ?>> <?= $title_recipr ?>
                                                </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Giá (1 trang/MB)<small class="text-red">*</small></label>
                                            <input type="number" class="form-control" name="price"
                                                placeholder="Giá (1 trang/MB)" value="{{ $detail->price ?? old('price') }}"
                                                required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>X% giá sách giấy<small class="text-red">*</small></label>
                                            <input type="number" class="form-control" name="percent"
                                                placeholder="X% giá sách giấy"
                                                value="{{ $detail->percent ?? old('percent') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Làm tròn giá<small class="text-red">*</small></label>
                                            <select name="rounding" class="form-control">
                                                <?php foreach($array_rounding as $key_round => $title_round){ ?>
                                                <option value="{{ $key_round }}" <?php if ($detail->rounding == $key_round) {
                                                    echo 'selected';
                                                } ?>>
                                                    {{ $title_round }} </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Giá tối thiểu<small class="text-red">*</small></label>
                                            <input type="number" class="form-control" name="min_price"
                                                placeholder="Giá tối thiểu"
                                                value="{{ $detail->min_price ?? old('min_price') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Trạng thái</label>
                                            <div class="form-control">
                                                <label>
                                                    <input type="radio" name="status" value="1"
                                                        <?php if ($detail->status == 1) {
                                                            echo 'checked';
                                                        } ?>>
                                                    <small>Hiển thị</small>
                                                </label>
                                                <label>
                                                    <input type="radio" name="status" value="0"
                                                        <?php if ($detail->status == 0) {
                                                            echo 'checked';
                                                        } ?> class="ml-15">
                                                    <small>Không hiển thị</small>
                                                </label>
                                            </div>
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
