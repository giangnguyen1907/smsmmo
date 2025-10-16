<header class="main-header">
  <!-- Logo -->
  <a href="{{ route('frontend.home') }}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>MMO</b></span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>SMS MMO</b></span>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->

    <a href="javascipt:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>

    <div class="navbar-custom-menu">
  <ul class="nav navbar-nav">

    {{-- Nếu chưa đăng nhập --}}
    @guest
      <li>
        <a href="{{ route('frontend.login') }}">
          <i class="fa fa-sign-in-alt"></i> <span>Đăng Nhập</span>
        </a>
      </li>
    @endguest

    {{-- Nếu đã đăng nhập --}}
    @auth
      <li class="dropdown user user-menu">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <span class="hidden-xs">
            {{ Auth::user()->name ?? 'Người dùng' }}
          </span>
        </a>
        <ul class="dropdown-menu">
          <!-- Thông tin user -->
          <li class="user-header">
            <p>
              {{ Auth::user()->name ?? 'Người dùng' }}
              <small>{{ Auth::user()->email ?? '' }}</small>
            </p>
          </li>

          <li class="user-footer">
            <div class="pull-right">
              <form action="{{ route('frontend.logout') }}" method="GET" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-default btn-flat">@lang('Logout')</button>
              </form>
            </div>
          </li>
        </ul>
      </li>
    @endauth

  </ul>
</div>

  </nav>
</header>
