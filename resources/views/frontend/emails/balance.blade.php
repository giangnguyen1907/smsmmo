@extends('frontend.layouts.email')
@section('content')
<h3>Kính gửi Admin ,</h3>
<p>Tài khoản trên hệ thống bossotp sắp hết, vui lòng nạp thêm tiền để hệ thống không bị giám đoạn </p>
<p>Số dư hiện tại của bạn là: <b><?php echo $balance ?></b></p>

@endsection