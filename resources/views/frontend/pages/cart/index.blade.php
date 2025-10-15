@extends('frontend.layouts.default')

@php
    $page_title = $taxonomy->title ?? ($page->title ?? ($page->name ?? ''));
    $image_background =
        $taxonomy->json_params->image_background ?? ($web_information->image->background_breadcrumbs ?? '');
    $randomNumber = time() . mt_rand(100, 999);
@endphp

@section('content')

    <div style="clear: both;"></div>

    <section id="content">

        <div class="breadcrum container" style="font-size: 17px;margin-bottom: 20px;">
            <a href="{{ route('frontend.home') }}">{{ $array_translate[strtolower('Home')]->$locale ?? 'Home' }}</a>
            <span> / {{ $array_translate[strtolower('Cart')]->$locale ?? 'Cart' }}</span>
        </div>

        <div class="main-content full-width inner-page">
            <div class="background-content"></div>
            <div class="background">
                <div class="shadow"></div>
                <div class="pattern">
                    <div class="container">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {{ session('error') }}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                {{ session('success') }}
                            </div>

                            <script>
                                var linearGradient = getComputedStyle(document.documentElement).getPropertyValue('--linear-gradient');
                                document.addEventListener("DOMContentLoaded", function() {
                                    Toastify({
                                        text: "Đã thanh toán thành công!",
                                        duration: 5000,
                                        gravity: "top",
                                        position: "right",
                                        backgroundColor: linearGradient
                                    }).showToast();
                                });
                            </script>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>

                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach

                            </div>
                        @endif

                        @if (session('cart'))
                            <div class="row">
                                <form action="{{ route('frontend.order.store.product') }}" method="POST">
                                    @csrf

                                    <div class="col-md-8 border-right">
                                        {{-- <h3>Giỏ hàng</h3> --}}
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Hình ảnh</th>
                                                    <th scope="col">Tên sản phẩm</th>
                                                    <th scope="col">Giá</th>
                                                    <th scope="col">Số lượng</th>
                                                    <th scope="col">Tổng</th>
                                                    <th scope="col">Xóa</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @php $total = 0 @endphp
                                                @foreach (session('cart') as $id => $details)
                                                    @php
                                                        $total += $details['price'] * $details['quantity'];
                                                        $alias_detail = Str::slug($details['title']);
                                                        $url_link =
                                                            route('frontend.cms.view', [
                                                                'alias' => $alias_detail,
                                                            ]) . '.html';
                                                    @endphp
                                                    <tr class="tr-border cart-item" data-product-id="{{ $id }}">
                                                        <td>
                                                            <a href="{{ $url_link }}">
                                                                <img src="{{ $details['image_thumb'] ?? $details['image'] }}"
                                                                    alt="Product Image" style="width: 70px; height:70px;">
                                                            </a>
                                                        </td>
                                                        <td class="text-left">
                                                            <a href="{{ $url_link }}">
                                                                <span>{{ $details['title'] }}</span>
                                                            </a>
                                                        </td>
                                                        <td><span class="cart-price">
                                                                {{ isset($details['price']) && $details['price'] > 0 ? number_format($details['price']) : 0 }}
                                                            </span>
                                                        </td>
                                                        <td class="class-quantity">
                                                            <!--<button class="btn-xs btn minus">-</button>-->
                                                            <input type="number" class="quantity-input" min="1"
                                                                max="999" step="1" name="quantity"
                                                                id="quantity{{ $id }}"
                                                                value="{{ $details['quantity'] }}"
                                                                onchange="updateCart({{ $id }})">
                                                            <!--<button class="btn-xs btn plus">+</button>-->
                                                        </td>
                                                        <td>
                                                            <span class="price" id="price{{ $id }}">
                                                                {{ number_format($details['price'] * $details['quantity']) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-delete"
                                                                onclick="removecart({{ $id }})">
                                                                <i class="fa fa-trash-o"></i>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="2" class="text-right">Phí vận chuyển</td>
                                                    <td class="text-right"><b
                                                            id="shipCod">{{ number_format($web_information->information->shipper) ?? 0 }}</b>
                                                    </td>
                                                    <td class="text-right">Đơn hàng</td>
                                                    <td colspan="2" class="text-center">
                                                        <b id="donHang">{{ number_format($total) }}</b>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-right">
                                                        <input type="text" id="voucher"
                                                            placeholder="Nhập mã giảm giá..." class="form-control" /><button
                                                            type="button" onclick="checkVoucher()"
                                                            class="btn btn-xs btn-primary">Áp dụng</button>
                                                    </td>
                                                    <td>
                                                        <b id="txt_discount">0</b>
                                                    </td>
                                                    <td class="text-right">Tổng hóa đơn</td>
                                                    <td colspan="2" class="text-center"><b
                                                            id="totalOrder">{{ number_format($total + $web_information->information->shipper ?? 0) }}</b>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>

                                        <div class="checkTransfer">
                                            <?php foreach ($blocksContent as $banner){
                                                if ($banner->block_code == 'transfer' ){ ?>
                                            <h3>{{ $banner->title->$locale }}</h3>

                                            {!! $banner->content->$locale !!}
                                            <p>Nội dung: <b id="orderInfo"> DH - {{ $randomNumber }}</b> <i
                                                    class="fa fa-clone" onclick="coppyContent()"></i></p>

                                            <input type="hidden" name="trans_code_transfer" id="trans_code_transfer"
                                                value="DH - {{ $randomNumber }}">
                                            <?php } } ?>


                                            <button type="submit" name="submit" value="transfer" class="btn-success"
                                                style="width: max-content; padding: 10px 25px;margin-top: 10px;">Xác nhận đã
                                                chuyển khoản
                                            </button>


                                        </div>

                                    </div>
                                    <div class="col-md-4" style="margin-bottom: 40px;">
                                        <div class="container-border">
                                            <div class="row">
                                                <div class="col-md-12" style="padding: 0 15px 15px;">

                                                    <h3>Thông tin người dùng</h3>
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <!-- Khung thông tin người dùng -->

                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="name"
                                                                    name="name" placeholder="Nhập họ và tên *" required
                                                                    <?php if (isset(Auth::user()->name)) {
                                                                        echo "value='" . Auth::user()->name . "'";
                                                                        echo 'readonly';
                                                                    } ?>>
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" id="phone"
                                                                    name="phone" placeholder="Nhập số điện thoại *"
                                                                    <?php if (isset(Auth::user()->phone)) {
                                                                        echo "value='" . Auth::user()->phone . "'";
                                                                        echo 'readonly';
                                                                    } ?> required>
                                                            </div>
                                                            <div class="form-group">
                                                                <input type="email" class="form-control" id="email"
                                                                    name="email" placeholder="Nhập địa chỉ email"
                                                                    <?php if (isset(Auth::user()->email)) {
                                                                        echo "value='" . Auth::user()->email . "'";
                                                                        echo 'readonly';
                                                                    } ?>>
                                                            </div>

                                                            <?php if(!isset(Auth::user()->id)){ ?>
                                                            <div class="form-group">
                                                                <label for="address">Địa chỉ:<small
                                                                        class="text-red">*</small></label>
                                                                <select name="tinhthanh" id="tinhthanh"
                                                                    onchange="chooseProvince()"
                                                                    class="form-control select2" required>
                                                                    <option value="">-Chọn tỉnh/thành phố-</option>
                                                                    <?php foreach($listProvince as $province){ ?>
                                                                    <option value="<?= $province->s_code ?>">
                                                                        <?= $province->name ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <select name="quanhuyen" id="quanhuyen"
                                                                    onchange="chooseDistrict()"
                                                                    class="form-control select2" required>
                                                                    <option value="">-Chọn quận/huyện-</option>

                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <select name="xaphuong" id="xaphuong"
                                                                    onchange="chooseWard()" class="form-control select2"
                                                                    required>
                                                                    <option value="">-Chọn xã/ phường-</option>
                                                                </select>
                                                            </div>

                                                            <input type="hidden" name="txt_xaphuong" id="txt_xaphuong"
                                                                value="">
                                                            <input type="hidden" name="txt_quanhuyen" id="txt_quanhuyen"
                                                                value="">
                                                            <input type="hidden" name="txt_tinhthanh" id="txt_tinhthanh"
                                                                value="">

                                                            <div class="form-group">
                                                                <input type="text" class="form-control" required
                                                                    id="address" name="address" readonly
                                                                    placeholder="Nhập số nhà, tên đường/phố,...">
                                                            </div>

                                                            <input type="hidden" name="customer_id" id="customer_id"
                                                                value="">
                                                            <?php }else{ ?>

                                                            <input type="hidden" name="xaphuong" id="xaphuong"
                                                                value="{{ Auth::user()->json_params->ward_id }}">
                                                            <input type="hidden" name="quanhuyen" id="quanhuyen"
                                                                value="{{ Auth::user()->json_params->district_id }}">
                                                            <input type="hidden" name="tinhthanh" id="tinhthanh"
                                                                value="{{ Auth::user()->json_params->province_id }}">

                                                            <input type="hidden" name="txt_xaphuong" id="txt_xaphuong"
                                                                value="{{ Auth::user()->json_params->ward_name }}">
                                                            <input type="hidden" name="txt_quanhuyen" id="txt_quanhuyen"
                                                                value="{{ Auth::user()->json_params->district_name }}">
                                                            <input type="hidden" name="txt_tinhthanh" id="txt_tinhthanh"
                                                                value="{{ Auth::user()->json_params->province_name }}">
                                                            <input type="hidden" name="customer_id" id="customer_id"
                                                                value="{{ Auth::user()->id }}">
                                                            <div class="form-group">
                                                                <input type="text" class="form-control" required
                                                                    id="address" name="address" readonly
                                                                    value="{{ Auth::user()->address }}"
                                                                    placeholder="Nhập số nhà, tên đường/phố,...">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="note">Ghi chú:</label>
                                                                <textarea name="customer_note" class="form-control" id="customer_note" rows="3" placeholder="Nhập ghi chú">{{ Auth::user()->note }}</textarea>
                                                            </div>

                                                            <?php } ?>

                                                            <input type="hidden" name="total_payment" id="total_payment"
                                                                value="{{ $total }}">
                                                            <input type="hidden" name="payment" id="payment"
                                                                value="{{ $total }}">
                                                            <input type="hidden" name="ship" id="ship"
                                                                value="{{ $web_information->information->shipper ?? 0 }}">

                                                            <input type="hidden" name="discount" id="discount"
                                                                value="">
                                                            <input type="hidden" name="voucher_id" id="voucher_id"
                                                                value="">

                                                            <input type="hidden" name="madonhang" id="madonhang"
                                                                value="{{ $randomNumber }}">

                                                            <button type="button" class="btn-warning"
                                                                onclick="checkTransfer()"
                                                                style="width: 100%; padding: 10px 0; margin-top: 10px;">{{ $array_translate[strtolower('Transfer')]->$locale ?? 'Thanh toán chuyển khoản' }}
                                                            </button>

                                                            <button type="submit" name="submit" value="cod"
                                                                class="btn-success"
                                                                style="width: 100%; padding: 10px 0;margin-top: 10px;">{{ $array_translate[strtolower('Checkout')]->$locale ?? 'Thanh toán COD' }}
                                                            </button>

                                                            <button type="submit" name="submit" value="vnpay"
                                                                class="btn-primary checkout-button alt wc-forward"
                                                                style="width: 100%; padding: 10px 0; margin-top: 10px;">{{ $array_translate[strtolower('Checkout to vnpay')]->$locale ?? 'Thanh toán qua VNPAY' }}</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="woocommerce">
                                <div class="text-center pt pb">
                                    <div class="woocommerce-notices-wrapper"></div>
                                    <h3 class="cart-empty alert alert-warning">Chưa có sản phẩm nào trong giỏ hàng!</h3>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .breadcrum {
            padding: 20px 0;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #f2f2f2;
        }

        .card-total {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }

        .span-total {
            font-weight: 900;
            font-size: 18px;
        }

        .container-border {
            border: 1px solid var(--primary-color);
            padding: 15px 20px !important;
        }

        .table>tbody>tr>td {
            vertical-align: middle;
        }

        .quantity {
            display: flex;
            align-items: center;
            margin: 5px 0 20px;
        }

        .minus,
        .plus {
            background-color: #ccc;
            border-radius: 6px;
            margin: 0 10px;
        }

        .minus {
            padding: 5px 14px;
        }

        .plus {
            padding: 5px 12px;
        }

        .quantity-input {
            text-align: center;
            border: none;
            outline: none;
        }

        .btn-delete:hover {
            cursor: pointer;
            opacity: 0.8;
        }

        .btn-primary,
        .btn-success,
        .btn-warning {
            width: 100%;
            border-radius: 20px;
        }

        .text-red {
            color: red;
        }

        textarea.form-control {
            height: auto;
        }

        .checkTransfer {
            display: none;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
        }

        ul.select2-results__options li {
            list-style: none;
        }
    </style>

    {{-- tăng, giảm input --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var increaseButtons = document.querySelectorAll('.plus');
            var decreaseButtons = document.querySelectorAll('.minus');

            increaseButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var item = this.closest('.cart-item');
                    var quantityInput = item.querySelector('.quantity-input');
                    var quantity = parseInt(quantityInput.value);
                    quantityInput.value = quantity + 1;
                });
            });

            decreaseButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var item = this.closest('.cart-item');
                    var quantityInput = item.querySelector('.quantity-input');
                    var quantity = parseInt(quantityInput.value);
                    if (quantity > 1) {
                        quantityInput.value = quantity - 1;
                    }
                });
            });
        });
    </script>
    <script>
        document.querySelectorAll('.cart-item').forEach(function(item) {
            var quantityInput = item.querySelector('.quantity-input');
            var priceElement = item.querySelector('.price');
            var increaseButton = item.querySelector('.plus');
            var decreaseButton = item.querySelector('.minus');
            var totalPrice1 = document.getElementById('class-total1');
            var totalPrice2 = document.getElementById('class-total2');
            var productId = item.dataset.productId;
            var totalPayment = document.getElementById('total_payment');
            var payment = document.getElementById('payment');
            /*
                        increaseButton.addEventListener('click', function() {
                            var newQuantity = parseInt(quantityInput.value) + 1;
                            updateQuantity(productId, newQuantity);
                        });

                        decreaseButton.addEventListener('click', function() {
                            var newQuantity = parseInt(quantityInput.value) - 1;
                            if (newQuantity >= 1) {
                                updateQuantity(productId, newQuantity);
                            }
                        });
            						*/
            function updateQuantity(productId, quantity) {
                var f = "?quantity=" + quantity + "&id=" + productId;
                var _url = "{{ route('frontend.order.cart.update') }}" + f;
                jQuery.ajax({
                    type: "GET",
                    url: _url,
                    data: f,
                    cache: false,
                    context: document.body,
                    success: function(data) {
                        quantityInput.value = data.quantity;
                        priceElement.textContent = data.price;
                        totalPrice1.textContent = formatNumber(data.totalPrice);
                        totalPrice2.textContent = formatNumber(data.totalPrice);
                        totalPayment.value = data.totalPrice;
                        payment.value = data.totalPrice;
                    }
                });

                function formatNumber(number) {
                    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            }
        });


        function updateCart(id) {
            const quantityInput = document.querySelector('.quantity-input');
            var priceElement = document.querySelector('.price');
            var quantity = document.getElementById('quantity' + id);
            var price = document.getElementById('price' + id);
            var donHang = document.getElementById('donHang');
            var shipCod = document.getElementById('shipCod').textContent;
            var shipCodeValue = parseInt(shipCod.replace(/,/g, ''));
            var txt_discount = document.getElementById('txt_discount').textContent;
            var txt_discount_value = parseInt(txt_discount.replace(/,/g, ''));
            var totalOrder = document.getElementById('totalOrder');
            var totalPayment = document.getElementById('total_payment');
            var payment = document.getElementById('payment');

            if (quantity.value * 1.0 < 1) {
                document.getElementById('quantity' + id).value = 1;
                return;
            }
            if (typeof quantity.value == "undefined") {
                quantity.value = 1;
            }

            var f = "?quantity=" + quantity.value + "&id=" + id;
            var _url = "/update-cart" + f;
            jQuery.ajax({
                type: "GET",
                url: _url,
                data: f,
                cache: false,
                context: document.body,
                success: function(data) {
                    quantity.value = data.quantity;
                    price.textContent = formatNumber(data.price);
                    donHang.textContent = formatNumber(data.totalPrice);
                    totalOrder.textContent = formatNumber(data.totalPrice + shipCodeValue + txt_discount_value);
                }
            });

            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
        }

        function removecart(id) {
            var f = "?id=" + id;
            var _url = "/remove-from-cart" + f;
            jQuery.ajax({
                type: "GET",
                url: _url,
                data: f,
                cache: false,
                context: document.body,
                success: function(data) {
                    window.location.reload();
                }
            });
        }


        function chooseProvince() {
            var p = $('#tinhthanh').val();
            var f = "?id=" + p;
            var n = $("#tinhthanh option:selected").text();
            $('#txt_tinhthanh').val(n);

            $('#quanhuyen').select2('val', '');
            $('#xaphuong').select2('val', '');
            $('#quanhuyen').empty().append('<option value="" selected>-Chọn quận/huyện-</option>');
            $('#xaphuong').empty().append('<option value="" selected>-Chọn xã/ phường-</option>');
            jQuery.ajax({
                type: "GET",
                url: "{{ route('frontend.load_district') }}" + f,
                data: f,
                cache: false,
                context: document.body,
                success: function(response) {
                    //alert(response);
                    //$('#quanhuyen').text('');
                    $('#quanhuyen').append(response);
                }
            });
        }

        function chooseDistrict() {
            var p = $('#quanhuyen').val();
            var f = "?id=" + p
            //alert(p);
            var n = $("#quanhuyen option:selected").text();
            $('#txt_quanhuyen').val(n);
            $('#xaphuong').select2('val', '');
            $('#xaphuong').empty().append('<option value="" selected>-Chọn xã/ phường-</option>');
            jQuery.ajax({
                type: "GET",
                url: "{{ route('frontend.load_ward') }}" + f,
                data: f,
                cache: false,
                context: document.body,
                success: function(response) {

                    //$('#xaphuong').text('');
                    $('#xaphuong').append(response);
                }
            });
        }

        function chooseWard() {
            var p = $('#xaphuong').val();
            var n = $("#xaphuong option:selected").text();
            $('#txt_xaphuong').val(n);
            if (p != "") {
                $('#address').attr('readonly', false);
            } else {
                $('#address').attr('readonly', true);
            }
        }

        function checkTransfer() {
            $('.checkTransfer').attr('style', 'display: block');
        }

        function coppyContent() {

            navigator.clipboard.writeText($('#orderInfo').html());

        }

        function checkVoucher() {
            //alert('AAAA');
            var vc = $('#voucher').val();
            var f = "?p=" + vc;
            var ship = $('#shipCod').text();
            var don = $('#donHang').text();
            //alert(don+"__"+ship);
            if (vc !== "") {
                jQuery.ajax({
                    type: "GET",
                    url: "{{ route('frontend.load_voucher') }}" + f,
                    data: f,
                    cache: false,
                    context: document.body,
                    success: function(response) {
                        if (Number(response) == 0) {
                            alert("Mã không tồn tại")
                        } else if (Number(response) == -1) {
                            alert("Vui lòng nhập mã")
                        } else {

                            var arr = response.split("_");

                            $('#discount').val(Number(arr[1]));
                            $('#txt_discount').text("-" + Number(arr[1]).toLocaleString());
                            $('#voucher_id').val(Number(arr[0]));

                            var tong = Number(don.replace(',', '')) + Number(ship.replace(',', '')) - Number(
                                arr[1]);

                            $('#totalOrder').text(Number(tong).toLocaleString());

                            $('#total_payment').val(tong);

                        }
                    }
                });
            } else {
                alert('Bạn chưa nhập mã');
            }
        }
    </script>
@endsection
