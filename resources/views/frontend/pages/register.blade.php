@extends('frontend.layouts.default')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <b>Đăng ký</b>
    </div>
    <div class="login-box-body">
        <form action="{{ route('frontend.register') }}" method="post">
            @csrf

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

            {{-- Email --}}
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <input type="text" name="email" value="{{ old('email') }}" required class="form-control" placeholder="Username">
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

            <button type="submit" class="btn btn-primary btn-block btn-flat">
                Đăng ký
            </button>

            @php
                $referer = request()->headers->get('referer');
            @endphp
            <input type="hidden" name="url" value="{{ $referer }}">
        </form>

        <p class="mt-3">
            <a href="{{ route('frontend.login') }}">Bạn đã có tài khoản? Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
