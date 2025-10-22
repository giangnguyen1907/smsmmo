@extends('frontend.layouts.default')

@section('content')

<section class="content">
    <div class="box box-primary">
      
      <div class="card mb-2">
        <div class="card login-box">
          <div class="card-header d-flex justify-content-between myAdvertise">
            <h4>
              Đăng Nhập Tài Khoản
            </h4>
          </div>
          <div class="card-body">
            <p class="text-center text-muted">
              Nhập thông tin bên dưới
            </p>
            <div id="msgLogin" class="alert alert-danger d-none">
            </div>
            <form action="{{ route('frontend.login.post') }}" method="post" class="mb-3">
              @csrf
              @if (session('errorMessage'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h4>Alert!</h4>
                  {{ session('errorMessage') }}
                </div>
              @endif
                  {{-- Hiển thị lỗi từ session --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ session('error') }}
                </div>
            @endif
              <div class="mb-3 text-start">
                <label for="username" class="form-label">
                  Tên Tài Khoản
                </label>
                <input type="text" name="email" required id="username" class="form-control" placeholder="Nhập tên tài khoản">
                @if ($errors->has('email'))
                  <span class="help-block">
                    {{ $errors->first('email') }}
                  </span>
                @endif
              </div>
              <div class="mb-3 text-start">
                <label for="password" class="form-label">
                  Mật Khẩu
                </label>
                <input type="password" required name="password"  id="password" class="form-control" placeholder="Nhập mật khẩu">
                @if ($errors->has('password'))
                  <span class="help-block">
                    {{ $errors->first('password') }}
                  </span>
                @endif
              </div>
              <div class="mb-3 form-check text-start">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">
                  Ghi nhớ đăng nhập
                </label>
              </div>
              <button type="submit" id="LoginTK" class="btn btn-primary w-100">
                Đăng Nhập Ngay
              </button>
              @php
                $referer = request()->headers->get('referer');
              @endphp
              <input type="hidden" name="url" value="{{ $referer }}">
            </form>
            <div class="mb-3 forgot-password">
              <a href="{{ $web_information->information->contact ?? '' }}">
                Quên mật khẩu?
              </a>
            </div>
            <div class="mb-3 signup-link">
              Bạn chưa có tài khoản?
              <a href="{{ route('frontend.register') }}">
                Tạo tài khoản mới
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>

</section>

@endsection
