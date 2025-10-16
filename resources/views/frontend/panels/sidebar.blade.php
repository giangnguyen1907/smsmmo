

<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
      @guest
        <li class="">
          <a href="{{ route('frontend.login') }}">
            <i class="fa fa-sign-in"></i> <span>Đăng Nhập</span>
          </a>
        </li>
        <li class="">
          <a href="#">
            <i class="fa fa-user-circle"></i> <span>Đăng Ký</span>
          </a>
        </li>
      @endguest
      @auth 
       <li class="">
          <a href="{{ route('frontend.user.index') }}">
            <i class="fa fa-sign-in"></i> <span> {{ Auth::user()->name ?? 'Người dùng' }}</span>
          </a>
        </li>
      @endauth
        <hr class="hr-customer">

        <li class="">
          <a href="{{ route('frontend.service.recharge-account') }}">
            <i class="fa fa-btc"></i> <span>Nạp tiền tài khoản</span>
          </a>
        </li>
        <li class="">
          <a href="#">
            <i class="fa fa-history"></i> <span>Lịch sử nạp tiền</span>
          </a>
        </li>

        <hr class="hr-customer">

        <li><a href="{{ route('frontend.service.rent-sim') }}"><i class="fa fa-hand-o-right"></i> Thuê SIM</a></li>
        <li><a href="{{ route('frontend.service.rent-old-number') }}"><i class="fa fa-hand-o-right"></i> Thuê số cũ</a></li>
        <li><a href="{{ route('frontend.service.history') }}"><i class="fa fa-history"></i> Lịch sử thuê SIM</a></li>
        <li><a href="{{ route('frontend.service.recharge') }}"><i class="fa fa-money"></i> Nạp 1000 vào SIM</a></li>
        
        <hr class="hr-customer">

        <li>
          <a href="{{ route('frontend.service.create-101') }}"><i class="fa fa-file-image-o"></i> Ảnh *101# </a>
        </li>

        <li>
          <a href="{{ route('frontend.service.send-message-img') }}"><i class="fa fa-file-image-o"></i> Ảnh gửi tin nhắn </a>
        </li>

        <li>
          <a href="#"><i class="fa fa-envelope"></i> Liên hệ & Hỗ trợ </a>
        </li>

    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
