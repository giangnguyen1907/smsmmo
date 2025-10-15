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
    $array_location = APP\Consts::POST_POSITION;
    @endphp
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
              
              <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" id="capnhat" name="submit" value="news_edit">Cập nhật</button>
              
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-8 col-lg-8 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <div class="form-group">
                        <label>@lang('Content'): </label>
                        <textarea name="content" class="form-control" id="content_vi">{{ $detail->content }}</textarea>
                      </div>
                    </div>

                    <div class="form-group hidden">
                      <label>@lang('Tin liên quan')</label>
                      <button onclick="addNewRelative()" type="button" class="btn btn-sm btn-primary">Chọn</button>
                      <ul id="list_relative">
                        @foreach($relative_posts as $relative)
                        <li id='addRow_{{ $relative->id }}'><input type='checkbox' checked onclick='removePostReative({{ $relative->id }})' name='relation[]' value='{{ $relative->id }}' id='relative_{{ $relative->id }}'/><span>{{ $relative->title }}</span></li>
                        @endforeach
                      </ul>
                    </div>

                    <div class="form-group hidden">
                      <label>@lang('seo_title')</label>
                      <input id="txtSeoTitle" maxlength="160" name="seo_title" class="form-control" value="{{ $detail->seo_title }}"
                        onkeyup="demKytu('txtSeoTitle','remainingInput_seoTitle');" onblur="demKytu('txtSeoTitle','remainingInput_seoTitle');" onclick="demKytu('txtSeoTitle','remainingInput_seoTitle');">
                        <span id='remainingInput_seoTitle' class="note pull-right">{{ mb_strlen($detail->seo_title) }}/160</span>
                    </div>
                    <div class="form-group hidden">
                      <label>@lang('seo_keyword')</label>
                      <input id="txtSeoKeyword" maxlength="255" name="seo_keyword" class="form-control" value="{{ $detail->seo_keyword }}"
                      onkeyup="demKytu('txtSeoKeyword','remainingInput_seoKeyword');" onblur="demKytu('txtSeoKeyword','remainingInput_seoKeyword');" onclick="demKytu('txtSeoKeyword','remainingInput_seoKeyword');">
                        <span id='remainingInput_seoKeyword' class="note pull-right">{{ mb_strlen($detail->seo_keyword) }}/255</span>
                    </div>
                    <div class="form-group hidden">
                      <label>@lang('seo_description')</label>
                      <input id="txtSeoDescription" maxlength="255" name="seo_description" class="form-control" value="{{ $detail->seo_description }}" 
                      onkeyup="demKytu('txtSeoDescription','remainingInput_seoDescription');" onblur="demKytu('txtSeoDescription','remainingInput_seoDescription');" onclick="demKytu('txtSeoDescription','remainingInput_seoDescription');">
                        <span id='remainingInput_seoDescription' class="note pull-right">{{ mb_strlen($detail->seo_description) }}/255</span>
                    </div>
                    

                    <div class="form-group">
                      <label>@lang('Danh sách hình ảnh')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image_list" onclick="openPopupMultiImg('image_list')" data-preview="image_list-holder" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                      </div>
                    </div>

                    <div class="col-md-12" id="frames">
                      <?php $i = 0;
                      foreach ($images as $key => $image) {
                          $i++;
                          ?>
                          <div class="form-group" id ="<?= $key?>">
                              {{-- <div class="col-md-2" style="padding-left:0px;">
                                  <image id="srcImage_<?= $key?>" src="<?= $image->link_file ?>" style="height:100px; width:100%;" />
                              </div> --}}
                              <div class="col-md-6" style="padding-left:0px;">
                                  <input class="form-control" type="text" readonly name="imagelist[<?= $key?>]" id="imagelist_<?= $key?>" txthide="imagelist_<?= $key?>" placeholder="Đường dẫn <?= $key?> cho sản phẩm" value="<?= $image->link_image ?>" />
                              </div>
                              <div class="col-md-2" style="padding-left:0px;">
                                  <input class="btn btn-info form-control" onclick="openPopupImg2(<?= $key?>)" txthide="imagelist_<?= $key?>" type="button" value="Chọn file <?= $key?>" name="imageadd[<?= $key?>]" id="imageadd_<?= $key?>" />
                              </div>

                              <div class="col-md-2" style="padding-left:0px;">
                                  <input class="btn btn-danger form-control"  onclick="delete_img(<?= $key?>)" value="Xóa <?= $key?>"/>
                              </div>
                          </div>

                      <?php } ?>
                    </div>


                    <div class="form-group">
                      <label>@lang('Danh sách video')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="document_list" onclick="openPopupMultiDocument('document_list')" data-preview="document_list-holder" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                      </div>
                    </div>

                    <div class="col-md-12" id="frames_document">
                      <?php $i = $i - 1;
                      foreach ($document as $key1 => $documents) {
                          $i++;
                          ?>
                          <div class="form-group" id ="<?= $i?>">
                              <div class="col-md-6" style="padding-left:0px;">
                                  <input class="form-control" type="text" readonly name="documentlist[<?= $i?>]" id="documentlist_<?= $i?>" txthide="documentlist_<?= $i?>" placeholder="Đường dẫn <?= $i?> cho sản phẩm" value="<?= $documents->link_file ?>" />
                              </div>
                              <div class="col-md-2" style="padding-left:0px;">
                                  <input class="btn btn-info form-control" onclick="openPopupDocument2(<?= $i?>)" txthide="documentlist_<?= $i?>" type="button" value="Chọn file <?= $i?>" name="imageadd[<?= $i?>]" id="imageadd_<?= $i?>" />
                              </div>
                              <div class="col-md-2" style="padding-left:0px;">
                                  <input class="btn btn-danger form-control"  onclick="delete_document(<?= $i?>)" value="Xóa <?= $i?>"/>
                              </div>
                          </div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                      <label>@lang('Title') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" maxlength="160" id="txtTitle" onchange="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onclick="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" onblur="demKytu('txtTitle','remainingInput_text');getUrlPart('txtTitle','txtUrlPart')" name="title" placeholder="@lang('Title')"
                        value="{{ $detail->title }}" required>
                        <span id='remainingInput_text' class="note pull-right">{{ mb_strlen($detail->title) }}/160</span>
                    </div>
                    <div class="form-group">
                      <label>@lang('alias') <small class="text-red">*</small></label>
                      <input type="text" class="form-control" id="txtUrlPart" onchange="getUrlPart('txtUrlPart','txtUrlPart')" onclick="getUrlPart('txtUrlPart','txtUrlPart')" onblur="getUrlPart('txtTitle','txtUrlPart')" name="url_part" placeholder="@lang('Alias')"
                        value="{{ $detail->url_part }}" required>
                    </div>
                    <div class="form-group">
                      <label>@lang('Description')</label>
                      <textarea name="brief" id="description" class="form-control" maxlength="255" onkeyup="demKytu('description','remainingInput_textarea');" onblur="demKytu('description','remainingInput_textarea');" onclick="demKytu('description','remainingInput_textarea');" rows="5">{{ $detail->brief }}</textarea>
                      <span id='remainingInput_textarea' class="note pull-right">{{ mb_strlen($detail->brief) }}/255</span>
                    </div>
                    <div class="form-group">
                      <label>@lang('Danh mục') <small class="text-red">*</small></label>
                      <select name="taxonomy_id" id="taxonomy_id" class="form-control select2" style="width:100%">
                        
                        @foreach ($parents as $item)
                          @if ($item->parent_id == 0 || $item->parent_id == null)
                            <option value="{{ $item->id }}" {{ $detail->taxonomy_id == $item->id ? 'selected' : '' }}>{{ $item->title }}</option>

                            @foreach ($parents as $sub)
                              @if ($item->id == $sub->parent_id)
                                <option value="{{ $sub->id }}"
                                  {{ $detail->taxonomy_id == $sub->id ? 'selected' : '' }}>- - {{ $sub->title }}
                                </option>

                                @foreach ($parents as $sub_child)
                                  @if ($sub->id == $sub_child->parent_id)
                                    <option value="{{ $sub_child->id }}"
                                      {{ $detail->taxonomy_id == $sub_child->id ? 'selected' : '' }}>- - - -
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
                      <label>@lang('Chọn loại tin') <small class="text-red">*</small></label>
                      <select name="news_position" class="form-control" id="news_position">
                        @foreach($array_location as $key=>$position_text)
                        <option value="{{ $key }}" {{ $detail->news_position == $key ? 'selected' : '' }}>{{ $position_text }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>@lang('Ảnh đại diện')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image" data-preview="image-holder" onclick="openPopupImg('image')" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="image" class="form-control" type="text" name="image"
                          placeholder="@lang('image_link')..." value="{{ $detail->image }}">
                      </div>
                      <div id="image-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image != '')
                          <img id="view_image" style="height: 5rem;" src="{{ $detail->image }}">
                        @endif
                        
                      </div>
                    </div>

                    <div class="form-group">
                      <label>@lang('Mã nhúng video yotube')</label>
                      <input type="text" class="form-control" id="iframe_video" name="iframe_video" placeholder="@lang('Mã nhúng video')"
                        value="{{ $detail->iframe_video }}">
                    </div>
                    {{-- <div class="form-group">
                      <label>@lang('Image thumb')</label>
                      <div class="input-group">
                        <span class="input-group-btn">
                          <a data-input="image_thumb"  onclick="openPopupImg('image_thumb')"  data-preview="image_thumb-holder" class="btn btn-primary lfm"
                            data-type="cms-image">
                            <i class="fa fa-picture-o"></i> @lang('choose')
                          </a>
                        </span>
                        <input id="image_thumb" class="form-control" type="text" name="image_thumb"
                          placeholder="@lang('image_link')..." value="{{ $detail->image_thumb }}">
                      </div>
                      <div id="image_thumb-holder" style="margin-top:15px;max-height:100px;">
                        @if ($detail->image_thumb != '')
                          <img id="view_image_thumb" style="height: 5rem;" src="{{ $detail->image_thumb }}">
                        @endif
                        
                      </div>
                    </div> --}}
                    
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
			
      <button type="submit" class="btn btn-primary pull-right btn-sm mg-5" id="capnhat" name="submit" value="news_edit">Cập nhật</button>
						
        </div>
      </form>
    </div>
  </section>
<script type="text/javascript">

  <?php if(count($images) > 0) { ?>
    var index = <?= count($images)?>;
  <?php }else{ ?>
    var index = <?= count($document)?>;
  <?php } ?>

  function openPopupMultiImg(id) {
      CKFinder.popup( {
          chooseFiles: true,
          onInit: function( finder ) {
              finder.on( 'files:choose', function( evt ) {

                  // update multifile
                  var files = evt.data.files;
                  files.forEach( function( file, i ) {

                      $("#frames")
                          .append('<div class="form-group" id ="'+ index +'">' +
                              // '<div class="col-md-2" style="padding-left:0px;">' +
                              // '<image id="srcImage_'+ index +'" src="'+ file.getUrl() +'" style="height:100px; width:100%;" />'+
                              // '</div>' +
                              '<div class="col-md-6" style="padding-left:0px;">' +
                              '<input class="form-control" type="text" readonly name="imagelist['+ index +']" id="imagelist_'+ index +'" txthide="imagelist_'+ index +'" ondblclick="fcall.fcChoiceImg(this);" placeholder="Đường dẫn '+ index +'" value="'+ file.getUrl() +'" />' +
                              '</div>' +
                              '<div class="col-md-2" style="padding-left:0px;">' +
                              '<input class="btn btn-info form-control" onclick="openPopupImg2('+ index +')" txthide="imagelist_'+ index +'" type="button" value="Chọn file '+ index +'" name="imageadd['+ index +']" id="imageadd_'+ index +'" />' +
                              '</div>' +

                              '<div class="col-md-2" style="padding-left:0px;">' +
                              '<input class="btn btn-danger form-control"  onclick="delete_img('+ index +')" value="Xóa '+ index +'"/>' +
                              '</div>' +
                              '</div>');
                      index++;
                  } );
              } );
              finder.on( 'file:choose:resizedImage', function( evt ) {
                  document.getElementById( id ).value = evt.data.resizedUrl;
              } );
          }
      } );
  }
  function openPopupImg2(id) {
    CKFinder.popup( {
        chooseFiles: true,
        onInit: function( finder ) {
            finder.on( 'files:choose', function( evt ) {
                var file = evt.data.files.first();
                console.log(evt.data.files);

                document.getElementById( "imagelist_" + id ).value = file.getUrl();
                // document.getElementById( "srcImage_" + id ).src = file.getUrl();


            } );
            finder.on( 'file:choose:resizedImage', function( evt ) {
                document.getElementById( id ).value = evt.data.resizedUrl;
            } );
        }
    } );
}
 function delete_img(id){
      if(confirm("Bạn có muốn xóa")){
          $("#"+id).remove();
          document.getElementById('remove').value  += ","+id;
      }
  }


  // var index_document = <?= count($document)?>;
  function openPopupMultiDocument(id) {
  CKFinder.popup( {
      chooseFiles: true,
      onInit: function( finder ) {
          finder.on( 'files:choose', function( evt ) {

              // update multifile
              var files = evt.data.files;
              files.forEach( function( file, i ) {

                  $("#frames_document")
                      .append('<div class="form-group" id ="'+ index +'">' +
                          '<div class="col-md-6" style="padding-left:0px;">' +
                          '<input class="form-control" type="text" readonly name="documentlist['+ index +']" id="documentlist_'+ index +'" txthide="documentlist_'+ index +'" placeholder="Đường dẫn '+ index +'" value="'+ file.getUrl() +'" />' +
                          '</div>' +
                          '<div class="col-md-2" style="padding-left:0px;">' +
                          '<input class="btn btn-info form-control" onclick="openPopupDocument2('+ index +')" txthide="documentlist_'+ index +'" type="button" value="Chọn file '+ index +'" name="imageadd['+ index +']" id="imageadd_'+ index +'" />' +
                          '</div>' +

                          '<div class="col-md-2" style="padding-left:0px;">' +
                          '<input class="btn btn-danger form-control"  onclick="delete_document('+ index +')" value="Xóa'+ index +'"/>' +
                          '</div>' +
                          '</div>');
                  index++;
              } );
          } );
          finder.on( 'file:choose:resizedImage', function( evt ) {
              document.getElementById( id ).value = evt.data.resizedUrl;
          } );
      }
  } );
}
  function delete_document(id){
      if(confirm("Bạn có muốn xóa")){
          $("#"+id).remove();
          document.getElementById('remove').value  += ","+id;
      }
  }
   function openPopupDocument2(id) {
      CKFinder.popup( {
          chooseFiles: true,
          onInit: function( finder ) {
              finder.on( 'files:choose', function( evt ) {
                  var file = evt.data.files.first();
                  console.log(evt.data.files);

                  document.getElementById( "documentlist_" + id ).value = file.getUrl();
                  // document.getElementById( "srcImage_" + id ).src = file.getUrl();


              } );
              finder.on( 'file:choose:resizedImage', function( evt ) {
                  document.getElementById( id ).value = evt.data.resizedUrl;
              } );
          }
      } );
  }     
</script>

  <div class="modal modal-login fade" id="postnewModal" tabindex="-1" role="dialog" aria-labelledby="postnewModalModalLabel" aria-hidden="true">
    
    @csrf
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="loginModalLabel">Chọn tin liên quan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -22px;">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          
          <div class="input-group">
            <input id="keyword" type="text" class="form-control" name="search" placeholder="Keyword...">
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
              @foreach($cms_posts as $post)
              <tr>
                <td>{{ $post->title }}</td>
                <td>
                  <input type="checkbox" onclick="checkPostReative({{ $post->id }})" id="post_id_{{ $post->id }}" name="check_realtive[]" value="{{ $post->id }}" />
                  <input type="hidden" id="post_title_{{ $post->id }}" value="{{ $post->title }}" />
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <div class="text-right">
            <button type="buttom" name="btnLogin" id="btnLogin" class="btn btn-primary" class="close" data-dismiss="modal" aria-label="Close" spellcheck="false">Đóng</button>
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
  
  function searchPost(){
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


  
      CKEDITOR.replace('content_vi', ck_options); 


</script>

@endsection
