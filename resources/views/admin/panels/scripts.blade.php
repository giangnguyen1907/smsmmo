<!-- 
<script src="{{ asset('/themes/admin/editor/ckeditor/build/ckeditor.js') }}"></script>
<script src="{{ asset('/themes/admin/editor/ckfinder/ckfinder.js') }}"></script> 
<script src="{{ asset('/themes/admin/editor/ckeditor4/ckeditor.js') }}"></script> 
-->

<script src="//cdn.ckeditor.com/4.17.2/full/ckeditor.js"></script>

<!-- Bootstrap 3.3.7 -->

<script src="{{ asset('/themes/admin/js/bootstrap.min.js') }}"></script>

<!-- SlimScroll -->

<script src="{{ asset('/themes/admin/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>

<!-- Select2 -->

<script src="{{ asset('/themes/admin/plugins/select2/select2.full.min.js') }}"></script>

<!-- DatePicker -->

<script src="{{ asset('/themes/admin/plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<script src="{{ asset('/themes/admin/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

<!-- AdminLTE App -->

<script src="{{ asset('/themes/admin/js/app.min.js') }}"></script>

<script src="{{ asset('/themes/admin/js/custom.js') }}"></script>



<script>

  $(".select2").select2();



  $('.datepicker').datepicker({

    autoclose: true,

    todayHighlight: true,

    format: 'dd/mm/yyyy',

    language: 'vn'

  });



  //Timepicker

  $(".timepicker").timepicker({

    showInputs: false

  });



  $('.lfm').filemanager('other', {

    prefix: route_prefix

  });



  const filterArray = (array, fields, value) => {

    fields = Array.isArray(fields) ? fields : [fields];

    return array.filter((item) => fields.some((field) => item[field] === value));

  };

</script>

