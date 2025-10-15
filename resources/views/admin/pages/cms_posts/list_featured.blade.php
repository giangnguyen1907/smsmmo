@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <button class="btn btn-warning pull-right" data-toggle="modal" data-target="#showCNTT" onClick="showCNTT()" > @lang('Add') <i class="fa fa-plus"></i></button>
    </h1>
  </section>
@endsection

@php
$array_position = App\Consts::POST_POSITION_SORT;
$array_location = App\Consts::POST_POSITION;
@endphp

@section('content')

  <!-- Main content -->
  <section class="content">
    {{-- Search form --}}
    <div class="box box-default">

      <div class="box-header with-border">
        <h3 class="box-title">@lang('Filter')</h3>
        <div class="box-tools pull-right">
          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
      </div>
      <form action="{{ route(Request::segment(2) . '.index') }}" method="GET">
        <div class="box-body">
          <div class="row">

            <div class="col-md-3">
              <div class="form-group">
                <label>@lang('Keyword') </label>
                <input type="text" class="form-control" name="keyword" placeholder="@lang('keyword_note')"
                  value="{{ isset($params['keyword']) ? $params['keyword'] : '' }}">
              </div>
            </div>
				
            <div class="col-md-3">
              <div class="form-group">
                <label>@lang('Vị trí') </label>
                <select class="form-control" name="news_position" id="news_position">
                  @foreach($array_position as $key2=>$val)
                  <option value="{{ $key2 }}" {{ $params['news_position'] == $key2 ? 'selected' : '' }}>{{ $val }}</option>
                  @endforeach
                </select>
              </div>
            </div>
				
            <div class="col-md-3">
              <div class="form-group">
                <label>@lang('Post category')</label>
                <select name="taxonomy_id" id="taxonomy_id" class="form-control select2" style="width: 100%;">
                  <option value="">@lang('Please select')</option>
                  @foreach ($parents as $item)
                    @if ($item->parent_id == 0 || $item->parent_id == null)
                      <option value="{{ $item->id }}"
                        {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $item->id ? 'selected' : '' }}>
                        {{ $item->title }}</option>
                      @foreach ($parents as $sub)
                        @if ($item->id == $sub->parent_id)
                          <option value="{{ $sub->id }}"
                            {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $sub->id ? 'selected' : '' }}>
                            - -
                            {{ $sub->title }}
                          </option>
                          @foreach ($parents as $sub_child)
                            @if ($sub->id == $sub_child->parent_id)
                              <option value="{{ $sub_child->id }}"
                                {{ isset($params['taxonomy_id']) && $params['taxonomy_id'] == $sub_child->id ? 'selected' : '' }}>
                                - - - -
                                {{ $sub_child->title }}</option>
                            @endif
                          @endforeach
                        @endif
                      @endforeach
                    @endif
                  @endforeach
                </select>
              </div>
            </div>
            
            <div class="col-md-2">
              <div class="form-group">
                <label>@lang('Filter')</label>
                <div>
                  <button type="submit" class="btn btn-primary btn-sm mr-10">@lang('Submit')</button>
                  <a class="btn btn-default btn-sm" href="{{ route(Request::segment(2) . '.index') }}">
                    @lang('Reset')
                  </a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
    {{-- End search form --}}
    @php 
    $array_category = array();
    foreach ($parents as $item){
      $array_category[$item->id] = $item->title;
    }
    @endphp
    <div class="box">
      <div class="nav-tabs-custom">
    
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            <form id="form_news_update_iorder" action="{{ route('cms_posts.update_sort') }}" method="post">  
              @csrf
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

              @if (count($rows) == 0)
                <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  @lang('not_found')
                </div>
              @else
                <table id="table-2" class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th>@lang('STT')</th>
                      <th>@lang('Title')</th>
                      <th>@lang('Link')</th>
                      <th>@lang('Vị trí')</th>
                      <th>@lang('Thứ tự')</th>
                      <th>@lang('Post category')</th>
                      <th>@lang('Xuất bản')</th>
                      <th>@lang('Action')</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php $stt=0; ?>
                    @foreach ($rows as $row)
                      @if ($row->parent_id == 0 || $row->parent_id == null)
                          <?php $stt++; ?>
                          <tr id="{{ $row->id }}" class="valign-middle" style="cursor: grabbing;">
                            <td class="text-center">
                              {{ $stt }}
                            </td>  
                            <td>
                              <strong style="font-size: 14px;">{{ $row->title }}</strong>
                            </td>
                            @php
                              $url_mapping = url('');
                              $url_mapping .= '/';
                              $url_mapping .= App\Consts::ROUTE_PREFIX_TAXONOMY['post_detail'] . '/';
                              $url_mapping .= $row->url_part == '' ? Str::slug($row->title) : $row->url_part;
                              $url_mapping .= '.html';
                            @endphp
                            <td>
                              <a target="_new" href="{{ $url_mapping }}" data-toggle="tooltip" title="@lang('Link')"
                                data-original-title="@lang('Link')">
                                <span class="btn btn-flat btn-sm btn-info">
                                  <i class="fa fa-external-link"></i>
                                </span>
                              </a>
                            </td>
                            <td>
                              <select class="form-control" name="" id="tin_noi_bat_{{ $row->id }}" onchange="updatePosition({{ $row->id }})">
                                @foreach($array_location as $key=>$position)
                                <option value="{{ $key }}" {{ $row->news_position == $key ? 'selected' : '' }}>{{ $position }}</option>
                                @endforeach
                              </select>
                            </td>
                            <td class="text-center">
                              <input type="number" class="form-control" id="update_order_{{ $row->id }}" onblur="updateOrder({{ $row->id }})" value="{{ $row->iorder }}" style="width: 60px"/>
                              <img id="ic-loading_{{ $row->id }}" style="display: none;vertical-align: middle;" src="/images/load.gif" width="20px">
                            </td>
                            <td>
                              @php
                              $category = explode(',',trim($row->category,','));
                              foreach($category as $cat_id){
                                echo isset($array_category[$cat_id]) ? $array_category[$cat_id].', ' : '';
                              }
                              @endphp
                            </td>
                            <td>
                              {{ date('H:i d/m/Y',strtotime( $row->aproved_date)) }}
                            </td>
                            <td class="text-center">
                              <input type="number" class="form-control hidden" name="iorder[{{ $row->id }}]" id="iorder_{{ $row->id }}" value="{{ $stt }}"  />
                              <input type="checkbox" name="vitri[]" id="vitri_{{ $row->id }}" value="{{ $row->id }}">
                            </td>
                          </tr>
                      @endif
                    @endforeach
                  </tbody>
                </table>

              @endif
              <div class="box-footer clearfix">
                <div class="row">
                  <div class="col-sm-5">
                    
                  </div>
                  <div class="col-sm-7">
                    <div class="" style="float: right">
                      <input type="hidden" name="hid_remove" id="hid_remove" value="0">
                      <input type="button" onclick="setremove()" name="remove" class="btn btn-danger" value="Gỡ tin nổi bật">
                      <input type="submit" name="update_iorder" class="btn btn-danger" value="Cập nhật sắp xếp">
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          
        </div>

    </div>

    </div>
  </section>
  <script type="text/javascript">
    function updatePosition(id){
      var position = $('#tin_noi_bat_'+id).val();
      $('#ic-loading_'+id).show();
      $.ajax({
        url: '{{ route('cms_posts.update_position') }}',
        type: 'POST',
        data: {
        _token: '{{ csrf_token() }}',
        position: position,
        id: id,
        },
        context: document.body,
      }).done(function(data) {
        $('#ic-loading_'+id).hide();
        $('#tin_noi_bat_'+id).attr('readonly',true)
        //window.location.reload();
      });
    }

    function updateOrder(id){
      var stt_order = $('#update_order_'+id).val();
      $('#ic-loading_'+id).show();
      $.ajax({
        url: '{{ route('cms_posts.update_order') }}',
        type: 'POST',
        data: {
        _token: '{{ csrf_token() }}',
        stt_order: stt_order,
        id: id,
        },
        context: document.body,
      }).done(function(data) {
        $('#ic-loading_'+id).hide();
        //window.location.reload();
      });
    }
  
    function setremove(){
      $("#hid_remove").val("1");
      if(confirm("Bạn chắc chắn gỡ các tin đã chọn khỏi tin nổi bật ? "))
      $("#form_news_update_iorder").submit();	
      
    }
    $(document).ready(function() {
        table_2 = $("#table-2");
        table_2.find("tr:even").addClass("alt"); //alert("s");
        table_2.tableDnD({
            onDragClass: "myDragClass",
            onDrop: function(table, row) {
                var rows = table.tBodies[0].rows;
                var debugStr = "Row dropped was "+row.id+". New order: ";
                for (var i=0; i<rows.length; i++) {
                    debugStr += rows[i].id+" ";
                }
                // $(table).parent().find('.result').text(debugStr);
                // Xu ly o day
                // alert("Gọi ajax ở đây");
            },
            onDragStart: function(table, row) {
                //$(table).parent().find('.result').text("Started dragging row "+row.id);
            }
        });	
    });		

