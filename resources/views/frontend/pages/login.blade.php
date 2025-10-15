@extends('admin/layouts/auth')

@section('content')
  <div class="login-box">
    <div class="login-logo">
      <b>Đăng nhập</b>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <form action="{{ route('frontend.login.post') }}" method="post">
        @csrf
        @if (session('errorMessage'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4>Alert!</h4>
            {{ session('errorMessage') }}
          </div>
        @endif
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
          <input type="email" name="email" required class="form-control" placeholder="Email">

          @if ($errors->has('email'))
            <span class="help-block">
              {{ $errors->first('email') }}
            </span>
          @endif
        </div>
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
          <input type="password" required name="password" class="form-control" placeholder="Password">

          @if ($errors->has('password'))
            <span class="help-block">
              {{ $errors->first('password') }}
            </span>
          @endif
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-flat">
          Sign In
        </button>

        @php
          $referer = request()->headers->get('referer');
        @endphp
        <input type="hidden" name="url" value="{{ $referer }}">
      </form>
    </div>
  </div>
@endsection
