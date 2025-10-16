<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{ asset('themes/admin/css/bootstrap.min.css') }}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('themes/admin/css/font-awesome.min.css') }}">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('themes/admin/plugins/select2/select2.min.css') }}">
<!-- DatePicker -->
<link rel="stylesheet" href="{{ asset('themes/admin/plugins/datepicker/datepicker3.css') }}">
<link rel="stylesheet" href="{{ asset('themes/admin/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('themes/admin/css/AdminXKT.min.css') }}">
<!-- Skin style -->
<link rel="stylesheet" href="{{ asset('themes/admin/css/skins/_all-skins.min.css') }}">

<!-- jQuery 3 -->
<script src="{{ asset('themes/admin/js/jquery.min.js') }}"></script>

<script src="{{ asset('themes/admin/js/jquery.tablednd.js') }}"></script>


<style>
  .h4,
  .h5,
  .h6,
  h4,
  h5,
  h6 {
    margin-top: 0;
    margin-bottom: 0;
  }

  .ml-15 {
    margin-left: 15px !important;
  }

  .mr-15 {
    margin-right: 15px !important;
  }

  .mx-15 {
    margin: 0 15px !important;
  }

  .my-15 {
    margin: 15px 0 !important;
  }

  .ml-10 {
    margin-left: 10px !important;
  }

  .mr-10 {
    margin-right: 10px !important;
  }

  .mx-10 {
    margin: 0 10px !important;
  }

  .my-10 {
    margin: 10px 0 !important;
  }

  .checkbox_list {
    padding: 10px;
    list-style: none;
    border: 1px dashed;
  }

  .checkbox_list li label {
    font-size: 14px;
  }

  .border-top {
    border-top: 1px dashed #CDCDCD;
  }

  .border-bottom {
    border-bottom: 1px dashed #CDCDCD;
  }

  .py-2 {
    padding-top: .5rem !important;
    padding-bottom: .5rem !important;
  }

  ul.extra-service:last-child {
    margin: 0;
  }

  .extra-service {
    margin: 10px 0 0;
    display: table;
    width: 100%;
    padding: 0;
  }

  ul.extra-service {
    list-style: none;
  }

  .icon-box-icon-block {
    width: 100%;
    display: inline-block;
  }

  .icon-box-round {
    display: inline-block;
    width: 40px;
    height: 40px;
    line-height: 42px;
    margin-right: 5px;
    color: #27b737;
    font-size: 25px;
    text-align: center;
    border-radius: 50%;
    vertical-align: middle;
  }

  .icon-box-text {
    display: inline-block;
    vertical-align: middle;
  }

  .skin-green .sidebar-menu>li.header {
    color: #FFFFFF;
    background: #1a2226;
  }

  .sidebar-menu li.header {
    font-size: 16px !important;
    text-transform: uppercase;
    font-family: 'RobotoCondensed-Bold', sans-serif;
    padding: 10px 0px 10px 5px;
  }

  .sidebar-menu>li>a {
    padding: 10px 0px 10px 10px;
    display: block;
  }

  .sidebar-menu>li>a>.fa,
  .sidebar-menu>li>a>.glyphicon,
  .sidebar-menu>li>a>.ion,
  .sidebar-menu>li.header .fa,
  .sidebar-menu>li.header .glyphicon,
  .sidebar-menu>li.header .ion {
    max-width: 20px;
  }

  .sidebar-menu li span {
    font-size: 14px !important;
    font-family: 'RobotoCondensed-Regular', sans-serif;
  }

  .main-sidebar {
    box-shadow: 0 14px 28px rgba(0, 0, 0, .25), 0 10px 10px rgba(0, 0, 0, .22) !important;
    padding-top: 55px;
  }

  .skin-green-light .sidebar-menu>li.header {
    color: #FFFFFF;
    background: #00A157;
  }

  .skin-green-light .main-header .navbar {
    background-color: #00A157;
  }

  .skin-green-light .sidebar-menu>li>a {
    border-left: 3px solid transparent;
  }

  .skin-green-light .sidebar-menu>li:hover>a,
  .skin-green-light .sidebar-menu>li.active>a {
    color: darkcyan;
    border-left-color: darkcyan;
    background: #ecf0f5;
  }

  .mb-15 {
    margin-bottom: 15px;
  }

  .cursor {
    cursor: pointer;
  }

  #loading {
    display: none;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 50;
    background: rgba(255, 255, 255, 0.7);
  }

  .overlay {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    transform: -webkit-translate(-50%, -50%);
    transform: -moz-translate(-50%, -50%);
    transform: -ms-translate(-50%, -50%);
    color: #1f222b;
    z-index: 9999;
    background: rgba(255, 255, 255, 0.7);
  }

  .active-item {
    background: #c9eada !important;
  }
  .select2 {width: 100%!important}
  hr.hr-customer {margin-top: 10px;
    margin-bottom: 10px;
    border: 0;
    border-top: 2px solid #f5c5c5;}
</style>
