

<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">

        <li class="active">
          <a href="#"><i class="fa fa-cogs"></i> Trang Chủ</a>
          <ul class="treeview-menu">
            <li class="">
              <a href="{{ route('frontend.login') }}">
                <i class="fa fa-sign-in"></i> <span>Đăng Nhập</span>
              </a>
            </li>

            <li class="">
              <a href="#">
                <i class="fa fa-cog"></i> <span>Đăng Ký</span>
              </a>
            </li>
          </ul>
        </li>
        @auth
        <li>
            <a href="#"><i class="fa fa-credit-card-alt"></i> Giao dịch </a>
             <ul class="treeview-menu">
                <li class="">
                  <a href="{{ route('frontend.service.recharge-account') }}">
                    <i class="fa fa-cog"></i> <span>Nạp tiền tài khoản</span>
                  </a>
                </li>
                <li class="">
                  <a href="#">
                    <i class="fa fa-cog"></i> <span>Lịch sử nạp tiền</span>
                  </a>
                </li>
            </ul>
        </li>
        @endauth
      <li class="treeview">
            <a href="#">
              <i class="fa fa-bars"></i> <span>Danh sách dịch vụ</span>
            </a>
            <ul class="treeview-menu">
              <li><a href="{{ route('frontend.service.rent-sim') }}"><i class="fa fa-sim-card"></i> Thuê SIM</a></li>
              <li><a href="{{ route('frontend.service.rent-old-number') }}"><i class="fa fa-history"></i> Thuê số cũ</a></li>
              <li><a href="{{ route('frontend.service.history') }}"><i class="fa fa-list"></i> Lịch sử thuê SIM</a></li>
              <li><a href="{{ route('frontend.service.recharge') }}"><i class="fa fa-coins"></i> Nạp 1000 vào SIM</a></li>
            </ul>
      </li>


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
