@extends('frontend.layouts.email')

@section('content')
  <h1>@lang('You received a new order from the system')</h1>

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
    <strong>@lang('Order detail')</strong>:
    <a href="{{ route('order_products.show', $order->id) }}">
      @lang('View detail')
    </a>
  </p>
@endsection
