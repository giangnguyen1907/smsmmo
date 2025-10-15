@extends('admin.layouts.app')

@section('content')
  
  @php
    $status_work = App\Consts::STATUS_WORK;
  @endphp

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

      <form role="form" action="{{ route('admin.store_relation_work') }}" method="POST" enctype='multipart/form-data'>
        @csrf
        <input type="hidden" value="{{ $manage_work_id }}" name="manage_work_id" id="manage_work_id">
        <div class="box-body">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_1" data-toggle="tab">
                  <h5>Thêm mới công việc: <span style="color:red">{{ $cmsManageWork }}</span></h5>
                </a>
              </li>

              <button type="submit" class="btn btn-danger pull-right btn-sm mg-5"  name="submit">Lưu công việc</button>
              
            </ul>

            <div class="tab-content">

              <div class="tab-pane active" id="tab_1">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Công việc</label>
                      <input type="text" class="form-control" name="title" placeholder="Nhập tên công việc"
                        value="{{ old('title') }}" required>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Thời hạn</label>
                      <input class="form-control" name="deadline" id="deadline" type="datetime-local" value="<?=date('Y-m-d\TH:i')?>">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                        <label>@lang('Nội dung công việc')</label>
                        <textarea name="content" class="form-control" id="content_vi">{{ old('content') }}</textarea>
                      </div>
                  </div>
				  
				  <div class="col-md-6">
                    <div class="form-group">
                      <label>Tệp đính kèm</label>
                      <input class="form-control" name="file_work[]" type="file" multiple="multiple" title="Chọn tệp tải lên" placeholder="Chọn tệp tải lên">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Trạng thái</label>
                      <select name="status" class="form-control select2" id="status">
                        <option value="">Vui lòng chọn</option>
                        @foreach ($status_work as $key => $value)
                          <option value="{{ $key }}"
                              {{ old('status') == $key ? 'selected' : '' }}>
                              {{ $value }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                       <label>@lang('Cấu hình công việc')</label>
                        <div class="form-control">
                          <input type="radio" name="is_public" value="0" class="check-all-congkhai" checked="">
                          Cá nhân
                          <input type="radio" name="is_public" value="1" class="ml-15 check-all-congkhai">
                          Công khai
                      </div>
                    </div>
                  </div>

                  <div class="col-md-6" id="noinhan">
                    <div class="form-group hidden">
                      <label>@lang('Tìm phòng ban')</label>
                      <select class="form-control select2" onchange="loadMemberDepartment()" id="check_department">
                        <option>-Chọn nơi nhận -</option>
                          @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->title }}</option>
                          @endforeach
                      </select>
                    </div>
                    <section class="tbl-header table_scroll">
                      <div class="container_table custom-scroll table-responsive no-margin" style="max-height: 400px; overflow-y: scroll;">
                        <div id="dt_basic_wrapper" class="dataTables_wrapper form-inline"></div>
                        <div id="ic-loading" style="display: none;">
                          <i class="fa fa-spinner fa-2x fa-spin text-success" style="padding: 3px;"></i>Đang tải...
                        </div>
                        <table class="table table-striped table-bordered table-hover no-footer" width="100%" style="border: 1px solid #ccc !important">
                          <thead>
                            <tr>
                              <th class="col-xs-4">
                                <input class="checkbox check-all" type="checkbox" value="0" ><span>Chọn tất cả</span>
                              </th>
                              <th class="col-xs-8">
                                <input class="checkbox check-all-relative" type="checkbox"  value="0" ><span>Người tham gia</span>
                              </th>
                            </tr>
                          </thead>
                          <tbody id="load-ajax" >
                            @foreach($departments as $department)
                            <tr>
                              <td class="">
                                <input class="_check_all checkbox check_class" id="check_class_{{ $department->id }}" type="checkbox" name="department_user_work[]" value="{{ $department->id }}"> <span>{{ $department->title }}</span>
                              </td>
                              <td class="">
                                <?php foreach($admins as $admin){ 
                                  if($admin->department_id == $department->id){
                                  ?>
                                  <input class="checkbox _check_all _check_relative _check_all_{{ $department->id }}" type="checkbox" name="user_work[]" value="{{ $admin->id }}" data-value="{{ $department->id }}"> <span>{{ $admin->name }}</span><br>
                                <?php } } ?>
                              </td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    </section>
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
          <a class="btn btn-success btn-sm" href="{{ route('admin.view_relation_work', ['id' => $manage_work_id]) }}">
            <i class="fa fa-bars"></i> @lang('List')
          </a>
            <button type="submit" class="btn btn-danger pull-right btn-sm mg-5" name="submit" value="gui">Lưu công việc</button>
              
        </div>
      </form>
    </div>
  </section>
  
  @section('script')

  <style>
    div.ck-editor__editable {
      height: 350px !important;
      overflow: scroll;
    }
    .checkbox, .radio{
      display: unset !important;
    }
  </style>
<script>
  $(document).ready(function() {

    $('.check-all-congkhai').on('change', function() {

      
      if($(".check-all-congkhai:checked").val() == 1){

        document.getElementById("noinhan").style.display = "none";

        $('._check_all').prop('checked',true);
        $('.check-all-relative').prop('checked',true);
      }else{

        document.getElementById("noinhan").style.display = "block";
        $('._check_all').prop('checked',false);
        $('.check-all-relative').prop('checked',false);
      }
    });
  
    $('.check-all').on('change', function() {
      
      if($(".check-all:checked").val() == 0){
        $('._check_all').prop('checked',true);
        $('.check-all-relative').prop('checked',true);
      }else{
        $('._check_all').prop('checked',false);
        $('.check-all-relative').prop('checked',false);
      }
    });
  
    $('.check_class').on('change', function() {
      var class_id = $(this).val();
      if($("#check_class_"+class_id+":checked").val() == class_id){
        $('._check_all_'+class_id).prop('checked',true);
      }else{
        $('._check_all_'+class_id).prop('checked',false);
      }
      $('.check-all-relative').prop('checked',false);
    });
  
    $('._check_all').on('change', function() {
      var class_id = $(this).attr('data-value');
      $('.check-all').attr('disabled', false);
      $('.check-all').prop('checked',false);
      $('#check_class_'+class_id).prop('checked',false);
    });
  
    $('.btn-psadmin').attr('disabled', 'disabled');
    
    $('.checkbox').on('change', function() {
      if($(".checkbox:checked").val() >= 0){
        $('.btn-psadmin').attr('disabled', false);
      }else{
        $('.btn-psadmin').attr('disabled', 'disabled');
      }
    });
    
    $('.check-all-relative').on('change', function() {
      if($(".check-all-relative:checked").val() == 0){
        $('._check_relative').prop('checked',true);
      }else{
        $('._check_relative').prop('checked',false);
      }
      $('.check-all').prop('checked',false);
      $('.check_class').prop('checked',false);
    });
  
    $('._check_relative').on('change', function() {
      $('.check-all').prop('checked',false);
      $('.check-all-relative').prop('checked',false);
    });
  });
</script>

  <script>
    function loadMemberDepartment(){
      var dep = $('#check_department').val();
      $.ajax({
        url: '{{ route('admins.load_member') }}',
        type: 'POST',
        data: {
          _token: '{{ csrf_token() }}',
          dep: dep
        },
        context: document.body,
      }).done(function(data) {
        
        $('#load-ajax').html(data);
      });
    }
	
	CKEDITOR.replace('content_vi', ck_options);


    ClassicEditor.create( document.querySelector( '#content' ), {
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
  </script>
  @endsection
@endsection
