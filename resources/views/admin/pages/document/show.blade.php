@extends('admin.layouts.app')

@section('title')
  {{ $module_name }}
@endsection

@section('content-header')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      {{ $module_name }}
      <a class="btn btn-success btn-sm pull-right" href="{{ route(Request::segment(2) . '.index') }}">
        <i class="fa fa-bars"></i> @lang('List')
      </a>
    </h1>
  </section>
@endsection
<?php 
$filepdf = $detail->filepdf;
$filepdf = base64_encode(base64_encode($filepdf));

$limit_page = '';
// Kiểm tra xem có bắt phải đăng nhập hay không
if($detail->is_public == 1){ // Bắt đăng nhập
  if(!Auth::check()){
    $limit_page = base64_encode($detail->limit_page);
  }
}
?>
@section('content')

<link rel='stylesheet' id='wp-block-library-css' href="{{ asset('themes/frontend/css/dflip.min.css') }}" rel="stylesheet"  />
<link rel='stylesheet' id='wp-block-library-css' href="{{ asset('themes/frontend/css/themify-icons.min.css') }}" rel="stylesheet"  />
  <!-- Main content -->
  <section class="content">
    
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        
        <?php if($filepdf){ ?>
        <div class="_df_book" style="height: 100vh" webgl="true" backgroundcolor="gray"
          source="{{ $filepdf }}" data-value="{{ $limit_page }}"
            id="df_manual_book">
        </div>
        <?php } ?>
      </div>

    </div>
  </section>
<script src="{{ asset('themes/frontend/dflip/js/libs/jquery.min.js') }}"></script>
<script src="{{ asset('themes/frontend/dflip/js/dflip.min.js') }}"></script>
@endsection
