<?php 
$seo_title = $seo_title ?? $web_information->information->seo_title ?? '';
//dd($web_information->information->seo_title);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
    {{$seo_title}}
  </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('themes/admin/img/meta-logo-favicon.png') }}">
  {{-- Include style for app --}}
  @include('frontend.panels.styles')

  @yield('style')
</head>

<body class="hold-transition skin-green-light sidebar-mini fixed">
  <div class="wrapper">

    {{-- Include header --}}
    @include('frontend.panels.header')

    {{-- Include Sidebar --}}
    @include('frontend.panels.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      {{-- Header in content --}}
      @yield('content-header')
      {{-- Content detail --}}
      @yield('content')
    </div>
    <!-- /.content-wrapper -->

    {{-- Include footer --}}
    @include('frontend.panels.footer')

  </div>
  <!-- ./wrapper -->

  {{-- Include scripts --}}
  @include('frontend.panels.scripts')

  @yield('script')
</body>

</html>
