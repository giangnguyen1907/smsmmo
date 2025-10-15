<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('admin.home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>XKT</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>ADMINISTRATOR</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->

        <a href="javascipt:void(0);" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        {{--  $count_route['cms_posts?task=deactive'] --}}
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                @foreach ($accessMenus as $menu_top)
                    @if ($menu_top->is_check == 'active')
                        <li class="hidden-xs">
                            <a href="/admin/{{ $menu_top->url_link }}"><i
                                    class="{{ $menu_top->icon != '' ? $menu_top->icon : 'fa fa-angle-right' }}"></i>
                                <span
                                    style="color: #ffdf28">{{ $count_route[$menu_top->url_link] ?? '' }}</span><br><span>{{ $menu_top->name }}</span></a>
                        </li>
                    @endif
                @endforeach

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i><br>
                        <span class="hidden-xs">
                            {{ $admin_auth->name }}
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <p>
                                {{ $admin_auth->name }}
                                <small>{{ $admin_auth->email }}</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('admin_profile.index') }}"
                                    class="btn btn-default btn-flat">@lang('Profile')</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('admin.logout') }}"
                                    class="btn btn-default btn-flat">@lang('Logout')</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
