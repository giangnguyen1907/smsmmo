@extends('frontend.layouts.email')
@section('content')
<h3>Kính gửi Admin ,</h3>
<p>Có 1 đơn hàng yêu cầu mua sách <b><?php echo $document_name ?></b> từ khách hàng <b><?=$member_name?></b></p>
<p>Truy cập trang <a href="https://sachdientu.conduongmoi.vn/admin/history_buyebook">Admin</a> để kiểm tra và duyệt giao dịch </p>

@endsection