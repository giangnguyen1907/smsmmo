@extends('frontend.layouts.email')
@section('content')
<h3>Kính gửi <?=$member_name?> ,</h3>
<p>
Tôi xin gửi lời cảm ơn chân thành đến <?=$member_name?> đã sử dụng dịch vụ của chúng tôi. Chúng tôi rất trân trọng sự tin tưởng và lựa chọn của quý vị.</p>

<p>Tài khoản của quý khách đã được cộng <?=number_format($payment)?> VNĐ.</p>

<p>Quý khách vui lòng truy cập vào trang <a href="https://sachdientu.conduongmoi.vn/">Sách Điện Tử</a> để sử dụng dịch vụ.</p>

<p>Nếu quý vị có bất kỳ câu hỏi hay ý kiến đóng góp nào, xin vui lòng liên hệ với chúng tôi qua số điện thoại / zalo <a href="https://zalo.me/0969584998">0969 584 998</a> mr.Thành hoặc <a href="https://zalo.me/0397940122">0397 940 122</a> ms.Hiền . Chúng tôi luôn sẵn sàng hỗ trợ và cải thiện dịch vụ để mang lại sự hài lòng cao nhất cho quý vị.</p>

<p>Một lần nữa, xin chân thành cảm ơn và mong được phục vụ quý vị.</p>

<p>Trân trọng,</p>

@endsection