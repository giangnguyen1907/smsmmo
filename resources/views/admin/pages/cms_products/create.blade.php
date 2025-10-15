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
                      <label>@lang('Product category') <small class="text-red">*</small></label>
                      <select name="taxonomy_id" id="taxonomy_id" class="form-control select2" style="width: 100%">
                        <option value="">@lang('Please select')</option>
                        @foreach ($parents as $item)
                          @if ($item->parent_id == 0 || $item->parent_id == null)
                            <option value="{{ $item->id }}" {{ old('taxonomy_id') == $item->id ? 'selected' : '' }}>
                              {{ $item->title }}</option>

                            @foreach ($parents as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}"
                                  {{ old('taxonomy_id') == $sub->id ? 'selected' : '' }}>- - {{ $sub->title }}
                                </option>

                                @foreach ($parents as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}"
                                      {{ old('taxonomy_id') == $sub_child->id ? 'selected' : '' }}>- - - -
                                      {{ $sub_child->title }}</option>
                                  @endif
                                @endforeach
                              @endif
                            @endforeach
                          @endif
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>@lang('Title') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" maxlength="255" id="txtTitle" onchange="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onclick="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onblur="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" name="title" placeholder="@lang('Title')"
                        value="{{ old('title') }}" required>
                        <span id='remainingInput_text' class="note pull-right">{{ mb_strlen(old('title')) }}/255</span>
                    </div>
					<div class="form-group">
                      <label>@lang('Mô tả')</label>
                      <textarea name="mota" id="mota" maxlength="255" onkeyup="demKytu('description','remainingInput_textarea');" onblur="demKytu('description','remainingInput_textarea');" onclick="demKytu('description','remainingInput_textarea');"  class="form-control" rows="5">{{ old('mota') }}</textarea>
                        <span id='remainingInput_textarea' class="note pull-right">{{ mb_strlen(old('mota')) }}/255</span>
                    </div>
                    
                    <div class="form-group">
						 <label>@lang('Image')</label>
					  <div class="input-group">
						<span class="input-group-btn">
						  <a data-input="image" onclick="openPopupImg('image')" data-preview="image-holder" class="btn btn-primary lfm"
							data-type="cms-image">
							<i class="fa fa-picture-o"></i> @lang('choose')
						  </a>
						</span>
						<input id="image" class="form-control" type="text" name="image"
						  placeholder="@lang('image_link')...">
					  </div>
					  <div id="image-holder" style="margin-top:15px;max-height:100px;">
						@if (old('image') != '')
						<img id="view_image" style="height: 5rem;" src="{{ old('image') }}">
						@else
						<img id="view_image" style="height: 5rem;" >
						@endif
					  </div>
					</div>
					</div>
                   <div class="col-md-6">
                    
					<div class="form-group">
						<label>Vị trí hiển thị</label>
						<div class="form-control">
							<input type="checkbox" id="hienthi_0" name="hienthi[]" value="0">
							<label for="vehicle1"> SP nổi bật trang chủ</label>
							<input type="checkbox" class="ml-15" id="hienthi_1" name="hienthi[]" value="1">
							<label for="vehicle1"> SP nổi bật trang trong</label>
						</div>
                    </div>
                    <div class="form-group">
					  <label>@lang('alias') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" id="txtUrlPart" onchange="getUrlPart('txtUrlPart','txtUrlPart')" onclick="getUrlPart('txtUrlPart','txtUrlPart')" onblur="getUrlPart('txtUrlPart','txtUrlPart')" name="alias" placeholder="@lang('Alias')"
                        value="{{ old('alias') }}" required>
                    </div>
					<div class="form-group">
                      <div class="row">
                        <div class="col-xs-4">
                          <label>Giá</label>
                          <input type="text" class="form-control" name="gia"
                            placeholder="Giá" value="{{ old('gia') }}">
                        </div>
                        <div class="col-xs-4">
                          <label>Giá khuyến mại</label>
                          <input type="text" class="form-control" name="giakm"
                            placeholder="Giá khuyến mại" value="{{ old('giakm') }}">
                        </div>
						<div class="col-xs-4">
							<label>Thứ tự</label>
							<input type="number" name="iorder" class="form-control" value="{{ old('iorder') }}">
						</div>
                      </div>
                    </div>
					<div class="form-group">
						<div class="row">
							<div class="col-xs-6">
								<label>Tình trạng</label>
								  <div class="form-control">
									<label>
									  <input type="radio" name="tinhtrang" value="1" checked="">
									  <small>Còn hàng</small>
									</label>
									<label>
									  <input type="radio" name="tinhtrang" value="0" class="ml-15">
									  <small>Hết hàng</small>
									</label>
								  </div>
							</div>
							<div class="col-xs-6">
								<label>@lang('Status')</label>
								  <div class="form-control">
									<label>
									  <input type="radio" name="status" value="1" checked="">
									  <small>@lang('active')</small>
									</label>
									<label>
									  <input type="radio" name="status" value="0" class="ml-15">
									  <small>@lang('deactive')</small>
									</label>
								  </div>
							</div>
						</div>
                    </div>
					
					<div class="form-group hidden">
						<label>@lang('Image thumb')</label>
						  <div class="input-group">
							<span class="input-group-btn">
							  <a data-input="image_thumb" onclick="openPopupImg('image_thumb')" data-preview="image_thumb-holder" class="btn btn-primary lfm"
								data-type="cms-image">
								<i class="fa fa-picture-o"></i> @lang('choose')
							  </a>
							</span>
							<input id="image_thumb" class="form-control" type="text" name="image_thumb"
							  placeholder="@lang('image_link')...">
						  </div>
						  <div id="image_thumb-holder" style="margin-top:15px;max-height:100px;">
							@if (old('image_thumb') != '')
							  <img id="view_image_thumb" style="height: 5rem;" src="{{ old('image_thumb') }}">
							@else
							  <img id="view_image_thumb" style="height: 5rem;" >
							@endif
						  </div>
					</div>
                  </div>
				  
				  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('seo_title')</label>
                      <input name="meta_title" class="form-control" id="meta_title" value="{{ old('meta_title') }}"  onchange="demKytu('meta_title','remainingInput_meta_title')" onclick="demKytu('meta_title','remainingInput_meta_title')" onblur="demKytu('meta_title','remainingInput_meta_title')">
						<span id='remainingInput_meta_title' class="note pull-right">{{ mb_strlen(old('seo_title')) }}/255</span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>@lang('seo_keyword')</label>
                      <input name="meta_keyword" id="meta_keyword" class="form-control"  value="{{ old('meta_keyword') }}"
					  onchange="demKytu('meta_keyword','remainingInput_meta_keyword')" onclick="demKytu('meta_keyword','remainingInput_meta_keyword')" onblur="demKytu('meta_keyword','remainingInput_meta_keyword')">
						<span id='remainingInput_meta_keyword' class="note pull-right">{{ mb_strlen(old('meta_keyword')) }}/255</span>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>@lang('seo_description')</label>
                      <input name="meta_description" id="meta_description" class="form-control" value="{{ old('meta_description') }}"
					  onchange="demKytu('meta_description','remainingInput_meta_description')" onclick="demKytu('meta_description','remainingInput_meta_description')" onblur="demKytu('meta_description','remainingInput_meta_description')">
						<span id='remainingInput_meta_description' class="note pull-right">{{ mb_strlen(old('meta_description')) }}/255</span>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <hr style="border-top: dashed 2px #a94442; margin: 10px 0px;">
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Chi tiết</label>
                      <textarea name="chitiet" class="form-control" id="chitiet">{{ old('chitiet') }}</textarea>
                    </div>
                  </div>
				  <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label>Danh sách điểm bán</label>
                      <textarea name="diemban" class="form-control" id="diemban">{{ old('diemban') }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-6 col-xs-12">
                    <div class="form-group">
                      <label>Giấy tờ sản phẩm</label>
                      <textarea name="giayto" class="form-control" id="giayto">{{ old('giayto') }}</textarea>
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
    height: 305px !important;
</style>
@section('script')
  <script>
  ClassicEditor.create( document.querySelector( '#chitiet' ), {
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
          'LinkImage',
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
        toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full','imageStyle:side', 'imageStyle:alignCenter','linkImage'],
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
      
      
    } ) .then( editor => {
      window.editor = editor;
      
    } ) .catch( error => {
      console.error( 'Oops, something went wrong!' );
      console.error( 'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:' );
      console.warn( 'Build id: v10wxmoi2tig-mwzdvmyjd96s' );
      console.error( error );
    } );
	
	ClassicEditor.create( document.querySelector( '#diemban' ), {
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
          'LinkImage',
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
        toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full','imageStyle:side', 'imageStyle:alignCenter','linkImage'],
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
      
      
    } ) .then( editor => {
      window.editor = editor;
      
    } ) .catch( error => {
      
    } );
	
	ClassicEditor.create( document.querySelector( '#giayto' ), {
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
          'LinkImage',
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
        toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full','imageStyle:side', 'imageStyle:alignCenter','linkImage'],
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
      
      
    } ) .then( editor => {
      window.editor = editor;
      
    } ) .catch( error => {
      
    } );
</script>
@endsection
