@extends('frontend.layouts.default')

@section('content')

<section class="content">
    <div class="box box-primary">
      
      <div class="card mb-2">
        <div class="card login-box">
          <div class="card-header d-flex justify-content-between myAdvertise">
            <h4>
              Đăng Ký Tài Khoản
            </h4>
          </div>
          <div class="card-body">
            <p class="text-center text-muted">
              Nhập thông tin bên dưới
            </p>
            <div id="msgLogin" class="alert alert-danger d-none">
            </div>
            <form action="{{ route('frontend.register') }}" method="post" class="mb-3">
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

            {{-- Name --}}
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <input type="text" name="name" value="{{ old('name') }}" required class="form-control" placeholder="Họ và tên">
                @if ($errors->has('name'))
                    <span class="help-block">
                        {{ $errors->first('name') }}
                    </span>
                @endif
            </div>

                {{-- UserName --}}
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                <input type="text" name="username" value="{{ old('username') }}" required class="form-control" placeholder="Tên đăng nhập">
                @if ($errors->has('username'))
                    <span class="help-block">
                        {{ $errors->first('username') }}
                    </span>
                @endif
            </div>

            {{-- Email --}}
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <input type="email" name="email" value="{{ old('email') }}" required class="form-control" placeholder="Email">
                @if ($errors->has('email'))
                    <span class="help-block">
                        {{ $errors->first('email') }}
                    </span>
                @endif
            </div>

            {{-- Password --}}
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <input type="password" name="password" required class="form-control" placeholder="Password">
                @if ($errors->has('password'))
                    <span class="help-block">
                        {{ $errors->first('password') }}
                    </span>
                @endif
            </div>

            {{-- Password confirmation --}}
            <div class="form-group">
                <input type="password" name="password_confirmation" required class="form-control" placeholder="Xác nhận mật khẩu">
            </div>
            
              <button type="submit" class="btn btn-primary w-100">
                Đăng Ký
              </button>
              @php
                $referer = request()->headers->get('referer');
              @endphp
              <input type="hidden" name="url" value="{{ $referer }}">
            </form>
            <div class="mb-3 signup-link">
              <p class="mt-3">
            <a href="{{ route('frontend.login') }}">Bạn đã có tài khoản? Đăng nhập</a>
            </p>
            </div>
          </div>
        </div>
      </div>

    </div>

</section>

@endsection
