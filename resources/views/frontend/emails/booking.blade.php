@extends('frontend.layouts.email')

@section('content')
  <h1>@lang('You received a new service order from the system')</h1>

  <p>@lang('Content Order'): </p>

  <p>
    <strong>@lang('Fullname')</strong>: {{ $order->name }}
  </p>
  <p>
    <strong>@lang('Email')</strong>: {{ $order->email }}
  </p>
  <p>
    <strong>@lang('Phone')</strong>: {{ $order->phone }}
  </p>
  <p>
    <strong>@lang('Content note')</strong>: {{ $order->customer_note }}
  </p>
  <p>
    <strong>@lang('Booking this service')</strong>:
    <a href="{{ $order_detail->json_params->post_link ?? '' }}">
      @lang('View detail')
    </a>
  </p>
@endsection
