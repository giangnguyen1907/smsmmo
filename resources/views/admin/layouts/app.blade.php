<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>
    @yield('title') | Newwaytech
  </title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="icon" href="{{ asset('themes/admin/img/meta-logo-favicon.png') }}">

  {{-- Include style for app --}}
  @include('admin.panels.styles')

  @yield('style')
</head>

<body class="hold-transition skin-green-light sidebar-mini fixed">
  <div class="wrapper">

    {{-- Include header --}}
    @include('admin.panels.header')

    {{-- Include Sidebar --}}
    @include('admin.panels.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      {{-- Header in content --}}
      @yield('content-header')
      {{-- Content detail --}}
      @yield('content')
    </div>
    <!-- /.content-wrapper -->

    {{-- Include footer --}}
    @include('admin.panels.footer')

  </div>
  <!-- ./wrapper -->

  {{-- Include scripts --}}
  @include('admin.panels.scripts')

  @yield('script')
</body>

</html>