</script>

<div class="modal fade" id="showCNTT" data-backdrop="false" role="dialog" aria-hidden="true" style="display: none;"><div class="modal-dialog modal-wide">
	<form method="POST" action="{{ route('cms_posts.update_featured') }}" name="news_hot" id="news_hot" class="form-horizontal">
		@csrf
    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title">Thêm tin nổi bật</h4>
			</div>
			<div class="modal-body modal-scroll">
				<div class="form-group">
					<div class="col-md-2">
					</div>
					<div class="col-md-4">
						<input class="form-control" placeholder="Nhập từ khóa tìm kiếm" name="keyword" id="keyword" type="text">
					</div>
					<div class="col-md-3">
						<a class="btn btn-success" href="javascript:;" onclick="timkiemthongtin()"> Tìm kiếm</a>
					</div>
				</div>
				<div class="box-body table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th class="text-center" width="3%">STT</th>
								<th width="">Tiêu đề</th>
								<th width="">Danh mục</th>
								<th width="">Ngày XB</th>
								<th width="">Chọn</th>
							</tr>
						</thead>
						<tbody id="thongtintimkiem">
																						
            </tbody>
					</table>
				</div><!-- /.Đã duyệt -->
			</div>
			<div class="modal-footer">
				<div class="form-group">
					<div class="col-md-12 text-center">
						<button type="submit" name="tin_top" class="btn btn-success" value="xu_huong" >Tin xu hướng</button>
						<button type="submit" name="tin_top" class="btn btn-primary" value="chu_y" >Đáng chú ý</button>
						<button type="button" class="btn btn-default " data-dismiss="modal">Đóng lại</button>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</form>
</div><!-- /.modal-dialog -->
<script>
	//$(".select2").select2();
	
  function showCNTT() {
    
    $.ajax({
      url: '{{ route('cms_posts.load_featured') }}',
      type: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
      },
      context: document.body,
    }).done(function(data) {
      
      $('#thongtintimkiem').html(data);
    });
	}

	function timkiemthongtin() {
		var keyword = $('#keyword').val();
		$.ajax({
		  url: '{{ route('cms_posts.load_featured') }}',
		  type: 'POST',
		  data: {
			_token: '{{ csrf_token() }}',
			keyword: keyword
		  },
		  context: document.body,
		}).done(function(data) {
		  
		  $('#thongtintimkiem').html(data);
		});
	}
</script>
</div>
@endsection
