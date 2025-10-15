@extends('frontend.layouts.email')
@section('content')
<h1>@lang('Thông tin liên hệ từ website')</h1>
<p><strong>@lang('Họ tên')</strong>: {{ $contact->name }}  </p>
<p><strong>@lang('Email')</strong>: {{ $contact->email }}  </p>
<p><strong>@lang('Số điện thoại')</strong>: {{ $contact->phone }}  </p>
<p><strong>@lang('Ghi chú')</strong>: {{ $contact->content }}  </p>
@endsection